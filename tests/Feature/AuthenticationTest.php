<?php

use App\Models\User;

it('redirects guests from the admin dashboard to login', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('redirects guests from the home dashboard to login', function () {
    $this->get(route('home'))
        ->assertRedirect(route('login'));
});

it('shows the login page to guests', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(__('admin.auth.welcome'));
});

it('authenticates a user with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials', function () {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs the authenticated user out', function () {
    $user = actingAsAdmin();

    $this->post(route('logout'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
    expect($user)->not->toBeNull();
});

it('redirects authenticated users away from the login page', function () {
    actingAsAdmin();

    $this->get(route('login'))
        ->assertRedirect(route('admin.dashboard'));
});
