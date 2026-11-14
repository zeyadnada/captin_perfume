<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['brand:id,name', 'category:id,name'])
            ->withCount('variants')
            ->when($request->string('search')->trim()->value(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('brand_id'), fn ($query) => $query->where('brand_id', $request->integer('brand_id')))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->boolean('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'brands' => Brand::query()->active()->orderBy('name')->get(),
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'brands' => Brand::query()->active()->orderBy('name')->get(),
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->validated());

        return to_route('admin.products.show', $product)->with('success', __('admin.flash.product_created'));
    }

    public function show(Product $product): View
    {
        $product->load([
            'brand:id,name,slug,logo',
            'category:id,name,slug',
            'variants' => fn ($query) => $query->latest()->limit(5),
            'images' => fn ($query) => $query->orderBy('sort_order')->orderBy('id')->limit(6),
        ]);
        $product->loadCount('variants');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'brands' => Brand::query()->active()->orderBy('name')->get(),
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('admin.products.show', $product)->with('success', __('admin.flash.product_updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('success', __('admin.flash.product_deleted'));
    }
}
