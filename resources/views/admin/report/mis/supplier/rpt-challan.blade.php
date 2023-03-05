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
</div>
<div class="row">
    <div class="col-12">
        <div class="tile">
            <div class="tile-body px-3">
                <form action="{{ route('admin.reports.supplier.getchallan') }}" method="post"
                            class="justify-content-center">
                    @csrf                    
                    <div class="row mb-5 mt-3">
                        <div class="col-md-10 col-12 mx-auto">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="start_date">From Date :<span class="text-danger"> *</span></label>                                    
                                        <input type="text" class="form-control datetimepicker" name="start_date"
                                            placeholder="choose date (d-m-Y)" required>                                
                                    </div> 
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold">To Date :<span class="text-danger"> *</span></label>
                                            <input type="text" class="form-control datetimepicker" name="end_date"
                                                placeholder="choose date (d-m-Y)" required>                            
                                    </div> 
                                </div>                         
                                <div class="col-md-3 col-12">           
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="supplier_id">Supplier: <span class="text-danger"> *</span></label>
                                        <select name="supplier_id" id="supplier_id" class="form-control" required>
                                            @foreach($suppliers as $supplier)
                                            <option value=""></option>
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-2 col-12">                 
                                    <div class="form-group mb-2">
                                        <label class="control-label font-weight-bold" for="report_type">Report Type:<span class="text-danger"> *</span></label><br>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input" type="radio" name="report_type" id="inlineRadio1" value="summary" required>
                                            <label class="form-check-label" for="inlineRadio1">Summary</label>
                                        </div>
                                        <div class="form-check form-check-inline mt-1">
                                            <input class="form-check-input" type="radio" name="report_type" id="inlineRadio2" value="details">
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
                            <th class="text-center"> Supplier </th>
                            <th class="text-center"> Receiving No </th>
                            <th class="text-center"> Product Name </th>
                            <th class="text-center"> Stock Qty</th>
                            <th class="text-center"> Receiving Qty </th>                            
                            <th class="text-center"> Unit Cost </th>
                            <th class="text-center"> Total Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                      
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