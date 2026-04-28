<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

   public function boot()
{
    View::composer('*', function ($view) {
        $user = Auth::user();

        // default safe value
        $filteredNotifs = collect();

        if ($user) {

            // ❌ QS Editor → walang notif
            if ($user->hasRole('qs_editor')) {
                // keep empty
            }

            // ✅ Admin → pending
            elseif ($user->hasRole('admin')) {
                $filteredNotifs = Application::where('status', 'pending')
                    ->where('admin_is_read', 0)
                    ->latest()
                    ->get();
            }

            // ✅ Super Admin → evaluated
            elseif ($user->hasRole('super_admin')) {
                $filteredNotifs = Application::where('status', 'evaluated')
                    ->where('super_admin_is_read', 0)
                    ->latest()
                    ->get();
            }
        }

        // 🔥 ALWAYS PASS (important!)
        $view->with('filteredNotifs', $filteredNotifs);
    });
}
}