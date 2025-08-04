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
    });
});
