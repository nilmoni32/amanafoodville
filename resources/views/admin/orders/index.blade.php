@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-bar-chart"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <form action="{{ route('admin.orders.search') }}" method="get">
                    @csrf
                    <div class="row mb-3 mr-4">

                        <div class="col-4 mx-auto">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search..." name="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="app-search offset-xl-10 col-xl-2 offset-md-6 col-md-3 col-7">
                            <input class="app-search__input"
                                style="background:rgb(230, 230, 230); border: 1px solid rgb(201, 201, 201); margin-right:-50px; width:112%;"
                                type="search" placeholder="Search" name="search" />
                            <button type="submit" class="app-search__button" style="margin-right:-18px;">
                                <i class="fa fa-search"></i>
                            </button>
                        </div> --}}
                    </div>
                </form>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> Order No </th>
                            <th class="text-center"> Order Date</th>
                            <th class="text-center"> Paid Amount </th>
                            <th class="text-center"> Payment Status </th>
                            <th class="text-center"> Payment Type </th>
                            <th class="text-center"> Order Status</th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                    class="fa fa-bolt"></i></th>
                            <th class="text-center">View Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->order_number }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{-- {{ $order->order_date }} --}}
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ round($order->grand_total,0) }}
                            </td>
                            @if($order->payment_status)
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <span class="badge badge-success">{{ __('Paid') }}</span>
                            </td>
                            @else
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <span class="badge badge-danger">{{ __('Not paid') }}</span>
                            </td>
                            @endif
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->payment_method }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->status }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    @if($order->status == 'cancel' || $order->status == 'delivered' || $order->status ==
                                    'failed' )
                                    <a href="#" class="btn btn-sm btn-secondary"
                                        style="background-color:rgb(142, 177, 183); border-color:rgb(142, 177, 183);"
                                        disabled><i class="fa fa-edit"></i></a>
                                    @else
                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                    @endif
                                    @if($order->status == 'delivered')
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}"
                                        class="btn btn-sm btn-dark" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    @else
                                    <a href="#" class="btn btn-sm btn-secondary" disabled><i
                                            class="fa fa-file-pdf-o"></i></a>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#userModal{{ $order->id }}"><i class="fa fa-user"></i></a>
                                    <!-- User Details Modal -->
                                    @include('admin.orders.includes.userDetail')

                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#userCartModal{{ $order->id }}"><i
                                            class="fa fa-shopping-basket"></i></a>
                                    <!-- User Cart Modal -->
                                    @include('admin.orders.includes.userCart')
                                    <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#userBankModal{{ $order->id }}"><i class="fa fa-money"></i></a>
                                    <!-- User Bank Transaction Modal -->
                                    @include('admin.orders.includes.userBank')
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="pt-4 text-right">
    {{ $orders->links() }}
</div>

@endsection
{{-- Reloading this page after 30 sec --}}
{{-- @push('scripts')
<script type="text/javascript">
    setTimeout(function(){
 
        location.reload();
 
    },90000);
 
</script>
@endpush --}}