@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-10 mx-auto">
        <div class="tile px-5">
            <form action="{{ route('admin.supplier.stock.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <h3 class="tile-title">Add Supplier Product Details</h3>
                <hr>
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="typeingredient_id">Recipe ingredient types</label>
                                <select name="typeingredient_id" id="typeingredient_id" class="form-control  @error('typeingredient_id') is-invalid @enderror">
                                    @foreach($typeingredients as $typeingredient)
                                    <option value=""></option>
                                    <option value="{{ $typeingredient->id }}">{{ $typeingredient->name }}</option>
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
                                    <option value=""></option>                                   
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
                                {{-- <input class="form-control @error('measurement_unit') is-invalid @enderror" type="text"
                                    placeholder="Enter Measurement Unit" id="measurement_unit" name="measurement_unit" value="{{ old('measurement_unit') }}" readonly/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit')
                                    <span>{{ $message }}</span> @enderror
                                </div> --}}
                                <select name="measurement_unit" id="measurement_unit" class="form-control" @error('measurement_unit') is-invalid @enderror">
                                    <option></option>                                    
                                    @foreach(App\Models\Unit::all() as $unit)
                                    <option></option>
                                    <option value="{{ $unit->measurement_unit }}">{{ $unit->measurement_unit }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('measurement_unit')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="supplier_id">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                                    @foreach($suppliers as $supplier)
                                    <option value=""></option>
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                                <label class="control-label" for="supplier_product_name">Product Name</label>
                                <input class="form-control @error('supplier_product_name') is-invalid @enderror" type="text"
                                    placeholder="Enter name" id="supplier_product_name" name="supplier_product_name" value="{{ old('supplier_product_name') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('supplier_product_name')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="unit_cost">Cost Price</label>
                                <input class="form-control @error('unit_cost') is-invalid @enderror" type="text"
                                    placeholder="Cost Price" id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit_cost')
                                    <span>{{ $message }}</span> @enderror
                                </div>
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
                                        <input class="form-check-input" type="radio" name="has_differ_product_unit" id="differ_product_unit_yes" value="yes">
                                        <label class="form-check-label" for="differ_product_unit_yes">Yes</label>
                                    </div> 
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="has_differ_product_unit" id="differ_product_unit_no" value="no" checked>
                                        <label class="form-check-label" for="differ_product_unit_no">No</label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-4">
                        <div class="form-group stock-product d-none">
                            <label class="control-label" for="product_unit">Product Unit</label>
                            {{-- <input class="form-control @error('product_unit') is-invalid @enderror" type="text"
                                placeholder="Product Unit" id="product_unit" name="product_unit" value="{{ old('product_unit') }}" /> --}}
                            <select name="product_unit" id="product_unit" class="form-control" @error('product_unit') is-invalid @enderror">
                                <option></option>                                    
                                @foreach(App\Models\Unit::all() as $unit)
                                <option></option>
                                <option value="{{ $unit->measurement_unit }}">{{ $unit->measurement_unit }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('product_unit')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group stock-product d-none">
                            <label class="control-label" for="product_qty">Product Quantity</label>
                            <input class="form-control @error('product_qty') is-invalid @enderror" type="text"
                                placeholder="Product Quantity" id="product_qty" name="product_qty" value="{{ old('product_qty') }}" />
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('product_qty')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Product</button>
                            <a class="btn btn-danger" href="{{ route('admin.supplier.stock.index') }}"><i
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
<script>
    $(document).ready(function() {
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
                //allowClear: true,
                multiple: false,  
                width: '100%',                        
             });
             $('#ingredient_id').select2({
                placeholder: "Select a Recipe Ingredient",              
                multiple: false, 
                width: '100%',
               // minimumResultsForSearch: -1,                        
             });

            // $("#ingredient_id").change(function(){
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
               // $('#ingredient_id')[0].options.length = 0; 
               $("#ingredient_id").empty().trigger('change');
                $.ajax({
                    url: '/admin/supplier/stock/types/ingredient/' + typeIngredientId,
                    type: 'get',
                    dataType: 'JSON',
                    data: {},
                    success: function(data) {                      
                        //console.log(data.unit);                        
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