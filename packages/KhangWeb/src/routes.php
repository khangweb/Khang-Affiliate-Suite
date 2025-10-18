<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClearCache\Http\Controllers\CacheController;

Route::group(['prefix' => 'admin/clear-cache', 'middleware' => ['web', 'admin']], function () {
    Route::get('/', [CacheController::class, 'clear'])->name('admin.clear.cache');
});
