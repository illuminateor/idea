<?php

it('register a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password')
        ->click('Create Account')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'name' => 'John Doe',
    ]);
});

it('requires a valid email', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john123')
        ->fill('password', 'password')
        ->click('Create Account')
        ->assertPathIs('/register');
});
