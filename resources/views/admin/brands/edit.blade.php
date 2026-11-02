@extends('layouts.admin', ['title' => 'Edit Brand'])

@section('content')
    <x-page-header title="Edit Brand" subtitle="Update brand details and visibility." />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.brands.partials.form', [
                'action' => route('admin.brands.update', $brand),
                'method' => 'PUT',
                'submitLabel' => 'Update brand',
            ])
        </div>
    </div>
@endsection
