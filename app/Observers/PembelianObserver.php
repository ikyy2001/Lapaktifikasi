<?php

namespace App\Observers;

use App\Models\CustomerModel;
use App\Models\CustomerTier;
use App\Models\CustomerTierLog;
use App\Models\MutasiSaldo;
use App\Models\Pembelian;
use App\Models\PembelianLog;
use App\Models\Toko;
use App\Services\FonnteService;
use App\Services\KomisiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Idempotency guard: only trigger when transitioning to 'success' from a non-success status
        if ($oldStatusVal !== 'success' && $newStatusVal === 'success') {
            $harga_saat_beli = (float) ($pembelian->harga_saat_beli ?? 0);

            // 1. Process Seller Balance & Commission
            $varian = $pembelian->varianLayanan;
            $tipe = $varian ? $varian->tipeLayanan : null;
            $produk = $tipe ? $tipe->produk : null;
            $toko = $produk ? $produk->toko : null;

            if ($toko) {
                $komisiService = new KomisiService();
                $komisi_efektif = $komisiService->getKomisiEfektif($toko);
                $komisi_amount = ($harga_saat_beli * $komisi_efektif) / 100;
                $nominal_masuk = (int) round($harga_saat_beli - $komisi_amount);

                DB::transaction(function () use ($toko, $nominal_masuk, $pembelian) {
                    $lockedToko = Toko::where('id_toko', $toko->id_toko)->lockForUpdate()->first();
                    if ($lockedToko) {
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
                    }
                });

                Log::info("Observer Pembelian: Saldo berhasil dikreditkan ke Toko #{$toko->id_toko} sebesar Rp {$nominal_masuk} untuk order #{$pembelian->order_id}");
            } else {
                Log::error("Observer Pembelian: Toko tidak ditemukan pada order #{$pembelian->order_id}");
            }

            // 2. Process Customer Accumulation & Tier Upgrade
            if ($pembelian->id_customer) {
                DB::transaction(function () use ($pembelian, $harga_saat_beli) {
                    $lockedCustomer = CustomerModel::where('id', $pembelian->id_customer)->lockForUpdate()->first();
                    if (!$lockedCustomer) {
                        return;
                    }

                    // Increment total accumulation
                    $lockedCustomer->total_belanja_akumulasi += $harga_saat_beli;
                    $lockedCustomer->save();

                    // Check if eligible for tier upgrade
                    $newTier = CustomerTier::where('minimal_belanja', '<=', $lockedCustomer->total_belanja_akumulasi)
                        ->orderBy('urutan', 'desc')
                        ->first();

                    if ($newTier && $lockedCustomer->id_tier_saat_ini !== $newTier->id_tier) {
                        $tierLamaId = $lockedCustomer->id_tier_saat_ini;
                        $lockedCustomer->id_tier_saat_ini = $newTier->id_tier;
                        $lockedCustomer->save();

                        // Log tier upgrade event
                        CustomerTierLog::create([
                            'id_customer' => $lockedCustomer->id,
                            'id_tier_lama' => $tierLamaId,
                            'id_tier_baru' => $newTier->id_tier,
                            'created_at' => now(),
                        ]);

                        // Send WhatsApp notification
                        if (!empty($lockedCustomer->nomor_telepon)) {
                            try {
                                $fonnte = new FonnteService();
                                $message = $this->formatTierUpgradeMessage($lockedCustomer, $newTier, false);
                                $fonnte->sendText($lockedCustomer->nomor_telepon, $message);
                            } catch (\Throwable $e) {
                                Log::error("Observer Pembelian: Gagal mengirim WA naik tier ke customer #{$lockedCustomer->id}: " . $e->getMessage());
                            }
                        }

                        Log::info("Observer Pembelian: Customer #{$lockedCustomer->id} berhasil naik ke tier {$newTier->nama_tier}");
                    }
                });
            }

            // 3. Process Voucher Usage Completion
            if ($pembelian->id_voucher_dipakai) {
                DB::transaction(function () use ($pembelian) {
                    $voucher = \App\Models\Voucher::where('id_voucher', $pembelian->id_voucher_dipakai)->lockForUpdate()->first();
                    if ($voucher) {
                        $voucher->increment('kuota_terpakai');

                        // Update id_pembelian in tbl_voucher_klaim
                        $klaim = \App\Models\VoucherKlaim::where('id_voucher', $voucher->id_voucher)
                            ->where('id_customer', $pembelian->id_customer)
                            ->whereNull('id_pembelian')
                            ->latest('created_at')
                            ->first();

                        if ($klaim) {
                            $klaim->update(['id_pembelian' => $pembelian->id_pembelian]);
                        }
                    }
                });

                Log::info("Observer Pembelian: Voucher #{$pembelian->id_voucher_dipakai} berhasil digunakan untuk order #{$pembelian->order_id}");
            }

            // 4. Process Referral Bonus on First Successful Purchase
            if ($pembelian->id_customer) {
                $customerReferred = CustomerModel::find($pembelian->id_customer);
                if ($customerReferred && $customerReferred->direferensikan_oleh) {
                    $successCount = Pembelian::where('id_customer', $customerReferred->id)
                        ->where('status', \App\Enums\PembelianStatus::SUCCESS)
                        ->count();

                    if ($successCount === 1) {
                        $bonusAmount = (float) config('referral.bonus_akumulasi', 50000);

                        DB::transaction(function () use ($customerReferred, $bonusAmount) {
                            $referrer = CustomerModel::where('id', $customerReferred->direferensikan_oleh)->lockForUpdate()->first();
                            if (!$referrer) {
                                return;
                            }

                            $referrer->jumlah_referral_sukses += 1;
                            $referrer->total_belanja_akumulasi += $bonusAmount;
                            $referrer->save();

                            $newTier = CustomerTier::where('minimal_belanja', '<=', $referrer->total_belanja_akumulasi)
                                ->orderBy('urutan', 'desc')
                                ->first();

                            if ($newTier && $referrer->id_tier_saat_ini !== $newTier->id_tier) {
                                $tierLamaId = $referrer->id_tier_saat_ini;
                                $referrer->id_tier_saat_ini = $newTier->id_tier;
                                $referrer->save();

                                CustomerTierLog::create([
                                    'id_customer' => $referrer->id,
                                    'id_tier_lama' => $tierLamaId,
                                    'id_tier_baru' => $newTier->id_tier,
                                    'created_at' => now(),
                                ]);

                                if (!empty($referrer->nomor_telepon)) {
                                    try {
                                        $fonnte = new FonnteService();
                                        $message = $this->formatTierUpgradeMessage($referrer, $newTier, true);
                                        $fonnte->sendText($referrer->nomor_telepon, $message);
                                    } catch (\Throwable $e) {
                                        Log::error("Observer Pembelian: Gagal kirim WA referral tier upgrade ke #{$referrer->id}: " . $e->getMessage());
                                    }
                                }
                            }
                        });

                        Log::info("Observer Pembelian: Bonus referral Rp {$bonusAmount} diberikan ke Customer #{$customerReferred->direferensikan_oleh} dari transaksi pertama customer #{$customerReferred->id}");
                    }
                }
            }
        }
    }

    /**
     * Format pesan WhatsApp pemberitahuan naik level (Tier Upgrade).
     */
    private function formatTierUpgradeMessage($customer, $newTier, bool $isReferral = false): string
    {
        $customer->loadMissing('user');
        $namaCustomer = $customer->nama_customer ?? $customer->user?->name ?? 'Pelanggan Setia';
        $emailUser = $customer->user?->email ?? '-';
        $noTelp = $customer->nomor_telepon ?? '-';
        $totalAkumulasi = number_format((float) $customer->total_belanja_akumulasi, 0, ',', '.');
        $cashback = (float) $newTier->benefit_cashback_persen;

        $benefitsList = "";
        $benefitsData = is_array($newTier->benefit_deskripsi)
            ? $newTier->benefit_deskripsi
            : json_decode($newTier->benefit_deskripsi ?? '[]', true);

        if (!empty($benefitsData)) {
            foreach ($benefitsData as $b) {
                $benefitsList .= "• " . trim($b) . "\n";
            }
        } else {
            $benefitsList = "• Akses ke berbagai voucher diskon & cashback eksklusif\n";
        }

        $reasonHeader = $isReferral
            ? "Kabar gembira! Karena teman yang kamu ajak berhasil menyelesaikan transaksi pertamanya, akun kamu resmi naik ke *Level {$newTier->nama_tier}*!"
            : "Selamat! Karena transaksi belanja kamu terus bertambah, akun kamu resmi naik ke *Level {$newTier->nama_tier}*!";

        return "🎉 *CONGRATULATIONS! LEVEL UP MEMBERSHIP* 🎉\n"
            . "--------------------------------------------------\n"
            . "Halo *{$namaCustomer}*!\n\n"
            . $reasonHeader . "\n\n"
            . "📋 *DETAIL AKUN CUSTOMER*\n"
            . "• Nama Akun: *{$namaCustomer}*\n"
            . "• Email: *{$emailUser}*\n"
            . "• No. Telepon: *{$noTelp}*\n"
            . "• Status Level Baru: *Level {$newTier->nama_tier}* ⭐\n"
            . "• Total Belanja Akumulasi: *Rp {$totalAkumulasi}*\n\n"
            . "🎁 *KEUNTUNGAN LEVEL {$newTier->nama_tier} KAMU*\n"
            . "• Bonus Cashback Transaksi: *{$cashback}%*\n"
            . $benefitsList . "\n"
            . "Cek benefit selengkapnya dan klaim voucher diskon spesial kamu sekarang di:\n"
            . url('/premium/member') . "\n\n"
            . "_Salam hangat,_\n"
            . "*Tim Lapaktifikasi - Marketplace Akun Premium*";
    }
}
