@php
    $locale = app()->getLocale();
    $localeMeta = config('app.available_locales.'.$locale, ['dir' => 'ltr']);
    $direction = $localeMeta['dir'] ?? 'ltr';
    $isRtl = $direction === 'rtl';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $direction }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} | {{ config('app.name') }}</title>

    @if ($isRtl)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="{{ asset('malefashion/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('malefashion/css/style.css') }}" type="text/css">
    @if ($isRtl)
        <style>
            body { font-family: 'Cairo', sans-serif; }
        </style>
    @endif
    @stack('styles')
</head>
<body class="{{ $isRtl ? 'storefront-rtl' : '' }}">
    @include('storefront.partials.preloader')
    @include('storefront.partials.offcanvas')
    @include('storefront.partials.header')

    @yield('content')

    @include('storefront.partials.footer')
    @include('storefront.partials.search')

    <script src="{{ asset('malefashion/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('malefashion/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('malefashion/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
