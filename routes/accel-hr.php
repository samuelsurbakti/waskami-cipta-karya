<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('hr.waskamirealty.online')->group(function () {
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

            Route::group(['middleware' => ['permission:AccelHr - Pekerja - Melihat Data'], 'prefix' => '{id}'], function () {
                Volt::route('', 'accel-hr.worker.show')->name('AccelHr | Worker | Show');
            });
        });

        Route::group(['middleware' => ['permission:AccelHr - Tim'], 'prefix' => 'team'], function () {
            Volt::route('', 'accel-hr.team.index')->name('AccelHr | Team');

            Route::group(['middleware' => ['permission:AccelHr - Tim - Melihat Data'], 'prefix' => '{id}'], function () {
                Volt::route('', 'accel-hr.team.show')->name('AccelHr | Team | Show');
            });
        });

        Route::group(['middleware' => ['permission:AccelHr - Kontrak'], 'prefix' => 'contract'], function () {
            Volt::route('', 'accel-hr.contract.index')->name('AccelHr | Contract');

            Route::group(['middleware' => ['permission:AccelHr - Tim - Melihat Data'], 'prefix' => '{id}'], function () {
                Volt::route('', 'accel-hr.team.show')->name('AccelHr | Team | Show');
            });
        });

        Route::group(['middleware' => ['permission:AccelHr - Presensi'], 'prefix' => 'attendance'], function () {
            Volt::route('', 'accel-hr.attendance.index')->name('AccelHr | Attendance');
        });
    });
});
