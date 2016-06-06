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
        // loads and publishes translation files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'lalu-jdr');
        $this->publishes([__DIR__.'/resources/lang' => $this->resourcePath('lang/vendor/lalu-jdr')]);
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

    /**
     * Lumen compatibility for resource_path().
     *
     * @param string $path
     *
     * @return string
     */
    private function resourcePath($path)
    {
        if (function_exists('resource_path')) {
            return resource_path($path);
        }

        return app()->basePath().DIRECTORY_SEPARATOR.'resources'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
