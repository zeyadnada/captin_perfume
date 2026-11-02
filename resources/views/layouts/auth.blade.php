<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sign in' }} | {{ config('app.name') }}</title>

    <link href="{{ asset('cooladmin/css/font-face.css') }}" rel="stylesheet" media="all">
    <link rel="preconnect" href="https://rsms.me">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="{{ asset('cooladmin/vendor/fontawesome-7.3.1/css/all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('cooladmin/vendor/bootstrap-5.3.8.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('cooladmin/vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('cooladmin/css/theme.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('cooladmin/css/app.css') }}" rel="stylesheet" media="all">
    @stack('styles')
</head>
<body class="app auth-page">
    <a class="visually-hidden-focusable skip-link" href="#auth-form">Skip to form</a>
    <main class="login-wrap" id="auth-form">
        <div class="login-content">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('cooladmin/js/vanilla-utils.js') }}"></script>
    <script src="{{ asset('cooladmin/vendor/bootstrap-5.3.8.bundle.min.js') }}"></script>
    <script src="{{ asset('cooladmin/js/bootstrap5-init.js') }}"></script>
    <script src="{{ asset('cooladmin/js/main-vanilla.js') }}"></script>
    <script src="{{ asset('cooladmin/js/modern-plugins.js') }}"></script>
    @stack('scripts')
</body>
</html>
