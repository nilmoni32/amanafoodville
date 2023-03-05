@extends("admin.app")
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.supplier.stock.index') }}">{{ __('List of Stock Products') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            {{-- <h3 class="tile-title text-center">{{$subTitle}}</h3> --}}
            <form action=" {{ route('admin.supplier.stock.update') }} " method="POST" role="form">
                @csrf
                <h3 class="tile-title">Edit Supplier Product Details</h3><hr>
                <input type="hidden" name="id" value="{{ $supplier_stock->id }}">
                <div class="tile-body"> 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="typeingredient_id">Recipe ingredient types</label>
                                <select name="typeingredient_id" id="typeingredient_id" class="form-control @error('typeingredient_id') is-invalid @enderror">
                                    @foreach($typeingredients as $typeingredient)
                                    @php 
                                        $check = $supplier_stock->typeingredient_id == $typeingredient->id ? 'selected' : '';
                                    @endphp
                                    <option value=""></option>
                                    <option value="{{ $typeingredient->id }}" {{ $check }}>{{ $typeingredient->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('typeingredient_id')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="ingredient_id">Recipe ingredient</label>
                                <select name="ingredient_id" id="ingredient_id" class="form-control @error('ingredient_id') is-invalid @enderror">
                                    @foreach($ingredients as $ingredient)
                                    @php 
                                        $check = $supplier_stock->ingredient_id == $ingredient->id ? 'selected' : '';
                                    @endphp
                                    <option></option>
                                    <option value="{{$ingredient->id }}" {{ $check }}>{{ $ingredient->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('ingredient_id')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="measurement_unit">Stock Measurement Unit</label>
                                <select name="measurement_unit" id="measurement_unit" class="form-control" @error('measurement_unit') is-invalid @enderror">                                                                       
                                    @foreach(App\Models\Unit::all() as $unit)
                                    @php 
                                        $check = $supplier_stock->measurement_unit ==  $unit->measurement_unit ? 'selected' : '';
                                    @endphp
                                    <option></option>
                                    <option value="{{ $unit->measurement_unit }}" {{ $check }}>{{ $unit->measurement_unit }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                                {{-- <label class="control-label" for="measurement_unit">Stock Measurement Unit</label>
                                <input class="form-control @error('measurement_unit') is-invalid @enderror" type="text"
                                    placeholder="Enter Measurement Unit" id="measurement_unit" name="measurement_unit" value="{{ old('measurement_unit', $supplier_stock->measurement_unit) }}" readonly/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit')
                                    <span>{{ $message }}</span> @enderror
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="supplier_id">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                                    @foreach($suppliers as $supplier)
                                    @php 
                                        $check = $supplier_stock->supplier_id == $supplier->id ? 'selected' : '';
                                    @endphp
                                    <option value="{{ $supplier->id }}" {{ $check }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('supplier_id')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="supplier_product_name">Product Name <span class="text-danger">*</span></label>
                                <input class="form-control @error('supplier_product_name') is-invalid @enderror" type="text"
                                    placeholder="Enter Product Name" id="supplier_product_name" name="supplier_product_name" 
                                    value="{{ old('supplier_product_name', $supplier_stock->supplier_product_name ) }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i>
                                    @error('supplier_product_name')
                                       <span>{{ $message }}</span> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="unit_cost">Cost Price</label>
                                <input class="form-control @error('unit_cost') is-invalid @enderror" type="text"
                                    placeholder="Cost Price" id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $supplier_stock->unit_cost) }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit_cost')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-5">
                                <label class="control-label" for="has_differ_product_unit">Is <strong>product unit</strong> differ from stock measurement unit?</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_differ_product_unit" id="differ_product_unit_yes" value="yes"
                                            {{ (isset($supplier_stock->has_differ_product_unit) && ($supplier_stock->has_differ_product_unit == 1 ) ? 'checked' : '' )}}>
                                            <label class="form-check-label" for="differ_product_unit_yes">Yes</label>
                                        </div> 
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_differ_product_unit" id="differ_product_unit_no" value="no"
                                            {{ (isset($supplier_stock->has_differ_product_unit) && ($supplier_stock->has_differ_product_unit == 0 ) ? 'checked' : '' )}}>
                                            <label class="form-check-label" for="differ_product_unit_no">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="form-group stock-product {{$supplier_stock->has_differ_product_unit == 1 ? '': 'd-none'}}">
                                <label class="control-label" for="product_unit">Product Unit</label>
                                {{-- <input class="form-control @error('product_unit') is-invalid @enderror" type="text"
                                    placeholder="Product Unit" id="product_unit" name="product_unit" value="{{ old('product_unit', $supplier_stock->product_unit) }}" /> --}}
                                <select name="product_unit" id="product_unit" class="form-control" @error('product_unit') is-invalid @enderror">                                                                       
                                    @foreach(App\Models\Unit::all() as $unit)
                                    @php 
                                        $check = $supplier_stock->product_unit ==  $unit->measurement_unit ? 'selected' : '';
                                    @endphp
                                    <option></option>
                                    <option value="{{ $unit->measurement_unit }}" {{ $check }}>{{ $unit->measurement_unit }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('product_unit')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group stock-product {{$supplier_stock->has_differ_product_unit == 1 ? '': 'd-none'}}">
                                <label class="control-label" for="product_qty">Product Quantity</label>
                                <input class="form-control @error('product_qty') is-invalid @enderror" type="text"
                                    placeholder="Product Quantity" id="product_qty" name="product_qty" value="{{ old('product_qty', $supplier_stock->product_qty) }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('product_qty')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>     
                    </div>
                </div>
                <div class="tile-footer pb-5">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>Update Details</button>
                        &nbsp;<a class="btn btn-danger" href="{{ route('admin.supplier.stock.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
    $( document ).ready(function() {

            $('#typeingredient_id').select2({
                placeholder: "Select Recipe Ingredient Types",
               // allowClear: true,
                multiple: false,  
                width: '100%',                        
             });   
             $('#measurement_unit').select2({
                placeholder: "Select a Measurement Unit",
               // allowClear: true,
                multiple: false,  
                width: '100%',  
             });
             $('#product_unit').select2({
                placeholder: "Select a Measurement Unit",
               // allowClear: true,
                multiple: false,  
                width: '100%',  
             });     
             $('#supplier_id').select2({
                placeholder: "Select a Supplier",
               // allowClear: true,
                multiple: false,  
                width: '100%',                        
             });
             $('#ingredient_id').select2({
                placeholder: "Select a Recipe Ingredient",              
                multiple: false, 
                width: '100%',
               // minimumResultsForSearch: -1,                        
             });
     


            //  $("#ingredient_id").change(function(){
            //     let ingredientId = $(this).val(); 
            //     //console.log(ingredientId);              
                
            //     if(ingredientId != null){
            //         $.ajax({
            //             url: '/admin/supplier/stock/recipe/ingredient/' + ingredientId,
            //             type: 'get',
            //             dataType: 'JSON',
            //             data: {},
            //             success: function(data) {
            //                 //setting measurement unit based based on recipe ingredient unit

            //                 $('#measurement_unit').val(data.unit);
            //                 //console.log(data.unit);
            //             }
            //         });
            //     }
            // });

            $("#typeingredient_id").change(function(){               
                let typeIngredientId = $(this).val();
                //$('#ingredient_id')[0].options.length = 0; 
                $("#ingredient_id").empty().trigger('change');  
               
                $.ajax({
                    url: '/admin/supplier/stock/types/ingredient/' + typeIngredientId,
                    type: 'get',
                    dataType: 'JSON',
                    data: {},
                    success: function(data) {                      
                       // console.log(data.unit);                        
                        if (data.unit.length > 0) {
                            for (var i = 0; i < data.unit.length; i++) {
                                var id = data.unit[i].id;
                                var name = data.unit[i].name;
                                var option = "<option value='"+id+"'>"+name+"</option>";                                
                                $("#ingredient_id").append(option);
                            }
                            $("#ingredient_id").trigger('change');
                        }
                    }
                });
            });

            $('#differ_product_unit_yes').click(function(){                          
                if($(this).attr("value") == 'yes'){
                    $('.stock-product').removeClass('d-none');
                } 
            });

            $('#differ_product_unit_no').click(function(){                          
                if($(this).attr("value") == 'no'){
                    $('.stock-product').addClass('d-none');
                }                 
            });
            
            
        });
      
</script>
@endpush