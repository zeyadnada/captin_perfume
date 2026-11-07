<?php

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

it('lists product images', function () {
    $product = Product::factory()->create();
    $image = ProductImage::factory()->for($product)->create();

    $this->get(route('admin.products.images.index', $product))
        ->assertOk()
        ->assertSee(asset('storage/'.$image->path), false);
});

it('uploads multiple product images', function () {
    Storage::fake('public');
    $product = Product::factory()->create();

    $this->post(route('admin.products.images.store', $product), [
        'images' => [
            UploadedFile::fake()->image('front.jpg'),
            UploadedFile::fake()->image('back.png'),
        ],
    ])->assertRedirect(route('admin.products.images.index', $product));

    expect($product->images()->count())->toBe(2);

    $image = $product->images()->first();
    Storage::disk('public')->assertExists($image->path);
});

it('updates image metadata', function () {
    $product = Product::factory()->create();
    $image = ProductImage::factory()->for($product)->create();

    $this->put(route('admin.products.images.update', [$product, $image]), [
        'sort_order' => 3,
    ])->assertRedirect(route('admin.products.images.index', $product));

    expect($image->fresh()->sort_order)->toBe(3);
});

it('deletes the image file and database record', function () {
    Storage::fake('public');
    $product = Product::factory()->create();
    $path = 'products/'.$product->id.'/bottle.jpg';
    Storage::disk('public')->put($path, 'image');
    $image = ProductImage::factory()->for($product)->create(['path' => $path]);

    $this->delete(route('admin.products.images.destroy', [$product, $image]))
        ->assertRedirect(route('admin.products.images.index', $product));

    $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    Storage::disk('public')->assertMissing($path);
});

it('prevents managing an image through another product', function () {
    $product = Product::factory()->create();
    $otherProduct = Product::factory()->create();
    $image = ProductImage::factory()->for($otherProduct)->create();

    $this->put(route('admin.products.images.update', [$product, $image]), [
        'sort_order' => 1,
    ])->assertNotFound();
});
