<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('build.waskamirealty.online')->group(function () {
    Route::group(['middleware' => ['auth', 'log_page_view']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelBuild | Home'));
        });

        Route::group(['middleware' => ['permission:AccelBuild - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-build.home.index')->name('AccelBuild | Home');
        });
    });
});
