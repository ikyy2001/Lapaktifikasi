<?php

namespace App\Console\Commands;

use App\Enums\PembelianStatus;
use App\Models\Pembelian;
use App\Models\SellerBadge;
use App\Models\Toko;
use App\Services\FonnteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EvaluateSellerBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seller:evaluate-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evaluasi dan berikan/cabut badge otomatis ke seller berdasarkan kriteria objektif';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Memulai evaluasi badge seller...');

        $badges = SellerBadge::all();
        if ($badges->isEmpty()) {
            $this->info('Tidak ada badge seller yang terdaftar.');
            return;
        }

        $tokos = Toko::with('badges', 'user')->get();

        foreach ($tokos as $toko) {
            // Calculate store metrics
            $rating = (float) ($toko->rating_rata_rata ?? 0.0);
            $lamaBergabungHari = $toko->created_at ? (int) now()->diffInDays($toko->created_at) : 0;

            // Volume transaksi sukses toko
            $volumeTransaksi = Pembelian::whereHas('varianLayanan.tipeLayanan.produk', function ($q) use ($toko) {
                $q->where('id_toko', $toko->id_toko);
            })->where('status', PembelianStatus::SUCCESS)->count();

            foreach ($badges as $badge) {
                $isEligible = false;
                $kriteriaTipe = $badge->kriteria_tipe;
                $threshold = (float) $badge->kriteria_nilai;

                switch ($kriteriaTipe) {
                    case 'rating_minimal':
                        $isEligible = ($rating >= $threshold);
                        break;

                    case 'lama_bergabung':
                        $isEligible = ($lamaBergabungHari >= (int) $threshold);
                        break;

                    case 'volume_transaksi':
                        $isEligible = ($volumeTransaksi >= (int) $threshold);
                        break;

                    case 'response_time':
                    case 'kecepatan_restock':
                        // Kriteria ini membutuhkan tracking log terpisah. Lewati evaluasi otomatis.
                        Log::debug("Evaluasi Badge: Kriteria '{$kriteriaTipe}' belum memiliki tracking terpisah. Dilewati.");
                        continue 2;

                    default:
                        continue 2;
                }

                $hasBadge = $toko->badges->contains('id_badge', $badge->id_badge);

                // Grant Badge
                if ($isEligible && !$hasBadge) {
                    $toko->badges()->attach($badge->id_badge, ['diperoleh_pada' => now()]);

                    $message = "Selamat! Toko {$toko->nama_toko} berhasil mendapatkan badge baru: '{$badge->nama_badge}'.";
                    $noTelp = $toko->no_telp;

                    if (!empty($noTelp)) {
                        try {
                            $fonnte = new FonnteService();
                            $fonnte->sendText($noTelp, $message);
                        } catch (\Throwable $e) {
                            Log::error("Gagal mengirim WA badge ke Toko #{$toko->id_toko}: " . $e->getMessage());
                        }
                    }

                    $this->info("Badge '{$badge->nama_badge}' DIBERIKAN ke Toko #{$toko->id_toko} ({$toko->nama_toko})");
                    Log::info("Badge '{$badge->nama_badge}' DIBERIKAN ke Toko #{$toko->id_toko}");
                }

                // Revoke Badge (Hanya untuk kriteria dinamis seperti rating_minimal)
                if (!$isEligible && $hasBadge && $kriteriaTipe === 'rating_minimal') {
                    $toko->badges()->detach($badge->id_badge);

                    $this->warn("Badge '{$badge->nama_badge}' DICABUT dari Toko #{$toko->id_toko} ({$toko->nama_toko}) karena rating turun di bawah {$threshold}");
                    Log::info("Badge '{$badge->nama_badge}' DICABUT dari Toko #{$toko->id_toko} (Rating: {$rating} < Threshold: {$threshold})");
                }
            }
        }

        $this->info('Evaluasi badge seller selesai!');
    }
}
