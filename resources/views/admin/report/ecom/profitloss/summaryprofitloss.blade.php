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
        <a href="{{ route('admin.reports.ecom.pdfgetprofitloss', [$start_date, $end_date, 'summary']) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>
        <a href="#" class="btn btn-sm btn-info"><i
                class="fa fa-file-excel-o" style="font-size:17px;"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-12 pt-3">
                        <form action="{{ route('admin.reports.ecom.getprofitloss') }}" method="post"
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
                            <div class="form-group mx-sm-3 mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="profitLossOption" id="inlineRadio1" value="summary" required {{$summary ? 'checked' : ''}}>
                                    <label class="form-check-label font-weight-bold" for="inlineRadio1">Summary</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="profitLossOption" id="inlineRadio2" value="productwise">
                                    <label class="form-check-label font-weight-bold" for="inlineRadio2">Product wise</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">
                                Preview</button>
                        </form>
                    </div>
                </div>
                @if($time_sales->count() > 0)
                @php 
                $totalSalesCost=0.0; 
                $totalSales=0.0; 
                @endphp
                @foreach($time_sales as $cart)
                @php 
                $totalSalesCost +=$cart->salesCost;
                $totalSales+=$cart->sales;
                @endphp
                @endforeach                             
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>                                
                            <th class="text-center"> Name </th>
                            <th class="text-center"> Sales</th>
                            <th class="text-center"> SalesCost </th>                            
                            <th class="text-center"> Profit/Loss </th>
                        </tr>
                    </thead>
                    <tbody> 
                        <tr>
                            <td class="text-center">{{ __('1')}}</td>                               
                            <td class="text-center">
                                {{ config('settings.site_name') }}
                            </td>
                            <td class="text-center">{{ round( $totalSales,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                            <td class="text-center">{{ round($totalSalesCost,2) }}
                                {{ config('settings.currency_symbol') }}                                  
                            </td>
                            <td class="text-center">
                                {{round(($totalSales - $totalSalesCost),2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="row h6">
                                    <div class="col-10 text-right">Net Total Sales :</div>
                                    <div class="col-2 pl-0"><span>{{ round($totalSales,2) }}</span><span class="ml-1 mr-2">{{ config('settings.currency_symbol') }}</span></div>                        
                                </div>
                                <div class="row h6">
                                    <div class="col-10 text-right">Net Sales Cost :</div>
                                    <div class="col-2 pl-0"><span>{{ round($totalSalesCost,2) }}</span><span class="ml-1 mr-2">{{ config('settings.currency_symbol') }}</span></div>                        
                                </div>
                                <div class="row h6">
                                    <div class="col-10 text-right">Net Profit/Loss :</div>
                                    <div class="col-2 pl-0"><span>{{ round(($totalSales-$totalSalesCost) ,2) }}</span>
                                        <span class="ml-1 mr-2">{{ config('settings.currency_symbol') }}</span>
                                        &nbsp; {{ "( " . round((($totalSales - $totalSalesCost) / $totalSalesCost)*100,2) ."% )" }}
                                    </div>                        
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

     //$('#sampleTable').DataTable();
    });
</script>

@endpush