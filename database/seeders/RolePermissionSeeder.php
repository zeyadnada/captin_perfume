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

        $permissions = collect(PermissionEnum::values())
            ->map(fn (string $name) => Permission::findOrCreate($name, 'web'));

        $registrar->forgetCachedPermissions();

        $admin = Role::findOrCreate(RoleEnum::Admin->value, 'web');
        $admin->syncPermissions($permissions);
    }
}
