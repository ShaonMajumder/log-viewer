<?php

use Illuminate\Support\Facades\Route;
use Shaon\LogViewer\Http\Controllers\LaravelLogController;

$middleware = (array) config('log-viewer.middleware', ['web', 'auth']);

// Gracefully skip unregistered middleware aliases (common in minimal app setups).
try {
    $aliases = app('router')->getMiddleware();
    $middleware = array_values(array_filter($middleware, static function ($name) use ($aliases) {
        return !is_string($name) || array_key_exists($name, $aliases);
    }));
} catch (\Throwable $e) {
    // Keep configured middleware when router resolution is unavailable.
}

Route::middleware($middleware)
    ->prefix(config('log-viewer.route_prefix', 'log-viewer'))
    ->as(config('log-viewer.route_name_prefix', 'laravel.log.'))
    ->group(function () {
        Route::get('/', [LaravelLogController::class, 'index'])->name('index');
        Route::get('/download', [LaravelLogController::class, 'download'])->name('download');
    });
