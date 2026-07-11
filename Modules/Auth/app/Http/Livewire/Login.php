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

#[Layout('auth::layouts.auth')]
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

        $throttleKey = Str::lower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts('login:'.$throttleKey, 5)) {
            $seconds = RateLimiter::availableIn('login:'.$throttleKey);
            $this->errorMessage = __('Too many login attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]);

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit('login:'.$throttleKey);
            $this->errorMessage = __('These credentials do not match our records.');

            return;
        }

        RateLimiter::clear('login:'.$throttleKey);
        session()->regenerate();

        $this->redirect(config('fortify.home', '/home'), navigate: false);
    }

    public function render(): View
    {
        return view('auth::livewire.login');
    }
}
