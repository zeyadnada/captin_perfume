@extends('layouts.admin', ['title' => 'Add Brand'])

@section('content')
    <x-page-header title="Add Brand" subtitle="Create a brand for perfume products." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.brands.partials.form', [
                'brand' => new \App\Models\Brand(['is_active' => true]),
                'action' => route('admin.brands.store'),
                'method' => 'POST',
                'submitLabel' => 'Create brand',
            ])
        </div>
    </div>
@endsection
