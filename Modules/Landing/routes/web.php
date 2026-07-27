<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Livewire\Landing;

Route::get('/', Landing::class)->name('landing');
