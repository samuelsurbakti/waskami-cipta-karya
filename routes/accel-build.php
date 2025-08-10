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

        Route::group(['middleware' => ['permission:AccelBuild - Proyek'], 'prefix' => 'project'], function () {
            Volt::route('', 'accel-build.project.index')->name('AccelBuild | Project');
        });

        Route::group(['middleware' => ['permission:AccelBuild - Komponen Proyek'], 'prefix' => 'project-component'], function () {
            Volt::route('', 'accel-build.project-component.index')->name('AccelBuild | Project Component');
        });
    });
});
