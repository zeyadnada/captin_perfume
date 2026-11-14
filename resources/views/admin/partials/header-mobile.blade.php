<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('cooladmin/images/icon/logo.png') }}" alt="{{ config('app.name') }}">
                </a>
                <button class="hamburger hamburger--slider" type="button" aria-label="{{ __('admin.common.toggle_navigation') }}">
                    <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                </button>
            </div>
        </div>
    </div>
</header>
