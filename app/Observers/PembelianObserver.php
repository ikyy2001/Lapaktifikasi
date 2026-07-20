<?php

namespace App\Observers;

use App\Models\Pembelian;
use App\Models\PembelianLog;

class PembelianObserver
{
    /**
     * Handle the Pembelian "updating" event.
     */
    public function updating(Pembelian $pembelian): void
    {
        if ($pembelian->isDirty('status')) {
            PembelianLog::create([
                'id_pembelian' => $pembelian->id_pembelian,
                'status_lama' => $pembelian->getOriginal('status'),
                'status_baru' => $pembelian->status,
                'sumber_perubahan' => Pembelian::$sumberPerubahan ?? 'unknown',
                'keterangan' => 'Status transaksi diperbarui.',
            ]);
        }
    }

    /**
     * Handle the Pembelian "updated" event.
     */
    public function updated(Pembelian $pembelian): void
    {
        $oldStatus = $pembelian->getOriginal('status');
        $oldStatusVal = $oldStatus instanceof \App\Enums\PembelianStatus ? $oldStatus->value : $oldStatus;
        $newStatusVal = $pembelian->status instanceof \App\Enums\PembelianStatus ? $pembelian->status->value : $pembelian->status;

        if ($oldStatusVal === 'pending' && $newStatusVal === 'success') {
            $varian = $pembelian->varianLayanan;
            $tipe = $varian ? $varian->tipeLayanan : null;
            $produk = $tipe ? $tipe->produk : null;
            $toko = $produk ? $produk->toko : null;

            if (!$toko) {
                \Illuminate\Support\Facades\Log::error("Observer Pembelian: Gagal memproses komisi karena Toko tidak ditemukan pada order #{$pembelian->order_id}");
                return;
            }

            $harga_saat_beli = $pembelian->harga_saat_beli ?? 0;

            // Calculate commission
            $komisiService = new \App\Services\KomisiService();
            $komisi_efektif = $komisiService->getKomisiEfektif($toko);
            $komisi_amount = ($harga_saat_beli * $komisi_efektif) / 100;
            $nominal_masuk = (int) round($harga_saat_beli - $komisi_amount);

            \Illuminate\Support\Facades\DB::transaction(function () use ($toko, $nominal_masuk, $pembelian) {
                $lockedToko = \App\Models\Toko::where('id_toko', $toko->id_toko)->lockForUpdate()->first();
                $saldo_akhir = $lockedToko->saldo + $nominal_masuk;

                // 1. Insert to tbl_mutasi_saldo
                \App\Models\MutasiSaldo::create([
                    'id_toko' => $lockedToko->id_toko,
                    'tipe' => 'kredit_penjualan',
                    'nominal' => $nominal_masuk,
                    'saldo_akhir' => $saldo_akhir,
                    'keterangan' => "Order #{$pembelian->order_id}",
                    'id_beli_produk' => null,
                    'dibuat_oleh' => null,
                ]);

                // 2. Update toko balance
                $lockedToko->update([
                    'saldo' => $saldo_akhir
                ]);
            });

            \Illuminate\Support\Facades\Log::info("Observer Pembelian: Saldo berhasil dikreditkan ke Toko #{$toko->id_toko} sebesar Rp {$nominal_masuk} (Komisi: {$komisi_efektif}%) untuk order #{$pembelian->order_id}");
        }
    }
}
