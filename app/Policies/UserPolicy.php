<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UsersView);
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::UsersView);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::UsersCreate);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::UsersUpdate);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo(Permission::UsersDelete);
    }
}
