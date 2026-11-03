@extends('layouts.admin', ['title' => 'Edit Category'])

@section('content')
    <x-page-header title="Edit Category" subtitle="Update category details and hierarchy." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.categories.partials.form', [
                'category' => $category,
                'parents' => $parents,
                'action' => route('admin.categories.update', $category),
                'method' => 'PUT',
                'submitLabel' => 'Update category',
            ])
        </div>
    </div>
@endsection
