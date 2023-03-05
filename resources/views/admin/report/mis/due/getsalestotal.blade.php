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
        <a href="{{ route('admin.reports.pdfDueSalesTotal', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.due.getsalesTotal') }}" method="post"
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
                <div class="row mx-1 py-2 border border-bottom-0">                                      
                    <div class="col-md-4 col-12 py-3">
                        <div class="col-12">
                            <div class="row h5">
                                <div class="col-7 text-right">Net Due Sales  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_sales,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row h5">
                                <div class="col-7 text-right">Net KOT Due Amount   :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_due,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>                                                
                    </div>  
                    <div class="col-md-4 col-12 py-3">                        
                        <div class="col-12">
                            <div class="row h5">
                                <div class="col-7 text-right">Net Due Sales Receive Total  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_receive,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-7 text-right">Net Advance Payment Total  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_booking_amount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-7 text-right">Net Cash Sales  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_cash_sales,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-7 text-right">Net Card Sales  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_card_sales,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 pb-2 h6">
                                <div class="col-7 text-right">Net Mobile Bank Sales  :</div>
                                <div class="col-5 pl-0 text-left"><span>{{ round($net_mobile_sales,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>                        
                    </div>                    
                    <div class="col-md-4 col-12 py-3">
                        <div class="col-12">
                            <div class="row h5">
                                <div class="col-8 text-right">Net Total Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round(($net_ref_discount + $net_points_discount
                                + $net_card_discount + $net_gpstar_discount + $net_fraction_discount) ,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-8 text-right">Net Reference Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round($net_ref_discount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>                    
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-8 text-right">Net Customer Points Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round($net_points_discount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-8 text-right">Net Card Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round($net_card_discount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-8 text-right">Net GP Star Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round($net_gpstar_discount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row pt-1 h6">
                                <div class="col-8 text-right">Net Fraction Discount  :</div>
                                <div class="col-4 pl-0 text-left"><span>{{ round($net_fraction_discount,2) }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>                            
                    </div>                                  
                </div>
                @if($cash_register->count() > 0)
                <div class="row mx-1 border p-3">              
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> Order No </th>
                                <th class="text-center"> Date</th>                                                     
                                <th class="text-center"> Order Total </th>
                                <th class="text-center"> Paid Amount </th> 
                                <th class="text-center"> Due Amount </th>  
                                <th class="text-center"> Discount </th>                              
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($cash_register as $cash)
                            @php $total_discount = $cash->discount + $cash->reward_discount + $cash->card_discount + $cash->gpstar_discount + $cash->fraction_discount;  @endphp
                            <tr>
                                <td class="text-center">{{ $loop->index + 1  }}</td>
                                <td class="text-center">{{ $cash->order_number }}</td>
                                <td class="text-center">{{  date("d-m-Y h:i:s A", strtotime($cash->order_date)) }}</td>
                                <td class="text-center">{{ round($cash->order_total,2) }} {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($cash->receive_total,2) }} {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($cash->due_payable,2) }} {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($total_discount,2) }} {{ config('settings.currency_symbol') }}</td>                                
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