<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Reservation;
use App\Models\Report;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.operator', function ($view) {
            $view->with([
                'navPendingCount'   => Reservation::where('status', 'pending')->count(),
                'navNewReportCount' => Report::where('status', 'baru')->count(),
            ]);
        });
    }
}
