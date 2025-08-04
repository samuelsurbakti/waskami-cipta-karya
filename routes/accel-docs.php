<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('docs.waskamirealty.online')->group(function () {
    Route::group(['middleware' => ['auth']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelDocs | Home'));
        });

        Route::group(['middleware' => ['permission:AccelDocs - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-docs.home.index')->name('AccelDocs | Home');
        });
    });
});
