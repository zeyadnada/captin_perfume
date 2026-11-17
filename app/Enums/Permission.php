<?php

namespace App\Enums;

enum Permission: string
{
    case DashboardView = 'dashboard.view';

    case BrandsView = 'brands.view';
    case BrandsCreate = 'brands.create';
    case BrandsUpdate = 'brands.update';
    case BrandsDelete = 'brands.delete';

    case CategoriesView = 'categories.view';
    case CategoriesCreate = 'categories.create';
    case CategoriesUpdate = 'categories.update';
    case CategoriesDelete = 'categories.delete';

    case ProductsView = 'products.view';
    case ProductsCreate = 'products.create';
    case ProductsUpdate = 'products.update';
    case ProductsDelete = 'products.delete';

    case ProductsVariantsManage = 'products.variants.manage';
    case ProductsImagesManage = 'products.images.manage';

    case InventoryView = 'inventory.view';
    case InventoryUpdate = 'inventory.update';

    case StockMovementsView = 'stock-movements.view';
    case StockMovementsCreate = 'stock-movements.create';

    case UsersView = 'users.view';
    case UsersCreate = 'users.create';
    case UsersUpdate = 'users.update';
    case UsersDelete = 'users.delete';

    case RolesView = 'roles.view';
    case RolesCreate = 'roles.create';
    case RolesUpdate = 'roles.update';
    case RolesDelete = 'roles.delete';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, list<self>>
     */
    public static function groupedByModule(): array
    {
        $groups = [];

        foreach (self::cases() as $permission) {
            $module = explode('.', $permission->value)[0];
            $groups[$module][] = $permission;
        }

        return $groups;
    }
}
