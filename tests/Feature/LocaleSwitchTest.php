<?php

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantStock;

it('defaults the admin dashboard to english', function () {
    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('lang="en"', false)
        ->assertSee('dir="ltr"', false)
        ->assertSee(__('admin.nav.dashboard', [], 'en'))
        ->assertDontSee('dir="rtl"', false);
});

it('switches the locale to arabic and applies rtl', function () {
    $this->from(route('admin.dashboard'))
        ->get(route('locale.switch', 'ar'))
        ->assertRedirect(route('admin.dashboard'));

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSee(__('admin.nav.dashboard', [], 'ar'))
        ->assertSee(__('admin.nav.brands', [], 'ar'));
});

it('switches back to english', function () {
    $this->withSession(['locale' => 'ar'])
        ->from(route('admin.dashboard'))
        ->get(route('locale.switch', 'en'))
        ->assertRedirect(route('admin.dashboard'));

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('lang="en"', false)
        ->assertSee('dir="ltr"', false)
        ->assertSee(__('admin.nav.dashboard', [], 'en'));
});

it('rejects unsupported locales', function () {
    $this->get('/locale/fr')
        ->assertNotFound();
});

it('translates brand flash messages for the active locale', function () {
    $this->withSession(['locale' => 'ar'])
        ->post(route('admin.brands.store'), [
            'name' => 'Dior',
            'slug' => 'dior',
            'description' => null,
            'is_active' => 1,
        ])
        ->assertRedirect(route('admin.brands.index'))
        ->assertSessionHas('success', __('admin.flash.brand_created', [], 'ar'));

    expect(Brand::query()->where('slug', 'dior')->exists())->toBeTrue();
});

it('translates inventory stock validation messages for arabic', function () {
    $product = Product::factory()->create(['is_active' => true]);
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);
    ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 5,
        'reserved_quantity' => 0,
    ]);

    $this->withSession(['locale' => 'ar'])
        ->from(route('admin.inventory.edit', [$product, $variant]))
        ->put(route('admin.inventory.update', [$product, $variant]), [
            'reserved_quantity' => 6,
            'low_stock_threshold' => 0,
        ])
        ->assertSessionHasErrors([
            'reserved_quantity' => __('admin.validation.reserved_exceeds_physical', [], 'ar'),
        ]);
});

it('translates stock movement validation messages for arabic', function () {
    $product = Product::factory()->create(['is_active' => true]);
    $variant = ProductVariant::factory()->for($product)->create(['is_active' => true]);
    ProductVariantStock::factory()->create([
        'product_variant_id' => $variant->id,
        'quantity' => 10,
        'reserved_quantity' => 0,
    ]);

    $this->withSession(['locale' => 'ar'])
        ->from(route('admin.stock-movements.create'))
        ->post(route('admin.stock-movements.store'), [
            'variant_id' => $variant->id,
            'type' => 'out',
            'quantity' => 11,
            'reason' => 'Removal',
        ])
        ->assertSessionHasErrors([
            'quantity' => __('admin.validation.outgoing_exceeds_available', [], 'ar'),
        ]);
});
