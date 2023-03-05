@extends('site.app')
@section('title', 'Homepage')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Payment Notification</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Payment Notification</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<div class="mycart">
    <div class="container paycard">
        <div class="row">
            <div class="offset-md-1"></div>
            <div class="col-md-10 col-12 mb-5 text-center">
                @if (session('success'))
                <div class="alert alert-success alert-block bg-success text-white">
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
            <div class="offset-md-1"></div>
        </div>
        <div class="row">
            <div class="col-12 pb-5">
                <h5 class="card-title mt-2 text-center mb-2">Order Number:
                    {{ $order->order_number }}</h5>
                <p class="text-center pt-0 font-weight-bold">
                </p>
                @if(session('success'))
                <p class="text-center py-3">
                    <strong>[ Track your recent order: <a href="{{ route('index')}}/user/dashboard"
                            style="color:#E9457A;">{{ route('index')}}/user/dashboard ]</a></strong>
                </p>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="offset-md-2"></div>
            <div class="col-md-8 col-12 mb-5">
                <div class="row">
                    <div class="col-md-6 col-12 mb-3">
                        <div class="card">
                            <div class="card-header text-center">
                                Delivery Date & Address
                            </div>
                            <div class="card-body text-center">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>{{ $order->address }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                            $time = strtotime($order->order_date);
                                            $date = date('Y-m-d', $time); @endphp
                                            <td class="text-center">Order Date: <span class="ml-5">{{ $date }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            @php
                                            $time = strtotime($order->delivery_date);
                                            $date = date('Y-m-d', $time); @endphp
                                            <td class="text-center">Delivery Date: <span class="ml-5">{{ $date }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 mb-3">
                        <div class="card">
                            <div class="card-header text-center">
                                Order Summary
                            </div>
                            <div class="card-body text-center">
                                <table class="table table-borderless">
                                    @php $subtotal = ($order->grand_total - config('settings.delivery_charge'))/(1+
                                    (config('settings.tax_percentage')/100)) @endphp
                                    <tbody>
                                        <tr>
                                            <td class="text-left">Subtotal</td>
                                            <td class="text-left">{{ config('settings.currency_symbol') }}
                                                {{  round($subtotal,0) }}
                                            </td>
                                        </tr>
                                        @if(config('settings.tax_percentage'))
                                        <tr>
                                            <td class="text-left">Vat ({{config('settings.tax_percentage')}}%)</td>
                                            <td class="text-left">
                                                {{ config('settings.currency_symbol') }}
                                                {{ round($subtotal * (config('settings.tax_percentage')/100),0) }}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-left">Shipping Cost</td>
                                            <td class="text-left">{{ config('settings.currency_symbol') }}
                                                {{  config('settings.delivery_charge') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Order Total</td>
                                            <td class="text-left">{{ config('settings.currency_symbol') }}
                                                {{  round($order->grand_total,0) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offset-md-2"></div>
        </div>
    </div>
</div>

@endsection