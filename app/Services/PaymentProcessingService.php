<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Pembelian;
use App\Models\Pembayaran;
use App\Models\StokAkun;
use App\Enums\PembelianStatus;
use App\Enums\StokStatus;
use App\Jobs\SendAccountInvoiceWhatsapp;

class PaymentProcessingService
{
    /**
     * Mark transaction as SUCCESS and process side effects.
     * 
     * $paymentDetails structure:
     * - payment_type (string)
     * - payment_gateway (string)
     * - gross_amount (int)
     * - transaction_id (string|null)
     */
    public function markAsSuccess(Pembelian $pembelian, array $paymentDetails)
    {
        if ($pembelian->status === PembelianStatus::SUCCESS) {
            return;
        }

        DB::transaction(function () use ($pembelian, $paymentDetails) {
            // Re-fetch and lock the record to prevent race conditions
            $lockedPembelian = Pembelian::where('id_pembelian', $pembelian->id_pembelian)->lockForUpdate()->first();
            
            if (!$lockedPembelian || $lockedPembelian->status === PembelianStatus::SUCCESS) {
                return;
            }

            $gateway = $paymentDetails['payment_gateway'] ?? 'unknown';
            Pembelian::$sumberPerubahan = "webhook_{$gateway}";
            
            $lockedPembelian->update([
                'status' => PembelianStatus::SUCCESS,
                'reserved_until' => null,
            ]);
            
            Pembelian::$sumberPerubahan = null;

            $pembayaranExists = Pembayaran::where('id_pembelian', $lockedPembelian->id_pembelian)->exists();
            if (!$pembayaranExists) {
                Pembayaran::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'metode_pembayaran' => $paymentDetails['payment_type'] ?? 'unknown',
                    'payment_gateway' => $gateway,
                    'jumlah_dibayar' => $paymentDetails['gross_amount'] ?? $pembelian->harga_saat_beli,
                    'midtrans_transaction_id' => $paymentDetails['transaction_id'] ?? null,
                    'tanggal_bayar' => now(),
                ]);
            }

            $stok = StokAkun::find($pembelian->id_stok);

            $canUseOldStok = $stok && (
                ($stok->status === StokStatus::RESERVED && $stok->id_pembelian == $pembelian->id_pembelian) ||
                ($stok->status === StokStatus::TERSEDIA)
            );

            if ($canUseOldStok) {
                $stok->update([
                    'status' => StokStatus::TERJUAL,
                    'id_pembelian' => $pembelian->id_pembelian,
                    'tanggal_terjual' => now(),
                ]);
            } else {
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

                    $lockedPembelian->update([
                        'id_stok' => $newStok->id_stok,
                    ]);
                } else {
                    try {
                        $orderId = $pembelian->order_id;
                        Mail::raw(
                            "Peringatan: Transaksi dengan Order ID {$orderId} berhasil dibayar, tetapi stok untuk varian tersebut telah habis. Silakan lakukan pengisian stok secara manual untuk pengguna.",
                            function ($message) use ($orderId) {
                                $message->to('g4lihanggoro@gmail.com')
                                    ->subject("Peringatan: Stok Akun Premium Habis (Order ID: {$orderId})");
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
            $gateway = $paymentDetails['payment_gateway'] ?? 'unknown';
            Log::error("Failed to send premium purchase email in {$gateway} callback: " . $mailEx->getMessage());
        }
    }

    /**
     * Mark transaction as FAILED/EXPIRED and release reserved stock.
     */
    public function markAsFailed(Pembelian $pembelian, string $reason, string $gateway = 'midtrans')
    {
        $statusPembelian = PembelianStatus::FAILED;
        if (strtolower($reason) === 'expire' || strtolower($reason) === 'expired') {
            $statusPembelian = PembelianStatus::EXPIRED;
        }

        DB::transaction(function () use ($pembelian, $statusPembelian, $gateway) {
            Pembelian::$sumberPerubahan = "webhook_{$gateway}";
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
    }
}
