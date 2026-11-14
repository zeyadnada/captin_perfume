<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $brands = Brand::query()
            ->when($request->string('search')->trim()->value(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->boolean('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['logo']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return to_route('admin.brands.index')->with('success', __('admin.flash.brand_created'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->validated();
        unset($data['logo']);

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($brand->logo);
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return to_route('admin.brands.index')->with('success', __('admin.flash.brand_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return to_route('admin.brands.index')->with('success', __('admin.flash.brand_deleted'));
    }
}
