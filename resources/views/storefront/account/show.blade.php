@extends('layouts.storefront')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>{{ __('storefront.account.title') }}</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                            <span>{{ __('storefront.account.breadcrumb') }}</span>
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
                        <h6 class="checkout__title">{{ __('storefront.account.title') }}</h6>
                        <p class="mb-4">{{ __('storefront.account.subtitle') }}</p>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('account.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.name') }}<span>*</span></p>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    autocomplete="name"
                                    required
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
                                    value="{{ old('email', $user->email) }}"
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
                                    value="{{ old('phone', $user->phone) }}"
                                    autocomplete="tel"
                                    class="@error('phone') is-invalid @enderror"
                                >
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.account.password_optional') }}</p>
                                <input
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    class="@error('password') is-invalid @enderror"
                                >
                                <small class="text-muted">{{ __('storefront.account.password_hint') }}</small>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="checkout__input">
                                <p>{{ __('storefront.auth.password_confirmation') }}</p>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                >
                            </div>

                            <button type="submit" class="site-btn">{{ __('storefront.account.save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
