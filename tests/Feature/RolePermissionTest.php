<?php

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

it('allows an admin role user to access the admin dashboard', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Admin);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('forbids an authenticated user without the admin role from the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('forbids an authenticated user without the admin role from brand management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.brands.index'))
        ->assertForbidden();
});

it('seeds the admin role with the expected permissions', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Admin);

    expect($admin->hasRole(RoleEnum::Admin))->toBeTrue()
        ->and($admin->can('dashboard.view'))->toBeTrue()
        ->and($admin->can('brands.create'))->toBeTrue()
        ->and($admin->can('products.variants.manage'))->toBeTrue()
        ->and($admin->can('stock-movements.create'))->toBeTrue();
});
