@extends('layouts.storefront')

@section('content')
    <section class="main-project-section">
        <div class="container">
            <nav aria-label="breadcrumb" class="py-3">
                <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '>';">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ __('storefront.auth.login_title') }}</li>
                </ol>
            </nav>
        </div>
    </section>

    <section class="pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="border rounded-3 p-4 p-md-5 bg-white">
                        <h3 class="mb-2">{{ __('storefront.auth.login_title') }}</h3>
                        <p class="text-muted mb-4">{{ __('storefront.auth.login_subtitle') }}</p>

                        <form method="post" action="{{ route('login.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="email">{{ __('storefront.auth.email') }}</label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                    autofocus
                                    class="form-control @error('email') is-invalid @enderror"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">{{ __('storefront.auth.password') }}</label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    class="form-control @error('password') is-invalid @enderror"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                                <label class="form-check-label" for="remember">{{ __('storefront.auth.remember') }}</label>
                            </div>

                            <button type="submit" class="btn btn-teal w-100 rounded-pill text-uppercase">
                                {{ __('storefront.auth.submit_login') }}
                            </button>
                        </form>

                        <p class="mt-4 mb-0 text-center">
                            {{ __('storefront.auth.no_account') }}
                            <a href="{{ route('register') }}">{{ __('storefront.auth.register_link') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
