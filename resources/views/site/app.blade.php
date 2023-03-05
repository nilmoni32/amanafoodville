<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="keywords" content="{{ config('settings.seo_meta_title') }}">
    <meta name="description" content="{{ config('settings.seo_meta_description') }}">
    <!-- Styles -->
    @include('site.partials.styles')
    <script src="https://use.fontawesome.com/3a7e4171cb.js"></script>
    {{-- google recaptcha --}}
    {!! htmlScriptTagJsApi() !!} 
    {{-- google analytics code --}}
    {!! config('settings.google_analytics') !!}
     {{-- FaceBook Pixels code --}}
    {!! config('settings.facebook_pixels') !!}
</head>

<body>
    <div class="wrapper">
        <!-- Loader Start -->
        <div class="loader">
            <div class="loader-inner">
                <h4>Amana Foodville Restaurant and Party Center</h4>
                <div id="cooking">
                    <div class="bubble"></div>
                    <div class="bubble"></div>
                    <div class="bubble"></div>
                    <div class="bubble"></div>
                    <div class="bubble"></div>
                    {{-- <div id="area">
                        <div id="sides">
                            <div id="pan"></div>
                            <div id="handle"></div>
                        </div>
                        <div id="pancake">
                            <div id="pastry"></div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Loader End -->
        @include('site.partials.header')
        <!--inside header we include navigation -->
        @yield('content')
        @include('site.partials.footer')
    </div>
    @include('site.partials.scripts')
    @stack('scripts')
</body>

</html>
