@extends('layouts.admin', ['title' => __('admin.products.edit_title')])

@section('content')
    <x-page-header :title="__('admin.products.edit_title')" :subtitle="__('admin.products.edit_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.products.partials.form', [
                'product' => $product,
                'brands' => $brands,
                'categories' => $categories,
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'submitLabel' => __('admin.products.submit_update'),
            ])
        </div>
    </div>
@endsection