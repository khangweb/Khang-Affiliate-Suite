<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\SharedPost\Http\Controllers\Shop\SharedPostController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {

        Route::get('/{slug}', [SharedPostController::class, 'show'])
        ->where('slug', 'about-khangweb') // chỉ chấp nhận slug này
        ->name('shared-post.show');
});