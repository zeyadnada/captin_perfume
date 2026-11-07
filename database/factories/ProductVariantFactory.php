<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductVariant> */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'volume' => fake()->randomElement([30, 50, 100]),
            'volume_unit' => fake()->randomElement(['ml', 'oz']),
            'sku' => strtoupper(fake()->unique()->bothify('VAR-####-??')),
            'price' => fake()->randomFloat(2, 10, 5000),
            'compare_at_price' => null,
            'is_active' => fake()->boolean(85),
        ];
    }
}
