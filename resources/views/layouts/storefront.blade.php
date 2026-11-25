@php
    $locale = app()->getLocale();
    $localeMeta = config('app.available_locales.'.$locale, ['dir' => 'ltr']);
    $direction = $localeMeta['dir'] ?? 'ltr';
    $isRtl = $direction === 'rtl';
    $bootstrapCss = $isRtl ? 'kalles/css/bootstrap-rtl.min.css' : 'kalles/css/bootstrap.min.css';
    $appCss = $isRtl ? 'kalles/css/app-rtl.min.css' : 'kalles/css/app.min.css';
@endphp
<!doctype html>
<html
    lang="{{ str_replace('_', '-', $locale) }}"
    dir="{{ $direction }}"
    data-kalles-dir="{{ $direction }}"
    data-bootstrap-ltr="{{ asset('kalles/css/bootstrap.min.css') }}"
    data-bootstrap-rtl="{{ asset('kalles/css/bootstrap-rtl.min.css') }}"
    data-app-ltr="{{ asset('kalles/css/app.min.css') }}"
    data-app-rtl="{{ asset('kalles/css/app-rtl.min.css') }}"
    x-data
    :dir="$store.appStore.dir"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} | {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('kalles/images/k_favicon_32x.png') }}">

    <link href="{{ asset('kalles/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('kalles/libs/flickity/flickity.min.css') }}">
    <link rel="stylesheet" href="{{ asset('kalles/libs/jarallax/jarallax.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:300,300i,400,400i,500,500i&display=swap" rel="stylesheet">
    <link href="{{ asset($bootstrapCss) }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('kalles/icons/font-icon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('kalles/libs/pixeden-stroke-7-icon/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset($appCss) }}" id="app-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('kalles/css/storefront-theme.css') }}" rel="stylesheet" type="text/css">
    <style>
        .product-card-media {
            aspect-ratio: 4 / 5;
            background: var(--perfume-well);
        }

        .product-card-media > a,
        .product-card-media > img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .product-card-media img {
            object-fit: contain;
            object-position: center;
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ showMenuScroll: false }">
    @include('storefront.partials.top-banner')
    @include('storefront.partials.header')

    @if (session('success') || session('status'))
        <div class="container pt-3">
            <div class="alert alert-success mb-0" role="alert">
                {{ session('success') ?? session('status') }}
            </div>
        </div>
    @endif

    @yield('content')

    @include('storefront.partials.footer')
    @include('storefront.partials.popup')
    @include('storefront.partials.card-model')

    <script src="{{ asset('kalles/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('kalles/js/laravel-store.js') }}"></script>
    <script src="{{ asset('kalles/libs/jarallax/jarallax.min.js') }}"></script>
    <script src="{{ asset('kalles/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('kalles/libs/alpinejs/cdn.min.js') }}"></script>
    <script src="{{ asset('kalles/libs/jquery-countdown/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('kalles/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('kalles/js/product-slider.init.js') }}"></script>
    <script src="{{ asset('kalles/js/popup.js') }}"></script>
    <script src="{{ asset('kalles/libs/flickity/flickity.pkgd.min.js') }}"></script>
    <script src="{{ asset('kalles/js/main.js') }}"></script>
    <script src="{{ asset('kalles/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
