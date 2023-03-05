@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <div class="pull-right">
        <a href="{{ route('admin.reports.pdfdaily') }}" class="btn btn-sm btn-dark" target="_blank"><i
                class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>
        <a href="{{ route('admin.reports.exceldaily') }}" class="btn btn-sm btn-primary"><i class="fa fa-file-excel-o"
                style="font-size:16px;"></i></a>

        {{-- <div style="display:inline-block;">
            <form action="{{ route('admin.reports.exceldaily') }}" method="post" enctype="multipart/form-data">
        @csrf
        <button class="btn btn-sm btn-info"><i class="fa fa-file-excel-o" style="font-size:17px;"></i></button>
        </form>
    </div> --}}
</div>

</div>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="tile">
            <div class="tile-body">
                <h4 class="tile-title">{{ __('Product wise Daily Sale :') }}
                </h4>
                @if($daily_carts->count() > 0)
                <p class="text-right h6">Date: {{ date('Y-m-d', strtotime("-1 days")) }} </p>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Food Name </th>
                            <th class="text-center"> Unit Price </th>
                            <th class="text-center"> Total Qty </th>
                            <th class="text-center"> Subtotal </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total =0.0; @endphp
                        @foreach($daily_carts as $cart)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">
                                @if($cart->product_attribute_id)
                                {{ App\Models\Product::find($cart->product_id)->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})
                                @else
                                {{ App\Models\Product::find($cart->product_id)->name }}
                                @endif

                            </td>
                            <td class="text-center">{{ round( $cart->unit_price,0) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                            <td class="text-center">{{ $cart->total_qty }}</td>
                            <td class="text-center">
                                {{ round($cart->subtotal,0) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                        </tr>
                        @php $total += $cart->subtotal ;
                        @endphp
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <h4 class="text-right mb-0 ">Total Sale:
                                    {{ round( $total, 0) }}
                                    {{ config('settings.currency_symbol') }}
                                </h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-center p-5 h5">No Food Items has been sold out on
                    {{ date('Y-m-d', strtotime("-1 days")) }}
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection