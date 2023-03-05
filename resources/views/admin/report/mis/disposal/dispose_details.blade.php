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
        <a href="{{ route('admin.reports.pdfDispose', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
            target="_blank"><i class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>        
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
                                <div class="col-md-2 col-12 mt-4">
                                    <button type="submit" class="btn btn-primary" name="btnProfitLoss">Preview</button>
                                </div> 
                            </div>
                        </div>                                          
                    </div>                   
                </form> 
                             
                @php $grnd_total_qty = 0; $grnd_total_amount = 0.0; $tot_req =0.0; $req_no = 0; @endphp
                @foreach($disposals as $disposal)
                <div class="row">
                    <div class="col-md-2 col-12">
                        <label class="control-label font-weight-bold">Disposal Id:  #{{ $disposal->id }}</label>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="control-label font-weight-bold">Disposal Date: {{ explode(' ', $disposal->created_at)[0] }}</label>
                    </div>
                    <div class="col-md-3 col-12">
                        <label class="control-label font-weight-bold">Created By: {{ App\Models\Admin::find($disposal->admin_id)->name  }}</label>
                    </div>                    
                </div>
                <table class="table table-hover table-bordered mb-5" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Recipe Ingredient </th>                            
                            <th class="text-center"> Product Name </th>
                            <th class="text-center"> Product Unit </th>                            
                            <th class="text-center"> Disposal Qty </th>
                            <th class="text-center"> Unit Cost </th>                            
                            <th class="text-center"> Total Cost Amount </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach(App\Models\DisposalIngredientList::where('ingredient_disposal_id', $disposal->id)->get() as $disposal_product)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="text-center">{{ App\Models\Ingredient::find($disposal_product->ingredient_id)->name }}</td>
                            <td class="text-center">{{ $disposal_product->name }}</td>
                            <td class="text-center">{{ $disposal_product->unit }}</td>                            
                            <td class="text-center">{{ $disposal_product->quantity }}</td>
                            <td class="text-center">{{ $disposal_product->unit_cost }}</td>
                            <td class="text-center">{{ round($disposal_product->total, 2) }} {{ config('settings.currency_symbol') }}</td>
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
       
        $('.datetimepicker').datetimepicker({
            timepicker:false,
            datepicker:true,        
            format: 'd-m-Y',              
        });
        $(".datetimepicker").attr("autocomplete", "off");
    });
</script>

@endpush