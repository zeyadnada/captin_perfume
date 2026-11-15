<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UpdateVariantStockRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryController extends Controller
{
    public function index(): View
    {
        return view('admin.inventory.index', [
            'variants' => ProductVariant::query()
                ->with(['product:id,name,slug', 'stock'])
                ->whereHas('product', fn ($query) => $query->where('is_active', true))
                ->where('is_active', true)
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        abort_unless($variant->product_id === $product->getKey(), 404);

        return view('admin.inventory.edit', [
            'product' => $product,
            'variant' => $variant->load('stock'),
        ]);
    }

    public function update(UpdateVariantStockRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->product_id === $product->getKey(), 404);

        DB::transaction(function () use ($request, $variant): void {
            ProductVariant::query()->lockForUpdate()->findOrFail($variant->getKey());
            $stock = $variant->stock()->lockForUpdate()->first() ?? new ProductVariantStock;
            $reservedQuantity = (int) $request->validated('reserved_quantity');

            if ($reservedQuantity > $stock->quantity) {
                throw ValidationException::withMessages([
                    'reserved_quantity' => __('admin.validation.reserved_exceeds_physical'),
                ]);
            }

            $stock->fill([
                'reserved_quantity' => $reservedQuantity,
                'low_stock_threshold' => $request->validated('low_stock_threshold'),
            ]);

            if (! $stock->exists) {
                $stock->product_variant_id = $variant->getKey();
            }

            $stock->save();
        });

        return to_route('admin.inventory.index')->with('success', __('admin.flash.stock_updated'));
    }
}
