@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-paw"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row justify-content-center">
    <div class="col-sm-12">
        <div class="tile">
            <div class="tile-body">
                {{-- <h3 class="tile-title">{{ __('Sales') }}</h3> --}}
                <!-- For defining autocomplete -->
                <div class="row">
                    <div class="col-md-8 text-center">
                        <div class="form-group row mt-2">
                            <label class="col-md-4 col-form-label font-weight-bold text-right text-uppercase">Search
                                Food</label>
                            <div class="col-md-6 text-left">
                                <input type="text" class="form-control" id="product_search" name="product_search"
                                    placeholder="Find Foods">
                            </div>
                        </div>
                        {{-- to display cart message --}}
                        <div class="form-group row mt-2">
                            <div class="col-md-12 text-center" style="margin-bottom:-10px;" id="message">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="tbl-sale-cart">
                                    <thead>
                                        <tr>
                                            <th class="text-left pl-3" style="min-width:190px;"> Food Name </th>
                                            <th class="text-center"> Price </th>
                                            <th class="text-center"> Qty </th>
                                            <th class="text-center"> Subtotal </th>
                                            <th style="min-width:50px;" class="text-center text-danger"><i
                                                    class="fa fa-bolt"> </i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1; $total_taka = 0; @endphp
                                        @foreach(App\Models\Complimentarysale::where('admin_id', Auth::id())->where('complimentary_ordersales_id',
                                        NULL)->get() as $sale)
                                        <tr>
                                            <td class="text-left pl-3">{{ $sale->product_name }}</td>
                                            <td class="text-center">{{ round($sale->unit_price,0) }}</td>
                                            {{-- <td class="text-center">{{ $sale->product_quantity }}</td> --}}
                                            <td>
                                                <p class="qtypara">
                                                    <span class="cart-id d-none">{{ $sale->id }}</span>
                                                    <span id="minus{{$i}}" class="minus"
                                                        onclick="updateAddtoSale({{ $sale->id }}, 'minus{{$i}}' )"><i
                                                            class="fa fa-minus" aria-hidden="true"></i></span>
                                                    <input type="text" name="product_quantity" id="input-quantity{{$i}}"
                                                        value="{{ $sale->product_quantity  }}" size="2"
                                                        class="form-control qty" readonly="true"
                                                        ondblclick="this.readOnly='';" />
                                                    <span id="add{{$i}}" class="add"
                                                        onclick="updateAddtoSale({{ $sale->id }}, 'add{{$i}}' )"><i
                                                            class="fa fa-plus" aria-hidden="true"></i></span>
                                                </p>
                                            </td>
                                            <td class="text-center" id="price{{$i}}">
                                                {{ round($sale->product_quantity * $sale->unit_price,0) }}</td>
                                            @php $total_taka += $sale->product_quantity * $sale->unit_price @endphp
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-danger" id="cart-close{{$i}}"
                                                    onclick="cartClose({{ $sale->id }}, 'cart-close{{$i}}')"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-sm-12" style="visibility:{{ $total_taka ? 'visible': 'hidden' }};"
                                        id="total">
                                        <h5 class="text-right pb-3" style="margin-right:13%">Subtotal :
                                            <span
                                                id="sub-total-tk">{{ $total_taka }}</span>&nbsp;<span>{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">                        
                        <form method="POST" action="{{ route('admin.complimentary.sales.orderplace') }}">
                            @csrf
                            <div class="border px-4 rounded" style="border-color:rgb(182, 182, 182);">
                                <h5 class="text-left mt-3 mb-4">Short Notes for Complimentary sales :</h5>
                                {{-- <div class="form-group my-2">
                                    <input type="text" class="form-control @error('order_tableNo') is-invalid @enderror"
                                        id="order_tableNo" placeholder="Enter Order Table No (required)"
                                        name="order_tableNo" required>
                                    @error('order_tableNo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    </div>
                                 --}}
                                <div class="form-group">
                                    {{-- <label for="exampleFormControlTextarea1"></label> --}}
                                    <textarea class="form-control" id="complimentary_notes" name="complimentary_notes" rows="2" placeholder="Enter short notes" required></textarea>
                                </div>
                                <div class="form-group mt-2 mb-4">
                                    <button type="submit" class="btn btn-primary text-uppercase"
                                        style="display:block; width:100%;" id="submit">Place order </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){
        
        $('#order_tableNo').select2({
                placeholder: "Select Order Table No",
               // allowClear: true,
                multiple: false,  
                width: '100%',                       
             });
     // POS system starts here
     // Initialize jQuery UI autocomplete on #product_search field.
     
      $("#product_search").autocomplete({
        // resetting the combobox option
        position: {
            my: "left top-16",
            of: event,
            collision: "fit"
            },
        //FIX JQUERY UIS AUTOCOMPLETE WIDTH
        open: function(event, ui) {
            $(this).autocomplete("widget").css({
                "width": ($(this).width() + "px")
            });
        },       
        //Using source option to send AJAX post request to route('employees.getEmployees') to fetch data
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{ route('admin.complimentary.sales.getfoods') }}",
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
           $('#product_search').val(ui.item.label); // display the selected text
        //  calling to add to cart function addToSale  
           addToSale(ui.item.label, ui.item.value);
           return false;
        }
      });

 

      function addToSale(foodName, foodId){
        $.post("{{ route('admin.complimentary.sales.addtosales') }}", {        
            _token: CSRF_TOKEN,
            foodName: foodName,
            foodId: foodId
        }).done(function(data) {
            data = JSON.parse(data);
            if(data.status == "success") {
                    var i = document.getElementById("tbl-sale-cart").rows.length;
                    tableBody = $("table tbody"); 
                    markup = "<tr><td class='text-left pl-3'>"  + data.foodname + "</td>" + 
                    "<td class='text-center'>"  + Math.round(data.price) + "</td>" +                   
                    "<td><p class='qtypara'>"+
                        "<span class='cart-id d-none'>"+ data.id +"</span>"
                    +"<span id='minus"+ i +"' class='minus' onclick='updateAddtoSale("+ data.id +", \"minus"+ i +"\")' >" +
                         "<i class='fa fa-minus' aria-hidden='true'></i></span>"+                                    
                         "<input type='text' name='product_quantity' id='input-quantity"+i+"' value="+ data.qty +" size= '2' class='form-control qty' readonly='true' ondblclick=\"this.readOnly='';\" />" + "<span id='add"+i+"' class='add'" +
                                "onclick='updateAddtoSale("+ data.id +", \"add"+ i +"\")' >" +
                                "<i class='fa fa-plus' aria-hidden='true'></i></span></p></td>" +

                    "<td class='text-center' id='price"+i+"'>"  + data.price*data.qty + "</td>" +
                    "<td class='text-center'>"+ 
                        "<button class='btn btn-sm btn-danger' id='close" +i+
                        "' onclick='cartClose(" + data.id +", \"close"+ i + "\")' >" +
                                           "<i class='fa fa-trash'></i></button></td>"+ 
                    "</tr>"; 
                    tableBody.append(markup);
                    $('#total').css('visibility', 'visible');                    
                    $("#sub-total-tk").html(data.sub_total); 
                }
                
            if(data.status == 'info'){
                message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +                
                            data.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                           '<span aria-hidden="true">&times;</span></button></div>';                
                $('#message').html(message);
            }   
            
        });
        }   

        
        //when cart quantity text is editable and an text input event is take place.
        // delegated binding by using on().
        $(document).on('input', '.qty', function() {
           if($.isNumeric( $.trim($(this).val()) )){
               var sale_id = $(this).closest("p").find(".cart-id").text();
               var product_quantity = $.trim($(this).val());
               var id = $(this).attr("id"); // getting the id of input product_qty
               
            $.post("{{ route('admin.complimentary.sales.saleCartUpdate') }}", {
            _token: CSRF_TOKEN,
            sale_id: sale_id,
            product_quantity: product_quantity
            }).done(function(data) {
                data = JSON.parse(data);
                if(data.status == "success") { 
                    // finding the rowno from the id such add1, add2, minus1 etc.
                    var row = id.substring(id.length - 1); //Displaying the last character                    
                    $("#price" + row).html(data.total_unit_price);                    
                    $("#sub-total-tk").html(data.sub_total);
                }
            });
               
           }
        });

    });



     /*Product Quantity Plus/Minus Start and send to cart */
        function updateAddtoSale(sale_id, id) {
            if (id.includes("add")) {
                var $qty = $("#" + id)
                    .closest("p")
                    .find(".qty");
                var currentVal = parseFloat($qty.val());
                $qty.val(currentVal + 1);
            } else if (id.includes("minus")) {
                var $qty = $("#" + id)
                    .closest("p")
                    .find(".qty");
                var currentVal = parseFloat($qty.val());
                if (currentVal > 1) {
                    $qty.val(currentVal - 1);
                }
            }

            $.post("{{ route('admin.complimentary.sales.saleCartUpdate') }}", {
                _token: CSRF_TOKEN,
                sale_id: sale_id,
                product_quantity: $qty.val()
            }).done(function(data) {
                data = JSON.parse(data);
                if(data.status == "success") { 
                    // finding the rowno from the id such add1, add2, minus1 etc.
                    var row = id.substring(id.length - 1); //Displaying the last character                    
                    $("#price" + row).html(data.total_unit_price);                    
                    $("#sub-total-tk").html(data.sub_total);
                }
            });
        }

        function cartClose(saleId, delBtnId) {
            var parent = $("#" + delBtnId).parent(); //getting the td of the del button
            $.post("{{ route('admin.complimentary.sales.saleCartDelete') }}", {
                _token: CSRF_TOKEN,
                sale_id: saleId
            }).done(function(data) {
                data = JSON.parse(data);
                if (data.status == "success") {                 
                    // not to reload the page, just removing the row from DOM                    
                    $("#sub-total-tk").html(data.sub_total);
                    parent.slideUp(100, function() {
                        parent.closest("tr").remove();
                    });
                    if(!data.sub_total){
                        $('#total').css('visibility', 'hidden');
                    }                    
                }
            });
        }

</script>
@endpush