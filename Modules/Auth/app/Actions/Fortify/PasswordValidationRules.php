<?php

namespace Modules\Auth\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::min((int) config('auth.password.min_length')), 'confirmed'];
    }
}
