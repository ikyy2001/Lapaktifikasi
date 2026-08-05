<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\SettingKomisi;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * If Maintenance Mode is active:
     * - Admin (role_id = 1) can access everything (dashboard & settings).
     * - Landing page & Login/Auth routes are accessible.
     * - Seller (role_id = 3) and Customer (role_id = 2) or guest trying to access dashboard/protected routes get 503 Under Maintenance.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $setting = Cache::remember('setting_komisi_global', 300, function () {
            return SettingKomisi::first();
        });

        if ($setting && $setting->is_maintenance) {
            // Admin (role_id == 1) retains full access
            if (Auth::check() && (int) Auth::user()->role_id === 1) {
                return $next($request);
            }

            // Excluded paths (Landing Page & Login/Auth routes)
            $excludedPaths = [
                '/',
                'login',
                'pendaftaran',
                'proses_login',
                'proses_pendaftaran',
                'logout',
                'lupa_password',
                'reset_password',
                'reset_password/*',
                'daftar-jadi-seller',
                'join-partner',
                'redirect',
                'auth/google/callback',
            ];

            foreach ($excludedPaths as $path) {
                if ($request->is($path)) {
                    return $next($request);
                }
            }

            // If Ajax / JSON request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sistem sedang dalam pemeliharaan (Under Maintenance).'
                ], 503);
            }

            // Return modern Under Maintenance page
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
