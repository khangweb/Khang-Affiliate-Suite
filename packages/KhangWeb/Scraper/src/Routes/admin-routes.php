<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use KhangWeb\Scraper\Http\Controllers\Admin\ScrapedProductController;
use KhangWeb\Scraper\Http\Controllers\Admin\ImportSettingController;
use KhangWeb\Scraper\Http\Controllers\Admin\ScrapingTemplateController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/scraper'], function () {

    Route::controller(ScrapedProductController::class)->group(function () {
        Route::get('', 'index')->name('scraper.admin.scraped_products.index');
        Route::get('create', 'create')->name('scraper.admin.scraped_products.create');
        Route::post('store', 'store')->name('scraper.admin.scraped_products.store');
        Route::get('edit/{id}', 'edit')->name('scraper.admin.scraped_products.edit');
        Route::put('update/{id}', 'update')->name('scraper.admin.scraped_products.update');
        Route::post('mass-destroy', 'massDestroy')->name('admin.scraped_products.mass_destroy');
        Route::delete('destroy/{id}', 'destroy')->name('scraper.admin.scraped_products.destroy');
        Route::post('mass-dispatch-for-import', 'massDispatchForImport')->name('admin.scraped_products.mass_dispatch_for_import');
        Route::post('mass_update_status', 'massUpdateStatus')->name('admin.scraped_products.mass_update_status');

    });


    Route::controller(ImportSettingController::class)->group(function () {
        Route::get('import-setting', 'index')->name('admin.scraper.import.index');
        Route::post('import-setting', 'save')->name('admin.scraper.import.save');
    });
    
    Route::controller(ScrapingTemplateController::class)->group(function () {
        Route::get('scraping-templates', 'index')->name('admin.scraper.scraping-templates.index');
        Route::get('scraping-templates/create', 'create')->name('admin.scraper.scraping-templates.create');
        Route::post('scraping-templates', 'store')->name('admin.scraper.scraping-templates.store');
        Route::get('scraping-templates/{id}/edit', 'edit')->name('admin.scraper.scraping-templates.edit');
        Route::put('scraping-templates/{id}', 'update')->name('admin.scraper.scraping-templates.update');
        Route::post('scraping-templates/{id}/delete', 'destroy')->name('admin.scraper.scraping-templates.destroy');
        Route::post('scraping-templates/mass-destroy', 'massDestroy')->name('admin.scraper.scraping-templates.mass_destroy');

        // Export & Import
        Route::get('scraping-templates/export', 'export')->name('admin.scraper.scraping-templates.export');
        Route::post('scraping-templates/import', 'import')->name('admin.scraper.scraping-templates.import');
    });

});
