@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ingredient.damage.index', $ingredient->id)}}">{{ __('Ingredient Damage List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.ingredients.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <h3 class="tile-title">Add Damage Details for {{ $ingredient->name }}</h3>
            <hr>
            <form action="{{ route('admin.ingredient.damage.store') }}" method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Ingredient Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    placeholder="Enter Ingredient name" id="ingredient_search" name="name" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="quantity">Quantity</label>
                                <input class="form-control @error('quantity') is-invalid @enderror" type="text"
                                    placeholder="Enter Quantity" id="quantity" name="quantity"
                                    value="{{ old('quantity') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('quantity')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="unit">Measurement Unit</label>
                                <select name="unit" id="unit" class="form-control">
                                    <option></option>
                                    @if($ingredient->measurement_unit == $ingredient->smallest_unit)
                                    <option value={{ $ingredient->measurement_unit }}>
                                        {{ $ingredient->measurement_unit }}</option>
                                    @elseif($ingredient->measurement_unit != $ingredient->smallest_unit)
                                    <option value={{ $ingredient->measurement_unit }}>
                                        {{ $ingredient->measurement_unit }}</option>
                                    <option value={{ $ingredient->smallest_unit }}>{{ $ingredient->smallest_unit }}
                                    </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="reported_date">Report Date</label>
                                <input type="text" class="form-control datetimepicker" name="reported_date"
                                    placeholder="choose date (d-m-Y)" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Ingredient</button>
                            <a class="btn btn-danger"
                                href="{{ route('admin.ingredient.damage.index', $ingredient->id) }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
    $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
    });
    $(".datetimepicker").attr("autocomplete", "off");

    $('#unit').select2({
                placeholder: "Select an measurement Unit",              
                multiple: false, 
                minimumResultsForSearch: -1,  
                width: '100%',                       
    });
    
    $("#ingredient_search").autocomplete({
        //Using source option to send AJAX post request to route('ingredient.getingredients') to fetch data
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{ route('admin.ingredient.getingredients') }}",
            type: 'post',
            dataType: "json",
            // passing CSRF_TOKEN along with search value in the data
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            //On successful callback pass response in response() function.
            success: function( data ) {
               response( data );
            }
          });
        },
        // Using select option to display selected option label in the #product_search
        select: function (event, ui) {
           // Set selection           
           $('#ingredient_search').val(ui.item.label); // display the selected text        
           return false;
        }
      });

    });
</script>

@endpush
