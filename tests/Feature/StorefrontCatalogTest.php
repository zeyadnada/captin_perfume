<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ProductVariant;

function createCatalogProduct(array $productAttributes = [], array $variantAttributes = []): Product
{
    $brand = Brand::factory()->create([
        'name' => $productAttributes['brand_name'] ?? 'Catalog Brand',
        'slug' => $productAttributes['brand_slug'] ?? 'catalog-brand',
        'is_active' => true,
    ]);

    $category = Category::factory()->create([
        'name' => $productAttributes['category_name'] ?? 'Catalog Category',
        'slug' => $productAttributes['category_slug'] ?? 'catalog-category',
        'is_active' => true,
    ]);

    unset($productAttributes['brand_name'], $productAttributes['brand_slug'], $productAttributes['category_name'], $productAttributes['category_slug']);

    $product = Product::factory()->create(array_merge([
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'name' => 'Ocean Breeze',
        'slug' => 'ocean-breeze',
        'sku' => 'OB-001',
        'is_active' => true,
    ], $productAttributes));

    ProductVariant::factory()->create(array_merge([
        'product_id' => $product->id,
        'volume' => 50,
        'volume_unit' => 'ml',
        'price' => 120.00,
        'is_active' => true,
    ], $variantAttributes));

    return $product->fresh(['brand', 'category', 'variants']);
}

it('lists active catalog products on the shop page', function () {
    $visible = createCatalogProduct([
        'name' => 'Visible Perfume',
        'slug' => 'visible-perfume',
    ]);

    createCatalogProduct([
        'name' => 'Inactive Perfume',
        'slug' => 'inactive-perfume',
        'sku' => 'IN-001',
        'brand_name' => 'Other Brand',
        'brand_slug' => 'other-brand',
        'category_name' => 'Other Category',
        'category_slug' => 'other-category',
        'is_active' => false,
    ]);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertSee(__('storefront.shop.title'))
        ->assertSee($visible->name)
        ->assertDontSee('Inactive Perfume');
});

it('hides products with inactive brand category or no active variants', function () {
    $inactiveBrandProduct = createCatalogProduct([
        'name' => 'Inactive Brand Product',
        'slug' => 'inactive-brand-product',
        'sku' => 'IB-001',
        'brand_name' => 'Inactive Brand',
        'brand_slug' => 'inactive-brand',
        'category_name' => 'Active Category A',
        'category_slug' => 'active-category-a',
    ]);
    $inactiveBrandProduct->brand->update(['is_active' => false]);

    $inactiveCategoryProduct = createCatalogProduct([
        'name' => 'Inactive Category Product',
        'slug' => 'inactive-category-product',
        'sku' => 'IC-001',
        'brand_name' => 'Active Brand B',
        'brand_slug' => 'active-brand-b',
        'category_name' => 'Inactive Category',
        'category_slug' => 'inactive-category',
    ]);
    $inactiveCategoryProduct->category->update(['is_active' => false]);

    $noActiveVariantProduct = createCatalogProduct([
        'name' => 'No Active Variant Product',
        'slug' => 'no-active-variant-product',
        'sku' => 'NV-001',
        'brand_name' => 'Active Brand C',
        'brand_slug' => 'active-brand-c',
        'category_name' => 'Active Category C',
        'category_slug' => 'active-category-c',
    ], [
        'is_active' => false,
    ]);

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertDontSee('Inactive Brand Product')
        ->assertDontSee('Inactive Category Product')
        ->assertDontSee('No Active Variant Product');
});

it('accepts the expanded shop sort options', function () {
    createCatalogProduct([
        'name' => 'Alpha Perfume',
        'slug' => 'alpha-perfume',
        'sku' => 'AP-001',
    ]);

    foreach (['featured', 'best_selling', 'name_asc', 'name_desc', 'price_asc', 'price_desc', 'date_desc', 'date_asc'] as $sort) {
        $this->get(route('shop.index', ['sort' => $sort]))
            ->assertOk()
            ->assertSee('Alpha Perfume');
    }

    $this->get(route('shop.index'))
        ->assertOk()
        ->assertSee(__('storefront.shop.sort.featured'))
        ->assertSee(__('storefront.shop.sort.best_selling'))
        ->assertSee(__('storefront.shop.sort.name_asc'));
});

