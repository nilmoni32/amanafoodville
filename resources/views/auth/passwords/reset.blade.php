@extends('site.app')
@section('title', 'Reset Password')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Reset Password</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="index.html">HOME</a></li>
                <li class="list-inline-item"><a href="#">Reset Password</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<!-- adding session messages -->
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
</div>
<div class="container mb-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-5 mt-5">
                <div class="card-header"><strong>{{ __('Reset Password') }}</strong></div>

                <div class="card-body">
                    {{-- we have modified the route name to resetpassword --}}
                    <form method="POST" action="{{ route('resetpassword.update', $token) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail or Phone No') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email"
                                    value="{{ $email ?? old('email') }}" disabled="disabled">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <input type="submit" value="Reset Password" class="btn btn-theme btn-md btn-block"
                                    style="width:75%; font-weight:normal;" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection