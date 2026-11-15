<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductVariantController extends Controller
{
    public function index(Product $product): View
    {
        return view('admin.products.variants.index', [
            'product' => $product,
            'variants' => $product->variants()->latest()->paginate(15),
        ]);
    }

    public function create(Product $product): View
    {
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(StoreProductVariantRequest $request, Product $product): RedirectResponse
    {
        $product->variants()->create($request->validated());

        return to_route('admin.products.variants.index', $product)->with('success', __('admin.flash.variant_created'));
    }

    public function edit(Product $product, ProductVariant $variant): View
    {
        abort_unless($variant->product_id === $product->getKey(), 404);

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->product_id === $product->getKey(), 404);
        $variant->update($request->validated());

        return to_route('admin.products.variants.index', $product)->with('success', __('admin.flash.variant_updated'));
    }

    public function destroy(Product $product, ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->product_id === $product->getKey(), 404);
        $variant->delete();

        return to_route('admin.products.variants.index', $product)->with('success', __('admin.flash.variant_deleted'));
    }
}
