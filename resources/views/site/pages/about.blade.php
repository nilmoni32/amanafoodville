@extends('site.app')
@section('title', 'About Us')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>About Us</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">About Us</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<div class="container">
    <div class="row">
        <!-- Title Content Start -->
        <div class="col-sm-12 commontop text-center mb-2">
            <h4 class="text-center">{{ __('Welcome to Amana Foodville') }}</h4>
            <div class="divider style-1 center">
                <span class="hr-simple left"></span>
                <i class="icofont icofont-ui-press hr-icon"></i>
                <span class="hr-simple right"></span>
            </div>
            <div class="row px-6">
                <div class="offset-md-1"></div>
                <div class="col-md-4 col-sm-12 text-center mb-3 pl-4">
                    <picture>
                        <source srcset="{{ asset('/frontend')}}/images/restaurant-back.webp">
                        <source srcset="{{ asset('/frontend')}}/images/restaurant-back.jpg">
                        <img src="{{ asset('/frontend')}}/images/funville.jpg" alt="" class="img-responsive img-story">
                    </picture>

                </div>
                <div class="col-md-6 col-sm-12 about">
                    <p class="text-left mt-3">
                        {{ __('Amana Foodnville restaurant features sophisticated interpretations of traditional fare that is
            accented with artistic touches, presenting one of the most unique dining experiences in Mohakhali Dhaka. A
            combination of gracious service, inventive cuisine, stylish décor and stunning views ensure that
            the restaurant is a hit with both guests and locals alike. Using its panoramic city views for
            inspiration, the focal point of the restaurant is an incredible city view.') }} </p>
                </div>
                <div class="offset-md-1"></div>

            </div>

            <!-- Title Content End -->
            <div class="row">
                <div class="col-sm-12 col-xs-12 commontop text-center mb-5">
                    <h4 class="text-center">{{ __('Our Services') }}</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                @foreach(App\Models\Service::orderBy('id', 'desc')->get() as $service)
                <div class="col-lg-4 col-md-6 col-12 service-item">
                    <i class="fa {{ $service->icon }}"></i>
                    <div>
                        <h4>{{ $service->title }}</h4>
                        <p class="ml-3">{{ $service->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

    </div>
</div>


@endsection