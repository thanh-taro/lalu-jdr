<?php

namespace LaLu\JDR;

use Illuminate\Support\ServiceProvider;
use LaLu\JDR\Helpers\Helper;

class JDRServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // loads and publishes translation files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'lalu-jdr');
        $this->publishes([__DIR__.'/resources/lang' => Helper::resourcePath('lang/vendor/lalu-jdr')]);
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
