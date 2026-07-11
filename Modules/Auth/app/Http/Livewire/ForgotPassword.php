<?php

namespace Modules\Auth\Http\Livewire;

use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('auth::layouts.auth')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    public string $status = '';

    public string $errorMessage = '';

    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);
            $this->email = '';
        } else {
            $this->errorMessage = __($status);
        }
    }

    public function render(): View
    {
        return view('auth::livewire.forgot-password');
    }
}
