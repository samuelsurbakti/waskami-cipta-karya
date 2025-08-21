<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('task.waskamirealty.com')->group(function () {
    Route::group(['middleware' => ['auth']], function ()
    {
        Route::get('/', function () {
            return redirect(route('AccelTask | Home'));
        });

        Route::group(['middleware' => ['permission:AccelTask - Beranda'], 'prefix' => 'home'], function () {
            Volt::route('', 'accel-task.home.index')->name('AccelTask | Home');
        });
    });
});
