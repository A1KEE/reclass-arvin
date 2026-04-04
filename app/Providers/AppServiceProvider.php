<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
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
    View::composer('layouts.admin', function ($view) {

        $newPending = Application::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $newPendingCount = Application::where('status', 'pending')->count();

        $view->with([
            'newPending' => $newPending,
            'newPendingCount' => $newPendingCount
        ]);
    });
}
}
