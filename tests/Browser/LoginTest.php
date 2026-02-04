<?php

use App\Models\User;

it('logs in a user', function () {
    $user = User::factory()->create();

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->click('@login-button')
        ->assertRoute('idea.index');

    $this->assertAuthenticated();
});

it('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')
        ->click('Log Out')
        ->assertRoute('login');

    $this->assertGuest();
});
