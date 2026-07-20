<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Toko;
use App\Models\Pembelian;
use App\Models\MutasiSaldo;
use App\Services\KomisiService;
use Illuminate\Support\Facades\DB;
use App\Enums\PembelianStatus;

class RepairBalancesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:repair';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Retroactively credit store balances for successful premium purchases that were missed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $komisiService = new KomisiService();
        $this->info("Starting retroactive balance repair...");

        $completedPembelian = Pembelian::where('status', PembelianStatus::SUCCESS)->get();
        $count = 0;

        foreach ($completedPembelian as $pembelian) {
            $varian = $pembelian->varianLayanan;
            $tipe = $varian ? $varian->tipeLayanan : null;
            $produk = $tipe ? $tipe->produk : null;
            $toko = $produk ? $produk->toko : null;

            if (!$toko) {
                $this->comment("Order {$pembelian->order_id}: Skipped (No Toko associated)");
                continue;
            }

            // Check if already has a mutasi entry
            $exists = MutasiSaldo::where('id_toko', $toko->id_toko)
                ->where('keterangan', "Order #{$pembelian->order_id}")
                ->exists();

            if ($exists) {
                $this->comment("Order {$pembelian->order_id}: Already processed");
                continue;
            }

            $harga = $pembelian->harga_saat_beli;
            $komisi_efektif = $komisiService->getKomisiEfektif($toko);
            $komisi_amount = ($harga * $komisi_efektif) / 100;
            $nominal_masuk = (int) round($harga - $komisi_amount);

            $this->info("Order {$pembelian->order_id} (Toko: {$toko->nama_toko}): crediting Rp " . number_format($nominal_masuk) . " (Price: Rp " . number_format($harga) . ", Commission: {$komisi_efektif}%)");

            DB::transaction(function () use ($toko, $nominal_masuk, $pembelian) {
                $lockedToko = Toko::where('id_toko', $toko->id_toko)->lockForUpdate()->first();
                $saldo_akhir = $lockedToko->saldo + $nominal_masuk;

                MutasiSaldo::create([
                    'id_toko' => $lockedToko->id_toko,
                    'tipe' => 'kredit_penjualan',
                    'nominal' => $nominal_masuk,
                    'saldo_akhir' => $saldo_akhir,
                    'keterangan' => "Order #{$pembelian->order_id}",
                    'id_beli_produk' => null,
                    'dibuat_oleh' => null,
                ]);

                $lockedToko->update([
                    'saldo' => $saldo_akhir
                ]);
            });
            $count++;
        }

        $this->info("Retroactive repair finished! Total orders repaired: {$count}");
    }
}
