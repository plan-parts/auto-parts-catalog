<?php

namespace PlanParts\Catalog;

use Illuminate\Support\ServiceProvider;

class AutoPartsCatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/auto-parts-catalog.php',
            'auto-parts-catalog'
        );

        $this->app->singleton(ApiClient::class, function () {
            return new ApiClient(
                token:   config('auto-parts-catalog.token'),
                baseUrl: config('auto-parts-catalog.base_url'),
                timeout: config('auto-parts-catalog.timeout'),
            );
        });

        $this->app->alias(ApiClient::class, 'auto-parts-catalog');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'catalog');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/auto-parts-catalog.php' => config_path('auto-parts-catalog.php'),
            ], 'auto-parts-catalog-config');

            $this->publishes([
                __DIR__ . '/../resources/views/' => resource_path('views/vendor/auto-parts-catalog'),
            ], 'auto-parts-catalog-views');
        }
    }

}
