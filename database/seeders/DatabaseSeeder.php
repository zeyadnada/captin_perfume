<?php

namespace Database\Seeders;

use App\Enums\Role as RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'super@example.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'phone' => null,
            ],
        );

        $superAdmin->syncRoles([RoleEnum::SuperAdmin]);
    }
}
