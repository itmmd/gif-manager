<?php

namespace Modules\Auth\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('auth::layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';

    public function login(): void
    {
        $this->validate();

        $prefix = config('auth.limits.cache_prefix');
        $max = (int) config('auth.limits.login_max');
        $key = $prefix.':'.Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, $max)) {
            $this->errorMessage = __('Too many login attempts. Please try again in :seconds seconds.', [
                'seconds' => RateLimiter::availableIn($key),
            ]);

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key);
            $this->errorMessage = __('These credentials do not match our records.');

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        $this->redirect(route(config('auth.redirects.after_login')), navigate: false);
    }

    public function render(): View
    {
        return view('auth::livewire.login');
    }
}
