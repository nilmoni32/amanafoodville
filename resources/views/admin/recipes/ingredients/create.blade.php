@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-modx"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.recipe.ingredient.index', $recipe->id)}}">{{ __('Recipe Ingredient List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.recipes.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <h3 class="tile-title">Add Ingredient for {{ $recipe->product->name }}</h3>
            <hr>
            <form action="{{ route('admin.recipe.ingredient.store') }}" method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="form-group">
                                <label class="control-label" for="ingredient_name">Ingredient Name</label>
                                <select name="ingredient_name" id="ingredient_id"
                                    class="form-control @error('ingredient_name') is-invalid @enderror">
                                    <option></option>
                                    @foreach(App\Models\Ingredient::all() as $ingredient)
                                    <option value={{ $ingredient->id }}>
                                        {{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                @error('ingredient_name') {{ $message }}@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="form-group">
                                <label class="control-label" for="measurement_unit">Measurement Unit</label>
                                <input name="measurement_unit" id="measure_unit"
                                    class="form-control @error('measurement_unit') is-invalid @enderror" value=""
                                    readonly>
                                @error('measurement_unit') {{ $message }}@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7 mx-auto">
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

                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Ingredient</button>
                            <a class="btn btn-danger"
                                href="{{ route('admin.recipe.ingredient.index', $recipe->id) }}"><i
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
            $('#ingredient_id').select2({
                placeholder: "Select an ingredient",              
                multiple: false, 
                //minimumResultsForSearch: -1, 
                width: '100%',                        
             });

            //  $('#measure_unit').select2({
            //     placeholder: "Select an ingredient",              
            //     multiple: false, 
            //     minimumResultsForSearch: -1,                        
            //  });

            $('#ingredient_id').on('change',function(){
                if($(this).val() != "default"){ 
                    //getting the smallest measurement unit of the corresponding ingredient   
                    $.post("{{ route('admin.recipe.ingredient.getunit') }}", {
                        _token: CSRF_TOKEN,
                        ingredient_id: $(this).val(),                        
                    }).done(function(data) {
                        data = JSON.parse(data);
                        if(data.status == "success") { 
                            $('#measure_unit').val(data.small_unit); 
                        }
                    });             
                   
                }else{
                    $('#measure_unit').val('');
                }
            });

    });


</script>

@endpush
