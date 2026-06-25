<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Hilangkan spasi yang tidak perlu dan pecah peran menjadi array
        $roles = array_map('trim', $roles);  // Hapus spasi di sekitar elemen

        // Periksa apakah user memiliki salah satu role yang diizinkan
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            Auth::logout();
            return redirect()->route('login')->with('fail', 'Anda tidak memiliki akses ke halaman ini, Harap masuk kembali untuk membuktikan bahwa itu Anda');
        }

        return $next($request);
    }
}
