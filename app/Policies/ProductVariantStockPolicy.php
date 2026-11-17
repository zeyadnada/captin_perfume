<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\ProductVariantStock;
use App\Models\User;

class ProductVariantStockPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::InventoryView);
    }

    public function view(User $user, ProductVariantStock $stock): bool
    {
        return $user->hasPermissionTo(Permission::InventoryView);
    }

    public function update(User $user, ProductVariantStock $stock): bool
    {
        return $user->hasPermissionTo(Permission::InventoryUpdate);
    }
}
