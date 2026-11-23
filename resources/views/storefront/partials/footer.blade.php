<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <div class="footer__logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('malefashion/img/footer-logo.png') }}" alt="{{ config('app.name') }}"></a>
                    </div>
                    <p>{{ __('storefront.footer.about') }}</p>
                    <a href="#"><img src="{{ asset('malefashion/img/payment.png') }}" alt=""></a>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h6>{{ __('storefront.footer.shopping') }}</h6>
                    <ul>
                        <li><a href="{{ route('home') }}">{{ __('storefront.nav.home') }}</a></li>
                        <li><a href="{{ route('shop.index') }}">{{ __('storefront.nav.shop') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-6">
                <div class="footer__widget">
                    <h6>{{ __('storefront.footer.support') }}</h6>
                    <ul>
                        @auth
                            <li><a href="{{ route('account.show') }}">{{ __('storefront.nav.account') }}</a></li>
                        @else
                            <li><a href="{{ route('login') }}">{{ __('storefront.nav.sign_in') }}</a></li>
                            <li><a href="{{ route('register') }}">{{ __('storefront.nav.register') }}</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h6>{{ __('storefront.footer.newsletter') }}</h6>
                    <div class="footer__newslatter">
                        <p>{{ __('storefront.footer.newsletter_text') }}</p>
                        <form action="#" onsubmit="return false;">
                            <input type="text" placeholder="{{ __('storefront.footer.email_placeholder') }}">
                            <button type="submit"><span class="icon_mail_alt"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="footer__copyright__text">
                    <p>
                        Copyright &copy; {{ date('Y') }}
                        {{ __('storefront.footer.copyright') }}
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        {{ __('storefront.footer.by') }}
                        <a href="https://colorlib.com" target="_blank" rel="noopener">Colorlib</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
