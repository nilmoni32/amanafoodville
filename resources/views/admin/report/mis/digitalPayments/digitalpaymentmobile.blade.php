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
        <a href="{{ route('admin.reports.pdfgetDigitalPayment', [$start_date, $end_date, 'mobile']) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.getdigitalPayments') }}" method="post"
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
                                    <input class="form-check-input" type="radio" name="digitalPayOption" id="inlineRadio1" value="card" required>
                                    <label class="form-check-label font-weight-bold" for="inlineRadio1">Card Payments</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="digitalPayOption" id="inlineRadio2" value="mobile" {{$mobile ? 'checked' : ''}}>
                                    <label class="form-check-label font-weight-bold" for="inlineRadio2">Mobile Payments</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">Preview</button>
                        </form>
                    </div>
                </div>
                @if($mobile_sales->count() > 0)
                @php $total_paid=0.0; $total_discount=0.0; @endphp                                             
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>                            
                            <th class="text-center"> Mobile Bank </th>
                            <th class="text-center"> Mobile Paid Amount</th>
                            <th class="text-center"> Mobile Discount Amount </th>
                        </tr>
                    </thead>
                    <tbody> 
                        @foreach($mobile_sales as $card)
                        @php $total_paid +=$card->mobile_paid; $total_discount +=$card->mobile_discount; @endphp               
                        <tr>
                            <td class="text-center">{{ $loop->index + 1}}</td>
                            <td class="text-center">{{ $card->bank_name }} </td>                        
                            <td class="text-center">{{ round($card->mobile_paid,2) }} {{ config('settings.currency_symbol') }}</td>
                            <td class="text-center">{{ round($card->mobile_discount,2) }} {{ config('settings.currency_symbol') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="6">
                                <div class="row h6">
                                    <div class="col-10 text-right">Total Mobile Paid :</div>
                                    <div class="col-2 pl-0"><span>{{ round($total_paid,2) }}</span><span class="ml-1 mr-2">{{ config('settings.currency_symbol') }}</span></div>                        
                                </div>
                                <div class="row h6">
                                    <div class="col-10 text-right">Total Mobile Discount :</div>
                                    <div class="col-2 pl-0"><span>{{ round($total_discount,2) }}</span><span class="ml-1 mr-2">{{ config('settings.currency_symbol') }}</span></div>                        
                                </div>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-center p-5 h5">{{ __('No card payments has been made by the specified date.')}}                    
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