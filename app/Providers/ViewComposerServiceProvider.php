<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'operator') {
                $userId = Auth::id();

                $navInfo = DB::table('nav_user_relations')
                ->join('navigation', 'navigation.id', '=', 'nav_user_relations.navigation_id')
                ->join('group_navigation', 'group_navigation.id', '=', 'navigation.group_navigation_id')
                ->where('nav_user_relations.user_id', $userId)
                ->select(
                    'group_navigation.name as group_name',
                    'navigation.name as navigation_name',
                    'navigation.value'
                )
                ->orderBy('group_navigation.id', 'asc')    // urut berdasarkan id grup dulu
                ->orderBy('group_navigation.name', 'asc')  // baru nama grup
                ->get();


                $groupedNav = $navInfo->groupBy('group_name');

                $view->with('groupedNav', $groupedNav);
            } else {
                // Share default kosong jika bukan operator atau belum login
                $view->with('groupedNav', collect());
            }
        });
    }

    public function register(): void
    {
        //
    }
}
