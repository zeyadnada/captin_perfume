@extends('layouts.storefront')

@section('content')
    <section class="main-project-section">
        <div class="container">
            <nav aria-label="breadcrumb" class="py-3">
                <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '>';">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('storefront.auth.register_title') }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="border rounded-3 p-4 p-md-5 bg-white">
                        <h3 class="mb-2">{{ __('storefront.auth.register_title') }}</h3>
                        <p class="text-muted mb-4">{{ __('storefront.auth.register_subtitle') }}</p>

                        <form method="post" action="{{ route('register.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="name">{{ __('storefront.auth.name') }}</label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    required
                                    autofocus
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
                                    value="{{ old('email') }}"
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
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    class="form-control @error('phone') is-invalid @enderror"
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('storefront.auth.password') }}</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    class="form-control @error('password') is-invalid @enderror"
                                >
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
                                    required
                                    class="form-control"
                                >
                            </div>

                            <button type="submit" class="btn btn-teal w-100 rounded-pill text-uppercase">
                                {{ __('storefront.auth.submit_register') }}
                            </button>
                        </form>

                        <p class="mt-4 mb-0 text-center">
                            {{ __('storefront.auth.has_account') }}
                            <a href="{{ route('login') }}">{{ __('storefront.auth.login_link') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
