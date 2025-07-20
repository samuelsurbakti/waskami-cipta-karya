<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('accel.waskami-cipta-karya.test')->group(function () {
    require __DIR__.'/auth.php';

    Route::get('/', function () {
        return redirect(route('Waskami Control Center | Gate'));
    });

    Route::group(['middleware' => ['auth']], function () {
        Volt::route('gate', 'wcc.gate')->name('Waskami Control Center | Gate');
    });
});
