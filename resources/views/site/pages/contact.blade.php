@extends('site.app')
@section('title', 'Contact Us')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Contact Us</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<div class="contactus contactus-2">
    <div class="container">
        <div class="row">
            <!-- Title Content Start -->
            <div class="col-sm-12 commontop text-center">
                <h4>Get In Touch</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
                <p>We are very approachable and would love to speak to you. Let’s get in
                    touch.
                </p>
            </div>
            <div class="col-sm-12 commontop text-center">
                @if (session('success'))
                <div class="alert alert-success alert-block bg-success text-white mt-3">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-error alert-block bg-danger text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('error') }}</strong>
                </div>
                @endif
            </div>
            <div class="col-sm-12 col-xs-12 mb-5">
                <!--  Map Start  -->
                <div class="map">
                    <iframe src="{{ config('settings.google_map') }}"></iframe>
                </div>
                <!--  Map End  -->
            </div>

            <div class="col-md-5 col-sm-6 col-xs-12">
                <!--  Contact form Start  -->
                <form action="{{ route('contact.post.mail') }}" method="post" enctype="multipart/form-data"
                    class="form-horizontal">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input name="name" placeholder="Your name" id="name"
                                class="form-control @error('name') is-invalid @enderror" type="text" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input name="email" placeholder="Your email" id="email"
                                class="form-control @error('email') is-invalid @enderror" type="email" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <input name="mobile" placeholder="Your Phone Number" id="mobile"
                                class="form-control @error('mobile') is-invalid @enderror" type="number" required>
                            @error('mobile')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <textarea name="enquiry" id="enquiry" class="form-control" rows="4"
                                placeholder="Your Message" required></textarea>
                        </div>
                    </div>
                    <div class="buttons">
                        <input class="btn btn-theme-alt btn-block" type="submit" value="Send Message" />
                    </div>
                </form>
                <!--  Contact form End  -->
            </div>
            <div class="col-md-7 col-sm-6 col-xs-12">
                <!-- Address Start  -->
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="address-box">
                            <div class="box text-center">
                                <div class="icon">
                                    <i class="icofont icofont-home"></i>
                                </div>
                                <h4>ADDRESS</h4>
                                <p>{{ config('settings.contact_address') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="address-box">
                            <div class="box text-center">
                                <div class="icon">
                                    <i class="icofont icofont-phone"></i>
                                </div>
                                <h4>PHONE NO.</h4>
                                <p>{{ config('settings.phone_no') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="address-box">
                            <div class="box text-center">
                                <div class="icon">
                                    <i class="icofont icofont-ui-message"></i>
                                </div>
                                <h4>EMAIL</h4>
                                <p><a
                                        href="mailto://{{ config('settings.default_email_address') }}">{{ config('settings.default_email_address') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="address-box">
                            <div class="box social">
                                <h4>SOCIAL LINKES</h4>
                                <ul class="list-inline mt-1">
                                    <li class="list-inline-item"><a href="{{ config('settings.social_facbook') }}"
                                            target="_blank"><i class="icofont icofont-social-facebook"></i></a></li>
                                    <li class="list-inline-item"><a href="{{ config('settings.social_twitter') }}"
                                            target="_blank"><i class="icofont icofont-social-twitter"></i></a></li>
                                    <li class="list-inline-item"><a href="{{ config('settings.social_instagram') }}"
                                            target="_blank"><i class="icofont icofont-social-instagram"></i></a></li>
                                    <li class="list-inline-item"><a href="{{ config('settings.social_youtube') }}"
                                            target="_blank"><i class="icofont icofont-social-youtube-play"></i></a></li>
                                </ul>
                                <p class="py-3 text-center">Let’s get in touch.</p>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- Address End  -->
            </div>
        </div>
    </div>
</div>
</div>








@endsection