it('filters the shop by search brand and category', function () {
    $matching = createCatalogProduct([
        'name' => 'Rose Elixir',
        'slug' => 'rose-elixir',
        'sku' => 'RE-001',
        'brand_name' => 'Maison Rose',
        'brand_slug' => 'maison-rose',
        'category_name' => 'Floral',
        'category_slug' => 'floral',
    ]);

    createCatalogProduct([
        'name' => 'Citrus Splash',
        'slug' => 'citrus-splash',
        'sku' => 'CS-001',
        'brand_name' => 'Citrus House',
        'brand_slug' => 'citrus-house',
        'category_name' => 'Fresh',
        'category_slug' => 'fresh',
    ]);

    $this->get(route('shop.index', ['search' => 'Rose']))
        ->assertOk()
        ->assertSee('Rose Elixir')
        ->assertDontSee('Citrus Splash');

    $this->get(route('shop.index', ['brand' => 'maison-rose']))
        ->assertOk()
        ->assertSee('Rose Elixir')
        ->assertDontSee('Citrus Splash');

    $this->get(route('shop.index', ['category' => 'floral']))
        ->assertOk()
        ->assertSee($matching->name)
        ->assertDontSee('Citrus Splash');
});

it('shows a catalog product by slug and returns 404 for inactive products', function () {
    $product = createCatalogProduct([
        'name' => 'Amber Night',
        'slug' => 'amber-night',
        'sku' => 'AN-001',
        'short_description' => 'Warm amber notes.',
    ]);

    $inactive = createCatalogProduct([
        'name' => 'Hidden Mist',
        'slug' => 'hidden-mist',
        'sku' => 'HM-001',
        'brand_name' => 'Hidden Brand',
        'brand_slug' => 'hidden-brand',
        'category_name' => 'Hidden Category',
        'category_slug' => 'hidden-category',
        'is_active' => false,
    ]);

    $this->get(route('shop.show', $product->slug))
        ->assertOk()
        ->assertSee('Amber Night')
        ->assertSee('Warm amber notes.')
        ->assertSee(__('storefront.product.add_to_cart_coming_soon'));

    $this->get(route('shop.show', $inactive->slug))
        ->assertNotFound();
});

it('links the shop navigation to the catalog index', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('shop.index'), false)
        ->assertSee(__('storefront.home.cta'));
});

it('shows the reviews tab only when approved reviews exist', function () {
    $product = createCatalogProduct([
        'name' => 'Reviewed Perfume',
        'slug' => 'reviewed-perfume',
        'sku' => 'RP-001',
    ]);

    $this->get(route('shop.show', $product->slug))
        ->assertOk()
        ->assertDontSee(__('storefront.product.reviews').' (', false);

    ProductReview::query()->create([
        'product_id' => $product->id,
        'author_name' => 'Sara',
        'rating' => 5,
        'title' => 'Beautiful scent',
        'body' => 'Long lasting and elegant.',
        'is_approved' => true,
    ]);

    ProductReview::query()->create([
        'product_id' => $product->id,
        'author_name' => 'Hidden Reviewer',
        'rating' => 1,
        'title' => 'Pending',
        'body' => 'Should not appear.',
        'is_approved' => false,
    ]);

    $this->get(route('shop.show', $product->slug))
        ->assertOk()
        ->assertSee(__('storefront.product.reviews').' (1)', false)
        ->assertSee('Beautiful scent')
        ->assertSee('Sara')
        ->assertDontSee('Hidden Reviewer')
        ->assertDontSee('Should not appear.');
});
