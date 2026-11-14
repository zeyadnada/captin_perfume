@extends('layouts.admin', ['title' => __('admin.variants.create_title')])

@section('content')
    <x-page-header :title="__('admin.variants.create_title')" :subtitle="__('admin.variants.create_subtitle', ['product' => $product->name])" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.variants.partials.form', [
                'product' => $product,
                'variant' => new \App\Models\ProductVariant(['volume_unit' => 'ml', 'is_active' => true]),
                'action' => route('admin.products.variants.store', $product),
                'method' => 'POST',
                'submitLabel' => __('admin.variants.submit_create'),
            ])
        </div>
    </div>
@endsection