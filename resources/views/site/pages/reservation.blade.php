@extends('site.app')
@section('title', 'Reservation')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Reservation</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Reservation</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="reservation no-bg coffee">
    <div class="container">
        <div class="row">
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
            <!-- Title Content Start -->
            <div class="col-sm-12 commontop text-center mb-2">
                <h4 class="text-center">{{ __('Book Your Table') }}</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="mt-3 mx-3">
                            {{ __("We look forward to serving you delicious, healthy and quality food. Let's start  online booking at the Funville Restaurant of your choice. 
                        If you wish to make a reservation for a larger party please contact us on the number below, we also accept walk-ins.") }}
                        </p>
                    </div>
                    <div class="col-sm-12">
                        <a href="" class="btn btn-theme-alt"><i class="icofont icofont-phone"></i>
                            {{ config('settings.phone_no') }} </li></a>
                    </div>
                    <div class="offset-md-2"></div>
                    <div class="col-md-8 col-xs-12 mt-5">
                        <!-- Reservation Form Start -->
                        <form action="{{ route('reservation.post.mail') }}" method="POST" class="row" role="form">
                            @csrf
                            <div class="form-group col-sm-6">
                                <input name="fname" placeholder="First name" id="fname"
                                    class="form-control @error('fname') is-invalid @enderror" type="text" required>
                                @error('fname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <input name="lname" placeholder="Last name" id="lname"
                                    class="form-control @error('lname') is-invalid @enderror" type="text" required>
                                @error('lname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <input name="email" placeholder="Your email" id="email"
                                    class="form-control @error('email') is-invalid @enderror" type="email" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <input name="mobile" placeholder="Phone Number" id="mobile"
                                    class="form-control @error('mobile') is-invalid @enderror" type="number" required>
                                @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <input name="appointment_dt" placeholder="Select Date & Time" id="date"
                                    class="form-control datetimepicker" type="text" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <input name="persons" placeholder="Number of persons" id="persons" class="form-control"
                                    type="number" required>
                            </div>
                            <div class="form-group col-xs-12 col-md-12">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-theme btn-wide">book now</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="offset-md-2"></div>

                </div>
            </div>
        </div>
    </div>
</div>





<!-- Fun-Factor Start -->
<div class="fun-factor">
    <div class="container">
        <div class="row ">
            <div class="col-sm-3 col-6">
                <!-- Fun-Factor Box Start -->
                <div class="single-box">
                    <span>
                        <i class="icofont icofont-spoon-and-fork"></i>
                    </span>
                    <h4 class="number" data-from="100" data-to="400" data-refresh-interval="10">100</h4>
                    <h3>MENU ITEMS</h3>
                </div>
                <!-- Fun-Factor Box End -->
            </div>
            <div class="col-sm-3 col-6">
                <!-- Fun-Factor Box Start -->
                <div class="single-box">
                    <span>
                        <i class="icofont icofont-emo-simple-smile"></i>
                    </span>
                    <h4 class="number" data-from="50" data-to="500" data-refresh-interval="10">50</h4>
                    <h3>VISITOR EVERYDAY</h3>
                </div>
                <!-- Fun-Factor Box End -->
            </div>
            <div class="col-sm-3 col-6">
                <!-- Fun-Factor Box Start -->
                <div class="single-box">
                    <span>
                        <i class="icofont icofont-waiter-alt"></i>
                    </span>
                    <h4 class="number" data-from="1" data-to="50" data-refresh-interval="10">1</h4>
                    <h3>EXPERT CHEF</h3>
                </div>
                <!-- Fun-Factor Box End -->
            </div>
            <div class="col-sm-3 col-6">
                <!-- Fun-Factor Box Start -->
                <div class="single-box">
                    <span>
                        <i class="icofont icofont-heart-alt"></i>
                    </span>
                    <h4 class="number" data-from="10" data-to="300" data-refresh-interval="10">10</h4>
                    <h3>TEST & LOVE</h3>
                </div>
                <!-- Fun-Factor Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Fun-Factor End -->


@endsection
@push('scripts')

<script>
    $(document).ready(function () {
      $('.datetimepicker').datetimepicker({
        timepicker:true,
        datepicker:true,        
        format: 'd-m-Y H:i a',
        step:30,
        ampm: true,
        hours12:false,        
      });
      $(".datetimepicker").attr("autocomplete", "off");
    });
</script>

@endpush