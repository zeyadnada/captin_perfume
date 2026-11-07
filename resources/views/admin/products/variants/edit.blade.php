@extends('layouts.admin', ['title' => "Edit {$product->name} Variant"])

@section('content')
    <x-page-header title="Edit Product Variant" :subtitle="'Update '.$product->name.' variant details.'" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.variants.partials.form', [
                'product' => $product,
                'variant' => $variant,
                'action' => route('admin.products.variants.update', [$product, $variant]),
                'method' => 'PUT',
                'submitLabel' => 'Save changes',
            ])
        </div>
    </div>
@endsection
