<?php

use App\Enums\Permission;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

it('lists roles for an authorized admin', function () {
    actingAsAdmin();

    $this->get(route('admin.roles.index'))
        ->assertOk()
        ->assertSee(__('admin.roles.title'))
        ->assertSee(RoleEnum::SuperAdmin->value)
        ->assertSee(RoleEnum::Admin->value);
});

it('forbids listing roles without roles.view', function () {
    actingAsAdminWithout(Permission::RolesView);

    $this->get(route('admin.roles.index'))
        ->assertForbidden();
});

it('creates a role with selected permissions', function () {
    actingAsAdmin();

    $this->post(route('admin.roles.store'), [
        'name' => 'inventory_staff',
        'permissions' => [
            Permission::InventoryView->value,
            Permission::InventoryUpdate->value,
        ],
    ])
        ->assertRedirect(route('admin.roles.index'))
        ->assertSessionHas('success', __('admin.flash.role_created'));

    $role = Role::findByName('inventory_staff', 'web');

    expect($role->hasPermissionTo(Permission::InventoryView))->toBeTrue()
        ->and($role->hasPermissionTo(Permission::InventoryUpdate))->toBeTrue()
        ->and($role->hasPermissionTo(Permission::BrandsView))->toBeFalse();
});

it('updates role permissions', function () {
    actingAsAdmin();

    $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
    $role->syncPermissions([Permission::BrandsView->value]);

    $this->put(route('admin.roles.update', $role), [
        'name' => 'editor',
        'permissions' => [
            Permission::BrandsView->value,
            Permission::BrandsCreate->value,
        ],
    ])
        ->assertRedirect(route('admin.roles.index'))
        ->assertSessionHas('success', __('admin.flash.role_updated'));

    expect($role->fresh()->hasPermissionTo(Permission::BrandsCreate))->toBeTrue();
});

it('prevents deleting the system super_admin role', function () {
    actingAsAdmin();

    $superAdminRole = Role::findByName(RoleEnum::SuperAdmin->value, 'web');

    $this->from(route('admin.roles.index'))
        ->delete(route('admin.roles.destroy', $superAdminRole))
        ->assertRedirect(route('admin.roles.index'))
        ->assertSessionHasErrors('role');

    expect(Role::findByName(RoleEnum::SuperAdmin->value, 'web'))->not->toBeNull();
});

it('deletes a custom role', function () {
    actingAsAdmin();

    $role = Role::create(['name' => 'temp_role', 'guard_name' => 'web']);

    $this->delete(route('admin.roles.destroy', $role))
        ->assertRedirect(route('admin.roles.index'))
        ->assertSessionHas('success', __('admin.flash.role_deleted'));

    expect(Role::query()->where('name', 'temp_role')->exists())->toBeFalse();
});

it('allows a custom role user into the admin area when they have dashboard access', function () {
    $role = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
    $role->syncPermissions([Permission::DashboardView->value]);

    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertOk();
});
