<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $expiredStok = \App\Models\StokAkun::where('status', \App\Enums\StokStatus::RESERVED)
                ->where('reserved_expired_at', '<', now())
                ->get();

            foreach ($expiredStok as $stok) {
                \Illuminate\Support\Facades\DB::transaction(function () use ($stok) {
                    $id_pembelian = $stok->id_pembelian;

                    $stok->update([
                        'status' => \App\Enums\StokStatus::TERSEDIA,
                        'reserved_at' => null,
                        'reserved_expired_at' => null,
                        'id_pembelian' => null,
                    ]);

                    if ($id_pembelian) {
                        $pembelian = \App\Models\Pembelian::where('id_pembelian', $id_pembelian)
                            ->where('status', \App\Enums\PembelianStatus::PENDING)
                            ->first();
                        if ($pembelian) {
                            \App\Models\Pembelian::$sumberPerubahan = 'scheduler_expired';
                            $pembelian->update(['status' => \App\Enums\PembelianStatus::EXPIRED]);
                            \App\Models\Pembelian::$sumberPerubahan = null;
                        }
                    }
                });
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
