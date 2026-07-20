<?php

namespace App\Http\Controllers;

use App\Models\BeliProdukModel;
use App\Models\ProdukModel;
use App\Models\User;
use App\Models\CustomerModel;
use App\Models\PembayaranModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailProdukBeli;

class PembayaranController extends Controller
{

    public function __construct()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }

    private function syncTransactionStatus(string $order_id, $beli_produk = null)
    {
        // 1. Check if the order is for a Premium Account (Pembelian)
        $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->first();
        if ($pembelian) {
            if ($pembelian->status == \App\Enums\PembelianStatus::SUCCESS) {
                return $pembelian;
            }

            try {
                $status = \Midtrans\Transaction::status($order_id);
                if ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status == 'accept')) {
                    $pembayaranExists = \App\Models\Pembayaran::where('id_pembelian', $pembelian->id_pembelian)->exists();
                    if (!$pembayaranExists) {
                        \App\Models\Pembayaran::create([
                            'id_pembelian' => $pembelian->id_pembelian,
                            'metode_pembayaran' => $status->payment_type ?? 'midtrans',
                            'jumlah_dibayar' => $status->gross_amount,
                            'midtrans_transaction_id' => $status->transaction_id ?? null,
                            'tanggal_bayar' => now(),
                        ]);
                    }

                    \App\Models\Pembelian::$sumberPerubahan = 'webhook_midtrans';
                    $pembelian->update(['status' => \App\Enums\PembelianStatus::SUCCESS]);
                    \App\Models\Pembelian::$sumberPerubahan = null;

                    // Send Email to Customer
                    try {
                        $customerUser = $pembelian->customer->user;
                        $varian = $pembelian->varianLayanan;
                        $namaProdukVarian = $varian->tipeLayanan->produk->nama_produk . ' - ' . $varian->tipeLayanan->nama_tipe . ' (' . $varian->nama_varian . ')';

                        \Illuminate\Support\Facades\Mail::to($customerUser->email)->send(new \App\Mail\MailPremiumBeli(
                            $customerUser->name ?? $customerUser->email,
                            $namaProdukVarian,
                            $pembelian->harga_saat_beli,
                            $pembelian->order_id
                        ));
                    } catch (\Exception $mailEx) {
                        \Log::error('Failed to send premium purchase email in syncTransactionStatus: ' . $mailEx->getMessage());
                    }

                    if ($pembelian->id_stok) {
                        $stok = \App\Models\StokAkun::find($pembelian->id_stok);
                        if ($stok) {
                            $stok->update([
                                'status' => \App\Enums\StokStatus::TERJUAL,
                                'tanggal_terjual' => now(),
                            ]);
                        }
                    }

                    // Dispatch WA Invoice Job
                    \App\Jobs\SendAccountInvoiceWhatsapp::dispatch($pembelian->id_pembelian);
                } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                    \App\Models\Pembelian::$sumberPerubahan = 'webhook_midtrans';
                    $pembelian->update(['status' => \App\Enums\PembelianStatus::FAILED]);
                    \App\Models\Pembelian::$sumberPerubahan = null;
                }
            } catch (\Exception $e) {
                // Ignore status check failure
            }

            return $pembelian;
        }

        // 2. Otherwise fall back to old ZIP product order
        if (!$beli_produk) {
            $beli_produk = BeliProdukModel::where('order_id', $order_id)->first();
        }

        if (!$beli_produk || $beli_produk->status == 'success') {
            return $beli_produk;
        }

        try {
            $status = \Midtrans\Transaction::status($order_id);
            if ($status->transaction_status == 'settlement' || ($status->transaction_status == 'capture' && $status->fraud_status == 'accept')) {
                $tanggal_saat_ini = date('Y-m-d');

                $pembayaranExists = PembayaranModel::where('order_id', $order_id)->exists();
                if (!$pembayaranExists) {
                    PembayaranModel::create([
                        'total' => $status->gross_amount,
                        'metode' => $status->payment_type ?? 'midtrans',
                        'order_id' => $order_id
                    ]);
                }

                $beli_produk->update([
                    'status' => 'success',
                    'tanggal_transaksi' => $tanggal_saat_ini
                ]);

                // Send email
                $produk = ProdukModel::find($beli_produk->produk_id);
                $user = User::find($beli_produk->user_id);

                try {
                    Mail::to($user->email)->send(new MailProdukBeli(
                        $user->name,
                        $produk->nama_produk,
                        $produk->deskripsi,
                        $status->gross_amount,
                        $order_id
                    ));
                } catch (\Exception $mailEx) {
                    \Log::error('Failed to send mail in syncTransactionStatus: ' . $mailEx->getMessage());
                }
            } elseif (in_array($status->transaction_status, ['deny', 'expire', 'cancel'])) {
                $beli_produk->update(['status' => 'deny']);
            }
        } catch (\Exception $e) {
            // Ignored, order_id might not exist on Midtrans yet
        }

        return $beli_produk;
    }

    public function index()
    {
        $id = session('id');

        // Sync pending ZIP product transactions
        $pending_purchases = BeliProdukModel::where('user_id', $id)
            ->whereIn('status', ['pending', 'deny'])
            ->get();

        foreach ($pending_purchases as $purchase) {
            $this->syncTransactionStatus($purchase->order_id, $purchase);
        }

        // Sync pending Premium Account purchases
        $customer = CustomerModel::where('user_id', $id)->first();
        if ($customer) {
            $pending_pembelian = \App\Models\Pembelian::where('id_customer', $customer->id)
                ->where('status', \App\Enums\PembelianStatus::PENDING)
                ->get();
            foreach ($pending_pembelian as $pembelian) {
                $this->syncTransactionStatus($pembelian->order_id);
            }
        }

        $produk = ProdukModel::withWhereHas('produk_beli', function ($query) use ($id) {
            $query->where('user_id', $id)->with('toko');
        })->get();

        return view('pembayaran.bukti_pembayaran', compact('produk'));
    }

    public function metode_pembayaran(Request $request, string $order_id)
    {
        $id = session('id');

        // Check if the order is for a Premium Account (Pembelian)
        $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->first();
        if ($pembelian) {
            $this->authorize('view', $pembelian);
            // Sync status with Midtrans first
            $this->syncTransactionStatus($order_id);

            // Re-fetch
            $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->first();

            if ($pembelian->status == \App\Enums\PembelianStatus::SUCCESS) {
                return redirect('/bukti_pembayaran')->with('success', 'Pembayaran berhasil dikonfirmasi.');
            }

            $user = User::find($id);
            $nomorTeleponCustomer = CustomerModel::where('user_id', $user->id)->first();
            $varian = \App\Models\VarianLayanan::findOrFail($pembelian->id_varian);
            $tipe = $varian->tipeLayanan;
            $produk = $tipe->produk;

            $items = array(
                array(
                    'id' => $varian->id_varian,
                    'price' => $pembelian->harga_saat_beli,
                    'quantity' => 1,
                    'name' => $produk->nama_produk . ' - ' . $tipe->nama_tipe . ' (' . $varian->nama_varian . ')'
                )
            );

            $params = array(
                'item_details' => $items,
                'transaction_details' => array(
                    'order_id' => $pembelian->order_id,
                    'gross_amount' => $pembelian->harga_saat_beli,
                ),
                'customer_details' => array(
                    'first_name' => $user->name,
                    'phone' => $nomorTeleponCustomer->nomor_telepon ?? '',
                ),
                'callbacks' => array(
                    'finish' => route('bukti_pembayaran.status', ['order_id' => $pembelian->order_id])
                )
            );

            $pathId = $pembelian->order_id;
            $orderIdProduk = $pembelian->order_id;

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'has already been taken')) {
                    try {
                        $status = \Midtrans\Transaction::status($orderIdProduk);
                        if ($status->transaction_status == 'pending') {
                            return redirect('/bukti_pembayaran')->with('error', 'Pembayaran sedang ditangguhkan (pending) di Midtrans. Harap selesaikan pembayaran Anda.');
                        }
                    } catch (\Exception $statusEx) {
                        // ignore status check failure
                    }
                }
                return redirect('/bukti_pembayaran')->with('error', 'Gagal memproses pembayaran Midtrans: ' . $e->getMessage());
            }

            $reserved_expired_at = $pembelian->reserved_until;

            return view('pembayaran.metode_pembayaran', compact('produk', 'snapToken', 'pathId', 'orderIdProduk', 'user', 'nomorTeleponCustomer', 'pembelian', 'varian', 'reserved_expired_at'));
        }

        // Old ZIP product order code follows...
        $beli_produk = BeliProdukModel::where('order_id', $order_id)->first();

        if (!$beli_produk) {
            return redirect('/bukti_pembayaran')->with('error', 'Order tidak ditemukan.');
        }

        if ($beli_produk->status == 'success') {
            return redirect('/bukti_pembayaran')->with('success', 'Pembayaran berhasil dikonfirmasi.');
        } else {
            $produk = ProdukModel::find($beli_produk->produk_id);
            $user = User::find($id);
            $nomorTeleponCustomer = CustomerModel::where('user_id', $user->id)->first();

            $items = array(
                array(
                    'id' => $produk->id_produk,
                    'price' => $produk->harga,
                    'quantity' => $beli_produk->qty,
                    'name' => $produk->nama_produk
                )
            );

            $params = array(
                'item_details' => $items,
                'transaction_details' => array(
                    'order_id' => $beli_produk->order_id,
                    'gross_amount' => $produk->harga,
                ),
                'customer_details' => array(
                    'first_name' => $user->name,
                    'phone' => $nomorTeleponCustomer->nomor_telepon,
                ),
                'callbacks' => array(
                    'finish' => route('bukti_pembayaran.status', ['order_id' => $beli_produk->order_id])
                )
            );

            $pathId = $beli_produk->order_id;
            $orderIdProduk = $beli_produk->order_id;

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            } catch (\Exception $e) {
                if (str_contains($e->getMessage(), 'has already been taken')) {
                    try {
                        $status = \Midtrans\Transaction::status($orderIdProduk);
                        if ($status->transaction_status == 'pending') {
                            return redirect('/bukti_pembayaran')->with('error', 'Pembayaran sedang ditangguhkan (pending) di Midtrans. Harap selesaikan pembayaran Anda.');
                        }
                    } catch (\Exception $statusEx) {
                        // ignore status check failure
                    }
                }
                return redirect('/bukti_pembayaran')->with('error', 'Gagal memproses pembayaran Midtrans: ' . $e->getMessage());
            }

            return view('pembayaran.metode_pembayaran', compact('produk', 'snapToken', 'pathId', 'orderIdProduk', 'user', 'nomorTeleponCustomer'));
        }
    }

    public function download_bukti_pembayaran(string $order_id)
    {
        $id = session('id');
        $user = User::find($id);

        $beliProduk = \App\Models\BeliProdukModel::where('order_id', $order_id)->first();
        if ($beliProduk && $beliProduk->user_id != $id) {
            abort(403, 'Unauthorized access.');
        }

        $pembayaran = PembayaranModel::where('order_id', $order_id)->first();
        $produk = ProdukModel::withWhereHas('produk_beli', function ($query) use ($order_id) {
            $query->where('order_id', $order_id);
        })->get();

        $invoice = 'invoice-' . $pembayaran->order_id . '.pdf';
        $pdf = Pdf::loadView('pembayaran.download_bukti_pembayaran', compact('user', 'produk', 'pembayaran'));
        return $pdf->download($invoice);
    }

    public function status(string $order_id)
    {
        // 1. Sync status dengan Midtrans terlebih dahulu via API backend (reload-safe/bookmarkable)
        $this->syncTransactionStatus($order_id);

        // 2. Query Pembelian (Premium Account)
        $pembelian = \App\Models\Pembelian::with(['varianLayanan.tipeLayanan.produk', 'pembayaran'])
            ->where('order_id', $order_id)
            ->first();

        if ($pembelian) {
            $this->authorize('view', $pembelian);
            return view('customer.status_pembayaran', [
                'type' => 'premium',
                'order' => $pembelian,
                'status' => strtolower($pembelian->status->value ?? $pembelian->status),
            ]);
        }

        // 3. Fallback ke Legacy BeliProdukModel (Zip Product)
        $beli_produk = BeliProdukModel::with('produk')->where('order_id', $order_id)->first();
        if ($beli_produk) {
            if ($beli_produk->user_id != auth()->id()) {
                abort(403, 'Unauthorized access.');
            }
            return view('customer.status_pembayaran', [
                'type' => 'legacy',
                'order' => $beli_produk,
                'status' => strtolower($beli_produk->status),
            ]);
        }

        abort(404, 'Transaksi tidak ditemukan.');
    }

    public function statusApi(string $order_id)
    {
        // 1. Sinkronisasi status dengan server Midtrans via API backend (polling-safe)
        $this->syncTransactionStatus($order_id);

        // 2. Query data Pembelian (Premium Account)
        $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->first();
        if ($pembelian) {
            try {
                // Cek otorisasi kepemilikan transaksi dengan Policy yang ada
                $this->authorize('view', $pembelian);
            } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'status' => strtolower($pembelian->status->value ?? $pembelian->status),
                'updated_at' => $pembelian->updated_at ? $pembelian->updated_at->toIso8601String() : null,
            ]);
        }

        // 3. Fallback ke Legacy BeliProdukModel (Zip Product)
        $beli_produk = BeliProdukModel::where('order_id', $order_id)->first();
        if ($beli_produk) {
            if ($beli_produk->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'status' => strtolower($beli_produk->status),
                'updated_at' => $beli_produk->tanggal_transaksi ? date('c', strtotime($beli_produk->tanggal_transaksi)) : null,
            ]);
        }

        return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
    }
}
