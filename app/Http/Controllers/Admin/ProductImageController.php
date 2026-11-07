<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Http\Requests\ProductImage\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index(Product $product): View
    {
        return view('admin.products.images.index', [
            'product' => $product,
            'images' => $product->images()->get(),
        ]);
    }

    public function store(StoreProductImageRequest $request, Product $product): RedirectResponse
    {
        $nextOrder = $product->images()->max('sort_order') + 1;
        foreach ($request->file('images') as $image) {
            $path = $image->store("products/{$product->getKey()}", 'public');

            $product->images()->create([
                'path' => $path,
                'sort_order' => $nextOrder++,
            ]);
        }

        return to_route('admin.products.images.index', $product)
            ->with('success', 'Product images uploaded successfully.');
    }

    public function update(UpdateProductImageRequest $request, Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->getKey(), 404);
        $image->update($request->validated());

        return to_route('admin.products.images.index', $product)
            ->with('success', 'Product image updated successfully.');
    }

    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->getKey(), 404);
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return to_route('admin.products.images.index', $product)
            ->with('success', 'Product image deleted successfully.');
    }
}
