@extends('layouts.admin', ['title' => __('admin.roles.create_title')])

@section('content')
    <x-page-header :title="__('admin.roles.create_title')" :subtitle="__('admin.roles.create_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.roles.partials.form', [
                'role' => new \Spatie\Permission\Models\Role(),
                'permissionGroups' => $permissionGroups,
                'isSystemRole' => false,
                'action' => route('admin.roles.store'),
                'method' => 'POST',
                'submitLabel' => __('admin.roles.submit_create'),
            ])
        </div>
    </div>
@endsection
