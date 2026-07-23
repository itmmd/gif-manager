<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Http\Livewire\GifGenieSearch;

/*
|--------------------------------------------------------------------------
| Ai Module — Public Routes
|--------------------------------------------------------------------------
*/
Route::prefix('gifs')
    ->name('gifs.')
    ->group(function () {
        // GIF Genie: natural-language semantic search — accessible to everyone
        Route::get('/genie', GifGenieSearch::class)->name('genie');
    });

/*
|--------------------------------------------------------------------------
| Ai Module — Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin/ai')
    ->name('admin.ai.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/moderation', \Modules\Ai\Http\Livewire\ModerationQueue::class)->name('moderation');
    });
