<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Livewire\ForgotPassword;
use Modules\Auth\Http\Livewire\Login;
use Modules\Auth\Http\Livewire\Register;
use Modules\Auth\Http\Livewire\ResetPassword;

/*
|--------------------------------------------------------------------------
| Auth Module Web Routes
|--------------------------------------------------------------------------
|
| Fortify به‌صورت خودکار routes اصلی (POST /login, POST /register, ...)
| را register می‌کنه. اینجا فقط GET views را map می‌کنیم که Fortify
| از ما می‌خواد.
|
| نکته: Fortify::loginView() و بقیه در FortifyServiceProvider تنظیم شدن
| و به view های این ماژول اشاره دارن. این routes برای Livewire full-page
| component ها هستند.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/home', fn () => redirect()->route('admin.dashboard'))->name('home');

    // Logout: POST to preserve CSRF protection, then redirect to landing.
    Route::post('/logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('landing');
    })->name('logout');
});

// Gentelella's built-in JS hardcodes "login.html" as the Sign Out destination.
// This route catches that GET request, logs the user out properly, and
// redirects to /login — no JavaScript patching needed.
Route::get('/login.html', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('gentelella.logout');
