<?php

use App\Enums\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

it('redirects guests from the admin dashboard to admin login', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});

it('shows the admin login page to guests', function () {
    $this->get(route('admin.login'))
        ->assertOk()
        ->assertSee(__('admin.auth.welcome'));
});

it('authenticates a staff user with valid credentials', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);
    $user->assignRole(Role::Admin);

    $this->post(route('admin.login.store'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects customers from the admin login', function () {
    User::factory()->create([
        'email' => 'customer@example.com',
        'password' => 'password',
    ]);

    $this->from(route('admin.login'))
        ->post(route('admin.login.store'), [
            'email' => 'customer@example.com',
            'password' => 'password',
        ])
        ->assertRedirect(route('admin.login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rejects invalid credentials on admin login', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);
    $user->assignRole(Role::Admin);

    $this->from(route('admin.login'))
        ->post(route('admin.login.store'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('admin.login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs the authenticated staff user out via admin logout', function () {
    $user = actingAsAdmin();

    $this->post(route('admin.logout'))
        ->assertRedirect(route('admin.login'));

    $this->assertGuest();
    expect($user)->not->toBeNull();
});

it('redirects authenticated staff away from the admin login page', function () {
    actingAsAdmin();

    $this->get(route('admin.login'))
        ->assertRedirect(route('admin.dashboard'));
});
