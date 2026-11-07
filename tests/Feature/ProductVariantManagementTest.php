<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists variants for a product', function () {
    $product = Product::factory()->create();
    ProductVariant::factory()->for($product)->create(['volume' => 50, 'volume_unit' => 'ml']);

    $this->get(route('admin.products.variants.index', $product))
        ->assertOk()
        ->assertSee('50 ml');
});

it('creates a product variant', function () {
    $product = Product::factory()->create();

    $this->post(route('admin.products.variants.store', $product), [
        'volume' => 50,
        'volume_unit' => 'ml',
        'sku' => 'var-50',
        'price' => 125.50,
        'compare_at_price' => 150,
        'is_active' => 1,
    ])->assertRedirect(route('admin.products.variants.index', $product));

    $this->assertDatabaseHas('product_variants', [
        'product_id' => $product->id,
        'volume' => 50,
        'volume_unit' => 'ml',
        'sku' => 'VAR-50',
    ]);
});

it('rejects a duplicate volume for a product', function () {
    $product = Product::factory()->create();
    ProductVariant::factory()->for($product)->create([
        'volume' => 50,
        'volume_unit' => 'ml',
    ]);

    $this->post(route('admin.products.variants.store', $product), [
        'volume' => 50,
        'volume_unit' => 'ml',
        'sku' => 'VAR-DUPLICATE',
        'price' => 100,
        'is_active' => 1,
    ])->assertSessionHasErrors('volume');
});

it('rejects updating a variant to a duplicate volume', function () {
    $product = Product::factory()->create();
    ProductVariant::factory()->for($product)->create([
        'volume' => 50,
        'volume_unit' => 'ml',
    ]);
    $variant = ProductVariant::factory()->for($product)->create([
        'volume' => 100,
        'volume_unit' => 'ml',
    ]);

    $this->put(route('admin.products.variants.update', [$product, $variant]), [
        'volume' => 50,
        'volume_unit' => 'ml',
        'sku' => $variant->sku,
        'price' => 100,
        'is_active' => 1,
    ])->assertSessionHasErrors('volume');
});

it('updates a product variant', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create(['volume' => 30, 'volume_unit' => 'ml']);

    $this->put(route('admin.products.variants.update', [$product, $variant]), [
        'volume' => 100,
        'volume_unit' => 'ml',
        'sku' => 'VAR-100',
        'price' => 250,
        'compare_at_price' => null,
        'is_active' => 1,
    ])->assertRedirect(route('admin.products.variants.index', $product));

    expect($variant->fresh()->volume)->toBe('100.00');
});

it('rejects a compare-at price below the variant price', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->put(route('admin.products.variants.update', [$product, $variant]), [
        'volume' => $variant->volume,
        'volume_unit' => $variant->volume_unit,
        'sku' => $variant->sku,
        'price' => 150,
        'compare_at_price' => 100,
        'is_active' => 1,
    ])->assertSessionHasErrors('compare_at_price');
});

it('prevents editing a variant through another product', function () {
    $product = Product::factory()->create();
    $otherProduct = Product::factory()->create();
    $variant = ProductVariant::factory()->for($otherProduct)->create();

    $this->get(route('admin.products.variants.edit', [$product, $variant]))
        ->assertNotFound();
});

it('soft deletes a product variant', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->for($product)->create();

    $this->delete(route('admin.products.variants.destroy', [$product, $variant]))
        ->assertRedirect(route('admin.products.variants.index', $product));

    $this->assertSoftDeleted('product_variants', ['id' => $variant->id]);
});
