<?php

use Illuminate\Support\Facades\Route;

Route::get('/core/ping', fn () => response()->json([
    'module' => 'Core',
    'status' => 'loaded',
]))->name('core.ping');

Route::get('/core/livewire-test', fn () => view('core::livewire-test'))->name('core.livewire-test');
