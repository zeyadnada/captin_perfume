@extends('layouts.admin', ['title' => __('admin.brands.edit_title')])

@section('content')
    <x-page-header :title="__('admin.brands.edit_title')" :subtitle="__('admin.brands.edit_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.brands.partials.form', [
                'action' => route('admin.brands.update', $brand),
                'method' => 'PUT',
                'submitLabel' => __('admin.brands.submit_update'),
            ])
        </div>
    </div>
@endsection