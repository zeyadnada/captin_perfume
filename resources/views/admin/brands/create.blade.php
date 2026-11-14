@extends('layouts.admin', ['title' => __('admin.brands.create_title')])

@section('content')
    <x-page-header :title="__('admin.brands.create_title')" :subtitle="__('admin.brands.create_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.brands.partials.form', [
                'brand' => new \App\Models\Brand(['is_active' => true]),
                'action' => route('admin.brands.store'),
                'method' => 'POST',
                'submitLabel' => __('admin.brands.submit_create'),
            ])
        </div>
    </div>
@endsection