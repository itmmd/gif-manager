<?php

use Illuminate\Support\Facades\Route;
use Modules\Gif\Http\Controllers\GifController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('gifs', GifController::class)->names('gif');
});
