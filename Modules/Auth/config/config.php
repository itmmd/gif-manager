<?php

return [
    'name' => 'Auth',

    'limits' => [
        'login_max' => (int) env('AUTH_LOGIN_MAX_ATTEMPTS', 5),
        'two_factor_max' => (int) env('AUTH_TWO_FACTOR_MAX_ATTEMPTS', 5),
        'cache_prefix' => (string) env('AUTH_THROTTLE_PREFIX', 'login'),
    ],

    'redirects' => [
        'after_login' => env('AUTH_REDIRECT_AFTER_LOGIN', 'landing'),
        'after_register' => env('AUTH_REDIRECT_AFTER_REGISTER', 'admin.dashboard'),
        'after_logout' => env('AUTH_REDIRECT_AFTER_LOGOUT', 'landing'),
        'guest' => 'login',
    ],

    'password' => [
        'min_length' => (int) env('AUTH_PASSWORD_MIN', 8),
        'max_length' => 255,
    ],

    'tokens' => [
        'remember_length' => 60,
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
        'name' => env('ADMIN_NAME', 'Admin'),
        'password' => env('ADMIN_PASSWORD', 'Admin123456'),
    ],
];
