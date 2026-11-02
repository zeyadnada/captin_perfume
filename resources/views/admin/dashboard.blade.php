@extends('layouts.admin', [
    'title' => 'Dashboard',
])

@section('content')
    <x-page-header
        title="Dashboard"
        subtitle="Welcome to the {{ config('app.name') }} administration dashboard."
    />

    <div class="row">
        <div class="col-md-6 col-lg-4">
            <section class="au-card m-b-30">
                <div class="au-card-inner">
                    <h4>Foundation ready</h4>
                    <p class="mb-0">CoolAdmin assets and reusable Blade layouts are integrated.</p>
                </div>
            </section>
        </div>
    </div>
@endsection
