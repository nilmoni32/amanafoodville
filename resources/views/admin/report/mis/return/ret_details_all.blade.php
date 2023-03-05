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
        <a href="{{ route('admin.reports.pdfReturn', [$start_date, $end_date, $report_type, $supplier_id]) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>        
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body px-3">
                <form action="{{ route('admin.reports.supplier.getreturn') }}" method="post"
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
                                            <option value=""></option>
                                            <option value="{{ $supplier_id }}" selected>All</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                             
                @php $grnd_total_qty = 0; $grnd_total_amount = 0.0; $tot_req =0.0; $req_no = 0; @endphp
                @foreach($returns as $return)
                <div class="row">
                    <div class="col-12">
                        <label class="control-label font-weight-bold">Supplier Name:  {{ App\Models\Supplier::find($return->supplier_id)->name }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-12">
                        <label class="control-label font-weight-bold">Return No:  #{{ $return->id }}</label>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="control-label font-weight-bold">Returning Date: {{ explode(' ', $return->chalan_date)[0] }}</label>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="control-label font-weight-bold">Return By: {{ App\Models\Admin::find($return->admin_id)->name  }}</label>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="control-label font-weight-bold">Total Qty: {{ $return->total_quantity }}</label>
                    </div>
                    <div class="col-md-2 col-12">
                        <label class="control-label font-weight-bold">Total Amount: {{ round($return->total_amount,2) }} {{ config('settings.currency_symbol') }}</label>
                    </div>
                </div>              
                
                <table class="table table-hover table-bordered mb-5" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>                            
                            <th class="text-center"> Product Name </th>
                            <th class="text-center"> Product Unit </th>
                            <th class="text-center"> Stock Qty </th>
                            <th class="text-center"> Return Qty </th>
                            <th class="text-center"> Unit Cost </th>                            
                            <th class="text-center"> Total Cost </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\ReturnIngredientList::where('return_to_supplier_id', $return->id)->get() as $return_product)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $return_product->name }}</td>
                            <td class="text-center">{{ $return_product->unit }}</td>
                            <td class="text-center">{{ $return_product->stock }}</td>
                            <td class="text-center">{{ $return_product->quantity }}</td>
                            <td class="text-center">{{ $return_product->unit_cost }}</td>
                            <td class="text-center">{{ round($return_product->total,2) }} {{ config('settings.currency_symbol') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endforeach
                
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