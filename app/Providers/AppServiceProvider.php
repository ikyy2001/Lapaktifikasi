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
        \App\Models\Review::observe(\App\Observers\ReviewObserver::class);

        // Global Site Settings
        try {
            if (!app()->runningInConsole() && \Illuminate\Support\Facades\Schema::hasTable('setting_websites')) {
                $websiteSettings = \App\Models\SettingWebsite::first();
                \Illuminate\Support\Facades\View::share('websiteSettings', $websiteSettings);
            }
        } catch (\Throwable $e) {
            // Safe fallback during testing or console commands
        }
    }
}
