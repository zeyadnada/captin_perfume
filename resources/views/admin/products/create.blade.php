@extends('layouts.admin', ['title' => __('admin.products.create_title')])

@section('content')
    <x-page-header :title="__('admin.products.create_title')" :subtitle="__('admin.products.create_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.partials.form', [
                'product' => new \App\Models\Product(['is_active' => true]),
                'brands' => $brands,
                'categories' => $categories,
                'action' => route('admin.products.store'),
                'method' => 'POST',
                'submitLabel' => __('admin.products.submit_create'),
            ])
        </div>
    </div>
@endsection