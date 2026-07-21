<?php

namespace Modules\Auth\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Modules\Auth\Actions\Fortify\UpdateUserPassword;
use Modules\Auth\Actions\Fortify\UpdateUserProfileInformation;

#[Layout('auth::layouts.guest')]
#[Title('My Profile')]
class Profile extends Component
{
    // ── Profile information ───────────────────────────────────────────────

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255')]
    public string $email = '';

    public bool $profileSaved = false;

    // ── Password change ───────────────────────────────────────────────────

    #[Validate('required|string')]
    public string $current_password = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public bool $passwordSaved = false;

    // ─────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->name  = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function updateProfile(UpdateUserProfileInformation $updater): void
    {
        $this->validateOnly('name');
        $this->validateOnly('email');

        $updater->update(auth()->user(), [
            'name'  => $this->name,
            'email' => $this->email,
        ]);

        $this->profileSaved = true;
    }

    public function updatePassword(UpdateUserPassword $updater): void
    {
        $this->validateOnly('current_password');
        $this->validateOnly('password');

        $updater->update(auth()->user(), [
            'current_password'      => $this->current_password,
            'password'              => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->passwordSaved = true;
    }

    public function render(): View
    {
        return view('auth::livewire.profile');
    }
}
