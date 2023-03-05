@extends('site.app')
@section('title', '404')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">        
    </div>
</div>
<!-- Breadcrumb End -->
<div class="reservation no-bg mt-5">
    <div class="container">
        <div class="row">
            <!-- Title Content Start -->
            <div class="col-sm-12 commontop text-center mb-2">
                <h1 class="text-center mt-2">{{ __('404') }}</h1>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mt-3 mx-3 py-4">
                            {{ __("We're sorry, the page you requested could not be found.") }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

