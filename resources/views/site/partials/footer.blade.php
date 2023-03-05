<!-- Footer Start -->
<footer class="top-border">
    <div class="container">
        <div class="row inner">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <!-- Footer Widget Start -->
                <span class="foot-title">About Us</span>
                <img src="{{ asset('storage/'.config('settings.site_logo')) }}" class="img-fluid mb-3" title="logo"
                    style="max-height:75px" alt="logo">
                <ul class="list-unstyled">
                    <li class="paragraph">
                        Amana Foodville restaurant features sophisticated interpretations of traditional fare that is
                        accented with artistic touches, presenting one of the most unique dining experiences in
                        Mohakhali Dhaka.
                    </li>
                </ul>
                <!--  Footer Social Start -->
                <ul class="list-inline social footer-social">
                    <li class="list-inline-item"><a href="{{ config('settings.social_facbook') }}" target="_blank"><i
                                class="icofont icofont-social-facebook"></i></a></li>
                    <li class="list-inline-item"><a href="{{ config('settings.social_twitter') }}" target="_blank"><i
                                class="icofont icofont-social-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="{{ config('settings.social_instagram') }}" target="_blank"><i
                                class="icofont icofont-social-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="{{ config('settings.social_youtube') }}" target="_blank"><i
                                class="icofont icofont-social-youtube-play"></i></a></li>
                </ul>
                <!--  Footer Social End -->

                <!-- Footer Widget End -->
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 hidden-sm hidden-xs">
                <!-- Footer Widget Start -->
                <span class="foot-title">Sitemap</span>
                <ul class="list-unstyled">
                    <li><a href="{{ route('index') }}"><span>Home</span></a></li>
                    <li><a href="{{ route('about')}}"><span>About Us</span></a></li>
                    <li><a href="{{ route('products.index') }}"><span>Food Menu</span></a></li>
                    <li><a href="{{ route('reservation') }}"><span>Reservation</span></a></li>
                    <li><a href="{{ route('contact') }}"><span>Contact Us</span></a></li>
                </ul>
                <!-- Footer Widget End -->
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <!-- Footer Widget Start -->
                <span class="foot-title">Contact Us</span>
                <ul class="list-unstyled contact">
                    <li><i class="icofont icofont-social-google-map"></i> {{ config('settings.contact_address') }}</li>
                    <li><i class="icofont icofont-phone"></i> {{ config('settings.phone_no') }} </li>
                    <li><a href="mailto://{{ config('settings.default_email_address') }}"><i
                                class="icofont icofont-ui-message"></i>{{ config('settings.default_email_address') }}</a>
                    </li>
                    <li><i class="icofont icofont-ui-clock"></i>Open Hours: {{ config('settings.open_hours') }}
                    </li>
                    <li></li>
                </ul>
                <!-- Footer Widget End -->
            </div>
        </div>

    </div>
    <div class="footer-bottom footer-bg">
        <div class="container">
	    <div class="row">               
                <div class="col-md-12 col-sm-12 col-xs-12 text-center pt-3">
		    <a target="_blank" href="https://www.sslcommerz.com/" title="SSLCommerz" alt="SSLCommerz">
                        <img style="width:95%;height:auto;" src="https://securepay.sslcommerz.com/public/image/SSLCommerz-Pay-With-logo-All-Size-03.png">
                    </a>
                </div>
            </div>
            <div class="row powered d-flex align-items-center">
                <!--  Copyright Start -->
                {{-- <div class="col-md-2 col-sm-6 order-md-1">
                </div> --}}
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">{{ config('settings.footer_copyright_text') }},
                        &nbsp;2022-<?php echo date("Y"); ?>.</p>
                </div>
                {{-- <div class="col-md-4 col-sm-12 text-right text-white">
                    <p>
                        {{ __('Pay With ') }}
                        <img src="{{ asset('frontend/')}}/images/visa-icon.png" alt="" class="d-inline-block"
                            width="35px;"><img src="{{ asset('frontend/')}}/images/mastercard-icon.png" alt=""
                            class="d-inline-block" width="35px;"><img
                            src="{{ asset('frontend/')}}/images/american-express.png" alt="" class="d-inline-block"
                            width="35px;"><img src="{{ asset('frontend/')}}/images/bkash_icon.png" alt=""
                            class="d-inline-block" width="35px;"></p>
                </div> --}}
                {{-- <div class="col-md-2 col-sm-6 text-right order-md-3">
                </div> --}}
                <!--  Copyright End -->
            </div>
        </div>
    </div>
</footer>
<!-- Footer End  -->
