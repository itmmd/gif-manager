<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Livewire\ForgotPassword;
use Modules\Auth\Http\Livewire\Login;
use Modules\Auth\Http\Livewire\Profile;
use Modules\Auth\Http\Livewire\Register;
use Modules\Auth\Http\Livewire\ResetPassword;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', fn () => redirect()->route(config('auth.redirects.after_login')))->name('home');
    Route::get('/profile', Profile::class)->name('profile');

    Route::post('/logout', function () {
        Auth::guard(config('fortify.guard'))->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route(config('auth.redirects.after_logout'));
    })->name('logout');
});

Route::get('/login.html', function () {
    Auth::guard(config('fortify.guard'))->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route(config('auth.redirects.guest'));
})->name('gentelella.logout');
