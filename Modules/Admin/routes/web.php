<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Livewire\Dashboard;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth', 'admin'])
    ->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
    });
