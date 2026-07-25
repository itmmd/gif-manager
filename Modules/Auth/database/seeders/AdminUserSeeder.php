<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Idempotent: updateOrCreate an admin from config('auth.admin.*').
     * Read via config (not env()) so it stays correct after `config:cache`.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => config('auth.admin.email')],
            [
                'name' => config('auth.admin.name'),
                'role' => 'admin',
                'password' => Hash::make(config('auth.admin.password')),
            ]
        );

        $this->command->info("Admin user ready: {$user->email} (id={$user->id})");
    }
}
