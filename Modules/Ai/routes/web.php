<?php

use Illuminate\Support\Facades\Route;
use Modules\Ai\Http\Livewire\GifGenieSearch;
use Modules\Ai\Http\Livewire\ModerationQueue;

Route::prefix('gifs')->name('gifs.')->group(function () {
    Route::get('/genie', GifGenieSearch::class)->name('genie');
});

Route::prefix('admin/ai')->name('admin.ai.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/moderation', ModerationQueue::class)->name('moderation');
});
