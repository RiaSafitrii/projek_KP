<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateRedirectUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $response = $next($request);

    //     if ($response instanceof \Illuminate\Http\RedirectResponse) {
    //         $redirectUrl = $response->getTargetUrl();

    //         // Pastikan hanya redirect ke domain sendiri
    //         $allowedDomain = parse_url(config('app.url'), PHP_URL_HOST);
    //         $targetDomain = parse_url($redirectUrl, PHP_URL_HOST);

    //         if ($targetDomain !== null && $targetDomain !== $allowedDomain) {
    //             logger()->warning('Blocked external redirect to: ' . $redirectUrl);
    //             return redirect()->back();
    //         }

    //     }

    //     return $response;
    // }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Mengecek jika response merupakan RedirectResponse
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            $redirectUrl = $response->getTargetUrl();

            // Ambil domain aplikasi yang diizinkan dari config
            $allowedDomain = parse_url(config('app.url'), PHP_URL_HOST);

            // Ambil domain tujuan redirect
            $targetDomain = parse_url($redirectUrl, PHP_URL_HOST);

            // Cek apakah redirect ke halaman login atau URL internal lainnya
            if ($targetDomain !== null && $targetDomain !== $allowedDomain && !str_contains($redirectUrl, route('login'))) {
                // Jika bukan domain yang sama dan bukan halaman login, block redirect
                logger()->warning('Blocked external redirect to: ' . $redirectUrl);
                return redirect()->back();
            }
        }

        return $response;
    }


}
