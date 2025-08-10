<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('ref.waskamirealty.online')->group(function () {
    Route::group(['middleware' => ['auth']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelRef | Home'));
        });

        Route::group(['middleware' => ['permission:AccelRef - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-ref.home.index')->name('AccelRef | Home');
        });

        Route::group(['middleware' => ['permission:AccelRef - Provinsi'], 'prefix' => 'province'], function () {
            Volt::route('', 'accel-ref.province.index')->name('AccelRef | Province');
        });

        Route::group(['middleware' => ['permission:AccelRef - Kabupaten/Kota'], 'prefix' => 'regency'], function () {
            Volt::route('', 'accel-ref.regency.index')->name('AccelRef | Regency');
        });

        Route::group(['middleware' => ['permission:AccelRef - Kecamatan'], 'prefix' => 'district'], function () {
            Volt::route('', 'accel-ref.district.index')->name('AccelRef | District');
        });

        Route::group(['middleware' => ['permission:AccelRef - Kelurahan/Desa'], 'prefix' => 'village'], function () {
            Volt::route('', 'accel-ref.village.index')->name('AccelRef | Village');
        });
    });
});
