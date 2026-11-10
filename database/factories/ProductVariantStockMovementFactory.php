<?php

namespace Database\Factories;

use App\Models\ProductVariantStock;
use App\Models\ProductVariantStockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductVariantStockMovement> */
class ProductVariantStockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_variant_stock_id' => ProductVariantStock::factory(),
            'type' => fake()->randomElement(['in', 'out']),
            'quantity' => fake()->numberBetween(1, 100),
            'reason' => fake()->sentence(),
            'balance_after' => fake()->numberBetween(0, 500),
            'reference_type' => null,
            'reference_id' => null,
        ];
    }
}
