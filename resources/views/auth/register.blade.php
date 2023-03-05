@extends('site.app')
@section('title', 'Register')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Sign up</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="index.html">HOME</a></li>
                <li class="list-inline-item"><a href="#">Sign up</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<!-- adding session messages -->
<div class="container">

</div>

<!-- Login Start -->
<div class="login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-1"></div>
            <div class="col-md-10 col-12 my-5 text-center">
                @if (session('error'))
                <div class="alert alert-error alert-block bg-danger text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('error') }}</strong>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success alert-block bg-success text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
                @endif

            </div>
            <div class="offset-md-1"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 commontop text-center">
                <h4>Create an Account</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
            </div>
            <div class="col-lg-10 col-md-12">
                <div class="row justify-content-center mt-5">
                    <div class="col-md-6 col-sm-12 ">
                        <div class="loginnow">
                            <h5>Register</h5>
                            <p>Do You have an account? So <a href="{{ route('login') }}">login</a> And starts less than
                                a minute.</p>
                            <form action="{{ route('register') }}" method="post" role="form">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" id="name" value="{{ old('name') }}"
                                        placeholder="Your Name (Required)" required autocomplete="name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="input-group mb-3 pb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="phone_number">+880</span>
                                    </div>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                        name="phone_number" value="{{ old('phone_number') }}"
                                        placeholder="Phone No e.g.017xxxxxxxx (Required)" id="phone_number" required
                                        autocomplete="phone_number" />
                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" value="{{ old('password') }}" placeholder="Password (Required)"
                                        id="password" required autocomplete="new-password" />
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" placeholder="Confirm Password (Required)"
                                        id="password_confirmation" required autocomplete="new-password" />
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group">
                                    <textarea placeholder="Contact Address" id="current-address"
                                        class="form-control mt-1" rows="4" name="address" autocomplete="address"
                                        style="height:100px; align-items:center;"></textarea>
                                </div> --}}
                                <label style="cursor:pointer; color:#757575; margin-top:-30px;"><input type="checkbox"
                                        class="radio-inline" name="check_email" id="check_email" onclick="mailCheck()">
                                    Do you have an email account? (optional)
                                </label>
                                <div class="form-group">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" placeholder="E-Mail Address" id="email"
                                        autocomplete="email" style="display:none;" />
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="SIGN UP" class="btn btn-theme btn-md btn-block my-4" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register End -->
@stop
@push('scripts')
<script type="text/javascript">
    //pos system discount option is showing or hiding.
    function mailCheck(){             
            if($("#check_email").prop("checked") == true) { 
                 $('#email').show();
            }else{          
                $('#email').removeAttr("style").hide();
            }           
        }
</script>
@endpush