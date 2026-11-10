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

it('creates stock settings without changing physical quantity', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'reserved_quantity' => 0,
        'low_stock_threshold' => 2,
    ])->assertRedirect(route('admin.inventory.index'));

    $this->assertDatabaseHas('product_variant_stocks', [
        'product_variant_id' => $variant->id,
        'quantity' => 0,
        'reserved_quantity' => 0,
        'low_stock_threshold' => 2,
    ]);
});

it('updates stock settings without overwriting physical quantity', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 20,
        'reserved_quantity' => 5,
        'low_stock_threshold' => 3,
    ]);

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'reserved_quantity' => 8,
        'low_stock_threshold' => 5,
    ]);

    expect($variant->fresh()->stock->quantity)->toBe(20)
        ->and($variant->fresh()->stock->availableQuantity())->toBe(12);
});

it('rejects negative reserved stock values', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'reserved_quantity' => -1,
        'low_stock_threshold' => 0,
    ])->assertSessionHasErrors('reserved_quantity');
});

it('rejects reserved quantity greater than physical quantity', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 5,
    ]);

    $this->put(route('admin.inventory.update', [$product, $variant]), [
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
        ->assertDontSee($variant->display_name);
});
