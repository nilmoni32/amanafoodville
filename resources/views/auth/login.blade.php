@extends('site.app')
@section('title', 'Login')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Login</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="index.html">HOME</a></li>
                <li class="list-inline-item"><a href="#">Login</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
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
                <h4>Login to Your Account</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
            </div>
            <div class="col-lg-10 col-md-12">
                <div class="row justify-content-center mt-5">
                    <div class="col-sm-12 col-md-6">
                        <div class="loginnow">
                            <h5>Login</h5>
                            <p>Don't have an account? So <a href="{{ route('register') }}">Register</a> And starts less
                                than a
                                minute</p>
                            <form action="{{ route('login') }}" method="post" role="form">
                                @csrf
                                <div class="form-group">
                                    <i class="icofont icofont-ui-message fa-fw mr-3"></i>
                                    <i class="icofont icofont-phone mr-3"></i>
                                    <input type="text"
                                        class="form-control @error('email_or_phone') is-invalid @enderror"
                                        name="email_or_phone" value="{{ old('email_or_phone') }}"
                                        placeholder="Email or Mobile Number(e.g.017xxxxxxxx)" id="email_or_phone"
                                        required />
                                    @error('email_or_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <i class="icofont icofont-lock"></i>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" value="{{ old('password') }}" placeholder="PASSWORD"
                                        id="password" required autocomplete="current-password" />
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="links">
                                        <label><input type="checkbox" class="radio-inline" name="remember" id="remember"
                                                {{ old('remember') ? 'checked' : '' }} />{{ __('Remember Me') }}</label>

                                        @if (Route::has('password.request'))
                                        <a class="float-right sign" href="{{ route('password.request') }}">
                                            {{ __('Forgot Password?') }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="SIGN IN" class="btn btn-theme btn-md btn-block" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login End -->


@stop