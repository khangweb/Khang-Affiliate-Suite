<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClearCache\Http\Controllers\Shop\ClearCacheController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'clearcache'], function () {
    Route::get('', [ClearCacheController::class, 'index'])->name('shop.clearcache.index');
});