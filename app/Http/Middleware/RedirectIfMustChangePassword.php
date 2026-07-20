<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfMustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Exclude password change routes and logout route
            if (!$request->is('ganti_password') && !$request->is('proses_ganti_password') && !$request->is('logout')) {
                return redirect('/ganti_password')->with('error', 'Anda harus mengganti password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
