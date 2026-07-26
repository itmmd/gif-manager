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

    public string $password = '';

    public string $password_confirmation = '';

    public string $errorMessage = '';

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:'.config('auth.password.min_length').'|confirmed',
        ];
    }

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
                    'remember_token' => Str::random((int) config('auth.tokens.remember_length')),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->redirect(route(config('auth.redirects.guest')).'?reset=1', navigate: false);
        } else {
            $this->errorMessage = __($status);
        }
    }

    public function render(): View
    {
        return view('auth::livewire.reset-password');
    }
}
