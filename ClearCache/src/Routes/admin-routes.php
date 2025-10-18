<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClearCache\Http\Controllers\Admin\ClearCacheController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/clear-cache'], function () {
    Route::get('/', [ClearCacheController::class, 'clear'])->name('admin.clear.cache');
});