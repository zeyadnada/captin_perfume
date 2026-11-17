<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\ProductVariant;
use App\Models\User;

class ProductVariantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProductsVariantsManage);
    }

    public function view(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermissionTo(Permission::ProductsVariantsManage);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProductsVariantsManage);
    }

    public function update(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermissionTo(Permission::ProductsVariantsManage);
    }

    public function delete(User $user, ProductVariant $variant): bool
    {
        return $user->hasPermissionTo(Permission::ProductsVariantsManage);
    }
}
