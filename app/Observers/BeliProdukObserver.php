<?php

namespace App\Observers;

use App\Models\BeliProdukModel;
use App\Models\Toko;
use App\Models\MutasiSaldo;
use App\Services\KomisiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BeliProdukObserver
{
    /**
     * Handle the BeliProdukModel "updated" event.
     */
    public function updated(BeliProdukModel $model): void
    {
        // Trigger HANYA saat status berubah dari 'pending' menjadi 'success'
        if ($model->isDirty('status') && $model->status === 'success' && $model->getOriginal('status') === 'pending') {
            
            // Ensure we have id_toko
            $id_toko = $model->id_toko;
            if (!$id_toko) {
                // If not set, try to get from product relation
                $produk = $model->produk;
                $id_toko = $produk ? $produk->id_toko : null;
            }

            if (!$id_toko) {
                Log::error("Observer BeliProduk: Gagal memproses komisi karena id_toko tidak ditemukan pada order #{$model->order_id}");
                return;
            }

            $toko = Toko::find($id_toko);
            if (!$toko) {
                Log::error("Observer BeliProduk: Toko dengan ID #{$id_toko} tidak ditemukan untuk order #{$model->order_id}");
                return;
            }

            // Get product price
            $harga_produk = $model->produk ? $model->produk->harga : 0;
            if ($harga_produk <= 0) {
                Log::warning("Observer BeliProduk: Harga produk untuk order #{$model->order_id} bernilai 0 atau tidak ditemukan.");
            }

            // Calculate commission
            $komisiService = new KomisiService();
            $komisi_efektif = $komisiService->getKomisiEfektif($toko);
            $komisi_amount = ($harga_produk * $komisi_efektif) / 100;
            $nominal_masuk = (int) round($harga_produk - $komisi_amount);

            DB::transaction(function () use ($toko, $nominal_masuk, $model) {
                // Lock toko for update to prevent race conditions
                $lockedToko = Toko::where('id_toko', $toko->id_toko)->lockForUpdate()->first();
                
                $saldo_akhir = $lockedToko->saldo + $nominal_masuk;

                // 1. Insert to tbl_mutasi_saldo
                MutasiSaldo::create([
                    'id_toko' => $lockedToko->id_toko,
                    'tipe' => 'kredit_penjualan',
                    'nominal' => $nominal_masuk,
                    'saldo_akhir' => $saldo_akhir,
                    'keterangan' => "Order #{$model->order_id}",
                    'id_beli_produk' => $model->id,
                    'dibuat_oleh' => null, // System credit
                ]);

                // 2. Update toko balance
                $lockedToko->update([
                    'saldo' => $saldo_akhir
                ]);
            });

            Log::info("Observer BeliProduk: Saldo berhasil dikreditkan ke Toko #{$id_toko} sebesar Rp {$nominal_masuk} (Komisi: {$komisi_efektif}%) untuk order #{$model->order_id}");
        }
    }
}
