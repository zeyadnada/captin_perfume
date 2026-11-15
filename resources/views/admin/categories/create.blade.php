@extends('layouts.admin', ['title' => __('admin.categories.create_title')])

@section('content')
    <x-page-header :title="__('admin.categories.create_title')" :subtitle="__('admin.categories.create_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.categories.partials.form', [
                'category' => new \App\Models\Category(['is_active' => true]),
                'parents' => $parents,
                'action' => route('admin.categories.store'),
                'method' => 'POST',
                'submitLabel' => __('admin.categories.submit_create'),
            ])
        </div>
    </div>
@endsection