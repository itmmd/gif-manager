<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Livewire\ForgotPassword;
use Modules\Auth\Http\Livewire\Login;
use Modules\Auth\Http\Livewire\Profile;
use Modules\Auth\Http\Livewire\Register;
use Modules\Auth\Http\Livewire\ResetPassword;

/*
|--------------------------------------------------------------------------
| Auth Module Web Routes
|--------------------------------------------------------------------------
|
| Fortify routes اصلی (POST /login, POST /register, ...) را خودش ثبت می‌کند؛
| اینجا فقط GET viewهای مورد نیاز Fortify و Livewire full-page componentها
| را تعریف می‌کنیم. Viewها در FortifyServiceProvider متصل شده‌اند.
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

    // Profile — accessible to every authenticated user (not admin-only).
    Route::get('/profile', Profile::class)->name('profile');

    // Logout via POST to keep CSRF protection.
    Route::post('/logout', function () {
        Auth::guard(config('fortify.guard'))->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route(config('auth.redirects.after_logout'));
    })->name('logout');
});

// Gentelella's JS hardcodes "login.html" as the Sign-Out target; this
// catches it, logs the user out, and redirects to /login.
Route::get('/login.html', function () {
    Auth::guard(config('fortify.guard'))->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route(config('auth.redirects.guest'));
})->name('gentelella.logout');
