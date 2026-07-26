<?php

beforeEach(function () {
    $this->withoutVite();
});

it('login page does not contain admin-assets references', function () {
    $this->get('/login')->assertStatus(200)->assertDontSee('admin-assets', escape: false);
});

it('register page does not contain admin-assets references', function () {
    $this->get('/register')->assertStatus(200)->assertDontSee('admin-assets', escape: false);
});

it('forgot-password page does not contain admin-assets references', function () {
    $this->get('/forgot-password')->assertStatus(200)->assertDontSee('admin-assets', escape: false);
});

it('login page does not load gentelella css', function () {
    $this->get('/login')
         ->assertStatus(200)
         ->assertDontSee('main-v4', escape: false)
         ->assertDontSee('admin-assets/css', escape: false);
});

it('login page does not load gentelella js modules', function () {
    $this->get('/login')
         ->assertStatus(200)
         ->assertDontSee('admin-assets/js', escape: false)
         ->assertDontSee('rolldown-runtime', escape: false);
});
