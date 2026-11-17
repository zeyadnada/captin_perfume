@extends('layouts.auth')

@section('content')
    <a href="{{ route('admin.login') }}" class="auth-brand" aria-label="{{ __('admin.auth.home_aria', ['app' => config('app.name')]) }}">
        <span class="logo-mark" aria-hidden="true">{{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}</span>
        <span class="logo-text">{{ config('app.name') }}</span>
    </a>

    <h1 class="auth-title">{{ __('admin.auth.welcome') }}</h1>
    <p class="auth-subtitle">{{ __('admin.auth.subtitle') }}</p>

    <form class="login-form" method="post" action="{{ route('admin.login.store') }}">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('admin.auth.email') }}</label>
            <input
                id="email"
                class="au-input @error('email') is-invalid @enderror"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="{{ __('admin.auth.email_placeholder') }}"
                autocomplete="email"
                required
                autofocus
            >
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">{{ __('admin.auth.password') }}</label>
            <input
                id="password"
                class="au-input @error('password') is-invalid @enderror"
                type="password"
                name="password"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            >
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="login-checkbox">
            <label>
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                {{ __('admin.auth.remember') }}
            </label>
        </div>

        <button class="au-btn au-btn--green" type="submit">{{ __('admin.auth.submit') }}</button>
    </form>
@endsection
