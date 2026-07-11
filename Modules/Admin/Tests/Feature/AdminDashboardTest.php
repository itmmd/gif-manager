<?php

it('returns 200 for admin dashboard', function () {
    $this->get('/admin')->assertStatus(200);
});

it('renders the sidebar in admin layout', function () {
    $this->get('/admin')->assertSee('sidebar', escape: false);
});

it('references gentelella css asset in admin layout', function () {
    $this->get('/admin')->assertSee('admin-assets/css', escape: false);
});
