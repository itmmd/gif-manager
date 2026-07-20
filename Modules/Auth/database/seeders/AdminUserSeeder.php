<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Idempotent — safe to run multiple times.
     *
     * - If user with this email exists → updates role=admin + password.
     * - If not → creates the user with role=admin.
     *
     * On a fresh environment set ADMIN_EMAIL / ADMIN_PASSWORD in .env,
     * or the defaults below will be used.
     *
     * Usage:
     *   php artisan db:seed --class="Modules\Auth\Database\Seeders\AdminUserSeeder"
     *   php artisan db:seed   (runs via AuthDatabaseSeeder)
     */
    public function run(): void
    {
        $adminEmail    = env('ADMIN_EMAIL',    'admin@gmail.com');
        $adminName     = env('ADMIN_NAME',     'Admin');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin123456');

        // password is included in the update values so:
        //   - on create  → user gets password immediately (no nullable violation)
        //   - on update  → password is refreshed to the current env value
        $user = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name'     => $adminName,
                'role'     => 'admin',
                'password' => Hash::make($adminPassword),
            ]
        );

        $this->command->info("Admin user ready: {$user->email} (id={$user->id})");
    }
}
