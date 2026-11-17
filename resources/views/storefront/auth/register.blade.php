@extends('layouts.storefront')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>{{ __('storefront.auth.register_title') }}</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                            <span>{{ __('storefront.auth.register_title') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container">
            <div class="checkout__form">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <h6 class="checkout__title">{{ __('storefront.auth.register_title') }}</h6>
                        <p class="mb-4">{{ __('storefront.auth.register_subtitle') }}</p>

                        <form method="post" action="{{ route('register.store') }}">
                            @csrf

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.name') }}<span>*</span></p>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    required
                                    autofocus
                                    class="@error('name') is-invalid @enderror"
                                >
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.email') }}<span>*</span></p>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                    class="@error('email') is-invalid @enderror"
                                >
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.phone') }} <span class="text-muted">({{ __('storefront.auth.optional') }})</span></p>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    autocomplete="tel"
                                    class="@error('phone') is-invalid @enderror"
                                >
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.password') }}<span>*</span></p>
                                <input
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    class="@error('password') is-invalid @enderror"
                                >
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.password_confirmation') }}<span>*</span></p>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>

                            <button type="submit" class="site-btn">{{ __('storefront.auth.submit_register') }}</button>
                        </form>

                        <p class="mt-4 mb-0">
                            {{ __('storefront.auth.has_account') }}
                            <a href="{{ route('login') }}">{{ __('storefront.auth.login_link') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
