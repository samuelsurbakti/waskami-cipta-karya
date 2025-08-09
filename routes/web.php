<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

require __DIR__.'/accel-hr.php';
require __DIR__.'/accel-build.php';
require __DIR__.'/accel.php';

Route::get('/', function () {
    return view('welcome');
})->name('home-1');

require __DIR__.'/themes.php';
require __DIR__.'/src.php';
