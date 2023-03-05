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
        <a href="{{ route('admin.reports.pdfstock', [$cat_id]) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.getstock') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            {{-- <div class="form-group mb-2">
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
                            </div> --}}
                            <div class="form-group mx-sm-3 mb-2">  
                                <label class="form-check-label font-weight-bold pr-3" style="margin-top:-2px;" for="inlineRadio2">Search Criteria :</label>                             
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="stockOption" id="inlineRadio2" value="product">
                                    <label class="form-check-label font-weight-normal" for="inlineRadio2">Product</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="stockOption" id="inlineRadio1" value="category" checked required>
                                    <label class="form-check-label font-weight-normal" for="inlineRadio1">Product-category</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <select name='category' style='min-width:180px;' id="category" class="form-control font-weight-normal" required>
                                        @foreach(App\Models\Typeingredient::where('id','!=', 1)->get() as $ingredienttype)
                                        <option value=""></option>
                                        @php $check = $cat_id == $ingredienttype->id ? 'selected' : ''; @endphp
                                        <option value="{{ $ingredienttype->id }}" {{ $check }}>{{ $ingredienttype->name }}</option>
                                        @endforeach                                     
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">
                                Preview</button>
                        </form>
                    </div>
                </div>
                <div class="row mx-1 border p-3">              
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> Product Name </th>                                
                                <th class="text-center"> Qty</th>
                                <th class="text-center"> Threshold Qty</th>
                                <th class="text-center"> Unit </th>                                
                                <th class="text-center"> Total Cost </th>
                            </tr>
                        </thead>                        
                        <tbody>                           
                            @foreach($ingredients as $ingredient)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1  }}</td>
                                <td class="text-center">{{ $ingredient->name }}</td>                                
                                <td class="text-center">{{ round($ingredient->total_quantity,2) }} {{ $ingredient->measurement_unit }}</td>
                                <td class="text-center">{{ round($ingredient->alert_quantity,2) }} {{ $ingredient->measurement_unit }}</td>  
                                <td class="text-center">{{ $ingredient->measurement_unit }}</td>                              
                                <td class="text-center">{{ round($ingredient->total_price, 2)}} {{ config('settings.currency_symbol') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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

        $('#sampleTable').DataTable();
    });
</script>

@endpush