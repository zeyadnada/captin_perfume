<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists products in the admin panel', function () {
    $product = Product::factory()->create(['name' => 'Amber Nights']);

    $this->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee($product->name);
});

it('creates a product', function () {
    $brand = Brand::factory()->create();
    $category = Category::factory()->create();

    $this->post(route('admin.products.store'), [
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Amber Nights',
        'slug' => '',
        'sku' => 'per-1001',
        'short_description' => 'A warm amber fragrance.',
        'description' => 'Amber, vanilla and sandalwood.',
        'price' => 125.50,
        'compare_at_price' => 150.00,
        'is_active' => 1,
    ])->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Amber Nights',
        'slug' => 'amber-nights',
        'sku' => 'PER-1001',
        'brand_id' => $brand->id,
        'category_id' => $category->id,
    ]);
});

it('shows all product details', function () {
    $product = Product::factory()->create([
        'name' => 'Amber Nights',
        'sku' => 'PER-DETAILS',
        'description' => 'Amber, vanilla and sandalwood.',
    ]);

    $this->get(route('admin.products.show', $product))
        ->assertOk()
        ->assertSee('Amber Nights')
        ->assertSee('PER-DETAILS')
        ->assertSee('Amber, vanilla and sandalwood.');
});

it('updates a product', function () {
    $product = Product::factory()->create();

    $this->put(route('admin.products.update', $product), [
        'brand_id' => $product->brand_id,
        'category_id' => $product->category_id,
        'name' => 'Nocturne',
        'slug' => $product->slug,
        'sku' => 'PER-UPDATE',
        'short_description' => null,
        'description' => null,
        'price' => 99.99,
        'compare_at_price' => null,
        'is_active' => 1,
    ])->assertRedirect(route('admin.products.index'));

    expect($product->fresh()->name)->toBe('Nocturne');
});

it('rejects a compare-at price below the price', function () {
    $product = Product::factory()->create();

    $this->put(route('admin.products.update', $product), [
        'brand_id' => $product->brand_id,
        'category_id' => $product->category_id,
        'name' => $product->name,
        'slug' => $product->slug,
        'sku' => $product->sku,
        'price' => 150,
        'compare_at_price' => 100,
        'is_active' => 1,
    ])->assertSessionHasErrors('compare_at_price');
});

it('soft deletes a product', function () {
    $product = Product::factory()->create();

    $this->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertSoftDeleted('products', ['id' => $product->id]);
});
