@extends('layouts.admin', ['title' => __('admin.categories.edit_title')])

@section('content')
    <x-page-header :title="__('admin.categories.edit_title')" :subtitle="__('admin.categories.edit_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.categories.partials.form', [
                'category' => $category,
                'parents' => $parents,
                'action' => route('admin.categories.update', $category),
                'method' => 'PUT',
                'submitLabel' => __('admin.categories.submit_update'),
            ])
        </div>
    </div>
@endsection