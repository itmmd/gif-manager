<?php

use Illuminate\Support\Facades\Route;

// Route تستی ساده — بررسی لود شدن ماژول
Route::get('/core/ping', function () {
    return response()->json([
        'module' => 'Core',
        'status' => 'loaded',
        'message' => 'Core module is working correctly.',
    ]);
})->name('core.ping');

// Route تستی Livewire — بررسی صحت راه‌اندازی Livewire 4
Route::get('/core/livewire-test', function () {
    return view('core::livewire-test');
})->name('core.livewire-test');
