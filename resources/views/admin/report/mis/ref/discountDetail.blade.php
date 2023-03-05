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
        <a href="{{ route('admin.reports.pdfrefDiscount', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>
        {{-- <a href="#" class="btn btn-sm btn-info"><i
                class="fa fa-file-excel-o" style="font-size:17px;"></i></a> --}}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-12 pt-3">
                        <form action="{{ route('admin.reports.getrefDiscount') }}" method="post"
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
                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">
                                Preview</button>
                        </form>
                    </div>
                </div>                
                @if($ref_orders->count() > 0)
                <div class="row mx-1 border p-3">              
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> Reference Name </th>  
                                <th class="text-center"> Reference Type </th>                                                                                
                                <th class="text-center"> Ref Discount Amount </th>
                                <th class="text-center"> Offered Discount slab (%) </th> 
                                <th class="text-center"> Allotted Discount slab (%) </th>                        
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($ref_orders as $order)                           
                            <tr>
                                @php 
                                //calculate paid_total for each reference
                                $paid_total = $order->grand_total + $order->ref_discount + $order->reward_discount ?? 0 
                                + $order->card_discount ?? 0 + $order->fraction_discount ?? 0 + $order->gpstar_discount ?? 0;
                                $offered_discount_slab = ($order->ref_discount / $paid_total) * 100;                               
                                $referee = App\Models\Director::where('id', $order->director_id)->first(); 
                                @endphp
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td class="text-center">{{ $referee->name }}</td>
                                <td class="text-center">{{ $referee->ref_type }}</td>
                                <td class="text-center">{{ round($order->ref_discount,2) }} {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($offered_discount_slab,2) }}</td>
                                <td class="text-center">{{ $referee->discount_slab_percentage }}</td>                                                            
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>                
                @else
                <p class="text-center p-5 h5">{{ __('No food items has been sold out by the specified date.')}}                    
                </p>
                @endif
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