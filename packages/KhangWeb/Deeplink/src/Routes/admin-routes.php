<?php

use Illuminate\Support\Facades\Route;
use KhangWeb\Deeplink\Http\Controllers\Admin\DeeplinkController;
use KhangWeb\Deeplink\Http\Controllers\Admin\ProductUrlController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/deeplink'], function () {
    Route::controller(DeeplinkController::class)->group(function () {
        // Danh sách
        Route::get('', 'index')->name('admin.deeplink.index');

        // Form tạo mới
        Route::get('create', 'create')->name('admin.deeplink.create');

        // Lưu mới
        Route::post('', 'store')->name('admin.deeplink.store');

        // Form chỉnh sửa
        Route::get('{id}/edit', 'edit')->name('admin.deeplink.edit');

        // Cập nhật
        Route::put('{id}', 'update')->name('admin.deeplink.update');

        // Xoá
        Route::delete('{id}/delete', 'destroy')->name('admin.deeplink.destroy');

        // ✅ Thêm route mass delete
        Route::post('mass-delete', 'massDelete')->name('admin.deeplink.mass_delete');

        Route::post('deeplink/mass-update-status', 'massUpdateStatus')->name('admin.deeplink.mass_update_status');
            
    });
});

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/product-url'], function () {
    Route::controller(ProductUrlController::class)->group(function () {
        // Danh sách
        Route::get('', 'index')->name('admin.product-url.index');
        // Form chỉnh sửa
        Route::get('{id}/edit', 'edit')->name('admin.product-url.edit');
        // Cập nhật
        Route::put('{id}', 'update')->name('admin.product-url.update');

    });
});
