<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\ClientNotification\Http\Controllers\Admin\NotificationController;


Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('host-notifications', [NotificationController::class, 'index'])->name('admin.client-notification.index');
    Route::post('notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('admin.client-notification.read');
    Route::post('notifications/check-email', [NotificationController::class, 'check'])->name('admin.client-notification.check');
    Route::post('notifications/get-token', [NotificationController::class, 'getToken'])->name('admin.client-notification.get-token');

});
