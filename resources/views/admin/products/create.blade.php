@extends('layouts.admin', ['title' => 'Add Product'])

@section('content')
    <x-page-header title="Add Product" subtitle="Create a perfume product." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.partials.form', [
                'product' => new \App\Models\Product(['is_active' => true]),
                'brands' => $brands,
                'categories' => $categories,
                'action' => route('admin.products.store'),
                'method' => 'POST',
                'submitLabel' => 'Create product',
            ])
        </div>
    </div>
@endsection
