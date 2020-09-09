<?php

namespace MartinMulder\VMWare\Laravel;

use Illuminate\Support\ServiceProvider;

class VMWareServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fredbradley');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'fredbradley');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        //$this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/vmware.php', 'vmware');

        // Register the service the package provides.
        $this->app->singleton('vmware', function ($app) {
            return new VMWare;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['vmware'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/vmware.php' => config_path('vmware.php'),
        ], 'vmware.config');

    }
}
