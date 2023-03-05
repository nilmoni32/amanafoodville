@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-tags"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <div class="pull-right">
        <a href="{{ route('admin.reports.pdfgetTop20', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>
        <a href="{{ route('admin.reports.excelgetTop20', [$start_date, $end_date]) }}" class="btn btn-sm btn-info"><i
                class="fa fa-file-excel-o" style="font-size:17px;"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-12 pt-3">
                        <form action="{{ route('admin.reports.getTop20') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            <div class="form-group mb-2">
                                <label>
                                    <span class="font-weight-bold pr-1">From Date :</span>
                                    <input type="text" name="start_date" class="form-control datetimepicker"
                                        value="{{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}" required>
                                </label>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label class="font-bold">
                                    <span class="font-weight-bold pr-1">To Date :</span>
                                    <input type="text" name="end_date" class="form-control datetimepicker"
                                        value="{{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}" required>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2" name="top20btn">
                                Preview</button>
                        </form>
                    </div>
                </div>
                <table class="table table-hover table-bordered" id="sampleTable">
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
                        @foreach($time_carts as $cart)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- we need to add  @stack('scripts') in the app.blade.php for the following scripts --}}
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
    $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
    });
    $(".datetimepicker").attr("autocomplete", "off");

    $('#sampleTable').DataTable();
    });
</script>

@endpush