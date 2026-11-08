<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists variants with zero stock when no stock record exists', function () {
    $product = Product::factory()->create(['is_active' => true]);
    ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->get(route('admin.inventory.index'))
        ->assertOk()
        ->assertSee('0')
        ->assertSee('Low stock');
});

it('creates stock for a product variant', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'quantity' => 12,
        'reserved_quantity' => 3,
        'low_stock_threshold' => 2,
    ])->assertRedirect(route('admin.inventory.index'));

    $this->assertDatabaseHas('product_variant_stocks', [
        'product_variant_id' => $variant->id,
        'quantity' => 12,
        'reserved_quantity' => 3,
        'low_stock_threshold' => 2,
    ]);

    expect($variant->fresh()->stock->availableQuantity())->toBe(9);
});

it('updates existing stock and omits reserved stock from availability', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 20,
        'reserved_quantity' => 5,
        'low_stock_threshold' => 3,
    ]);

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'quantity' => 25,
        'reserved_quantity' => 8,
        'low_stock_threshold' => 5,
    ]);

    expect($variant->fresh()->stock->availableQuantity())->toBe(17);
});

it('rejects negative stock values', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'quantity' => -1,
        'reserved_quantity' => 0,
        'low_stock_threshold' => 0,
    ])->assertSessionHasErrors('quantity');
});

it('rejects reserved quantity greater than physical quantity', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'quantity' => 5,
        'reserved_quantity' => 6,
        'low_stock_threshold' => 0,
    ])->assertSessionHasErrors('reserved_quantity');
});

it('prevents opening stock through another product', function () {
    $product = Product::factory()->create();
    $otherProduct = Product::factory()->create();
    $variant = ProductVariant::factory()->for($otherProduct)->create();

    $this->get(route('admin.inventory.edit', [$product, $variant]))
        ->assertNotFound();
});

it('does not list soft deleted variants', function () {
    $product = Product::factory()->create(['is_active' => true]);
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);
    $variant->delete();

    $this->get(route('admin.inventory.index'))
        ->assertOk()
        ->assertDontSee($variant->sku);
});
