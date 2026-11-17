<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\ProductImage;
use App\Models\User;

class ProductImagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProductsImagesManage);
    }

    public function view(User $user, ProductImage $image): bool
    {
        return $user->hasPermissionTo(Permission::ProductsImagesManage);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ProductsImagesManage);
    }

    public function update(User $user, ProductImage $image): bool
    {
        return $user->hasPermissionTo(Permission::ProductsImagesManage);
    }

    public function delete(User $user, ProductImage $image): bool
    {
        return $user->hasPermissionTo(Permission::ProductsImagesManage);
    }
}
