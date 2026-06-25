<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');
        $today = now()->toDateString();

        // Cek apakah kunjungan sudah dicatat hari ini
        $alreadyTracked = DB::table('visitor_stats')
            ->where('ip_address', $ip)
            ->where('visit_date', $today)
            ->exists();

        if (!$alreadyTracked) {
            DB::table('visitor_stats')->insert([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'visit_date' => $today,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }
}
