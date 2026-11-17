@php
    $currentLocale = app()->getLocale();
    $availableLocales = config('app.available_locales', []);
@endphp
<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <button class="sidebar-toggle js-sidebar-toggle" type="button" aria-label="{{ __('admin.common.toggle_navigation') }}" aria-expanded="false" aria-controls="main-sidebar">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <div class="header-button ms-auto d-flex align-items-center gap-3">
                    <div class="locale-switcher" role="group" aria-label="{{ __('admin.common.language') }}">
                        @foreach ($availableLocales as $code => $meta)
                            <a
                                href="{{ route('locale.switch', $code) }}"
                                class="locale-switcher__link{{ $currentLocale === $code ? ' is-active' : '' }}"
                                @if ($currentLocale === $code) aria-current="true" @endif
                            >{{ $meta['label'] }}</a>
                        @endforeach
                    </div>
                    <div class="account-wrap">
                        <div
                            class="account-item clearfix js-item-menu"
                            role="button"
                            tabindex="0"
                            aria-haspopup="true"
                            aria-label="{{ __('admin.auth.account_menu') }}"
                        >
                            <div class="image">
                                <img src="{{ asset('cooladmin/images/icon/avatar-7.jpg') }}" alt="{{ auth()->user()->name }}">
                            </div>
                            <div class="content">
                                <a class="js-acc-btn" href="#">{{ auth()->user()->name }}</a>
                            </div>
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image">
                                        <img src="{{ asset('cooladmin/images/icon/avatar-7.jpg') }}" alt="{{ auth()->user()->name }}">
                                    </div>
                                    <div class="content">
                                        <h5 class="name">
                                            <a href="#">{{ auth()->user()->name }}</a>
                                        </h5>
                                        <span class="email">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                                <div class="account-dropdown__footer">
                                    <form method="post" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit">
                                            <i class="fa-solid fa-power-off" aria-hidden="true"></i>
                                            {{ __('admin.auth.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
