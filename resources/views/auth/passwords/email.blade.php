@extends('site.app')
@section('title', 'Password Recovery')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Password Recovery</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="index.html">HOME</a></li>
                <li class="list-inline-item"><a href="#">Password Recovery</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="login">
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-1"></div>
            <div class="col-md-10 col-12 commontop my-5 text-center">
                <h4>User Account Recovery</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
            </div>
            <div class="offset-md-1"></div>
            <div class="col-lg-10 col-md-12">
                <div class="row justify-content-center mt-5">
                    <div class="col-sm-12 col-md-6">
                        <div class="loginnow">
                            <h5>Password Recovery</h5>
                            <form action="{{ route('postforgot') }}" method="post" role="form">
                                @csrf
                                <div class="from-group mb-2">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text" id="email_or_phone">+880</span>
                                    </div> --}}
                                    <input type="text"
                                        class="form-control @error('email_or_phone') is-invalid @enderror"
                                        name="email_or_phone" value="{{ old('email_or_phone') }}"
                                        placeholder="Email or Mobile Number(e.g.017xxxxxxxx)" id="email_or_phone"
                                        required autocomplete="email_or_phone" />
                                    @error('email_or_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group text-center">
                                    <input type="submit" value="send email or phone number"
                                        class="btn btn-theme-alt btn-wide mt-4 mb-0" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection