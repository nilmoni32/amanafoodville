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
        <a href="{{ route('admin.reports.pdfcombinedgetprofitloss', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.combined.getcombinedprofitLoss') }}" method="post"
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
                @if($kot_sales->count() > 0 || $ecom_sales->count() > 0 || $buffet_sales->count() > 0)
                
                {{-- Calculating total sales of kot  --}}
                @php $kot_totalSalesCost=0.0; $ecom_totalSalesCost=0.0; $buffet_totalSalesCost=0.0; 
                     $ecom_totalSales=0.0; $kot_totalSales= $discount->total_sales - $discount->total_vat; $buffet_totalSales= $discountBuffet->total_sales - $discountBuffet->total_vat;
                @endphp
                @foreach($kot_sales as $cart)
                    @php $kot_totalSalesCost += $cart->salesCost; 
                    @endphp
                @endforeach 
                {{-- Calculating total sales of buffet  --}}
                @foreach($buffet_sales as $cart)
                    @php $buffet_totalSalesCost += $cart->salesCost; 
                    @endphp
                @endforeach 

                {{-- Calculating total sales of ecommerce  --}}
                @foreach($ecom_sales as $cart)
                    @php $ecom_totalSalesCost +=$cart->salesCost; $ecom_totalSales += $cart->sales;
                    @endphp                    
                @endforeach  
                
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>                                
                            <th class="text-center"> Sales Type </th>
                            <th class="text-center"> Net Sales</th>
                            <th class="text-center"> Net Sales Cost </th>                            
                            <th class="text-center"> Net Profit/Loss </th>
                        </tr>
                    </thead>
                    <tbody> 
                        @php $i=1; @endphp
                        @if($ecom_sales->count() > 0)
                         
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>                               
                            <td class="text-center">
                                {{ _('E-commerce profit-loss') }}
                            </td>
                            <td class="text-center">{{ round( $ecom_totalSales,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                            <td class="text-center">{{ round( $ecom_totalSalesCost,2) }}
                                {{ config('settings.currency_symbol') }}                                  
                            </td>
                            <td class="text-center">
                                {{ round(($ecom_totalSales - $ecom_totalSalesCost),2) }} {{ config('settings.currency_symbol')  }}  
                                &nbsp; {{ "( " . round((($ecom_totalSales - $ecom_totalSalesCost) / $ecom_totalSalesCost)*100,2) ."% )" }}
                            </td>
                        </tr>
                        @endif
                        @if($kot_sales->count() > 0)
                       
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>                               
                            <td class="text-center">
                                {{  _('MIS KOT profit-loss') }}
                            </td>
                             {{-- Already deducted Reference Discount & Customer Points Discount from KOT order sales. --}}
                            <td class="text-center">{{ round($kot_totalSales,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                                {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                            <td class="text-center">{{ round(($kot_totalSalesCost + $complimentary_sales_cost),2) }}
                                {{ config('settings.currency_symbol') }}                                  
                            </td>
                            <td class="text-center">
                                {{ round($kot_totalSales -($kot_totalSalesCost + $complimentary_sales_cost),2) }} {{ config('settings.currency_symbol')  }}  
                                &nbsp; {{ "( " . round((($kot_totalSales-($kot_totalSalesCost + $complimentary_sales_cost)) / 
                                ($kot_totalSalesCost + $complimentary_sales_cost))*100,2) ."% )" }}
                            </td>
                        </tr>
                        @endif

                        @if($buffet_sales->count() > 0)
                        
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>                               
                            <td class="text-center">
                                {{  _('MIS Buffet profit-loss') }}
                            </td>
                            {{-- Already deducted Reference Discount & Customer Points Discount from buffet order sales. --}}
                            <td class="text-center">{{ round($buffet_totalSales,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                                {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                            <td class="text-center">{{ round(($buffet_totalSalesCost),2) }}
                                {{ config('settings.currency_symbol') }}                                  
                            </td>
                            <td class="text-center">
                                {{ round($buffet_totalSales-($buffet_totalSalesCost),2) }} {{ config('settings.currency_symbol')  }}  
                                &nbsp; {{ "( " . round((($buffet_totalSales-($buffet_totalSalesCost)) /                                 
                                ($buffet_totalSalesCost))*100,2) ."% )" }}
                            </td>
                        </tr>
                        @endif

                        @php 
                            $total_sales = $kot_totalSales + $ecom_totalSales + $buffet_totalSales;
                            $total_sales_cost = $ecom_totalSalesCost + ($kot_totalSalesCost + $complimentary_sales_cost) + $buffet_totalSalesCost;
                            $net_profit_loss = $total_sales - $total_sales_cost;
                            $percentile_net_profit_loss = round(($net_profit_loss / $total_sales_cost)*100,2);
                        @endphp
                        <tr class="font-weight-bold">
                            <td class="text-center">{{ $i++ }}</td>                               
                            <td class="text-center">
                                {{  _('Combined profit-loss') }}
                            </td>
                            {{-- Already deducted Reference Discount & Customer Points Discount from KOT order sales. --}}
                            <td class="text-center">{{ round($total_sales,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                                {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                            <td class="text-center">{{ round($total_sales_cost,2) }}
                                {{ config('settings.currency_symbol') }}                                  
                            </td>
                            <td class="text-center">
                                {{ round($net_profit_loss,2) }} {{ config('settings.currency_symbol')  }}  
                                &nbsp; {{ "( " . $percentile_net_profit_loss ."% )" }}
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