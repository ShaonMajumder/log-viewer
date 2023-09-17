<?php

namespace Shaon\ServiceProvider;

use Illuminate\Support\ServiceProvider;

class LogViewerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('log-viewer', function () {
            return new \Shaon\LogViewer(); // Replace with your actual instantiation logic.
        });
    }

    public function boot()
    {
        $this->loadRoutes();
        $this->loadViews();
    }

    public function loadRoutes(){
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
    
    public function loadViews(){
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'log-viewer');
    }
}
