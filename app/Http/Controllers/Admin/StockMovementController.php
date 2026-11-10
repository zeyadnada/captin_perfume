<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreStockMovementRequest;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\ProductVariantStockMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    private const MAX_STOCK_QUANTITY = 4294967295;

    public function index(): View
    {
        return view('admin.stock-movements.index', [
            'movements' => ProductVariantStockMovement::query()
                ->with([
                    'stock.variant' => fn ($query) => $query->withTrashed(),
                    'stock.variant.product' => fn ($query) => $query->withTrashed(),
                ])
                ->latest('id')
                ->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.stock-movements.create', [
            'variants' => ProductVariant::query()
                ->with('product:id,name')
                ->where('is_active', true)
                ->whereHas('product', fn ($query) => $query->where('is_active', true))
                ->orderBy('product_id')
                ->orderBy('volume')
                ->get(),
            'selectedVariantId' => request()->query('variant_id'),
        ]);
    }

    public function store(StoreStockMovementRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            $variant = ProductVariant::query()
                ->where('is_active', true)
                ->whereHas('product', fn ($query) => $query->where('is_active', true))
                ->lockForUpdate()
                ->findOrFail($data['variant_id']);

            $stock = $variant->stock()->lockForUpdate()->first();

            if (! $stock) {
                $stock = new ProductVariantStock([
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 0,
                ]);
                $stock->product_variant_id = $variant->getKey();
                $stock->save();
            }

            $currentQuantity = $stock->quantity;
            $movementQuantity = (int) $data['quantity'];
            $newQuantity = $data['type'] === 'in'
                ? $currentQuantity + $movementQuantity
                : $currentQuantity - $movementQuantity;

            if ($data['type'] === 'out' && $movementQuantity > $stock->availableQuantity()) {
                throw ValidationException::withMessages([
                    'quantity' => 'The outgoing quantity cannot exceed available stock.',
                ]);
            }

            if ($newQuantity > self::MAX_STOCK_QUANTITY) {
                throw ValidationException::withMessages([
                    'quantity' => 'The resulting stock quantity exceeds the maximum supported value.',
                ]);
            }

            $stock->quantity = $newQuantity;
            $stock->save();

            $stock->movements()->create([
                'type' => $data['type'],
                'quantity' => $movementQuantity,
                'reason' => $data['reason'],
                'balance_after' => $newQuantity,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
            ]);
        });

        return to_route('admin.stock-movements.index')->with('success', 'Stock movement recorded successfully.');
    }
}
