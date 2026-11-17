<?php

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

it('allows a super_admin role user to access the admin dashboard', function () {
    $this->seed(RolePermissionSeeder::class);

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(RoleEnum::SuperAdmin);

    $this->actingAs($superAdmin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('allows an admin role user to access the admin dashboard', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Admin);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('forbids an authenticated user without any staff role from the admin dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('forbids an authenticated user without any staff role from brand management', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.brands.index'))
        ->assertForbidden();
});

it('seeds the super_admin role with administration permissions', function () {
    $this->seed(RolePermissionSeeder::class);

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(RoleEnum::SuperAdmin);

    expect($superAdmin->can('users.view'))->toBeTrue()
        ->and($superAdmin->can('roles.create'))->toBeTrue();
});

it('seeds the admin role without administration permissions', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Admin);

    expect($admin->hasRole(RoleEnum::Admin))->toBeTrue()
        ->and($admin->can('dashboard.view'))->toBeTrue()
        ->and($admin->can('brands.create'))->toBeTrue()
        ->and($admin->can('products.variants.manage'))->toBeTrue()
        ->and($admin->can('stock-movements.create'))->toBeTrue()
        ->and($admin->can('users.view'))->toBeFalse()
        ->and($admin->can('roles.view'))->toBeFalse();
});

it('forbids the admin role from the administration section', function () {
    $this->seed(RolePermissionSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Admin);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('admin.roles.index'))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('admin.brands.index'))
        ->assertOk();
});
