<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\SharedPost\Http\Controllers\Admin\SharedPostController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/sharedpost'], function () {
    Route::controller(SharedPostController::class)->group(function () {
        Route::get('', 'index')->name('admin.sharedpost.index');
    });
});