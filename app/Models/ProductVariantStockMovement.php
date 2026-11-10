<?php

namespace App\Models;

use Database\Factories\ProductVariantStockMovementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_variant_stock_id',
    'type',
    'quantity',
    'reason',
    'balance_after',
    'reference_type',
    'reference_id',
])]
class ProductVariantStockMovement extends Model
{
    /** @use HasFactory<ProductVariantStockMovementFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(ProductVariantStock::class, 'product_variant_stock_id');
    }
}
