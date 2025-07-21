<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::domain('accel.waskami-cipta-karya.test')->group(function () {
    require __DIR__.'/auth.php';

    Route::get('/', function () {
        return redirect(route('Accel | Gate'));
    });

    Route::group(['middleware' => ['auth']], function () {
        Volt::route('gate', 'accel.gate')->name('Accel | Gate');

        Volt::route('account', 'accel.account')->name('Accel | Account');
    });
});
