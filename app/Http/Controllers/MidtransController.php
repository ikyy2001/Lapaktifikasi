<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeliProdukModel;
use App\Models\PembayaranModel;
use App\Models\ProdukModel;
use App\Models\User;
use App\Mail\MailProdukBeli;
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

        if ($hashed === $request->signature_key) {
            $pembelian = \App\Models\Pembelian::where('order_id', $order_id)->first();

            if ($pembelian) {
                // PREMIUM ACCOUNT FLOW (Pembelian)
                if ($request->transaction_status == "capture" || $request->transaction_status == "settlement") {
                    if ($request->transaction_status == "settlement" || ($request->transaction_status == "capture" && $request->fraud_status == "accept")) {
                        
                        \Illuminate\Support\Facades\DB::transaction(function () use ($pembelian, $request, $order_id) {
                            // 1. Create Pembayaran record
                            $pembayaranExists = \App\Models\Pembayaran::where('id_pembelian', $pembelian->id_pembelian)->exists();
                            if (!$pembayaranExists) {
                                \App\Models\Pembayaran::create([
                                    'id_pembelian' => $pembelian->id_pembelian,
                                    'metode_pembayaran' => $request->payment_type,
                                    'jumlah_dibayar' => $request->gross_amount,
                                    'midtrans_transaction_id' => $request->transaction_id,
                                    'tanggal_bayar' => now(),
                                ]);
                            }

                            // 2. Check the reserved stock
                            $stok = \App\Models\StokAkun::find($pembelian->id_stok);
                            
                            if ($stok && $stok->status == \App\Enums\StokStatus::RESERVED && $stok->id_pembelian == $pembelian->id_pembelian) {
                                // CASE normal: stock is still reserved for this purchase
                                $stok->update([
                                    'status' => \App\Enums\StokStatus::TERJUAL,
                                    'tanggal_terjual' => now(),
                                ]);
                                
                                $pembelian->update([
                                    'status' => \App\Enums\PembelianStatus::SUCCESS,
                                    'reserved_until' => null,
                                ]);
                            } else {
                                // CASE edge case 4.3: reserved stock was released (status not reserved or assigned to another order)
                                // Try to assign another available stock of the SAME id_varian
                                $newStok = \App\Models\StokAkun::where('id_varian', $pembelian->id_varian)
                                    ->where('status', \App\Enums\StokStatus::TERSEDIA)
                                    ->orderBy('created_at', 'asc')
                                    ->lockForUpdate()
                                    ->first();

                                if ($newStok) {
                                    // Assign new stock
                                    $newStok->update([
                                        'status' => \App\Enums\StokStatus::TERJUAL,
                                        'id_pembelian' => $pembelian->id_pembelian,
                                        'tanggal_terjual' => now(),
                                    ]);

                                    $pembelian->update([
                                        'id_stok' => $newStok->id_stok,
                                        'status' => \App\Enums\PembelianStatus::SUCCESS,
                                        'reserved_until' => null,
                                    ]);
                                } else {
                                    // No stock available at all
                                    $pembelian->update([
                                        'status' => \App\Enums\PembelianStatus::SUCCESS,
                                        'reserved_until' => null,
                                    ]);

                                    // Send email notification to Admin
                                    try {
                                        \Illuminate\Support\Facades\Mail::raw(
                                            "Peringatan: Transaksi dengan Order ID {$order_id} berhasil dibayar, tetapi stok untuk varian tersebut telah habis. Silakan lakukan pengisian stok secara manual untuk pengguna.",
                                            function ($message) use ($order_id) {
                                                $message->to('g4lihanggoro@gmail.com')
                                                    ->subject("Peringatan: Stok Akun Premium Habis (Order ID: {$order_id})");
                                            }
                                        );
                                    } catch (\Exception $mailEx) {
                                        \Log::error("Failed to send admin out-of-stock notification: " . $mailEx->getMessage());
                                    }
                                }
                            }
                        });
                    }
                } elseif (in_array($request->transaction_status, ['deny', 'expire', 'cancel'])) {
                    // Map other states
                    $status_pembelian = \App\Enums\PembelianStatus::FAILED;
                    if ($request->transaction_status == 'cancel') {
                        $status_pembelian = \App\Enums\PembelianStatus::CANCELLED;
                    } elseif ($request->transaction_status == 'expire') {
                        $status_pembelian = \App\Enums\PembelianStatus::EXPIRED;
                    }
                    
                    \Illuminate\Support\Facades\DB::transaction(function () use ($pembelian, $status_pembelian) {
                        $pembelian->update(['status' => $status_pembelian]);
                        
                        // Release stock if it is still reserved for this purchase
                        if ($pembelian->id_stok) {
                            $stok = \App\Models\StokAkun::find($pembelian->id_stok);
                            if ($stok && $stok->status == \App\Enums\StokStatus::RESERVED && $stok->id_pembelian == $pembelian->id_pembelian) {
                                $stok->update([
                                    'status' => \App\Enums\StokStatus::TERSEDIA,
                                    'reserved_at' => null,
                                    'reserved_expired_at' => null,
                                    'id_pembelian' => null,
                                ]);
                            }
                        }
                    });
                }
            } else {
                // EXISTING ZIP PRODUCT FLOW
                if ($request->transaction_status == "capture" || $request->transaction_status == "settlement") {
                    if ($request->transaction_status == "settlement" || ($request->transaction_status == "capture" && $request->fraud_status == "accept")) {
                        $pembayaranProduk = PembayaranModel::create([
                            'total' => $total,
                            'metode' => $tipePembayaran,
                            'order_id' => $order_id
                        ]);

                        BeliProdukModel::where('order_id', $order_id)
                            ->update(['status' => 'success', 'tanggal_transaksi' => $tanggal_saat_ini]);

                        $detail = BeliProdukModel::where('order_id', $order_id)->first();
                        $produk = ProdukModel::find($detail->produk_id);
                        $user = User::find($detail->user_id);

                        Mail::to($user->email)->send(new MailProdukBeli(
                            $user->name,
                            $produk->nama,
                            $produk->deskripsi,
                            $pembayaranProduk->total,
                            $order_id
                        ));
                    }
                } else if ($request->transaction_status == "deny") {
                    BeliProdukModel::where('order_id', $order_id)
                        ->update(['status' => 'deny']);
                }
            }
        }
    }
}
