<?php

namespace Modules\Auth\Http\Livewire;

use Illuminate\View\View;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('auth::layouts.guest')]
#[Title('Register')]
class Register extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255|unique:users,email')]
    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:'.config('auth.password.min_length').'|confirmed',
        ];
    }

    public function register(CreatesNewUsers $creator): void
    {
        $this->validate();

        $user = $creator->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        auth()->login($user);
        session()->regenerate();

        $this->redirect(route(config('auth.redirects.after_register')), navigate: false);
    }

    public function render(): View
    {
        return view('auth::livewire.register');
    }
}
