<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Allows only Admin (role_id=1).
     * Blocks Seller (role_id=3) and Customer (role_id=2) with 403.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role_id != 1) {
            abort(403, 'Akses khusus Admin.');
        }
        return $next($request);
    }
}
