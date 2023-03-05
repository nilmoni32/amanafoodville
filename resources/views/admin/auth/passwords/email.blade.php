<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{asset('backend')}}/css/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - {{config('app.name')}}Admin</title>
</head>

<body>
    <section class="material-half-bg">
        <div class="cover"></div>
    </section>
    <section class="login-content">
        <div class="logo">
            <h1>{{config('app.name')}}</h1>
        </div>
        <div class="container mb-5 mt-5">
            <div class="row justify-content-center">
                <div class="col-sm-10 col-md-7">
                    <div class="card mb-5 mt-5">
                        <div class="card-header"><strong>{{ __('ADMIN RESET PASSWORD') }}</strong></div>

                        <div class="card-body">
                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif

                            <form method="POST" action="{{ route('admin.password.email') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email"
                                        class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section>
    <!-- Essential javascripts for application to work-->
    <script src="{{asset('backend')}}/js/jquery-3.2.1.min.js"></script>
    <script src="{{asset('backend')}}/js/popper.min.js"></script>
    <script src="{{asset('backend')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('backend')}}/js/main.js"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{asset('backend')}}js/plugins/pace.min.js"></script>
</body>

</html>