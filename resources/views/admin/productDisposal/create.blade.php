@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div class="col-md-7 col-12">
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>

@include('admin.partials.flash')

<div class="row user">
    <div class="col-md-11 mx-auto">
        <div class="tile px-5">                      
                <div class="row mb-0 mt-3">
                    <div class="col-md-7 col-12">
                        <h4 class="tile-title mt-2">Product Disposal</h4>
                    </div>
                    <div class="col-md-5 col-12">
                        <a href="{{ route('admin.product.disposal.index') }}" class="btn btn-primary pull-right mx-2">Disposal Records</a>
                        <a href="#" class="btn btn-primary pull-right" data-toggle="modal"  data-target="#loadDisposal">Disposal Collection Box (<span id="disposal-collection">0</span>) </a>
                        <!-- Load Disposal Modal -->
                        @include('admin.productDisposal.includes.loadDisposal')
                    </div>                    
                </div>   
                <hr class="pt-0 mt-0">             
                <div class="tile-body">                   
                    <div class="row pb-3">
                        <div class="offset-md-1"></div>                      
                        <div class="col-md-4 col-12">
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
                        <div class="col-md-4 col-12">
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
                        <div class="col-md-2 col-12 py-2">   
                            <button type="button" class="btn btn-primary w-100 mt-4" id="product-search">Search</button>
                        </div> 
                        <div class="offset-md-1"></div>                       
                    </div> 
                    <div class="row">
                        <div class="col-12">
                            <p>Total number of supplier poroducts is <span id="search-records">0</span></p>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tile">
                                <div class="tile-body">
                                    <table class="table table-hover table-bordered" id="productTable">
                                        <thead>
                                            <tr>                                                
                                                <th class="text-center"> # </th>                                                
                                                <th class="text-center"> Product Name </th>                                                
                                                <th class="text-center"> Stk Unit </th>                                                
                                                <th class="text-center"> Stk Qty </th>                                               
                                                <th class="text-center"> Disposal Qty </th>   
                                                <th class="text-center"> Recipe Stk Unit </th>
                                                <th class="text-center"> Recipe Stk Qty </th>
                                                <th class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>            
                </div>                
            {{-- </form> --}}
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        var disposalProducts=[], addBtnId = 0;
            $('#typeingredient_id').select2({
                placeholder: "Select Recipe Ingredient Types",
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

             $('#product-search').on("click", function() {
                 let ingredientId = $("#ingredient_id").val();              
                 if(ingredientId != null){
                    $.ajax({
                        url: '/admin/product/disposal/recipe/ingredients/' + ingredientId,
                        type: 'get',
                        dataType: 'JSON',
                        data: {},
                        success: function(data) {                            
                            // populating search products to productTable
                            // resetting the productTable before populate with data.
                            $("#productTable tbody").empty();  
                            let tableBody = $("#productTable tbody"); 
                            let markup = [];
                            let productCount = 0;                            
                            data.products.forEach((product, index) => {
                                let readOnly = product.recipe_stock_qty ? 'readonly' : '';
                                markup.push("<tr id='row"+ (index +1)+"'><td class='text-center index'><span>"  + (index +1) + "</span></td>" + 
                                            "<td class='text-center d-none' id='ingredient_id"+(index +1)+"'>"  + product.ingredient_id + "</td>" + 
                                            "<td class='text-center d-none' id='product_id"+(index +1)+"'>"  + product.id + "</td>" +  
                                            "<td class='text-center' id='supplier_product_name"+(index +1)+"'>"  + product.supplier_product_name + "</td>" +
                                            "<td class='text-center' id='measurement_unit"+(index +1)+"'>"  + product.measurement_unit + "</td>" +  
                                            "<td class='text-center d-none' id='unit_cost"+(index +1)+"'>"  + product.unit_cost + "</td>" +  
                                            "<td class='text-center' id='product_qty"+(index +1)+"'>"  + product.total_qty + "</td>" +
                                            "<td class='text-center tdQty'>"  + 
                                                "<input type='text' id='disposal-qty"+(index +1)+"' value='' size= '1' class='form-control qty' style='line-height: 10px;' />" +                                           
                                            "</td>" +
                                            "<td class='text-center' id='ingredient_unit"+(index +1)+"'>" + product.ingredient_unit + "</td>" + 
                                            "<td class='text-center ingredientQty'>"  + 
                                                "<input type='text' id='ingredient_qty"+(index +1)+"' size= '1' value= '"+ product.recipe_stock_qty +"' class='form-control inQty' style='line-height: 10px;'" + readOnly +" />"                                            
                                            +"</td>" +
                                            "<td class='text-center tdClsBtn' style='width:100px;'>"+ 
                                                "<button class='btn btn-sm btn-primary addBtn' id='add" +(index +1)+ "'>" +
                                            "<i class='fa fa-plus-circle' aria-hidden='true'></i>&nbsp;Add</button></td>"+ 
                                    "</tr>");                                   

                                   productCount++;
                                });
                            tableBody.append(markup);
                            if(productCount){
                                $('#search-records').text(productCount);
                            }
                            
                            
                        }
                    });                    
                }     
             });

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

            $(document).on('change', '.qty',function(){
                let disposalQtyId = $(this).attr('id');
                //get the row no from the id              
                let rowId = parseInt(disposalQtyId.match(/(\d+)/)[0]);
                //supplier product id
                let productId = $('#product_id'+rowId).text();
                let disposalQty =  $(this).val();

                $.ajax({
                    url: '/admin/product/disposal/recipe-quantity/' + productId + '/' + disposalQty, 
                    type: 'get',
                    dataType: 'JSON',
                    data: {},
                    success: function(data) {                      
                        if(data.recipe_stock_qty){
                            $('#ingredient_qty'+rowId).val(data.recipe_stock_qty);
                            $('#ingredient_qty'+rowId).attr('readonly', true);
                        }
                    }
                });
                
               
            });

            $(document).on('click', '.addBtn',function(){ 
                    let addBtnId = $(this).attr('id'); 
                    //get the row no from the id              
                    let rowId = parseInt(addBtnId.match(/(\d+)/)[0]);
                    //get the disposal quantity
                    let disposalQty = $('#disposal-qty'+rowId).val();
                    let recipeQty = $('#ingredient_qty'+rowId).val();

                    if(parseFloat(disposalQty) > 0){

                        if(parseFloat($('#product_qty'+rowId).text()) > 0 && parseFloat($('#product_qty'+rowId).text()) > parseFloat(disposalQty)){
                            
                            if(disposalQty && recipeQty){                       
                                if(disposalProducts.find(x => x.supplier_stock_id === $('#product_id'+rowId).text())){
                                    $('.app-title').after(  '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                                        '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                                        '<strong>Error! This product is already into disposal list.</strong></div>');
                                }else{                            
                                    let productDetails = {    
                                        'ingredient_id'     : $('#ingredient_id'+rowId).text(),                  
                                        'supplier_stock_id' : $('#product_id'+rowId).text(), 
                                        'name'              : $('#supplier_product_name'+rowId).text(),                          
                                        'unit'              : $('#measurement_unit'+rowId).text(),
                                        'unit_cost'         : $('#unit_cost'+rowId).text(),
                                        'stock'             : $('#product_qty'+rowId).text(),
                                        'quantity'          : parseFloat(disposalQty), //disposal quantity                                                                  
                                        'total'             : parseFloat(disposalQty)* parseFloat($('#unit_cost'+rowId).text()),
                                        'recipe_unit'       : $('#ingredient_unit'+rowId).text(),
                                        'recipe_stk_qty'    : recipeQty,
                                    }; 
                                    
                                    let disposalCount = parseInt($('#disposal-collection').text());
                                    disposalCount += 1;
                                    $('#disposal-collection').text(disposalCount);                                
                                    //adding products info to disposal Table 
                                    let i = document.getElementById("disposalTable").rows.length;                        
                                    tableBody = $("#disposalTable tbody");
                                    markup = "<tr id='row"+i+"'><td class='text-center index'><span>"  + i + "</span></td>" + 
                                                    "<td class='text-center'>"  + productDetails.name + "</td>" +                   
                                                    "<td class='text-center'>"  + productDetails.unit + "</td>" +
                                                    "<td class='text-center'>"  + productDetails.unit_cost + "</td>" +
                                                    "<td class='text-center'>"  + productDetails.stock + "</td>" +                                             
                                                    "<td class='text-center'>"  + productDetails.quantity + "</td>" +
                                                    "<td class='text-center'>"  + (productDetails.total).toFixed(2) + "</td>" +                                                                
                                                    "<td class='text-center tdClsBtn' style='width:100px;'>"+ 
                                                        "<button class='btn btn-sm btn-danger clsBtn' id='close" +i+"'><i class='fa fa-trash'></i></button></td>"+ 
                                                "</tr>";
                                            
                                    tableBody.append(markup);

                                    // array of objects holds all products details                
                                    disposalProducts.push(productDetails);
                                    //converting array of objects to json string to pass data to controller.
                                    let jsonStringDisposalProducts = JSON.stringify(disposalProducts); 
                                    //setting product lists to form hidden input field           
                                    $('#product_lists').val(jsonStringDisposalProducts);

                                }                        
                                                
                            }else{            
                                $('.app-title').after(  '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                                                '<strong>Error! Disposal Quantity and Recipe Stock Quantity can\'t be empty.</strong></div>');
                            
                            } 
                        }else{

                            $('.app-title').after(  '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                                                '<strong>Error! Supplier Product Stock can\'t be less or empty.</strong></div>');

                        }
                    }else{

                        $('.app-title').after(  '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                                                '<strong>Error! Disposal Quantity can\'t be negative in quantity.</strong></div>');
                        $('#ingredient_qty'+rowId).val('');
                        $('#disposal-qty'+rowId).val('');
                    }
                           
                               
               
            });


            //deleting the item from the table.
            // As table row is dynamically added to the table, need to use document.on() to capture the click events.            
            $(document).on('click','.clsBtn',function(e){
                e.preventDefault();   
                //get the row no from the id
                let clsBtnId = $(this).attr('id');
                let rowId = parseInt(clsBtnId.match(/(\d+)/)[0]);
                // Getting all the rows next to the row containing the clicked close button
                var child = $(this).closest('tr').nextAll();
                // Iterating across all the rows obtained to change the index
                child.each(function () {
                    // Getting <tr> id.
                    var delBtnId = $(this).attr('id');
                    // Getting the <td> with .index class
                    var idx = $(this).children('.index').children('span');                  
                    // Gets the row number from <tr> id.
                    var dig = parseInt(delBtnId.match(/(\d+)/)[0]);               
                    // Modifying row-index.
                    idx.html(`${dig - 1}`);
                    // Modifying row id.
                    $(this).attr('id', `row${dig - 1}`);                    
                    // Modifying row id of the close btn with .clsBtn class
                    $(this).children('.tdClsBtn').children('.clsBtn').attr('id', `close${dig - 1}`);
                    
                });
              
                // deleting the record from the array.
                disposalProducts.splice(rowId-1,1);   
                //converting array of objects to json string to pass data to controller.
                let jsonStringDisposalProducts = JSON.stringify(disposalProducts); 
                  //setting product lists to form hidden input field           
                $('#product_lists').val(jsonStringDisposalProducts);             
                // Removing the current row.
                $(this).parent().parent('tr').remove();  
                
                let disposalCount = parseInt($('#disposal-collection').text());
                disposalCount -= 1;
                $('#disposal-collection').text(disposalCount);  
            
            });

           



        });
      
</script>   
@endpush