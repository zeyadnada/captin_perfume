@extends('layouts.admin', ['title' => __('admin.variants.edit_title')])

@section('content')
    <x-page-header :title="__('admin.variants.edit_title')" :subtitle="__('admin.variants.edit_subtitle', ['product' => $product->name])" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.variants.partials.form', [
                'product' => $product,
                'variant' => $variant,
                'action' => route('admin.products.variants.update', [$product, $variant]),
                'method' => 'PUT',
                'submitLabel' => __('admin.variants.submit_update'),
            ])
        </div>
    </div>
@endsection