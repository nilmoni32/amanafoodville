@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-database"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <form action="{{ route('admin.due.orders.search') }}" method="get">
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

                        {{-- <div class="app-search offset-xl-10 col-xl-2 offset-md-9 col-md-3 col-7">
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
                            <th class="text-center"> Customer Phone No </th>                       
                            <th class="text-center"> Order Date </th>
                            <th class="text-center"> Last Payment Date</th>
                            <th class="text-center"> Paid Amount </th>
                            <th class="text-center"> Due Amount </th>                            
                            <th class="text-center"> Order Status</th>
                            <th style=" min-width:50px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                            {{-- <th class="text-center">Order Details</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->order_number }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->client->mobile }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">                                
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ \Carbon\Carbon::parse($order->payment_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ round($order->receive_total,2) }}
                            </td>
                            @php $total_taka = 0.0; @endphp
                            @foreach( App\Models\Duesale::where('dueordersale_id',
                                        $order->id)->get() as $sale)
                                        @php $total_taka += $sale->product_quantity * $sale->unit_price @endphp
                            @endforeach
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $total_taka ? round(($total_taka + ($total_taka * (config('settings.tax_percentage')/100))) - $order->receive_total,2) : 0 }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->status }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    @if($order->status == 'cancel' || $order->status == 'delivered')
                                    <a href="#" class="btn btn-sm btn-secondary"
                                        style="background-color:rgb(142, 177, 183); border-color:rgb(142, 177, 183);"
                                        disabled><i class="fa fa-edit"></i></a>
                                    @else
                                    <a href="{{ route('admin.due.orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    @endif
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#userCartModal{{ $order->id }}"><i
                                            class="fa fa-shopping-basket"></i></a>
                                    <!-- User Cart Modal -->
                                    @include('admin.sales.due.includes.userCart')                               
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    @if($order->status == 'cancel' || $order->status == 'delivered')
                                        <a href="#" class="btn btn-sm btn-secondary allprint" disabled>
                                            <i class="fa fa-print" style="font-size:15px"></i>
                                        </a>
                                    @else
                                        <!-- POS Print for Customer all orders placed before payments -->
                                        <a href="#" class="btn btn-sm btn-dark allprint" data-toggle="modal"
                                            data-target="#customerPrintModal{{ $order->id }}"
                                            data-orderId="{{ $order->id }}"><i class="fa fa-print"
                                            style="font-size:16px"></i>
                                        </a>
                                        @include('admin.sales.due.includes.print')
                                    @endif                                    
                                </div>

                            </td>
                            {{-- <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                
                            </td>                             --}}
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

