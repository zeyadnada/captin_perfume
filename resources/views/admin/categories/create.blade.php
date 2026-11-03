@extends('layouts.admin', ['title' => 'Add Category'])

@section('content')
    <x-page-header title="Add Category" subtitle="Create a category for perfume products." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.categories.partials.form', [
                'category' => new \App\Models\Category(['is_active' => true]),
                'parents' => $parents,
                'action' => route('admin.categories.store'),
                'method' => 'POST',
                'submitLabel' => 'Create category',
            ])
        </div>
    </div>
@endsection
