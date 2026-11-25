@extends('layouts.storefront')

@section('content')
    <section class="main-project-section">
        <div class="container">
            <nav aria-label="breadcrumb" class="py-3">
                <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '>';">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('storefront.account.breadcrumb') }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="border rounded-3 p-4 p-md-5 bg-white">
                        <h3 class="mb-2">{{ __('storefront.account.title') }}</h3>
                        <p class="text-muted mb-4">{{ __('storefront.account.subtitle') }}</p>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('account.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('storefront.auth.name') }}</label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    autocomplete="name"
                                    required
                                    class="form-control @error('name') is-invalid @enderror"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('storefront.auth.email') }}</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    autocomplete="email"
                                    required
                                    class="form-control @error('email') is-invalid @enderror"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">
                                    {{ __('storefront.auth.phone') }}
                                    <span class="text-muted">({{ __('storefront.auth.optional') }})</span>
                                </label>
                                <input
                                    id="phone"
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    autocomplete="tel"
                                    class="form-control @error('phone') is-invalid @enderror"
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('storefront.account.password_optional') }}</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    class="form-control @error('password') is-invalid @enderror"
                                >
                                <div class="form-text">{{ __('storefront.account.password_hint') }}</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="password_confirmation">{{ __('storefront.auth.password_confirmation') }}</label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    class="form-control"
                                >
                            </div>

                            <button type="submit" class="btn btn-teal w-100 rounded-pill text-uppercase">
                                {{ __('storefront.account.save') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
