<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductVariantStock> */
class ProductVariantStockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_variant_id' => ProductVariant::factory(),
            'quantity' => fake()->numberBetween(0, 200),
            'reserved_quantity' => 0,
            'low_stock_threshold' => fake()->numberBetween(0, 20),
        ];
    }
}
