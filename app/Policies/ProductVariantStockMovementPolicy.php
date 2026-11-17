<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class ProductVariantStockMovementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::StockMovementsView);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::StockMovementsCreate);
    }
}
