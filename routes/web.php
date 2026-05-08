<?php

use Illuminate\Support\Facades\Route;
use Shaon\LogViewer\Http\Controllers\LaravelLogController;

Route::middleware(config('log-viewer.middleware', ['web', 'auth']))
    ->prefix(config('log-viewer.route_prefix', 'laravel-log'))
    ->as(config('log-viewer.route_name_prefix', 'laravel.log.'))
    ->group(function () {
        Route::get('/', [LaravelLogController::class, 'index'])->name('index');
        Route::get('/download', [LaravelLogController::class, 'download'])->name('download');
    });
