<?php

namespace LaLu\JDR;

use Illuminate\Support\ServiceProvider;

class JDRServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('lalu-jdr', function ($app) {
            return new JsonResponse();
        });
    }
}
