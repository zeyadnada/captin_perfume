@extends('layouts.admin', ['title' => "Add {$product->name} Variant"])

@section('content')
    <x-page-header title="Add Product Variant" :subtitle="'Create a sellable size for '.$product->name.'.'" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.variants.partials.form', [
                'product' => $product,
                'variant' => new \App\Models\ProductVariant(['volume_unit' => 'ml', 'is_active' => true]),
                'action' => route('admin.products.variants.store', $product),
                'method' => 'POST',
                'submitLabel' => 'Create variant',
            ])
        </div>
    </div>
@endsection
