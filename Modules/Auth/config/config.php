<?php

return [
    'name' => 'Auth',

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Maximum attempts per minute for the login and two-factor throttles,
    | and the cache-key prefix used by the Login Livewire component's
    | custom RateLimiter logic.
    |
    */
    'limits' => [
        'login_max' => (int) env('AUTH_LOGIN_MAX_ATTEMPTS', 5),
        'two_factor_max' => (int) env('AUTH_TWO_FACTOR_MAX_ATTEMPTS', 5),
        'cache_prefix' => (string) env('AUTH_THROTTLE_PREFIX', 'login'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Post-Auth Redirects
    |--------------------------------------------------------------------------
    |
    | Route names the auth flows redirect to. Kept here so deployment can
    | point at a different landing/home page without touching code.
    |
    */
    'redirects' => [
        'after_login' => env('AUTH_REDIRECT_AFTER_LOGIN', 'landing'),
        'after_register' => env('AUTH_REDIRECT_AFTER_REGISTER', 'admin.dashboard'),
        'after_logout' => env('AUTH_REDIRECT_AFTER_LOGOUT', 'landing'),
        'guest' => 'login',
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Rules
    |--------------------------------------------------------------------------
    |
    | Central source of truth for the password minimum length used by both
    | the Fortify actions (via PasswordValidationRules trait) and the
    | Livewire components' rules().
    |
    */
    'password' => [
        'min_length' => (int) env('AUTH_PASSWORD_MIN', 8),
        'max_length' => 255,
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Lengths
    |--------------------------------------------------------------------------
    |
    | Length of random tokens issued by the auth flows (e.g. the remember
    | token regenerated on password reset).
    |
    */
    'tokens' => [
        'remember_length' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Admin User
    |--------------------------------------------------------------------------
    |
    | Credentials for the AdminUserSeeder. Read via config('auth.admin.*')
    | so it stays correct after `config:cache` (env() returns null there).
    |
    */
    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
        'name' => env('ADMIN_NAME', 'Admin'),
        'password' => env('ADMIN_PASSWORD', 'Admin123456'),
    ],
];
