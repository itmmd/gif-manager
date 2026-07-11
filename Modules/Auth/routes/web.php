<?php

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
});
