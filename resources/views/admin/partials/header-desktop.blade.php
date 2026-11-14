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
                        <div class="account-item clearfix">
                            <div class="content">
                                <span class="js-acc-btn">{{ __('admin.nav.app_admin', ['app' => config('app.name')]) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
