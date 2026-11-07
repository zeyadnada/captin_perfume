@extends('layouts.admin', ['title' => 'Edit Product'])

@section('content')
    <x-page-header title="Edit Product" subtitle="Update perfume product details." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.partials.form', [
                'product' => $product,
                'brands' => $brands,
                'categories' => $categories,
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'submitLabel' => 'Save changes',
            ])
        </div>
    </div>
@endsection
