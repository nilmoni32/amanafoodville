<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title') - {{ config('app.name', 'Funville') }}</title>
  <!-- Main CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/css/jquery.datetimepicker.css" />
  <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css"
    href="{{ asset('backend') }}/css/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/images/favicon.ico') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/css/bootstrap-toggle.min.css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/css/bootstrap-notifications.css" />
  <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/css/main.css" />
  <!--Jquery ui CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/jqueryui/jquery-ui.min.css">

  @yield('styles')
  {!! htmlScriptTagJsApi() !!} 
</head>

<body class="app sidebar-mini rtl" style="overflow-x: hidden;">
  @include('admin.partials.header');
  @include('admin.partials.sidebar');
  <main class="app-content">
    @yield('content')
  </main>
  <!-- Essential javascripts for application to work-->
  <script src="{{ asset('backend') }}/js/jquery-3.2.1.min.js"></script>
  <script src="{{ asset('backend') }}/js/popper.min.js"></script>
  <script src="{{ asset('backend') }}/js/bootstrap.min.js"></script>
  <script src="{{ asset('backend') }}/js/jquery.datetimepicker.full.min.js"></script>
  <script src="{{ asset('backend/js/plugins/select2.min.js') }}"></script>
  <script src="{{ asset('backend') }}/js/bootstrap-toggle.min.js"></script>
  <script src="{{ asset('backend') }}/js/pusher.min.js"></script>
  <script src="{{ asset('backend') }}/js/main.js"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="{{ asset('backend') }}/js/plugins/pace.min.js"></script>
  <!--Jquery ui script -->
  <script src="{{ asset('backend') }}/jqueryui/jquery-ui.min.js" type="text/javascript"></script>

  @stack('scripts')
</body>

</html>