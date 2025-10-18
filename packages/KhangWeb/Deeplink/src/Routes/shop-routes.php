<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\Deeplink\Http\Controllers\Shop\DeeplinkController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'deeplink'], function () {
    //Route::get('', [DeeplinkController::class, 'index'])->name('shop.deeplink.index');
    Route::get('/{id}/redirect', [DeeplinkController::class, 'redirect'])->name('shop.deeplink.redirect');
});