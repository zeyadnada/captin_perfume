@extends('layouts.admin', [
    'title' => __('admin.dashboard.title'),
])

@section('content')
    <x-page-header
        :title="__('admin.dashboard.title')"
        :subtitle="__('admin.dashboard.welcome', ['app' => config('app.name')])"
    />

    <div class="row">
        <div class="col-md-6 col-lg-4">
            <section class="au-card m-b-30">
                <div class="au-card-inner">
                    <h4>{{ __('admin.dashboard.foundation_title') }}</h4>
                    <p class="mb-0">{{ __('admin.dashboard.foundation_body') }}</p>
                </div>
            </section>
        </div>
    </div>
@endsection