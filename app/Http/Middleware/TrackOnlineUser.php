<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackOnlineUsers
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
        $now = now();

        // Cek apakah pengguna sudah ada
        $onlineUser = DB::table('online_users')
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->first();

        if ($onlineUser) {
            // Perbarui aktivitas terakhir
            DB::table('online_users')
                ->where('id', $onlineUser->id)
                ->update(['last_activity' => $now]);
        } else {
            // Tambahkan pengguna baru
            DB::table('online_users')->insert([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'last_activity' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return $next($request);
    }
}
