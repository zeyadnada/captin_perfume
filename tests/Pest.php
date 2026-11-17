<?php

use App\Enums\Permission;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/**
 * Authenticate as a fresh super_admin user for protected route tests.
 */
function actingAsAdmin(): User
{
    test()->seed(RolePermissionSeeder::class);

    $user = User::factory()->create();
    $user->assignRole(RoleEnum::SuperAdmin);

    test()->actingAs($user);

    return $user;
}

/**
 * Authenticate as a super_admin with one or more role permissions revoked.
 */
function actingAsAdminWithout(Permission ...$permissions): User
{
    $user = actingAsAdmin();

    $role = Role::findByName(RoleEnum::SuperAdmin->value, 'web');
    $role->revokePermissionTo(
        array_map(fn (Permission $permission) => $permission->value, $permissions),
    );

    app()[PermissionRegistrar::class]->forgetCachedPermissions();

    return $user->fresh();
}
