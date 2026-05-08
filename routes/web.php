<?php

use Illuminate\Support\Facades\Route;
use Robist\LaravelLogViewer\Http\Controllers\LaravelLogController;

Route::middleware(config('laravel-log-viewer.middleware', ['web', 'auth']))
    ->prefix(config('laravel-log-viewer.route_prefix', 'laravel-log'))
    ->as(config('laravel-log-viewer.route_name_prefix', 'laravel.log.'))
    ->group(function () {
        Route::get('/', [LaravelLogController::class, 'index'])->name('index');
        Route::get('/download', [LaravelLogController::class, 'download'])->name('download');
    });
