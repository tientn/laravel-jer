<?php

namespace Laravel\JER;

use Illuminate\Support\ServiceProvider;

class JERServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // loads and publishes translation files
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'jer');
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/jer')]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
