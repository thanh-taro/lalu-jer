<?php

namespace LaLu\JER;

use Illuminate\Support\ServiceProvider;

class JERServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // loads and publishes translation files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'lalu-jer');
        $this->publishes([__DIR__.'/resources/lang' => $this->resourcePath('lang/vendor/lalu-jer')]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('lalu-jer', function ($app) {
            return new JsonExceptionResponse();
        });
    }

    /**
     * Lumen compatibility for resource_path()
     *
     * @param  string  $path
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
