<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
  public function boot()
{
    View::composer('*', function ($view) {

    $user = Auth::user();

    if (!$user) {
        $view->with('filteredNotifs', collect());
        return;
    }

    if ($user->hasRole('admin')) {
        $filteredNotifs = Application::where('status', 'pending')
            ->where('admin_is_read', 0)
            ->latest()
            ->get();
    }

    elseif ($user->hasRole('super_admin')) {
        $filteredNotifs = Application::where('status', 'evaluated')
            ->where('super_admin_is_read', 0)
            ->latest()
            ->get();
    }

    else {
        $filteredNotifs = collect();
    }

    $view->with('filteredNotifs', $filteredNotifs);
});
}
}
