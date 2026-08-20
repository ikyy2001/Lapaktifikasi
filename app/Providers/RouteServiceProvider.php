<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth_rate_limit', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip())->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terlalu banyak percobaan autentikasi. Silakan tunggu 1 menit.',
                    'errors' => []
                ], 429);
            });
        });

        RateLimiter::for('checkout_rate_limit', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terlalu banyak permintaan transaksi dalam waktu singkat. Silakan tunggu.',
                    'errors' => []
                ], 429);
            });
        });

        RateLimiter::for('webhook_rate_limit', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('limit_login', function (Request $request) {
            return Limit::perMinute(6)->by($request->ip());
        });
    }
}
