<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    /**
     * Smoke test: admin dashboard route returns HTTP 200.
     */
    public function test_admin_dashboard_returns_200(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Verify the response contains the sidebar markup from the Gentelella layout.
     */
    public function test_admin_dashboard_contains_sidebar(): void
    {
        $response = $this->get('/admin');

        $response->assertSee('sidebar', escape: false);
    }

    /**
     * Verify the Gentelella CSS asset is referenced in the layout.
     */
    public function test_admin_dashboard_references_gentelella_css(): void
    {
        $response = $this->get('/admin');

        $response->assertSee('admin-assets/css', escape: false);
    }
}
