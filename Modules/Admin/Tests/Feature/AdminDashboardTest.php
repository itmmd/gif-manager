<?php

beforeEach(fn () => $this->withoutVite());

it('redirects guest to login', function () {
    $this->get('/admin')->assertRedirect('/login');
});
