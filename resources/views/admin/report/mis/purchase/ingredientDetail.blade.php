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
        <a href="{{ route('admin.reports.pdfgetingredient', [$start_date, $end_date]) }}" class="btn btn-sm btn-dark"
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
                        <form action="{{ route('admin.reports.getingredientPurchase') }}" method="post"
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
                    <div class="offset-md-3"></div>                    
                    <div class="col-md-6 col-12 py-3">
                        <div class="col-12">
                            <div class="row h5">
                                <div class="col-7 text-right">Total Purchase Amount :</div>
                                @php 
                                $purchase_total = 0.0;
                                foreach($ingredient_purchase as $ingredient){
                                    $purchase_total += $ingredient->price;
                                }
                                @endphp
                                <div class="col-5 pl-0 text-left"><span>{{ round($purchase_total,2)  }} {{ config('settings.currency_symbol') }}</span></div>                        
                            </div>
                        </div>                        
                    </div>                     
                     
                    <div class="offset-md-3"></div>                   
                </div>
                @if($ingredient_purchase->count() > 0)
                <div class="row mx-1 border p-3">              
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th class="text-center"> # </th>
                                <th class="text-center"> Ingredient Name </th>                                                                                
                                <th class="text-center"> Ingredient Category </th>
                                <th class="text-center"> Purchase Qty </th> 
                                <th class="text-center"> Total Cost </th>                            
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach($ingredient_purchase as $ingredient)                           
                            <tr>
                                <td class="text-center">{{ $loop->index + 1  }}</td>
                                <td class="text-center">{{ App\Models\Ingredient::find($ingredient->ingredient_id)->name}}</td>
                                <td class="text-center">
                                   {{ App\Models\Typeingredient::find(App\Models\Ingredient::find($ingredient->ingredient_id)->typeingredient_id)->name}}
                                </td>
                                <td class="text-center">{{ $ingredient->unit == 'kg' || $ingredient->unit == 'Kg' || $ingredient->unit == 'liter' ? round($ingredient->qty/1000, 2) : round($ingredient->qty,2)  }}   
                                   {{ App\Models\Ingredient::find($ingredient->ingredient_id)->measurement_unit }}</td>
                                <td class="text-center">{{ round($ingredient->price,2) }} {{ config('settings.currency_symbol') }}</td>                                                            
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