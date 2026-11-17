@extends('layouts.storefront')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>{{ __('storefront.auth.login_title') }}</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                            <span>{{ __('storefront.auth.login_title') }}</span>
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
                        <h6 class="checkout__title">{{ __('storefront.auth.login_title') }}</h6>
                        <p class="mb-4">{{ __('storefront.auth.login_subtitle') }}</p>

                        <form method="post" action="{{ route('login.store') }}">
                            @csrf

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.email') }}<span>*</span></p>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    required
                                    autofocus
                                    class="@error('email') is-invalid @enderror"
                                >
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.password') }}<span>*</span></p>
                                <input
                                    type="password"
                                    name="password"
                                    autocomplete="current-password"
                                    required
                                    class="@error('password') is-invalid @enderror"
                                >
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input__checkbox mb-3">
                                <label for="remember">
                                    {{ __('storefront.auth.remember') }}
                                    <input type="checkbox" id="remember" name="remember" value="1" @checked(old('remember'))>
                                    <span class="checkmark"></span>
                                </label>
                            </div>

                            <button type="submit" class="site-btn">{{ __('storefront.auth.submit_login') }}</button>
                        </form>

                        <p class="mt-4 mb-0">
                            {{ __('storefront.auth.no_account') }}
                            <a href="{{ route('register') }}">{{ __('storefront.auth.register_link') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
