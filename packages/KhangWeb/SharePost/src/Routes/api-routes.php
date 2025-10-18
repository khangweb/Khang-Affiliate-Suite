<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\SharedPost\Http\Controllers\Api\SharedPostController;


Route::prefix('api/shared-post')->middleware('verify.host.token')->group(function () {
    Route::get('{slug}', [SharedPostController::class, 'getPost']);
    Route::post('update', [SharedPostController::class, 'updateFromHost']);
});