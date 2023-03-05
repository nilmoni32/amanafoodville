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
    <div class="col-md-11 mx-auto">
        <div class="tile px-5">
            <form action="{{ route('admin.supplier.requisition.update') }}" method="POST" role="form"
                enctype="multipart/form-data" id="requisition-form">
                @csrf
                <h4 class="tile-title">Editing the Supplier Requisition: #{{ $supplier_requisition->id }}</h4>
                <hr>
                <input type="hidden" value="{{ $supplier_requisition->id }}" name="supplier_requisition_id">
                <div class="tile-body">
                    <div class="row pb-3">                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="requisition_date">Requisition Date<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control datetimepicker" name="requisition_date"
                                 value="{{ \Carbon\Carbon::parse($supplier_requisition->requisition_date)->format('d-m-Y') }}" required>
                            </div>
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="expected_delivery">Expected Delivery Date<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control datetimepicker" name="expected_delivery"
                                value="{{ \Carbon\Carbon::parse($supplier_requisition->expected_delivery)->format('d-m-Y') }}" required>
                            </div>
                        </div>  
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="purpose">Purpose</label>
                                <input class="form-control @error('purpose') is-invalid @enderror" type="text"
                                    placeholder="Purpose of requisition" id="purpose" name="purpose" value="{{ old('purpose', $supplier_requisition->purpose ) }}"/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('purpose')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>                     
                    </div>   
                    {{-- <div class="row">  
                        <div class="offset-md-1"></div>                      
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label" for="purpose">Purpose</label>
                                <input class="form-control @error('purpose') is-invalid @enderror" type="text"
                                    placeholder="Purpose of requisition" id="purpose" name="purpose" value="{{ old('purpose', $supplier_requisition->purpose ) }}"/>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('purpose')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label" for="remarks">Remarks</label>
                                <input class="form-control @error('remarks') is-invalid @enderror" type="text"
                                    placeholder="Remarks" id="remarks" name="remarks" value="{{ old('remarks', $supplier_requisition->remarks ) }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('remarks')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="offset-md-1"></div>                                               
                    </div>                                  --}}
                    <div class="row pb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="supplier_id">Supplier <span class="text-danger"> *</span></label>
                                <select name="supplier_id" id="supplier_id" class="form-control" disabled>
                                    <option value="{{ $supplier_requisition->supplier_id }}" selected>{{ $supplier_requisition->supplier->name }}</option>                                    
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="control-label" for="supplier_stock_id">Product</label>
                                    <select name="supplier_stock_id" id="supplier_stock_id" class="form-control">   
                                        <option></option>                                
                                        @foreach($items as $item) 
                                        <option value="{{$item->id }}">{{ $item->supplier_product_name }}</option>
                                        @endforeach                                
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
                                <h6 class="pb-3">Added all Products Details</h6>
                                <input type="hidden" id="product_lists" name="product_lists" value="">
                                <div class="tile-body">
                                    <table class="table table-hover table-bordered" id="productTable">
                                        <thead>
                                            <tr>
                                                <th class="text-center"> # </th>
                                                <th class="text-center"> Name </th>
                                                <th class="text-center"> Measurement Unit</th>
                                                <th class="text-center"> Req Price</th>
                                                <th class="text-center"> Req Qty</th>
                                                <th class="text-center"> Stock Qty</th>
                                                <th class="text-center"> Total </th>
                                                <th style="min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach($requisition_items as $item)
                                            <tr>
                                                <td id="index{{$loop->index + 1}}" class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">{{ $loop->index + 1 }}</td>
                                                <td class="text-center">{{ $item['name'] }}</td>
                                                <td class="text-center">{{ $item['unit'] }}</td>
                                                <td class="text-center" id="unitPrice{{$loop->index + 1}}">{{ $item['unit_cost'] }}</td>
                                                <td class="text-center">
                                                    <input type="text" id="product-qty{{$loop->index + 1}}" value="{{ $item['quantity'] }}" 
                                                    size="1" class="form-control qty" style="line-height: 10px;" />                                                    
                                                </td>                                              
                                                <td class="text-center">{{ $item['stock'] }}</td>
                                                <td class="text-center" id="price{{$loop->index + 1}}">{{ $item['total'] }}</td>                                               
                                                <td class="text-center" style="width:100px;">
                                                    <button class="btn btn-sm btn-danger" id="close{{$loop->index + 1}}" 
                                                        onclick="delProduct(event,{{ $loop->index + 1 }})"><i class='fa fa-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="offset-md-1"></div>
                        <div class="col-md-5">
                            <label class="control-label" for="total_quantity">Request Qty</label>
                            <input class="form-control @error('total_quantity') is-invalid @enderror" type="text" id="total_quantity" name="total_quantity" 
                            value="{{ old('total_quantity', number_format((float)$supplier_requisition->total_quantity, 2, '.', '') ) }}" placeholder="0" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('total_quantity')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="control-label" for="total_amount">Total Amount</label>
                            <input class="form-control @error('total_amount') is-invalid @enderror" type="text" id="total_amount" name="total_amount" 
                            value="{{ old('total_amount', number_format((float)$supplier_requisition->total_amount, 2, '.', '')) }}" placeholder="0.0" readonly/>
                            <div class="invalid-feedback active">
                                <i class="fa fa-exclamation-circle fa-fw"></i> @error('total_amount')
                                <span>{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="offset-md-1"></div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit" id="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                            <a class="btn btn-danger" href="{{ route('admin.supplier.requisition.index') }}"><i
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
        //getting the requisition items from the backend.     
        var requisitionProducts = <?php echo json_encode($requisition_items); ?>;     
        var productId, productMeasurementUnit, unitCost, productName, productStock;

        $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
        });
        
        $(".datetimepicker").attr("autocomplete", "off");

        $('#supplier_stock_id').select2({
        placeholder: "Choose a Product",
        //allowClear: true,
        multiple: false,  
        width: '100%',                 
        // minimumResultsForSearch: -1,                    
        });
        //loading requisitionProducts data
        (function loadTableData(){                                                    
            let tableBody = $("#productTable tbody"); 
            let markup = [];
            requisitionProducts.forEach((product, index) => {
                markup.push("<tr id='row"+ (index +1)+"'><td class='text-center index'><span>"  + (index +1) + "</span></td>" + 
                            "<td class='text-center'>"  + product.name + "</td>" +                   
                            "<td class='text-center'>"  + product.unit + "</td>" +  
                            "<td class='text-center unitCost' id='unitPrice"+(index +1)+"'>"  + product.unit_cost + "</td>" +  
                            "<td class='text-center tdQty'>"  + 
                                "<input type='text' id='product-qty"+(index +1)+"' value="+ product.quantity +" size= '1' class='form-control qty' style='line-height: 10px;' />"                                            
                            +"</td>" + 
                            "<td class='text-center'>"  + product.stock + "</td>" + 
                            "<td class='text-center totPrice' id='price"+(index +1)+"'>"  + product.total + "</td>" +                     
                            "<td class='text-center tdClsBtn' style='width:100px;'>"+ 
                                "<button class='btn btn-sm btn-danger clsBtn' id='close" +(index +1)+ "'>" +
                            "<i class='fa fa-trash'></i></button></td>"+ 
                    "</tr>"); 
                });
            tableBody.append(markup); 
        })();


        $("#supplier_stock_id").change(function(){
            productId = $(this).val();
            
            if(productId != null){
                $.ajax({
                    url: '/admin/supplier/requisition/product/details/' + productId,
                    type: 'get',
                    dataType: 'JSON',
                    data: {},
                    success: function(data) {
                        //setting measurement unit
                        $('#unit').val(data.item.measurement_unit);
                        //setting current stock
                        $('#total_qty').val(data.item.total_qty);
                        //setting cost price of the product
                        $('#unit_cost').val(data.item.unit_cost);

                        productMeasurementUnit = data.item.measurement_unit;
                        unitCost = data.item.unit_cost;
                        productName = data.item.supplier_product_name;
                        productStock = data.item.total_qty;                            
                    }
                });
            }
        });

        $("#addProduct").click(function(e){
            e.preventDefault();

            try{
                if($.trim($('#quantity').val()) != ""){
                    productDetails = {
                        'id' :'',
                        'requisition_to_supplier_id': {{ $supplier_requisition->id }},                           
                        'supplier_stock_id' : parseInt(productId), 
                        'name' : productName,                          
                        'unit': productMeasurementUnit,
                        'unit_cost': parseFloat(unitCost),
                        'quantity': parseFloat($.trim($('#quantity').val())),
                        'stock': productStock,
                        'total': (parseFloat(unitCost) * parseFloat($.trim($('#quantity').val()))),                            
                    }; 
                    //preventing the same product to be added
                    if(requisitionProducts.length > 0) {
                        requisitionProducts.forEach(product => {
                            if(product.supplier_stock_id == productId){                                
                                throw "exit";
                            }
                        }); 
                    }
                        
                    // array of objects holds all products details                
                    requisitionProducts.push(productDetails);  
                    //disabling the supplier after adding its products to allow single supplier requisition only
                    requisitionProducts.length > 0 ? $('#supplier_id').prop('disabled', true): '' ;
                    //resetting the product details all input fields.
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
                    markup = "<tr id='row"+i+"'><td class='text-center index'><span>"  + i + "</span></td>" + 
                                "<td class='text-center'>"  + productDetails.name + "</td>" +                   
                                "<td class='text-center'>"  + productDetails.unit + "</td>" +  
                                "<td class='text-center unitCost' id='unitPrice"+i+"'>"  + (productDetails.unit_cost).toFixed(2) + "</td>" +  
                                "<td class='text-center tdQty'>"  + 
                                    "<input type='text' id='product-qty"+i+"' value="+ (productDetails.quantity).toFixed(2) +" size= '1' class='form-control qty' style='line-height: 10px;' />"                                            
                                +"</td>" + 
                                "<td class='text-center'>"  + productDetails.stock + "</td>" + 
                                "<td class='text-center totPrice' id='price"+i+"'>"  + (productDetails.total).toFixed(2) + "</td>" +                     
                                "<td class='text-center tdClsBtn' style='width:100px;'>"+ 
                                    "<button class='btn btn-sm btn-danger clsBtn' id='close" +i+"'><i class='fa fa-trash'></i></button></td>"+ 
                            "</tr>";
                                
                    tableBody.append(markup); 
                    //$("total_quantity").val(productDetails.quantity);
                    let totalQty = 0, totalAmount = 0;
                    requisitionProducts.forEach(product => {
                        totalQty += parseFloat(product.quantity);
                        totalAmount += parseFloat(product.total);
                    }); 

                    $('#total_quantity').prop('readonly',false).val(totalQty.toFixed(2)).prop('readonly',true);
                    $('#total_amount').prop('readonly',false).val(totalAmount.toFixed(2)).prop('readonly',true);

                    //converting array of objects to json string to pass data to controller.
                    jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
                    //setting product lists to form hidden input field           
                    $('#product_lists').val(jsonStringRequisitionProducts);  

                        

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
                    $(this).after(
                        '<div class="alert alert-danger alert-dismissible mt-3" role="alert">' +    
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>' + 
                            '<strong>Error! This product is already added to the Supplier Requisition Product List</strong></div>');
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
            let row = product_id.substring(product_id.length - 1); 
            let unitCost = parseFloat($('#unitPrice'+ row).text());
            let totQty = 0.0,totAmount=0.0;
            
            if(product_qty){  
                //resetting the total price after getting the new quantity of the product.                 
                $("#price" + row).html(unitCost * product_qty); 
                // updating qty of the selected product in requisitionProducts array.
                requisitionProducts.forEach((product, index) => {
                        if(row == index+1){
                            product.quantity = product_qty;
                            product.total = (unitCost * product_qty);
                        }
                        totQty += parseFloat(product.quantity);
                        totAmount += parseFloat(product.total);
                    }); 
            }             
                            
            //console.log(requisitionProducts);
            $('#total_quantity').prop('readonly',false).val(totQty.toFixed(2)).prop('readonly',true);
            $('#total_amount').prop('readonly',false).val(totAmount.toFixed(2)).prop('readonly',true);

            //converting array of objects to json string to pass data to controller.
            jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
            //setting product lists to form hidden input field           
            $('#product_lists').val(jsonStringRequisitionProducts);  

        });

        $('#requisition-form').submit(function() {            
            $('select').removeAttr('disabled');
            $("#quantity").removeAttr('required');
        });


        //deleting the item from the table.
        // As table row is dynamically added to the table, need to use document.on() to capture the click events.            
        $(document).on('click','.clsBtn',function(e){
            e.preventDefault();   
            //get the row no from the id
            let rowId = $(this).attr('id')[$(this).attr('id').length -1];
            // Getting all the rows next to the row containing the clicked close button
            var child = $(this).closest('tr').nextAll();
            // Iterating across all the rows obtained to change the index
            child.each(function () {
                // Getting <tr> id.
                var delBtnId = $(this).attr('id');
                // Getting the <td> with .index class
                var idx = $(this).children('.index').children('span');                  
                // Gets the row number from <tr> id.
                var dig = delBtnId[delBtnId.length -1];                
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
                // Modifying row id of the close btn with .clsBtn class
                $(this).children('.tdClsBtn').children('.clsBtn').attr('id', `close${dig - 1}`);
                
            });

            let totQty =0.0, totAmount=0.0;
            // deleting the record from the array.
            requisitionProducts.splice(rowId-1,1);
            // re-calculating total quantity & total Amount
            requisitionProducts.forEach((product) => {                            
                totQty += parseFloat(product.quantity);
                totAmount += parseFloat(product.total);
            });
            // Removing the current row.
            $(this).parent().parent('tr').remove();
            // setting the new value of total quantity and total amount.
            $('#total_quantity').prop('readonly',false).val(totQty.toFixed(2)).prop('readonly',true);
            $('#total_amount').prop('readonly',false).val(totAmount.toFixed(2)).prop('readonly',true);  
            //converting array of objects to json string to pass data to controller.
            jsonStringRequisitionProducts = JSON.stringify(requisitionProducts);
            //setting product lists to form hidden input field           
            $('#product_lists').val(jsonStringRequisitionProducts); 
        
        });

    });


        

    
      
</script>
@endpush