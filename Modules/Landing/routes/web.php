<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Livewire\Landing;

/*
|--------------------------------------------------------------------------
| Landing Module Routes
|--------------------------------------------------------------------------
|
| The root route (/) serves the Landing page Livewire component.
| This replaces Laravel's default welcome.blade.php.
|
*/

Route::get('/', Landing::class)->name('landing');
