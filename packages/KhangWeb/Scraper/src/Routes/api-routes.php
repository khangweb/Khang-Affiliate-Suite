<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use KhangWeb\Scraper\Http\Controllers\Api\ScrapingTemplateController;
use KhangWeb\Scraper\Http\Controllers\Api\ScrapedProductController;
use KhangWeb\Scraper\Http\Controllers\Api\TokenReceiverController;
use KhangWeb\Scraper\Http\Controllers\Api\AuthController;

Route::group([
    'prefix' => 'scraper',
    'middleware' => ['api',
    'extension.key'
],
], function () {

    Route::get('verify-token', [AuthController::class, 'verify']);
    Route::get('templates', [ScrapingTemplateController::class, 'index']);
    Route::post('templates', [ScrapingTemplateController::class, 'store']);
    Route::get('templates/{id}', [ScrapingTemplateController::class, 'show']);
    Route::put('templates/{id}', [ScrapingTemplateController::class, 'update']);
    Route::delete('templates/{id}', [ScrapingTemplateController::class, 'destroy']);
    Route::post('product', [ScrapedProductController::class, 'store']);
});

Route::group([
    'prefix' => 'api',
    'middleware' => ['api' ],
], function () {
        // Route nhận token từ Web B
    Route::post('store-token', [TokenReceiverController::class, 'store']);
});

