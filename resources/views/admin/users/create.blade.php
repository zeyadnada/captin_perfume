@extends('layouts.admin', ['title' => __('admin.users.create_title')])

@section('content')
    <x-page-header :title="__('admin.users.create_title')" :subtitle="__('admin.users.create_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.users.partials.form', [
                'user' => new \App\Models\User(),
                'roles' => $roles,
                'action' => route('admin.users.store'),
                'method' => 'POST',
                'submitLabel' => __('admin.users.submit_create'),
            ])
        </div>
    </div>
@endsection
