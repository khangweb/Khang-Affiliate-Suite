<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClientMessage\Http\Controllers\Shop\ClientMessageController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'clientmessage'], function () {
    Route::get('', [ClientMessageController::class, 'index'])->name('shop.clientmessage.index');
});