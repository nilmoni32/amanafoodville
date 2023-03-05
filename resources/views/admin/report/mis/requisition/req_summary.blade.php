@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-tags"></i>&nbsp;{{ $pageTitle }}</h1>
        <h6 class="m-2 font-italic">{{ $subTitle }}</h6>
    </div>
    <div class="pull-right">
        <a href="{{ route('admin.reports.pdfRequisition', [$start_date, $end_date, $report_type, $supplier_id]) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body px-3">
                <form action="{{ route('admin.reports.supplier.getrequisition') }}" method="post"
                            class="justify-content-center">
                    @csrf                    
                    <div class="row mb-5 mt-3">
                        <div class="col-md-10 col-12 mx-auto">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="start_date">From Date :<span class="text-danger"> *</span></label>                                    
                                        <input type="text" class="form-control datetimepicker" name="start_date"
                                            placeholder="choose date (d-m-Y)" value="{{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}" required>                                
                                    </div> 
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold">To Date :<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control datetimepicker" name="end_date"
                                                placeholder="choose date (d-m-Y)" value="{{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}" required>                            
                                    </div> 
                                </div>                         
                                <div class="col-md-3 col-12">           
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="supplier_id">Supplier: <span class="text-danger"> *</span></label>
                                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                                            @foreach($suppliers as $supplier)
                                            <option value=""></option>
                                            <option value="{{ $supplier->id }}" {{ $supplier->id == $supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-2 col-12">                 
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="report_type">Report Type:<span class="text-danger"> *</span></label><br>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input" type="radio" name="report_type" id="inlineRadio1" value="summary" {{ $report_type == "summary" ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="inlineRadio1">Summary</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input" type="radio" name="report_type" id="inlineRadio2" value="details" {{ $report_type == "details" ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inlineRadio2">Details</label>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="col-md-1 col-12 mt-4">
                                    <button type="submit" class="btn btn-primary" name="btnProfitLoss">Preview</button>
                                </div> 
                            </div>
                        </div>                                          
                    </div>                   
                </form>               
                
                <table class="table table-hover table-bordered mb-5" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>                            
                            <th class="text-center"> Requisition No </th>
                            <th class="text-center"> Requisition Date </th>
                            <th class="text-center"> Exp. Delivery Date </th>
                            <th class="text-center"> Purpose </th>
                            <th class="text-center"> Total Qty </th>
                            <th class="text-center"> Total Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total_qty = 0; $total_amount = 0.0; $counter = 0; @endphp
                        @foreach($requisitions as $requisition)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $requisition->id }}</td>
                            <td class="text-center">
                                {{ explode(' ', $requisition->requisition_date)[0] }}
                            </td>
                            <td class="text-center">
                                {{ explode(' ', $requisition->expected_delivery)[0] }}
                            </td>
                            <td class="text-center">{{ $requisition->purpose }}</td>
                            <td class="text-center">{{ $requisition->total_quantity }}</td>                            
                            <td class="text-center">
                                {{ round($requisition->total_amount,2) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                            @php $total_qty += $requisition->total_quantity;  $total_amount += $requisition->total_amount; $counter++;@endphp
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-center font-weight-bold">Total :</td>
                            <td class="text-center font-weight-bold">{{ $counter }}</td>
                            <td colspan="3"></td>
                            <td  class="text-center font-weight-bold">{{ $total_qty }}</td>
                            <td class="text-center font-weight-bold">{{ round($total_amount,2) }} {{ config('settings.currency_symbol') }}</td>                            
                        </tr>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    $(document).ready(function () {
        $('#supplier_id').select2({
            placeholder: "Select a Supplier",
            //allowClear: true,
            multiple: false,  
            width: '100%',    
            // minimumResultsForSearch: -1,                    
            });

            $('#supplier_id').prepend($('<option>', {
                value: 'all',
                text: 'All'
            }));
        $('.datetimepicker').datetimepicker({
            timepicker:false,
            datepicker:true,        
            format: 'd-m-Y',              
        });
        $(".datetimepicker").attr("autocomplete", "off");
    });
</script>

@endpush