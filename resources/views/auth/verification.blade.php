@extends('site.app')
@section('title', 'Verify Code')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Verify Code</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="index.html">HOME</a></li>
                <li class="list-inline-item"><a href="#">Verify Code</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<div class="container">
    <div class="row justify-content-center">
        <div class="offset-md-1"></div>
        <div class="col-md-10 col-12 my-5 text-center">
            @if (session('success'))
            <div class="alert alert-success alert-block bg-success text-white">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ session('success') }}</strong>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-block bg-danger text-white">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ session('error') }}</strong>
            </div>
            @endif
        </div>
        <div class="offset-md-1"></div>
    </div>
</div>

<div class="container mb-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-8">
            <div class="card mb-5 mt-5">
                <div class="card-header"><strong>{{ __('Verify Code') }}</strong></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('verify') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="verify_token"
                                class="col-md-4 col-form-label text-md-right">{{ __('Verification Code') }}</label>

                            <div class="col-md-6">
                                <input id="verify_token" type="text"
                                    class="form-control @error('verify_token') is-invalid @enderror" name="verify_token"
                                    value="" required autofocus>

                                @error('verify_token')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <input type="submit" value="Verify" class="btn btn-theme btn-md btn-block"
                                    style="width:50%; font-weight:normal;" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection