@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ingredient.index') }}">{{ __('Ingredient List') }}</a></li>
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
            <div>
                <h3>Edit the ingredient details</h3>
            </div>
            <hr>
            <div class="tile-body mt-5">
                <form action="{{ route('admin.ingredient.update') }}" method="POST" role="form"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- <h3 class="tile-title"></h3> --}}

                    <div class="tile-body">
                        <input type="hidden" name="ingredient_id" value="{{ $ingredient->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="name">Ingredient Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        placeholder="Edit Name" id="name" name="name"
                                        value="{{ old('name', $ingredient->name) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="ingredienttypes">Ingredient Types</label>
                                    <select name="typeingredient_id" id="ingredienttypes" class="form-control">
                                        @foreach($ingredienttypes as $ingredienttype)
                                        @php $check = $ingredient->typeingredient_id == $ingredienttype->id ?
                                        'selected' : '';
                                        @endphp
                                        <option value="{{ $ingredienttype->id }}" {{ $check }}>
                                            {{ $ingredienttype->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                @if($ingredient->pic)
                                <img src="{{ asset('/storage/images/'. $ingredient->pic)}}" width="80"
                                    id="beforeUpload">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="control-label" for="name">Ingredient Image</label>
                                    <input type="file" name="pic"
                                        class="form-control @error('pic') is-invalid @enderror" id="pic">
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('pic')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="measurement_unit">Stock Unit</label>
                                    @php
                                    $val_kg = $ingredient->measurement_unit == 'Kg' ? 'selected' : '';
                                    $val_gm = $ingredient->measurement_unit == 'gm' ? 'selected' : '';
                                    $val_L = $ingredient->measurement_unit == 'liter' ? 'selected' : '';
                                    $val_ml = $ingredient->measurement_unit == 'ml' ? 'selected' : '';
                                    $val_pcs = $ingredient->measurement_unit == 'pcs' ? 'selected' : '';
                                    $val_bundle = $ingredient->measurement_unit == 'bundle' ? 'selected' : '';
                                    @endphp
                                    <select name="measurement_unit" id="measurement_unit" class="form-control" {{
                                        App\Models\IngredientPurchase::where('ingredient_id', $ingredient->id)->count()
                                        ? 'disabled' :'' }}>
                                        <option value="Kg" {{ $val_kg }}>Kg</option>
                                        <option value="gm" {{ $val_gm }}>gm</option>
                                        <option value="liter" {{ $val_L }}>liter</option>
                                        <option value="ml" {{ $val_ml }}>ml</option>
                                        <option value="pcs" {{ $val_pcs }}>pcs</option>
                                        <option value="bundle" {{ $val_bundle }}>bundle</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="name">Alert Quantity</label>
                                    <input class="form-control @error('alert_quantity') is-invalid @enderror"
                                        type="text" placeholder="Enter Quantity threshold value" id="alert_quantity"
                                        name="alert_quantity"
                                        value="{{ old('alert_quantity', $ingredient->alert_quantity) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('alert_quantity')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="smallest_unit">Cook Measurement
                                        Unit</label>
                                    @php
                                    $chk_gm = $ingredient->smallest_unit == 'gm' ? 'selected' : '';
                                    $chk_ml = $ingredient->smallest_unit == 'ml' ? 'selected' : '';
                                    $chk_pcs = $ingredient->smallest_unit == 'pcs' ? 'selected' : '';
                                    $chk_bundle = $ingredient->smallest_unit == 'bundle' ? 'selected' : '';
                                    @endphp
                                    <select name="smallest_unit" id="smallest_unit" class="form-control" {{
                                        App\Models\IngredientPurchase::where('ingredient_id', $ingredient->id)->count()
                                        ? 'disabled' :'' }}>
                            <option value="gm" {{ $chk_gm }}>gm</option>
                            <option value="ml" {{ $chk_ml }}>ml</option>
                            <option value="pcs" {{ $chk_pcs }}>pcs</option>
                            <option value="bundle" {{ $chk_bundle }}>bundle</option>
                            </select>
                        </div>
                    </div> --}}
            </div>
            <div class="form-group">
                <label class="control-label" for="description">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $ingredient->description) }}
                                </textarea>
            </div>
        </div>

        <div class="tile-footer">
            <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update
                        Ingredient</button>
                    <a class="btn btn-danger" href="{{ route('admin.ingredient.index') }}"><i
                            class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                </div>
            </div>
        </div>
        </form>

    </div>
</div>
{{-- <div class="tab-content">
            <div class="tab-pane active" id="general">
                <!-- Edit general ingredient -->
                @include('admin.ingredients.includes.general')
            </div>
            <div class="tab-pane" id="purchase">
                <!-- Edit general ingredient -->
                @include('admin.ingredients.includes.purchase')
            </div>
        </div> --}}

</div>
</div>
@endsection
@push('scripts')
<script>
    $( document ).ready(function() {          
        $('#ingredienttypes').select2({
                placeholder: "Select Ingredient types",
               // allowClear: true,
                multiple: false,  
                width: '100%',                        
             });

        $('#measurement_unit').select2({                 
            multiple: false, 
            minimumResultsForSearch: -1,
            width: '100%',                        
        });
        $('#smallest_unit').select2({           
            multiple: false, 
            minimumResultsForSearch: -1,                   
        });  


        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
            $('#beforeUpload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
        }

        $("#pic").change(function() {
            readURL(this);
        });

    });

</script>
@endpush
