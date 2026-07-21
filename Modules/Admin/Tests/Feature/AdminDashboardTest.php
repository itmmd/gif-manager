<?php

/**
 * Admin Dashboard — No-DB tests (always run)
 */

beforeEach(function () {
    $this->withoutVite();
});

it('redirects guest to login', function () {
    $this->get('/admin')->assertRedirect('/login');
});
