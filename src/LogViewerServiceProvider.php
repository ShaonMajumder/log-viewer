<?php

namespace Shaon\LogViewer;

use Illuminate\Support\ServiceProvider;

class LaravelLogViewerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-log-viewer.php', 'laravel-log-viewer');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-log-viewer');

        $this->publishes([
            __DIR__ . '/../config/laravel-log-viewer.php' => config_path('laravel-log-viewer.php'),
        ], 'laravel-log-viewer-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-log-viewer'),
        ], 'laravel-log-viewer-views');
    }
}
