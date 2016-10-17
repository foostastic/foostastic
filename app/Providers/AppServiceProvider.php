<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Api\Airbrake', function ($app) {
            $api =  new \App\Api\Airbrake();
            $api->init();
            return $api;
        });
    }
}
