<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\BeliProdukModel;
use App\Observers\BeliProdukObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        BeliProdukModel::observe(BeliProdukObserver::class);
        \App\Models\Pembelian::observe(\App\Observers\PembelianObserver::class);
    }
}
