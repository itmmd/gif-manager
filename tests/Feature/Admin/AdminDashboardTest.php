<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

/**
 * Admin Dashboard Feature Tests
 *
 * DB-dependent tests require pdo_sqlite and are grouped as 'db'.
 * Run without DB tests: vendor/bin/pest --exclude-group=db
 */
class AdminDashboardTest extends TestCase
{
    /**
     * Unauthenticated requests must redirect to login.
     * No DB required — middleware acts before any DB query.
     */
    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    // ── DB-dependent (require pdo_sqlite) ─────────────────────────────────

    /** @group db */
    public function test_admin_dashboard_returns_200(): void
    {
        $this->skipIfNoPdoSqlite();
        $this->artisan('migrate', ['--env' => 'testing', '--force' => true]);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->withoutVite()->get('/admin')->assertStatus(200);
    }

    /** @group db */
    public function test_admin_dashboard_contains_sidebar(): void
    {
        $this->skipIfNoPdoSqlite();
        $this->artisan('migrate', ['--env' => 'testing', '--force' => true]);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->withoutVite()->get('/admin')->assertSee('sidebar', escape: false);
    }

    /** @group db */
    public function test_admin_dashboard_references_gentelella_css(): void
    {
        $this->skipIfNoPdoSqlite();
        $this->artisan('migrate', ['--env' => 'testing', '--force' => true]);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->withoutVite()->get('/admin')->assertSee('admin-assets/css', escape: false);
    }

    /** @group db */
    public function test_regular_user_cannot_access_admin(): void
    {
        $this->skipIfNoPdoSqlite();
        $this->artisan('migrate', ['--env' => 'testing', '--force' => true]);

        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin')->assertStatus(403);
    }

    private function skipIfNoPdoSqlite(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('pdo_sqlite extension is not available.');
        }
    }
}
