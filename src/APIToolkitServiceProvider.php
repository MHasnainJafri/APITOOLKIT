<?php

namespace Mhasnainjafri\APIToolkit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as DBBuilder;
class APIToolkitServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerMacro();
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'apitoolkit');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'apitoolkit');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('apitoolkit.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/apitoolkit'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/apitoolkit'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/apitoolkit'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'apitoolkit');

        // Register the main class to use with the facade
        $this->app->singleton('API', function () {
            return new API;
        });
    }

    protected function registerMacro()
    {
        // Register macro for Eloquent Builder
        EloquentBuilder::macro('cachedResponse', function ($uniqueKey = false, $minutes = 30, $statusCode = API::SUCCESS) {
            /**
             * No content response.
             *
             * @var EloquentBuilder $this The current instance of EloquentBuilder.
             */
            return API::cachedResponse($this, $uniqueKey, $minutes, $statusCode);
        });

        // Register macro for Query Builder
        DBBuilder::macro('cachedResponse', function ($uniqueKey = false, $minutes = 30, $statusCode = API::SUCCESS) {
             /**
             * No content response.
             *
             * @var DBBuilder $this The current instance of EloquentBuilder.
             */
            return API::cachedResponse($this, $uniqueKey, $minutes, $statusCode);
        });

        EloquentBuilder::macro('paginatedCachedResponse', function ($uniqueKey = false, $minutes = 30, $statusCode = API::SUCCESS) {
            /**
             * No content response.
             *
             * @var EloquentBuilder $this The current instance of EloquentBuilder.
             */
            return API::paginatedCachedResponse($this, $uniqueKey, $minutes, $statusCode);
        });

        // Register macro for Query Builder
        DBBuilder::macro('paginatedCachedResponse', function ($uniqueKey = false, $minutes = 30, $statusCode = API::SUCCESS) {
             /**
             * No content response.
             *
             * @var DBBuilder $this The current instance of EloquentBuilder.
             */
            return API::paginatedCachedResponse($this, $uniqueKey, $minutes, $statusCode);
        });
    }
}
