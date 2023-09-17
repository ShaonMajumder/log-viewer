<?php
use Shaon\Controllers\LogViewerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('logs')->name('logs.')->middleware(['web','auth'])->group(function(){
    Route::get('/', [LogViewerController::class, 'index']);
    Route::get('/get', [LogViewerController::class, 'getFileContent']);
    Route::get('/search-files', [LogViewerController::class, 'searchLogFiles'])->name('search');
});
