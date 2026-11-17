<?php

namespace Database\Seeders;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application roles and permissions.
     */
    public function run(): void
    {
        $registrar = app()[PermissionRegistrar::class];
        $registrar->forgetCachedPermissions();

        foreach (PermissionEnum::values() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $registrar->forgetCachedPermissions();

        $allPermissions = PermissionEnum::values();

        $administrationPermissions = [
            PermissionEnum::UsersView->value,
            PermissionEnum::UsersCreate->value,
            PermissionEnum::UsersUpdate->value,
            PermissionEnum::UsersDelete->value,
            PermissionEnum::RolesView->value,
            PermissionEnum::RolesCreate->value,
            PermissionEnum::RolesUpdate->value,
            PermissionEnum::RolesDelete->value,
        ];

        $adminPermissions = array_values(array_diff($allPermissions, $administrationPermissions));

        $superAdmin = Role::findOrCreate(RoleEnum::SuperAdmin->value, 'web');
        $superAdmin->syncPermissions($allPermissions);

        $admin = Role::findOrCreate(RoleEnum::Admin->value, 'web');
        $admin->syncPermissions($adminPermissions);
    }
}
