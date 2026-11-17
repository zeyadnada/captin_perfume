@php
    $currentLocale = app()->getLocale();
    $availableLocales = config('app.available_locales', []);
@endphp
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-7">
                    <div class="header__top__left">
                        <p>{{ __('storefront.header.promo') }}</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5">
                    <div class="header__top__right">
                        <div class="header__top__links">
                            @auth
                                <a href="{{ route('account.show') }}">{{ __('storefront.nav.account') }}</a>
                                <form method="post" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 align-baseline text-reset text-decoration-none">
                                        {{ __('storefront.nav.logout') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}">{{ __('storefront.nav.sign_in') }}</a>
                                <a href="{{ route('register') }}">{{ __('storefront.nav.register') }}</a>
                            @endauth
                        </div>
                        <div class="header__top__hover">
                            <span>{{ $availableLocales[$currentLocale]['label'] ?? strtoupper($currentLocale) }} <i class="arrow_carrot-down"></i></span>
                            <ul>
                                @foreach ($availableLocales as $code => $meta)
                                    <li>
                                        <a href="{{ route('locale.switch', $code) }}">{{ $meta['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="header__logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('malefashion/img/logo.png') }}" alt="{{ config('app.name') }}"></a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <nav class="header__menu mobile-menu">
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a>
                        </li>
                        <li>
                            <a href="#">{{ __('storefront.nav.shop') }}</a>
                        </li>
                        @auth
                            <li class="{{ request()->routeIs('account.*') ? 'active' : '' }}">
                                <a href="{{ route('account.show') }}">{{ __('storefront.nav.account') }}</a>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="header__nav__option">
                    <a href="#" class="search-switch"><img src="{{ asset('malefashion/img/icon/search.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('malefashion/img/icon/heart.png') }}" alt=""></a>
                    <a href="#"><img src="{{ asset('malefashion/img/icon/cart.png') }}" alt=""> <span>0</span></a>
                    <div class="price">$0.00</div>
                </div>
            </div>
        </div>
        <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
</header>
