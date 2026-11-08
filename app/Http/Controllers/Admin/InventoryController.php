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
            $stock = $variant->stock()->lockForUpdate()->first() ?? new ProductVariantStock;

            $stock->fill([
                'quantity' => $request->validated('quantity'),
                'reserved_quantity' => $request->validated('reserved_quantity'),
                'low_stock_threshold' => $request->validated('low_stock_threshold'),
            ]);

            if (! $stock->exists) {
                $stock->product_variant_id = $variant->getKey();
            }

            $stock->save();
        });

        return to_route('admin.inventory.index')->with('success', 'Stock updated successfully.');
    }
}
