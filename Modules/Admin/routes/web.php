<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Livewire\Dashboard;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
});
