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
        <a href="{{ route('admin.reports.pdfgetCustomerSales', [$start_date, $end_date, $client->id]) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.getCustomerSales') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            <div class="form-group mb-2 mx-sm-3">
                                <label for="customer">
                                    <span class="font-weight-bold pr-1">Customer :</span>
                                    <select name='customer' style='width: 200px;' id="customer" class="form-control font-weight-normal" required>
                                        <option value="{{$client->id}}">{{ $client->name}}</option>                                       
                                    </select>
                                </label>
                            </div>
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
                    <div class="col-12">
                        <div class="row pt-3 h6">
                            <div class="col-6 text-right">Customer Name :</div>
                            <div class="col-6 pl-0 text-left"><span>{{ $client->name }}</span></div>                        
                        </div>
                    </div>                    
                    <div class="col-12">
                        <div class="row pt-1 h6">
                            <div class="col-6 text-right">Customer Mobile :</div>
                            <div class="col-6 pl-0 text-left"><span>{{ $client->mobile }}</span></div>                        
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row pt-1 h6">
                            <div class="col-6 text-right">Customer Reward Points :</div>
                            <div class="col-6 pl-0 text-left"><span>{{ $client->total_points }}</span></div>                        
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row pt-1 pb-2 h6">
                            <div class="col-6 text-right">Customer Address :</div>
                            <div class="col-6 pl-0 text-left"><span>{{ $client->address }}</span></div>                        
                        </div>
                    </div>
                </div>       
                <div class="row mx-1 border p-3"> 
                    @if($customer_receipt->count() > 0)
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> ReceiptNo </th>
                                <th class="text-center"> PaidAmount </th>
                                <th class="text-center"> Reference Discount</th>
                                <th class="text-center"> Earned Points Discount</th> 
                                <th class="text-center"> Card Discount</th> 
                                <th class="text-center"> GP Star Discount</th> 
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($customer_receipt as $receipt)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1  }}</td>
                                <td class="text-center">{{ $receipt->order_number  }}</td>
                                <td class="text-center">{{ round($receipt->grand_total,2)  }}
                                    {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($receipt->discount,2)  }}
                                    {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($receipt->reward_discount,2)  }}
                                    {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($receipt->card_discount,2)  }}
                                    {{ config('settings.currency_symbol') }}</td>
                                <td class="text-center">{{ round($receipt->gpstar_discount,2)  }}
                                    {{ config('settings.currency_symbol') }}</td>
                            </tr>
                            @endforeach                         
                        </tbody>
                    </table>
                    @else
                    <div class="col-12">
                    <p class="text-center p-5 h5">{{ __('No sales record by the specified date for the customer.')}}                    
                    </p>
                    </div>
                    @endif
                </div>
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
   // CSRF Token
   var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
        $('#customer').select2({
            //placeholder: "Select Customer",
            //select2-ajax
            ajax: { 
            url: "{{route('admin.reports.getClients')}}",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                _token: CSRF_TOKEN,
                search: params.term // search term
                };
            },
            processResults: function (response) {
                return {
                results: response
                };
            },
            cache: true
            }       
        });

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