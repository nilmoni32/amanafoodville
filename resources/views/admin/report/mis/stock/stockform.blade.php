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
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-12 pt-3">
                        <form action="{{ route('admin.reports.getstock') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            {{-- <div class="form-group mb-2">
                                <label>
                                    <span class="font-weight-bold pr-1">From Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="start_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label class="font-bold">
                                    <span class="font-weight-bold pr-1">To Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="end_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div> --}}
                            <div class="form-group mx-sm-3 mb-2">
                                <label class="form-check-label font-weight-bold pr-3" style="margin-top:-2px;" for="inlineRadio2">Search Criteria :</label>                               
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="stockOption" id="inlineRadio2" value="product">
                                    <label class="form-check-label font-weight-normal" for="inlineRadio2">Product</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="stockOption" id="inlineRadio1" value="category" required>
                                    <label class="form-check-label font-weight-normal" for="inlineRadio1">Product-category</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <select name='category' style='min-width:180px;' id="category" class="form-control font-weight-normal" disabled required>
                                        @foreach(App\Models\Typeingredient::where('id','!=', 1)->get() as $ingredienttype)
                                        <option value=""></option>
                                        <option value="{{ $ingredienttype->id }}">{{ $ingredienttype->name }}</option>
                                        @endforeach                                     
                                    </select>
                                </div>
                            </div>
                           
                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">
                                Preview</button>
                        </form>
                    </div>

                </div>
                <table class="table table-hover table-bordered mt-5" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Product Name </th>
                            <th class="text-center"> Qty</th>
                            <th class="text-center"> Threshold Qty</th>
                            <th class="text-center"> Unit </th>
                            <th class="text-center"> Amount </th>
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

        $('#category').select2({
            placeholder: "Select a Category", 
        });
        //enable and disable select/dropdown 
        $('input:radio[name="stockOption"]').change(function() {
            if ($(this).val()=='product') {
                $('#category').attr('disabled', true);
            } 
            else if ($(this).val()=='category') {
                $('#category').attr('disabled', false);                
            }
        });

        $('.datetimepicker').datetimepicker({
            timepicker:false,
            datepicker:true,        
            format: 'd-m-Y',              
        });
        $(".datetimepicker").attr("autocomplete", "off");
        //setting dynamically ingredient type value for product category.
        $('#category').on('change', function() {
           // selecting the category radio button and change its value.
            $('input:radio[name="stockOption"][value="category"]').val(this.value);
        });
    });
</script>

@endpush