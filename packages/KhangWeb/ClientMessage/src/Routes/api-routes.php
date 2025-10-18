<?php 

use Illuminate\Support\Facades\Route;
use KhangWeb\ClientMessage\Http\Controllers\Api\ClientMessageController;

Route::prefix('api/client-messages')->middleware(['api', 'client_message.api_access'])->group(function () {
    Route::get('/', [ClientMessageController::class, 'index']);
    Route::get('{id}', [ClientMessageController::class, 'show'])->whereNumber('id');
    Route::put('{id}', [ClientMessageController::class, 'update'])->whereNumber('id');
});;
