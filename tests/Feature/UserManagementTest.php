<?php

use App\Enums\Permission;
use App\Enums\Role as RoleEnum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

it('lists users for an authorized admin', function () {
    actingAsAdmin();

    $this->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee(__('admin.users.title'));
});

it('forbids listing users without users.view', function () {
    actingAsAdminWithout(Permission::UsersView);

    $this->get(route('admin.users.index'))
        ->assertForbidden();
});

it('creates a staff user with a single role', function () {
    actingAsAdmin();

    $role = Role::findByName(RoleEnum::Admin->value, 'web');

    $this->post(route('admin.users.store'), [
        'name' => 'Staff User',
        'email' => 'staff@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role_id' => $role->id,
    ])
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success', __('admin.flash.user_created'));

    $user = User::query()->where('email', 'staff@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->roles)->toHaveCount(1)
        ->and($user->hasRole(RoleEnum::Admin))->toBeTrue();
});

it('prevents deleting yourself', function () {
    $admin = actingAsAdmin();

    $this->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $admin))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasErrors('user');

    expect($admin->fresh())->not->toBeNull();
});

it('prevents deleting the last super_admin user', function () {
    $superAdmin = actingAsAdmin();

    $managerRole = Role::create(['name' => 'user_manager', 'guard_name' => 'web']);
    $managerRole->syncPermissions([
        Permission::DashboardView->value,
        Permission::UsersView->value,
        Permission::UsersDelete->value,
    ]);

    $manager = User::factory()->create();
    $manager->assignRole($managerRole);

    $this->actingAs($manager)
        ->from(route('admin.users.index'))
        ->delete(route('admin.users.destroy', $superAdmin))
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHasErrors('user');

    expect($superAdmin->fresh())->not->toBeNull();
});

it('creates a user with a phone number', function () {
    actingAsAdmin();

    $role = Role::findByName(RoleEnum::Admin->value, 'web');

    $this->post(route('admin.users.store'), [
        'name' => 'Phone User',
        'email' => 'phone@example.com',
        'phone' => '01012345678',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role_id' => $role->id,
    ])
        ->assertRedirect(route('admin.users.index'));

    expect(User::query()->where('email', 'phone@example.com')->value('phone'))->toBe('01012345678');
});
