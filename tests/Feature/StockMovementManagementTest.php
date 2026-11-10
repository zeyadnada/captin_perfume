<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;
use App\Models\ProductVariantStockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists stock movements with product and variant details', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    $stock = ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 12,
    ]);
    ProductVariantStockMovement::factory()->create([
        'product_variant_stock_id' => $stock->id,
        'type' => 'in',
        'quantity' => 12,
        'reason' => 'Initial delivery',
        'balance_after' => 12,
    ]);

    $this->get(route('admin.stock-movements.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($variant->display_name)
        ->assertSee('Initial delivery');
});

it('keeps movement history readable after a product and variant are soft deleted', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    $stock = ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 12,
    ]);
    ProductVariantStockMovement::factory()->create([
        'product_variant_stock_id' => $stock->id,
        'type' => 'in',
        'quantity' => 12,
        'reason' => 'Initial delivery',
        'balance_after' => 12,
    ]);

    $variant->delete();
    $product->delete();

    $this->get(route('admin.stock-movements.index'))
        ->assertOk()
        ->assertSee($product->name)
        ->assertSee($variant->display_name)
        ->assertSee('Initial delivery');
});

it('records incoming stock and its reference atomically', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->post(route('admin.stock-movements.store'), [
        'variant_id' => $variant->id,
        'type' => 'in',
        'quantity' => 15,
        'reason' => 'Supplier delivery',
        'reference_type' => 'purchase',
        'reference_id' => 42,
    ])->assertRedirect(route('admin.stock-movements.index'));

    $stock = $variant->fresh()->stock;

    expect($stock->quantity)->toBe(15);
    $this->assertDatabaseHas('product_variant_stock_movements', [
        'product_variant_stock_id' => $stock->id,
        'type' => 'in',
        'quantity' => 15,
        'reason' => 'Supplier delivery',
        'balance_after' => 15,
        'reference_type' => 'purchase',
        'reference_id' => 42,
    ]);
});

it('records outgoing stock without consuming reserved units', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);
    $stock = ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 20,
        'reserved_quantity' => 5,
    ]);

    $this->post(route('admin.stock-movements.store'), [
        'variant_id' => $variant->id,
        'type' => 'out',
        'quantity' => 15,
        'reason' => 'Damaged products',
    ])->assertRedirect(route('admin.stock-movements.index'));

    expect($stock->fresh()->quantity)->toBe(5);
    $this->assertDatabaseHas('product_variant_stock_movements', [
        'product_variant_stock_id' => $stock->id,
        'type' => 'out',
        'quantity' => 15,
        'balance_after' => 5,
    ]);
});

it('rejects outgoing stock that would consume reserved units', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);
    $stock = ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 20,
        'reserved_quantity' => 5,
    ]);

    $this->from(route('admin.stock-movements.create'))
        ->post(route('admin.stock-movements.store'), [
            'variant_id' => $variant->id,
            'type' => 'out',
            'quantity' => 16,
            'reason' => 'Removal',
        ])
        ->assertRedirect(route('admin.stock-movements.create'))
        ->assertSessionHasErrors('quantity');

    expect($stock->fresh()->quantity)->toBe(20);
    $this->assertDatabaseCount('product_variant_stock_movements', 0);
});

it('rejects an outgoing movement when no stock exists and rolls back the empty stock row', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->from(route('admin.stock-movements.create'))
        ->post(route('admin.stock-movements.store'), [
            'variant_id' => $variant->id,
            'type' => 'out',
            'quantity' => 1,
            'reason' => 'Removal',
        ])
        ->assertSessionHasErrors('quantity');

    $this->assertDatabaseCount('product_variant_stocks', 0);
    $this->assertDatabaseCount('product_variant_stock_movements', 0);
});

it('requires a reason and both reference fields together', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->post(route('admin.stock-movements.store'), [
        'variant_id' => $variant->id,
        'type' => 'in',
        'quantity' => 1,
        'reference_type' => 'purchase',
    ])->assertSessionHasErrors(['reason', 'reference_id']);
});

it('rejects unsupported movement directions', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->post(route('admin.stock-movements.store'), [
        'variant_id' => $variant->id,
        'type' => 'sideways',
        'quantity' => 1,
        'reason' => 'Invalid movement',
    ])->assertSessionHasErrors('type');

    $this->assertDatabaseCount('product_variant_stocks', 0);
    $this->assertDatabaseCount('product_variant_stock_movements', 0);
});

it('does not expose a path that directly changes physical stock', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();
    $stock = ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 12,
    ]);

    $this->put(route('admin.inventory.update', [$product, $variant]), [
        'quantity' => 1,
        'reserved_quantity' => 0,
        'low_stock_threshold' => 2,
    ])->assertRedirect(route('admin.inventory.index'));

    expect($stock->fresh()->quantity)->toBe(12);
    $this->assertDatabaseCount('product_variant_stock_movements', 0);
});

it('does not allow movements for inactive products or variants', function () {
    $product = Product::factory()->create(['is_active' => false]);
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);

    $this->post(route('admin.stock-movements.store'), [
        'variant_id' => $variant->id,
        'type' => 'in',
        'quantity' => 1,
        'reason' => 'Supplier delivery',
    ])->assertNotFound();

    $this->assertDatabaseCount('product_variant_stocks', 0);
    $this->assertDatabaseCount('product_variant_stock_movements', 0);
});
