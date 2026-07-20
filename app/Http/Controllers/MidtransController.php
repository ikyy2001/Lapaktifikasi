<?php

namespace App\Http\Controllers;

use App\Enums\PembelianStatus;
use App\Enums\StokStatus;
use App\Jobs\SendAccountInvoiceWhatsapp;
use App\Mail\MailProdukBeli;
use App\Models\BeliProdukModel;
use App\Models\Pembayaran;
use App\Models\PembayaranModel;
use App\Models\Pembelian;
use App\Models\ProdukModel;
use App\Models\StokAkun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        $tanggal_saat_ini = date('Y-m-d');
        $tipePembayaran = $request->payment_type;
        $total = $request->gross_amount;
        $order_id = $request->order_id;

        \App\Models\MidtransWebhookLog::create([
            'order_id' => $order_id,
            'status_code' => $request->status_code,
            'signature_key' => $request->signature_key,
            'payload' => $request->all(),
        ]);

        if ($hashed !== $request->signature_key) {
            Log::warning('Midtrans webhook invalid signature', ['order_id' => $order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        try {
            $midtransStatus = \Midtrans\Transaction::status($order_id);
            $transaction_status = $midtransStatus->transaction_status;
            $fraud_status = $midtransStatus->fraud_status ?? null;
        } catch (\Exception $e) {
            Log::error('Midtrans status check failed in webhook', ['order_id' => $order_id, 'error' => $e->getMessage()]);
            $transaction_status = $request->transaction_status;
            $fraud_status = $request->fraud_status;
        }

        $pembelian = Pembelian::where('order_id', $order_id)->first();

        if ($pembelian) {
            return $this->handlePremiumCallback($request, $pembelian, $order_id, $transaction_status, $fraud_status);
        }

        if (BeliProdukModel::where('order_id', $order_id)->exists()) {
            return $this->handleLegacyZipCallback($request, $order_id, $tipePembayaran, $total, $tanggal_saat_ini);
        }

        return response()->json([
            'message' => 'Pembelian dengan order_id "' . $order_id . '" tidak ditemukan.',
        ], 404);
    }

    private function handlePremiumCallback(Request $request, Pembelian $pembelian, string $order_id, $transaction_status = null, $fraud_status = null)
    {
        if ($pembelian->status === PembelianStatus::SUCCESS) {
            return response()->json(['message' => 'already processed']);
        }

        $transaction_status = $transaction_status ?? $request->transaction_status;
        $fraud_status = $fraud_status ?? $request->fraud_status;

        $isPaymentSuccess = $transaction_status === 'settlement'
            || ($transaction_status === 'capture' && $fraud_status === 'accept');

        if ($isPaymentSuccess) {
            DB::transaction(function () use ($pembelian, $request, $order_id) {
                Pembelian::$sumberPerubahan = 'webhook_midtrans';
                $pembelian->update([
                    'status' => PembelianStatus::SUCCESS,
                    'reserved_until' => null,
                ]);
                Pembelian::$sumberPerubahan = null;

                $pembayaranExists = Pembayaran::where('id_pembelian', $pembelian->id_pembelian)->exists();
                if (! $pembayaranExists) {
                    Pembayaran::create([
                        'id_pembelian' => $pembelian->id_pembelian,
                        'metode_pembayaran' => $request->payment_type,
                        'jumlah_dibayar' => $request->gross_amount,
                        'midtrans_transaction_id' => $request->transaction_id,
                        'tanggal_bayar' => now(),
                    ]);
                }

                $stok = StokAkun::find($pembelian->id_stok);

                if ($stok) {
                    $stok->update([
                        'status' => StokStatus::TERJUAL,
                        'tanggal_terjual' => now(),
                    ]);
                } else {
                    // CASE edge case 4.3: reserved stock was released (status not reserved or assigned to another order)
                    // Try to assign another available stock of the SAME id_varian
                    $newStok = StokAkun::where('id_varian', $pembelian->id_varian)
                        ->where('status', StokStatus::TERSEDIA)
                        ->orderBy('created_at', 'asc')
                        ->lockForUpdate()
                        ->first();

                    if ($newStok) {
                        $newStok->update([
                            'status' => StokStatus::TERJUAL,
                            'id_pembelian' => $pembelian->id_pembelian,
                            'tanggal_terjual' => now(),
                        ]);

                        $pembelian->update([
                            'id_stok' => $newStok->id_stok,
                        ]);
                    } else {
                        // No stock available at all
                        try {
                            Mail::raw(
                                "Peringatan: Transaksi dengan Order ID {$order_id} berhasil dibayar, tetapi stok untuk varian tersebut telah habis. Silakan lakukan pengisian stok secara manual untuk pengguna.",
                                function ($message) use ($order_id) {
                                    $message->to('g4lihanggoro@gmail.com')
                                        ->subject("Peringatan: Stok Akun Premium Habis (Order ID: {$order_id})");
                                }
                            );
                        } catch (\Exception $mailEx) {
                            Log::error('Failed to send admin out-of-stock notification: ' . $mailEx->getMessage());
                        }
                    }
                }
            });

            SendAccountInvoiceWhatsapp::dispatch($pembelian->id_pembelian);

            try {
                $pembelian->refresh();
                $customerUser = $pembelian->customer->user;
                $varian = $pembelian->varianLayanan;
                $namaProdukVarian = $varian->tipeLayanan->produk->nama_produk . ' - ' . $varian->tipeLayanan->nama_tipe . ' (' . $varian->nama_varian . ')';

                Mail::to($customerUser->email)->send(new \App\Mail\MailPremiumBeli(
                    $customerUser->name ?? $customerUser->email,
                    $namaProdukVarian,
                    $pembelian->harga_saat_beli,
                    $pembelian->order_id
                ));
            } catch (\Exception $mailEx) {
                Log::error('Failed to send premium purchase email in Midtrans callback: ' . $mailEx->getMessage());
            }

            return response()->json(['message' => 'payment processed']);
        }

        if (in_array($request->transaction_status, ['deny', 'expire', 'cancel'], true)) {
            $statusPembelian = $request->transaction_status === 'expire'
                ? PembelianStatus::EXPIRED
                : PembelianStatus::FAILED;

            DB::transaction(function () use ($pembelian, $statusPembelian) {
                Pembelian::$sumberPerubahan = 'webhook_midtrans';
                $pembelian->update(['status' => $statusPembelian]);
                Pembelian::$sumberPerubahan = null;

                if ($pembelian->id_stok) {
                    $stok = StokAkun::find($pembelian->id_stok);
                    if ($stok && $stok->status === StokStatus::RESERVED) {
                        $stok->update([
                            'status' => StokStatus::TERSEDIA,
                            'reserved_at' => null,
                            'reserved_expired_at' => null,
                            'id_pembelian' => null,
                        ]);
                    }
                }
            });

            return response()->json(['message' => 'payment ' . $request->transaction_status]);
        }

        return response()->json(['message' => 'notification received']);
    }

    private function handleLegacyZipCallback(Request $request, string $order_id, $tipePembayaran, $total, string $tanggal_saat_ini)
    {
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            if ($request->transaction_status == 'settlement' || ($request->transaction_status == 'capture' && $request->fraud_status == 'accept')) {
                $pembayaranProduk = PembayaranModel::create([
                    'total' => $total,
                    'metode' => $tipePembayaran,
                    'order_id' => $order_id,
                ]);

                BeliProdukModel::where('order_id', $order_id)
                    ->update(['status' => 'success', 'tanggal_transaksi' => $tanggal_saat_ini]);

                $detail = BeliProdukModel::where('order_id', $order_id)->first();
                $produk = ProdukModel::find($detail->produk_id);
                $user = User::find($detail->user_id);

                Mail::to($user->email)->send(new MailProdukBeli(
                    $user->name,
                    $produk->nama_produk,
                    $produk->deskripsi,
                    $pembayaranProduk->total,
                    $order_id
                ));
            }
        } elseif ($request->transaction_status == 'deny') {
            BeliProdukModel::where('order_id', $order_id)
                ->update(['status' => 'deny']);
        }
    }
}
