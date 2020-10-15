<?php

namespace App\Providers;

use EmailNotification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Mailing\NotificationContract;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->singleton(
            'App\Mailing\NotificationContract',
            'App\Mailing\EmailNotification'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
