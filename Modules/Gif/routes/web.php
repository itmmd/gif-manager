<?php

use Illuminate\Support\Facades\Route;
use Modules\Gif\Http\Livewire\GifIndex;
use Modules\Gif\Http\Livewire\PublicGallery;
use Modules\Gif\Http\Livewire\PublicShow;
use Modules\Gif\Http\Livewire\UploadGif;

Route::prefix('gifs')->name('gifs.')->group(function () {
    Route::get('/',           PublicGallery::class)->name('index');
    Route::get('/{gif:slug}', PublicShow::class)->name('show');
});

Route::prefix('admin/gifs')->name('admin.gifs.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',       GifIndex::class)->name('index');
    Route::get('/upload', UploadGif::class)->name('upload');
});
