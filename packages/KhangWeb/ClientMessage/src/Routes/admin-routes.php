<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClientMessage\Http\Controllers\Admin\ClientMessageController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/clientmessage'], function () {
    Route::controller(ClientMessageController::class)->group(function () {
        Route::get('', 'index')->name('admin.client_messages.index');
       
        Route::get('/{id}', [ClientMessageController::class, 'show'])->name('admin.client_messages.show');
            // Route cho việc gửi form liên hệ
        Route::post('/send', [ClientMessageController::class, 'store'])->name('admin.client-messages.store');
            // Xoá tin nhắn
        Route::post('/{id}/delete', [ClientMessageController::class, 'destroy'])->name('admin.client_messages.delete');
    });
});