@extends('layouts.storefront')

@section('content')
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="{{ asset('malefashion/img/hero/hero-1.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>{{ __('storefront.home.eyebrow') }}</h6>
                                <h2>{{ __('storefront.home.title') }}</h2>
                                <p>{{ __('storefront.home.subtitle') }}</p>
                                <a href="{{ route('shop.index') }}" class="primary-btn">
                                    {{ __('storefront.home.cta') }}
                                    <span class="arrow_right"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg" data-setbg="{{ asset('malefashion/img/hero/hero-2.jpg') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>{{ __('storefront.home.eyebrow') }}</h6>
                                <h2>{{ __('storefront.home.title') }}</h2>
                                <p>{{ __('storefront.home.subtitle') }}</p>
                                <a href="{{ route('shop.index') }}" class="primary-btn">
                                    {{ __('storefront.home.cta') }}
                                    <span class="arrow_right"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
