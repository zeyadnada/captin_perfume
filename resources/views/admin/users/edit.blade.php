@extends('layouts.admin', ['title' => __('admin.users.edit_title')])

@section('content')
    <x-page-header :title="__('admin.users.edit_title')" :subtitle="__('admin.users.edit_subtitle')" />

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            @include('admin.users.partials.form', [
                'user' => $user,
                'roles' => $roles,
                'action' => route('admin.users.update', $user),
                'method' => 'PUT',
                'submitLabel' => __('admin.users.submit_update'),
            ])
        </div>
    </div>
@endsection
