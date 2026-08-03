<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\CustomerModel;
use App\Models\StokAkun;
use App\Models\VarianLayanan;
use App\Models\Voucher;
use App\Models\VoucherKlaim;
use App\Enums\PembelianStatus;
use App\Enums\StokStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\PaymentProcessingService;
use App\Services\Gateways\PaymentGatewayFactory;

class CheckoutController extends ApiController
{
    protected $paymentProcessor;

    public function __construct(PaymentProcessingService $paymentProcessor)
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    /**
     * Proses Checkout (Pilih Varian & Voucher)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'id_varian' => 'required|exists:tbl_varian_layanan,id_varian',
            'kode_voucher' => 'nullable|string'
        ]);

        $id_varian = $request->id_varian;
        $kode_voucher = strtoupper(trim((string) $request->kode_voucher));
        $idCustomerUser = $request->user()->id;

        $customer = CustomerModel::where('user_id', $idCustomerUser)->first();
        if (!$customer) {
            return $this->sendError('Customer profile not found.', [], 404);
        }

        if (empty($customer->nomor_telepon) || empty($customer->user->name)) {
            return $this->sendError('Silakan lengkapi nama dan nomor telepon WhatsApp Anda di profil terlebih dahulu sebelum melakukan pembelian.', [], 400);
        }

        $id_customer = $customer->id;

        try {
            $pembelian = DB::transaction(function () use ($id_varian, $id_customer, $kode_voucher) {
                $stok = StokAkun::where('id_varian', $id_varian)
                    ->where('status', StokStatus::TERSEDIA)
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->first();

                if (!$stok) {
                    throw new \Exception('Stok Habis');
                }

                $varian = VarianLayanan::with('tipeLayanan.produk')->findOrFail($id_varian);
                $harga_varian = (float) $varian->harga;
                $harga_saat_beli = $harga_varian;
                $nominal_diskon = 0;
                $voucher_dipakai = null;

                if (!empty($kode_voucher)) {
                    $voucher = Voucher::where('kode', $kode_voucher)->first();

                    if (!$voucher || !$voucher->is_active) {
                        throw new \Exception("Kode voucher '{$kode_voucher}' tidak valid atau tidak aktif.");
                    }

                    $now = now();
                    if ($voucher->berlaku_dari && $now->lt($voucher->berlaku_dari)) throw new \Exception("Voucher belum berlaku.");
                    if ($voucher->berlaku_sampai && $now->gt($voucher->berlaku_sampai)) throw new \Exception("Voucher sudah kedaluwarsa.");
                    if ($voucher->kuota_total !== null && $voucher->kuota_terpakai >= $voucher->kuota_total) throw new \Exception("Kuota voucher telah habis.");

                    $idTokoProduk = $varian->tipeLayanan?->produk?->id_toko;
                    if ($voucher->scope === 'toko_spesifik' && $voucher->id_toko != $idTokoProduk) throw new \Exception("Voucher tidak berlaku untuk toko produk ini.");

                    if ($harga_varian < (float) $voucher->minimal_transaksi) {
                        throw new \Exception("Minimal transaksi tidak terpenuhi.");
                    }

                    $existingKlaim = VoucherKlaim::where('id_voucher', $voucher->id_voucher)->where('id_customer', $id_customer)->first();
                    if (!$existingKlaim) {
                        VoucherKlaim::create([
                            'id_voucher' => $voucher->id_voucher,
                            'id_customer' => $id_customer,
                            'id_pembelian' => null,
                            'created_at' => now(),
                        ]);
                    }

                    if ($voucher->tipe_diskon === 'persen') {
                        $potongan = ($harga_varian * (float) $voucher->nilai_diskon) / 100.0;
                        if ($voucher->maksimal_potongan !== null) $potongan = min($potongan, (float) $voucher->maksimal_potongan);
                    } else {
                        $potongan = (float) $voucher->nilai_diskon;
                    }

                    $nominal_diskon = min($harga_varian, max(0.0, $potongan));
                    $harga_saat_beli = max(0.0, $harga_varian - $nominal_diskon);
                    $voucher_dipakai = $voucher->id_voucher;
                }

                $reserved_expired_at = now()->addMinutes(15);

                $stok->update([
                    'status' => StokStatus::RESERVED,
                    'reserved_at' => now(),
                    'reserved_expired_at' => $reserved_expired_at,
                ]);

                $pembelian = Pembelian::create([
                    'order_id' => (string) Str::ulid(),
                    'id_customer' => $id_customer,
                    'id_varian' => $id_varian,
                    'id_stok' => $stok->id_stok,
                    'harga_saat_beli' => $harga_saat_beli,
                    'id_voucher_dipakai' => $voucher_dipakai,
                    'nominal_diskon' => $nominal_diskon,
                    'status' => PembelianStatus::PENDING,
                    'reserved_until' => $reserved_expired_at,
                ]);

                $stok->update(['id_pembelian' => $pembelian->id_pembelian]);

                return $pembelian;
            });

            return $this->sendResponse($pembelian, 'Checkout berhasil, lanjutkan ke pembayaran', 201);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }

    /**
     * Generate Instruksi Transaksi (Midtrans/Pakasir)
     */
    public function generateTransaction(Request $request, $order_id)
    {
        $pembelian = Pembelian::where('order_id', $order_id)->first();
        if (!$pembelian) return $this->sendError('Pesanan tidak ditemukan', [], 404);

        $customer = CustomerModel::where('user_id', $request->user()->id)->first();
        if ($pembelian->id_customer != $customer->id) return $this->sendError('Unauthorized', [], 403);

        $gateway_name = $request->input('gateway', 'midtrans');

        if ($gateway_name === 'pakasir' && $pembelian->payment_gateway === 'pakasir' && $pembelian->gateway_reference && $pembelian->reserved_until > now()) {
            return $this->sendResponse(['redirect_url' => $pembelian->gateway_reference], 'Transaksi sudah aktif');
        }

        $pembelian->payment_gateway = $gateway_name;
        $pembelian->save();

        try {
            if ($gateway_name === 'pakasir') {
                $slug = config('pakasir.project_slug');
                $amount = (int) $pembelian->harga_saat_beli;
                $redirectUrl = rtrim(config('pakasir.base_url', 'https://app.pakasir.com'), '/') . "/pay/{$slug}/{$amount}?order_id={$order_id}";
                
                $pembelian->gateway_reference = 'redirect';
                $pembelian->save();
                
                return $this->sendResponse(['redirect_url' => $redirectUrl], 'Generate Pakasir url success');
            } else {
                $gateway = PaymentGatewayFactory::make($gateway_name);
                $transactionData = $gateway->createTransaction($pembelian, 'qris');
                
                return $this->sendResponse(['snapToken' => $transactionData['token'] ?? null], 'Generate Midtrans token success');
            }
        } catch (\Exception $e) {
            return $this->sendError('Gagal memproses pembayaran: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Check status pembayaran
     */
    public function status(Request $request, $order_id)
    {
        $pembelian = Pembelian::where('order_id', $order_id)->first();
        if (!$pembelian) return $this->sendError('Pesanan tidak ditemukan', [], 404);

        $customer = CustomerModel::where('user_id', $request->user()->id)->first();
        if ($pembelian->id_customer != $customer->id) return $this->sendError('Unauthorized', [], 403);

        // Sync logic
        if ($pembelian->status == PembelianStatus::PENDING) {
            try {
                $gatewayName = $pembelian->payment_gateway ?? 'midtrans';
                $gateway = PaymentGatewayFactory::make($gatewayName);
                $statusData = $gateway->verifyStatus($order_id, (int)$pembelian->harga_saat_beli);
                
                if ($statusData['status'] === PembelianStatus::SUCCESS) {
                    $this->paymentProcessor->markAsSuccess($pembelian, [
                        'payment_type' => $statusData['payment_type'] ?? 'unknown',
                        'payment_gateway' => $gatewayName,
                        'gross_amount' => $statusData['gross_amount'] ?? $pembelian->harga_saat_beli,
                        'transaction_id' => $statusData['transaction_id'] ?? null,
                    ]);
                } elseif (in_array($statusData['status'], [PembelianStatus::FAILED, PembelianStatus::EXPIRED])) {
                    $this->paymentProcessor->markAsFailed($pembelian, $statusData['status']->value ?? 'failed', $gatewayName);
                }
                
                // Re-fetch
                $pembelian = Pembelian::where('order_id', $order_id)->first();
            } catch (\Exception $e) {
                // ignore
            }
        }

        return $this->sendResponse([
            'order_id' => $pembelian->order_id,
            'status' => strtolower($pembelian->status->value ?? $pembelian->status),
            'harga' => $pembelian->harga_saat_beli,
            'updated_at' => $pembelian->updated_at,
        ], 'Status pembayaran');
    }
}
