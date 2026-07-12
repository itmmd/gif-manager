<?php

namespace Modules\Auth\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('auth::layouts.guest')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    public string $token = '';

    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public string $errorMessage = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email')->value();
    }

    public function resetPassword(): void
    {
        $this->validate();

        $status = Password::reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->redirect(route('login').'?reset=1', navigate: false);
        } else {
            $this->errorMessage = __($status);
        }
    }

    public function render(): View
    {
        return view('auth::livewire.reset-password');
    }
}
