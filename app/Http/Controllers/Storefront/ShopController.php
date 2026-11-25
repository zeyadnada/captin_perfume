<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $sort = $request->string('sort')->toString();
        $allowedSorts = [
            'featured',
            'best_selling',
            'name_asc',
            'name_desc',
            'price_asc',
            'price_desc',
            'date_desc',
            'date_asc',
        ];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'featured';
        }

        $products = Product::query()
            ->forCatalog()
            ->with([
                'brand:id,name,slug',
                'category:id,name,slug',
                'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')->limit(1),
                'variants' => fn ($query) => $query->active()->with('stock'),
            ])
            ->when($request->string('search')->trim()->value(), function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            })
            ->when($request->string('brand')->trim()->value(), function (Builder $query, string $brandSlug): void {
                $query->whereHas('brand', fn (Builder $brandQuery) => $brandQuery->where('slug', $brandSlug));
            })
            ->when($request->string('category')->trim()->value(), function (Builder $query, string $categorySlug): void {
                $query->whereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('slug', $categorySlug));
            })
            ->when(in_array($sort, ['featured', 'date_desc'], true), fn (Builder $query) => $query->latest())
            ->when($sort === 'date_asc', fn (Builder $query) => $query->oldest())
            ->when($sort === 'name_asc', fn (Builder $query) => $query->orderBy('name'))
            ->when($sort === 'name_desc', fn (Builder $query) => $query->orderByDesc('name'))
            ->when($sort === 'price_asc', function (Builder $query): void {
                $query->orderByRaw('(
                    select min(product_variants.price)
                    from product_variants
                    where product_variants.product_id = products.id
                      and product_variants.is_active = 1
                      and product_variants.deleted_at is null
                ) asc');
            })
            ->when($sort === 'price_desc', function (Builder $query): void {
                $query->orderByRaw('(
                    select min(product_variants.price)
                    from product_variants
                    where product_variants.product_id = products.id
                      and product_variants.is_active = 1
                      and product_variants.deleted_at is null
                ) desc');
            })
            ->when($sort === 'best_selling', function (Builder $query): void {
                $query->orderByRaw('(
                    select coalesce(sum(product_variant_stock_movements.quantity), 0)
                    from product_variants
                    inner join product_variant_stocks
                        on product_variant_stocks.product_variant_id = product_variants.id
                    inner join product_variant_stock_movements
                        on product_variant_stock_movements.product_variant_stock_id = product_variant_stocks.id
                    where product_variants.product_id = products.id
                      and product_variants.is_active = 1
                      and product_variants.deleted_at is null
                      and product_variant_stock_movements.type = ?
                ) desc', ['out']);
            })
            ->paginate(12)
            ->withQueryString();

        return view('storefront.shop.index', [
            'title' => __('storefront.shop.title'),
            'products' => $products,
            'brands' => Brand::query()->active()->orderBy('name')->get(['id', 'name', 'slug']),
            'categories' => Category::query()->active()->orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => [
                'search' => $request->string('search')->trim()->toString(),
                'brand' => $request->string('brand')->trim()->toString(),
                'category' => $request->string('category')->trim()->toString(),
                'sort' => $sort,
            ],
        ]);
    }

    public function show(Product $product): View
    {
        $isVisible = Product::query()
            ->forCatalog()
            ->whereKey($product->getKey())
            ->exists();

        abort_unless($isVisible, 404);

        $product->load([
            'brand:id,name,slug',
            'category:id,name,slug',
            'images',
            'variants' => fn ($query) => $query->active()->with('stock')->orderBy('volume'),
            'reviews' => fn ($query) => $query->approved()->latest(),
        ]);

        $relatedProducts = Product::query()
            ->forCatalog()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->with([
                'brand:id,name,slug',
                'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')->limit(1),
                'variants' => fn ($query) => $query->active()->with('stock'),
            ])
            ->latest()
            ->limit(4)
            ->get();

        return view('storefront.shop.show', [
            'title' => $product->name,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'reviews' => $product->reviews,
        ]);
    }
}
