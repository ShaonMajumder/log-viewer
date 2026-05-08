<?php

namespace Shaon\LogViewer;

use Illuminate\Support\ServiceProvider;

class LaravelLogViewerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/log-viewer.php', 'log-viewer');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'log-viewer');

        $this->publishes([
            __DIR__ . '/../config/log-viewer.php' => config_path('log-viewer.php'),
        ], 'log-viewer-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/log-viewer'),
        ], 'log-viewer-views');
    }
}
