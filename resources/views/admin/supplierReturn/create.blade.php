@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div class="col-12">
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>   
</div>
<div class="app-title pt-2 pb-2">
     <div class="offset-md-7 col-md-5 col-12">
        <a href="{{ route('admin.supplier.return.index') }}" class="btn btn-primary pull-right mx-2">Supplier Return List</a>        
    </div>
</div>
@include('admin.partials.flash')

<div class="row user">
    <div class="col-md-11 mx-auto">
        <div class="tile px-5">
            <form action="{{ route('admin.supplier.return.store') }}" method="POST" role="form"
                enctype="multipart/form-data" id="requisition-form">
                @csrf
                <h4 class="tile-title">Return Invoice to Supplier</h4>
                <hr>
                <div class="tile-body">
                    <div class="row pb-3">
                        <div class="offset-md-2"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="chalan_date">Challan Date<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control datetimepicker" name="chalan_date"
                                    placeholder="choose date (d-m-Y)" required>
                            </div>
                        </div>                       
                         
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="purpose">Purpose</label>
                                <input class="form-control @error('purpose') is-invalid @enderror" type="text"
                                    placeholder="Purpose of return" id="purpose" name="purpose" value="{{ old('purpose') }}"/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('purpose')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>   
                        <div class="offset-md-2"></div>                  
                    </div>
                    
                    <div class="row pb-3">  
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="supplier_id">Supplier <span class="text-danger"> *</span></label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    @foreach($suppliers as $supplier)
                                    <option value=""></option>
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="control-label" for="supplier_stock_id">Product</label>
                                    <select name="supplier_stock_id" id="supplier_stock_id" class="form-control">                                   
                                        <option value=""></option>                                   
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="unit">Measurement Unit</label>
                            <input class="form-control  @error('unit') is-invalid @enderror" type="text" id="unit" name="unit" value="{{ old('unit') }}" 
                            placeholder="measurement unit" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>                                             
                    </div>
                    <div class="row pb-3">
                        <div class="col-md-4">
                            <label class="control-label" for="unit_cost">Cost Price</label>
                            <input class="form-control @error('unit_cost') is-invalid @enderror" type="text" id="unit_cost" name="unit_cost" 
                            value="{{ old('unit_cost') }}" placeholder="cost price" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit_cost')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="total_qty">Current Stock</label>
                            <input class="form-control @error('total_qty') is-invalid @enderror" type="text" id="total_qty" name="total_qty" 
                            value="{{ old('total_qty') }}" placeholder="current stock" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('total_qty')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="quantity">Quantity</label>
                            <input class="form-control @error('quantity') is-invalid @enderror" type="text" id="quantity" name="quantity" 
                            value="{{ old('quantity') }}" placeholder="0"/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('quantity')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>                    
                    <div class="row pb-3">
                        <div class="col-md-12">
                            <button class="btn mt-3" id="addProduct"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp; Add Products</button>
                        </div>
                    </div>
                    <div class="row pb-3">
                        <div class="col-md-12 mx-auto pt-3">
                            <div class="tile">
                                <h6 class="pb-3">Return Products Details</h6>
                                <input type="hidden" id="product_lists" name="product_lists" value="">
                                <div class="tile-body">
                                    <table class="table table-hover table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center"> # </th>
                                                <th class="text-center"> Name </th>                                                
                                                <th class="text-center"> Stk Unit</th>         
                                                <th class="text-center"> Stk Qty</th>
                                                <th class="text-center" style="min-width:90px;">Req Qty</th> 
                                                <th class="text-center"> Req Price</th> 
                                                <th class="text-center"> Total </th>                                                
                                                <th class="text-center"> Product Wgt </th>                                                
                                                <th class="text-center"> Recipe Stk Unit</th>
                                                <th class="text-center"> Recipe Stk Qty</th>  
                                                <th style="max-width:50px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row mb-4">
                        <div class="offset-md-2"></div>
                        <div class="col-md-4">
                            <label class="control-label" for="total_quantity">Total Req Qty</label>
                            <input class="form-control @error('total_quantity') is-invalid @enderror" type="text" id="total_quantity" name="total_quantity" 
                            value="{{ old('total_quantity') }}" placeholder="0" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('total_quantity')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label" for="total_amount">Total Amount</label>
                            <input class="form-control @error('total_amount') is-invalid @enderror" type="text" id="total_amount" name="total_amount" 
                            value="{{ old('total_amount') }}" placeholder="0.0" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('total_amount')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="offset-md-2"></div>
                    </div>                    
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit" id="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Requisition</button>
                            <a class="btn btn-danger" href="{{ route('admin.supplier.return.index') }}"><i
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
            var productId, productMeasurementUnit, unitCost, productName, productStock, hasDifferProductUnit, productUnit, productQty, smallMeasurementUnit, conversionUnit;
            var requisitionProducts=[];
            $('.datetimepicker').datetimepicker({
            timepicker:false,
            datepicker:true,        
            format: 'd-m-Y',              
            });
            $(".datetimepicker").attr("autocomplete", "off");
                
             $('#supplier_id').select2({
                placeholder: "Select a Supplier",
                //allowClear: true,
                multiple: false,  
                width: '100%',    
                // minimumResultsForSearch: -1,                    
             });

             $('#supplier_stock_id').select2({
                placeholder: "Choose a Product",
                //allowClear: true,
                multiple: false,  
                width: '100%',                 
                // minimumResultsForSearch: -1,                    
             });
           
            $("#supplier_id").change(function(){
                let supplierId = $(this).val(); 
                $("#supplier_stock_id").empty().trigger('change');          
                
                if(supplierId != null){
                    $.ajax({
                        url: '/admin/supplier/return/allproducts/' + supplierId,
                        type: 'get',
                        dataType: 'JSON',
                        data: {},
                        success: function(data) {
                            console.log(data.items);                                                       
                            data.items.forEach((product, index) => {
                                    var id =  product.id;
                                    var name = product.supplier_product_name;
                                    var option = "<option value='"+id+"'>"+name+"</option>";                                
                                    $("#supplier_stock_id").append(option);
                                });
                                $("#supplier_stock_id").trigger('change');

                        }
                    });
                }
            });

            $("#supplier_stock_id").change(function(){
                productId = $(this).val(); 

                if(productId != null){
                    $.ajax({
                        url: '/admin/supplier/challan/get/product-details/' + productId,
                        type: 'get',
                        dataType: 'JSON',
                        data: {},
                        success: function(data) {
                            //console.log(data);
                            $('#unit').val(data.item.measurement_unit);
                            $('#total_qty').val(data.item.total_qty);
                            $('#unit_cost').val(data.item.unit_cost);
                            //note:  supplier stock product purchase unit can be differ with product actual unit such as 1 packet termeric powder purchase unit pcs 
                            productMeasurementUnit = data.item.measurement_unit; // supplier stock product purchase unit such as pcs, kg, gm
                            unitCost = data.item.unit_cost;
                            productName = data.item.supplier_product_name;
                            productStock = data.item.total_qty;  
                            hasDifferProductUnit = data.item.has_differ_product_unit;
                            productUnit = data.item.has_differ_product_unit ? data.item.product_unit : data.item.measurement_unit; // productUnit: supplier stock product actual unit such as gm
                            productQty = data.item.has_differ_product_unit ? data.item.product_qty : '1';  // productQty: supplier stock product actual qty   such as 150gm  
                            smallMeasurementUnit = data.small_measurement_unit; // product smallest measurement unit
                            conversionUnit = data.conversion_unit;              
                        }
                    });
                }
            });

            $("#addProduct").click(function(e){
                e.preventDefault();             
                try{
                    if($.trim($('#quantity').val()) != ""){                        
                        //getting the recipe ingredient stock measurement unit
                        if(productId != null){

                            //preventing the same product to be added
                            if(requisitionProducts.length > 0) {
                                requisitionProducts.forEach(product => {
                                    if(product.supplier_stock_id == productId){                                
                                        throw "duplicateEntry";
                                    }
                                }); 
                            }

                            //checking return qty is less than supplier current stock
                            if(parseFloat($.trim($('#quantity').val())) > productStock){
                                throw "lessStock";
                            }

                            $.ajax({
                                url: '/admin/supplier/challan/get/ingredient_units/' + productId,
                                type: 'get',
                                dataType: 'JSON',
                                data: {},
                                success: function(data) { 
                                    if(data){
                                        //calculating recipeStockQty : 
                                        const recipeStockUnit = (data.recipeIngredientUnit).toLowerCase();
                                        const recipeStockSmallUnit = (data.recipeIngredientSmallUnit).toLowerCase(); 
                                        const recipeStockUnitConversion = (data.uc_recipe_ingredient_smallUnit).toLowerCase();                                  
                                        let recipeStkQty = hasDifferProductUnit && productUnit === recipeStockUnit  ? parseFloat(productQty * ($.trim($('#quantity').val()))).toFixed(2) 
                                                        : hasDifferProductUnit && productUnit !== recipeStockUnit && recipeStockUnit == smallMeasurementUnit ? (parseFloat(productQty) * (parseFloat($.trim($('#quantity').val()))* parseFloat(conversionUnit))).toFixed(2) 
                                                        : hasDifferProductUnit && productUnit !== recipeStockUnit && recipeStockSmallUnit == productUnit ?  (parseFloat(productQty) * parseFloat($.trim($('#quantity').val()))/parseFloat(recipeStockUnitConversion)).toFixed(2) 
                                                        : !hasDifferProductUnit && productUnit === recipeStockUnit ? parseFloat($.trim($('#quantity').val())).toFixed(2)
                                                        : !hasDifferProductUnit && productUnit !== recipeStockUnit && recipeStockUnit == smallMeasurementUnit ? (parseFloat($.trim($('#quantity').val())) * parseFloat(conversionUnit)).toFixed(2) 
                                                        : !hasDifferProductUnit && productUnit !== recipeStockUnit && recipeStockSmallUnit == productUnit ? (parseFloat($.trim($('#quantity').val())) / parseFloat(recipeStockUnitConversion)).toFixed(2): ''; 

                                        let productWeight = productQty + "" + productUnit;                                                                                                       
                                        productDetails = {                            
                                            'supplier_stock_id' : productId, 
                                            'name' : productName,                          
                                            'unit': productMeasurementUnit,                                        
                                            'unit_cost': unitCost,
                                            'quantity': parseFloat($.trim($('#quantity').val())),
                                            'stock': productStock,
                                            'total': (parseFloat(unitCost) * parseFloat($.trim($('#quantity').val()))), 
                                            'recipe_unit': recipeStockUnit,
                                            'recipe_stk_qty': recipeStkQty                         
                                        };                                   
                                        
                                        // array of objects holds all products details                
                                        requisitionProducts.push(productDetails);  
                                        //disabling the supplier after adding its products to allow single supplier requisition only
                                        requisitionProducts.length > 0 ? $('#supplier_id').prop('disabled', true): '' ;
                                        //resetting the product details all input fields to prepare for another product input.
                                        $('#unit').prop('readonly',false).val("").prop('readonly',true);
                                        $('#unit_cost').prop('readonly',false).val("").prop('readonly',true);
                                        $('#total_qty').prop('readonly',false).val("").prop('readonly',true); //stock
                                        $('#quantity').val("");
                                        $('#supplier_stock_id').select2("val", ""); 
                                        $('#quantity').css({
                                            "border": "",
                                            "background": ""
                                        });
                                        
                                        // adding products info to product Details table
                                        let i = document.getElementById("productTable").rows.length;                        
                                        tableBody = $("#productTable tbody"); 
                                        let readOnly = productDetails.recipe_stk_qty ? 'readonly' : '';

                                        markup = "<tr id='row"+i+"'><td class='text-center index'><span>"  + i + "</span></td>" + 
                                                        "<td class='text-center'>"  + productDetails.name + "</td>" +                   
                                                        "<td class='text-center'>"  + productDetails.unit + "</td>" +
                                                        "<td class='text-center'>"  + productDetails.stock + "</td>" +                                             
                                                        "<td class='text-center tdQty'>"  + 
                                                            "<input type='text' id='product-qty"+i+"' value="+ productDetails.quantity +" size= '1' class='form-control qty' style='line-height: 10px;' />"                                            
                                                        +"</td>" + 
                                                        "<td class='text-center unitCost' id='unitPrice"+i+"'>"  + productDetails.unit_cost + "</td>" +  
                                                        "<td class='text-center totPrice' id='price"+i+"'>"  + productDetails.total + "</td>" +  
                                                        "<td class='text-center'>"  + productWeight +"</td>" +  
                                                        "<td class='text-center'>"  + productDetails.recipe_unit + "</td>" + 
                                                        "<td class='text-center ingredientQty'>"  + 
                                                        "<input type='text' id='ingredient_qty"+i+"' size= '1' value= '"+ productDetails.recipe_stk_qty +"' class='form-control inQty' style='line-height: 10px;'"+ readOnly +" />"                                            
                                                        +"</td>" +                   
                                                        "<td class='text-center tdClsBtn' style='width:100px;'>"+ 
                                                            "<button class='btn btn-sm btn-danger clsBtn' id='close" +i+"'><i class='fa fa-trash'></i></button></td>"+ 
                                                    "</tr>";
                                                
                                        tableBody.append(markup); 
                                        //$("total_quantity").val(productDetails.quantity);
                                        let totalQty = 0, totalAmount = 0;
                                        requisitionProducts.forEach(product => {
                                            totalQty += product.quantity;
                                            totalAmount += product.total;
                                        }); 

                                        $('#total_quantity').prop('readonly',false).val(totalQty).prop('readonly',true);
                                        $('#total_amount').prop('readonly',false).val(totalAmount).prop('readonly',true);

                                        //converting array of objects to json string to pass data to controller.
                                        jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
                                        //setting product lists to form hidden input field           
                                        $('#product_lists').val(jsonStringRequisitionProducts);                                    
                                        
                                    }                           
                                }
                            });
                        } 

                    }else{
                        $("#quantity").attr('required', '');
                        $('#quantity').css({
                            "border": "2px solid #007065",
                            "background": "#e4f5f3"
                        });
                        
                    }

                }
                catch(e) {
                    //Create Bootstrap alert in JQuery                    
                    if(e == "duplicateEntry"){
                        $(this).after(
                        '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                            '<strong>Error! this product is already added to the Supplier Return Product List</strong></div>');
                    }
                    if(e == "lessStock"){
                        $(this).after(
                        '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                            '<strong>Error! Whoops this product current stock is less or empty.</strong></div>');
                    }
                   
                    //resetting the product details all input fields to prepare for another product input.
                    $('#unit').prop('readonly',false).val("").prop('readonly',true);
                    $('#unit_cost').prop('readonly',false).val("").prop('readonly',true);
                    $('#total_qty').prop('readonly',false).val("").prop('readonly',true); //stock
                    $('#quantity').val("");
                    $('#supplier_stock_id').select2("val", ""); 
                    $('#quantity').css({
                        "border": "",
                        "background": ""
                    });                                   
                }

            });

            $(document).on('input', '.qty',function(){
                let product_qty = parseFloat($.trim($(this).val()));                
                let product_id = $(this).attr("id"); // getting the id of input product_qty
                // finding the rowno from the id.
                let row = parseInt(product_id.match(/(\d+)/)[0]);
                let unitCost = parseFloat($('#unitPrice'+ row).text());
                
                let totQty = 0.0,totAmount=0.0;
                //console.log(unitCost);
                if(product_qty){   
                    let UnitOfRecipeStkQty = 0.0;
                    //resetting the total price after getting the new quantity of the product on requisition product table              
                    $("#price" + row).html(unitCost * product_qty); 
                    // updating qty of the selected product in requisitionProducts array.
                    requisitionProducts.forEach((product, index) => {
                            if(row == index+1){
                                //get the unit qunatity of RecipeStkQty from old recipe stock qty & stock quantity of the requisition product that have req qty has modified.                            
                                UnitOfRecipeStkQty = parseFloat(product.recipe_stk_qty)/parseFloat(product.quantity);
                                //updating the recipe stock quantity, req quantity and total price
                                product.recipe_stk_qty = UnitOfRecipeStkQty * product_qty;
                                product.quantity = product_qty;
                                product.total = (unitCost * product_qty);
                            }
                            totQty += product.quantity;
                            totAmount += product.total;
                        }); 
                    //resetting the recipe stock quantity of the product on requisition product table              
                    $("#ingredient_qty" + row).val((UnitOfRecipeStkQty * product_qty).toFixed(2)); 
                }                            
                //setting the value
                $('#total_quantity').prop('readonly',false).val(totQty).prop('readonly',true);
                $('#total_amount').prop('readonly',false).val(totAmount).prop('readonly',true);

                //converting array of objects to json string to pass data to controller.
                jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
                //setting product lists to form hidden input field           
                $('#product_lists').val(jsonStringRequisitionProducts);  

                console.log(requisitionProducts);

            });

            

            // Removing disability of the supplier while submitting the data.
            $('#requisition-form').submit(function() {
                $('select').removeAttr('disabled');
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
                    // Modifying row id of the <td> with .unitPrice class
                    $(this).children('.unitCost').attr('id', `unitPrice${dig - 1}`);                    
                    // Modifying row id of the <td> with .tdQty class
                    $(this).children('.tdQty').children('.qty').attr('id', `product-qty${dig - 1}`);
                    // Modifying row id of the <td> with .totPrice class
                    $(this).children('.totPrice').attr('id', `price${dig - 1}`);
                    // Modifying row id of the <td> with .ingredientQty class
                    $(this).children('.ingredientQty').children('.inQty').attr('id', `ingredient_qty${dig - 1}`);
                    // Modifying row id of the close btn with .clsBtn class
                    $(this).children('.tdClsBtn').children('.clsBtn').attr('id', `close${dig - 1}`);
                    
                });

                let totQty =0.0, totAmount=0.0;
                // deleting the record from the array.
                requisitionProducts.splice(rowId-1,1);
                // re-calculating total quantity & total Amount
                requisitionProducts.forEach((product) => {                            
                    totQty += product.quantity;
                    totAmount += product.total;
                });
                // Removing the current row.
                $(this).parent().parent('tr').remove();
                // setting the new value of total quantity and total amount.
                $('#total_quantity').prop('readonly',false).val(totQty).prop('readonly',true);
                $('#total_amount').prop('readonly',false).val(totAmount).prop('readonly',true);  
                //converting array of objects to json string to pass data to controller.
                jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
                //setting product lists to form hidden input field           
                $('#product_lists').val(jsonStringRequisitionProducts); 
            
            });


        });

        
      
</script>
@endpush