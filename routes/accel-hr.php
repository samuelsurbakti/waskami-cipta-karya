<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('hr.waskami-cipta-karya.test')->group(function () {
    Route::group(['middleware' => ['auth']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelHr | Home'));
        });

        Route::group(['middleware' => ['permission:AccelHr - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-hr.home.index')->name('AccelHr | Home');
        });

        Route::group(['middleware' => ['permission:AccelHr - Pekerja'], 'prefix' => 'worker'], function () {
            Volt::route('', 'accel-hr.worker.index')->name('AccelHr | Worker');
        });
    });
});
