<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

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
    $this->app->booted(function () {
        $schedule = app(Schedule::class);

        // Schedule publishing command to run every minute
        $schedule->command('posts:publish-scheduled')->everyMinute();
    });
}
}
