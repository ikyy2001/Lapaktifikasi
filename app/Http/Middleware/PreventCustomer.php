<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PreventCustomer
{
    /**
     * Handle an incoming request.
     * Allows Admin (role_id=1) and Seller (role_id=3).
     * Blocks Customer (role_id=2) with 403.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = Auth::user()->role_id;
        if ($role != 1 && $role != 3) {
            abort(403, 'Forbidden access.');
        }
        return $next($request);
    }
}
