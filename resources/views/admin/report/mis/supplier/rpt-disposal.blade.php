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
    <div class="col-10 mx-auto">
        <div class="tile">
            <div class="tile-body px-3">
                <form action="{{ route('admin.reports.product.getdisposal') }}" method="post"
                            class="justify-content-center">
                    @csrf                    
                    <div class="row mb-5 mt-3">
                        <div class="col-md-10 col-12 mx-auto">
                            <div class="row">
                                <div class="offset-md-2"></div>
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
                                <div class="col-md-2 col-12 mt-4 pt-1">
                                    <button type="submit" class="btn btn-primary" name="btnProfitLoss">Preview</button>
                                </div>
                                <div class="offset-md-2"></div> 
                            </div>
                        </div>                                          
                    </div>                   
                </form>
                
                <table class="table table-hover table-bordered mb-5" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Disposal ID </th>
                            <th class="text-center"> Category </th>
                            <th class="text-center"> Product </th>                            
                            <th class="text-center"> Quantity</th>
                            <th class="text-center"> Unit Cost </th>
                            <th class="text-center"> Total Cost Amount </th>
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
        $('.datetimepicker').datetimepicker({
            timepicker:false,
            datepicker:true,        
            format: 'd-m-Y',              
        });
        $(".datetimepicker").attr("autocomplete", "off");
    });
</script>

@endpush