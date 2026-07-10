<?php

use Illuminate\Support\Facades\Route;

// Route تستی برای اطمینان از لود شدن صحیح ماژول Core
Route::get('/core/ping', function () {
    return response()->json([
        'module' => 'Core',
        'status' => 'loaded',
        'message' => 'Core module is working correctly.',
    ]);
})->name('core.ping');
