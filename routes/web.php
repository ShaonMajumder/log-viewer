<?php

use Illuminate\Support\Facades\Route;
use Shaon\LogViewer\Http\Controllers\LaravelLogController;

$middleware = (array) config('log-viewer.middleware', ['web']);

// Gracefully skip unregistered middleware aliases (common in minimal app setups).
try {
    $router = app('router');
    $aliases = $router->getMiddleware();
    $groups = method_exists($router, 'getMiddlewareGroups') ? $router->getMiddlewareGroups() : [];
    $middleware = array_values(array_filter($middleware, static function ($name) use ($aliases, $groups) {
        return !is_string($name) || array_key_exists($name, $aliases) || array_key_exists($name, $groups);
    }));
} catch (\Throwable $e) {
    // Keep configured middleware when router resolution is unavailable.
}

Route::middleware($middleware)
    ->prefix(config('log-viewer.route_prefix', 'log-viewer'))
    ->as(config('log-viewer.route_name_prefix', 'log.viewer.'))
    ->group(function () {
        Route::get('/', [LaravelLogController::class, 'index'])->name('index');
        Route::get('/download', [LaravelLogController::class, 'download'])->name('download');
    });
