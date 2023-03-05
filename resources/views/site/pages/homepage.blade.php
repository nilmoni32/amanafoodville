@extends('site.app')
@section('title', 'Home')
@section('content')

<!-- Slider Start -->
<div class="slide">
    <div class="slideshow owl-carousel">
        <!-- Slider Backround Image Start -->
        <div class="item">
            <picture>
                <source srcset="{{ asset('frontend') }}/images/background/banner-1.webp" type="image/webp">
                <source srcset="{{ asset('frontend') }}/images/background/banner-1.jpg" type="image/jpeg">
                <img src="{{ asset('frontend') }}/images/background/banner-1.jpg" alt="banner" title="banner"
                    class="img-responsive" />
            </picture>
        </div>
        <div class="item">
            <picture>
                <source srcset="{{ asset('frontend') }}/images/background/banner-2.webp" type="image/webp">
                <source srcset="{{ asset('frontend') }}/images/background/banner-2.jpg" type="image/jpeg">
                <img src="{{ asset('frontend') }}/images/background/banner-2.jpg" alt="banner" title="banner"
                    class="img-responsive" />
            </picture>
        </div>
        <!-- Slider Backround Image End -->
    </div>

    <!-- Slide Detail Start  -->
    <div class="slide-detail">
        <div class="container">
            <div class="cd-headline clip">
                <h4>LOVES <span class="cd-words-wrapper">
                        <b class="is-visible">HEALTHY</b>
                        <b>QUALITY</b>
                        <b>TESTY</b>
                    </span>FOOD</h4>
            </div>
            <h5 class="text-white">Lets uncover the best places to eat and drink nearest to you.</h5>
        </div>
    </div>
    <!-- Slide Detail End  -->
</div>
<!-- Slider End  -->

<!-- Slider Dishes Start -->
@if(App\Models\Category::where('slug', 'offer-dishes')->first())
@php $slug_value = 'offer-dishes'; @endphp
@include('site.partials.homeslider')
@elseif(App\Models\Category::where('slug', 'popular-dishes')->first())
@php $slug_value = 'popular-dishes'; @endphp
@include('site.partials.homeslider')
@endif
<!-- Slider Dishes End -->

<!-- Reservation Start -->
<div class="reservation">
    <div class="container">
        <div class="row ">
            <!-- Title Content Start -->
            <div class="col-sm-12 commontop white text-center">
                <h4>Our Speciality</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
                <p></p>
            </div>
            <!-- Title Content End -->
            <div class="col-md-12 col-xs-12">
                <div class="row mt-2">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="speciality">
                            <div class="col-sm-12 commontop white text-center">
                                <h3>Best Quality</h3>
                                <div class="divider style-1 center">
                                    <span class="hr-simple left"></span>
                                    <i class="icofont icofont-ui-press hr-icon"></i>
                                    <span class="hr-simple right"></span>
                                </div>
                                <p></p>
                            </div>
                            <p>
                                We are undergoing regular inspections in various aspects to maintain the best
                                international
                                standards to achieve the assured "high quality" services. We choose those products that
                                satisfies high quality parameter to our clients. We have very well establised platform,
                                to
                                catering for demands in domestic as well as international market.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="speciality">
                            <div class="col-sm-12 commontop white text-center">
                                <h3>Customer Satisfaction</h3>
                                <div class="divider style-1 center">
                                    <span class="hr-simple left"></span>
                                    <i class="icofont icofont-ui-press hr-icon"></i>
                                    <span class="hr-simple right"></span>
                                </div>
                                <p></p>
                            </div>
                            <p>
                                The prime focus of the company is to deliver the products that meets the customers
                                satisfaction in an optimum manner. Product delivery,with promised time frame for even
                                bulk
                                orders makes our client have confidence with us and thus lead a good relationship with
                                them.
                                With the help of extensive distribution network and efficient facilities, we have formed
                                the
                                reputation of meeting the deadlines. We further make sure that our clients are benefited
                                with comfortable payment modes &amp;
                                cost
                                effective price. </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="speciality">
                            <div class="col-sm-12 commontop text-center">
                                <h3>Our Vendor Base</h3>
                                <div class="divider style-1 center">
                                    <span class="hr-simple left"></span>
                                    <i class="icofont icofont-ui-press hr-icon"></i>
                                    <span class="hr-simple right"></span>
                                </div>
                                <p></p>
                            </div>
                            <p>
                                Being a Merchant Exporter, we have built cozy relations with various reputed and
                                trustworthy
                                manufactures. We lay emphasis on the manufacturer's past track record,financial
                                stability
                                and goodwill in the maket, before choosing them. They also, faithfully follow the norms
                                of
                                quality specifiedby us, at every level of their work. Every single product from us meets
                                the
                                requirements of our customer and we make sure deliver in time, in consideration of
                                client
                                satisfaction. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Reservation End  -->

<!-- Top Lists Start -->
<div class="container">
    <div class="row ">
        <!-- Title Content Start -->
        <div class="col-sm-12 commontop text-center">
            <h4>Our Top Client Lists</h4>
            <div class="divider style-1 center">
                <span class="hr-simple left"></span>
                <i class="icofont icofont-ui-press hr-icon"></i>
                <span class="hr-simple right"></span>
            </div>
            <p></p>
        </div>
        <!-- Title Content End -->
        <div class="col-md-12 col-xs-12">
            <div class="row mt-4 mb-5 pb-4">
                @php
                $searchForValue = ',';
                $client_string = config('settings.client_lists');
                if(strpos($client_string, $searchForValue)):
                $clients = explode(',', $client_string);
                foreach($clients as $client):@endphp
                <div class="col-lg-3 col-md-4 col-sm-6 col-12 text-center">
                    <div>
                        <h3 class="client">{{ $client }} </h3>
                    </div>
                </div>
                @php endforeach;endif; @endphp
            </div>
        </div>
    </div>
</div>

<!-- Top Lists End  -->

@endsection