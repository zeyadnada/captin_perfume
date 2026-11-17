<?php

use App\Enums\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

it('shows the customer login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee(__('storefront.auth.login_title'));
});

it('shows the customer register page', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee(__('storefront.auth.register_title'));
});

it('registers a new customer without assigning staff roles', function () {
    $this->post(route('register.store'), [
        'name' => 'Jane Customer',
        'email' => 'jane@example.com',
        'phone' => '01000000000',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertRedirect(route('account.show'));

    $user = User::query()->where('email', 'jane@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->isStaff())->toBeFalse();

    $this->assertAuthenticatedAs($user);
});

it('authenticates a customer and redirects to the account page', function () {
    $user = User::factory()->create([
        'email' => 'customer@example.com',
        'password' => 'password',
    ]);

    $this->post(route('login.store'), [
        'email' => 'customer@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('account.show'));

    $this->assertAuthenticatedAs($user);
});

it('redirects staff who use customer login to the admin dashboard', function () {
    $this->seed(RolePermissionSeeder::class);

    $user = User::factory()->create([
        'email' => 'staff@example.com',
        'password' => 'password',
    ]);
    $user->assignRole(Role::Admin);

    $this->post(route('login.store'), [
        'email' => 'staff@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid customer credentials', function () {
    User::factory()->create([
        'email' => 'customer@example.com',
        'password' => 'password',
    ]);

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'customer@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs the customer out and returns to the storefront home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('allows a customer to view and update their account', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $this->actingAs($user)
        ->get(route('account.show'))
        ->assertOk()
        ->assertSee(__('storefront.account.title'));

    $this->actingAs($user)
        ->put(route('account.update'), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phone' => '01111111111',
        ])
        ->assertRedirect(route('account.show'))
        ->assertSessionHas('success');

    expect($user->fresh())
        ->name->toBe('New Name')
        ->email->toBe('new@example.com')
        ->phone->toBe('01111111111');
});

it('blocks customers from the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('redirects guests from the account page to customer login', function () {
    $this->get(route('account.show'))
        ->assertRedirect(route('login'));
});

it('redirects authenticated customers away from the login page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('login'))
        ->assertRedirect(route('home'));
});
