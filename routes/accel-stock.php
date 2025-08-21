<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('stock.waskamirealty.com')->group(function () {
    Route::group(['middleware' => ['auth']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelStock | Home'));
        });

        Route::group(['middleware' => ['permission:AccelStock - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-stock.home.index')->name('AccelStock | Home');
        });
    });
});
