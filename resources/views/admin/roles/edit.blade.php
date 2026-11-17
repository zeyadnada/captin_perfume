@extends('layouts.admin', ['title' => __('admin.roles.edit_title')])

@section('content')
    <x-page-header :title="__('admin.roles.edit_title')" :subtitle="__('admin.roles.edit_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.roles.partials.form', [
                'role' => $role,
                'permissionGroups' => $permissionGroups,
                'isSystemRole' => $isSystemRole,
                'action' => route('admin.roles.update', $role),
                'method' => 'PUT',
                'submitLabel' => __('admin.roles.submit_update'),
            ])
        </div>
    </div>
@endsection
