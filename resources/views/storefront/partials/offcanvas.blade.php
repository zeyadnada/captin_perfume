<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="offcanvas__option">
        <div class="offcanvas__links">
            @auth
                <a href="{{ route('account.show') }}">{{ __('storefront.nav.account') }}</a>
            @else
                <a href="{{ route('login') }}">{{ __('storefront.nav.sign_in') }}</a>
                <a href="{{ route('register') }}">{{ __('storefront.nav.register') }}</a>
            @endauth
        </div>
        <div class="offcanvas__top__hover">
            <span>{{ __('storefront.header.language') }} <i class="arrow_carrot-down"></i></span>
            <ul>
                @foreach (config('app.available_locales', []) as $code => $meta)
                    <li>
                        <a href="{{ route('locale.switch', $code) }}">{{ $meta['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="offcanvas__nav__option">
        <a href="#" class="search-switch"><img src="{{ asset('malefashion/img/icon/search.png') }}" alt=""></a>
        <a href="#"><img src="{{ asset('malefashion/img/icon/heart.png') }}" alt=""></a>
        <a href="#"><img src="{{ asset('malefashion/img/icon/cart.png') }}" alt=""> <span>0</span></a>
        <div class="price">$0.00</div>
    </div>
    <div id="mobile-menu-wrap"></div>
    <div class="offcanvas__text">
        <p>{{ __('storefront.header.promo') }}</p>
    </div>
</div>
