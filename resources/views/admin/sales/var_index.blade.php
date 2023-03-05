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
                        <form action="{{ route('admin.sales.search') }}" method="get" autocomplete="off">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-md-4 text-right">
                                    <label class="col-form-label font-weight-bold text-uppercase">Order Table
                                        No:</label>
                                </div>
                                <div class="col-md-5 ml-0">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search by Table No"
                                            name="search"
                                            value="{{ $order_id ? App\Models\Ordersale::where('id', $order_id)->first()->order_tableNo : '' }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

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
                                            <th class="text-center">#</th>
                                            <th class="text-center"> Food Name </th>
                                            <th class="text-center"> Price </th>
                                            <th class="text-center"> Qty </th>
                                            <th class="text-center"> Subtotal </th>
                                        </tr>
                                    </thead>
                                    @php $i=1; $total_taka = 0; @endphp

                                    @if($order_id && App\Models\Ordersale::where('id',
                                    $order_id)->first()->status == 'receive')
                                    <tbody>
                                        @foreach( App\Models\Sale::where('ordersale_id',
                                        $order_id)->get() as $sale)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td class="text-center" style="text-transform:capitalize; max-width:120px;">
                                                {{-- <img src="{{ asset('storage/'.$sale->product->images->first()->full) }}"
                                                title="{{ $sale->product->name }}"
                                                class="img-responsive pr-2 rounded" width="70px" /> --}}
                                                {{ $sale->product->name }}
                                            </td>
                                            <td class="text-center">{{ round($sale->unit_price,2) }}
                                                {{ config('settings.currency_symbol') }}</td>
                                            <td class="text-center">{{ $sale->product_quantity }}</td>
                                            <td class="text-center" id="price{{$i}}">
                                                {{ round($sale->product_quantity * $sale->unit_price,2) }}
                                                {{ config('settings.currency_symbol') }}</td>
                                            @php $total_taka += $sale->product_quantity * $sale->unit_price @endphp
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                    @endif
                                </table>
                                <div class="row">
                                    <div class="col-sm-12" style="visibility:{{ $order_id && App\Models\Ordersale::where('id',
                                    $order_id)->first()->status == 'receive' ? 'visible': 'hidden' }};" id="total">
                                        <h5 class="text-right pb-3 border-bottom">Subtotal :
                                            <span id="sub-total-tk">{{ $total_taka }}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        @if(config('settings.tax_percentage'))
                                        <h5 class="text-right pb-3 pt-1 border-bottom">Vat
                                            ({{ config('settings.tax_percentage')}}%):
                                            <span
                                                id="vat-percent">{{ $total_taka * (config('settings.tax_percentage')/100) }}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        @endif
                                        <h5 class="text-right pb-3 pt-1 border-bottom">Order Total :
                                            <span
                                                id="total-tk">{{ $total_taka + ($total_taka * (config('settings.tax_percentage')/100))}}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="discount-blk">
                                            <label id="discount-lbl" style="margin-right:-15px; cursor:pointer;"><input
                                                    type="checkbox" class="radio-inline" name="dicount"
                                                    id="discount_check" onclick="discountCheck()">
                                                Discount :
                                            </label>
                                            <div class="form-check form-check-inline cash-discount">
                                                {{-- <input type="text" class="form-control" id="discount_reference"
                                                    placeholder="Reference (Required)" name="discount_reference"
                                                   > --}}
                                                <select name="director_id" id="discount_reference"
                                                    class="form-control font-weight-normal" style="display:none;">
                                                    <option value="" disabled selected>Select Discount Reference
                                                    </option>
                                                    @foreach( App\Models\Director::orderBy('name', 'asc')->get() as
                                                    $director)
                                                    <option value="{{ $director->id }}">{{ $director->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-check form-check-inline cash-discount"
                                                style="margin-right:3em">
                                                <input type="text" class="form-control" id="discount"
                                                    placeholder="Amount (Required)" name="discount"
                                                    style="display:none;">
                                            </div>
                                            <br>
                                            <div class="text-right mt-1 mr-5 d-none" id="discount-limit">
                                                <span class="font-weight-normal text-danger w-25">
                                                    {{_('** The given amount exceeds the discount limit. **')}}
                                                </span>
                                            </div>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom d-none" id="reward-point-blk">
                                            <label id="discount-point-lbl"
                                                style="margin-right:-15px; cursor:pointer; margin-right:3em;"><input
                                                    type="checkbox" class="radio-inline" name="reward_discount"
                                                    id="reward_point_check" onclick="rewardPoint()"> Use Reward
                                                points:
                                                <span class="ml-1" id="reward-discount"></span>
                                                {{ config('settings.currency_symbol') }}
                                            </label>
                                        </h5>
                                        <h5 class="text-right pb-3 pt-1 border-bottom">Due Amount:
                                            <span
                                                id="due-tk">{{ $total_taka + $total_taka * (config('settings.tax_percentage')/100)  }}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="cash_blk">
                                            <label style="cursor:pointer;"><input type="checkbox"
                                                    class="radio-inline payments" name="cash_check" id="cash_check"
                                                    onclick="cashCheck()"> Cash
                                                Payment :
                                            </label>
                                            <div class="form-check form-check-inline cash-payment"
                                                style="margin-right:3em">
                                                <input type="text" class="form-control" id="cash_pay"
                                                    placeholder="Amount (Required)" name="cash_pay"
                                                    style="display:none;">
                                            </div>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="card_blk">
                                            <label style="cursor:pointer; margin-right:-1em" id="card_blk_lbl"><input type="checkbox"
                                                    class="radio-inline payments" name="card_check" id="card_check"
                                                    onclick="cardCheck()"> Card
                                                Payment :
                                            </label>
                                            <div class="form-check form-check-inline card-payment">
                                                <input type="text" class="form-control" id="card_pay"
                                                    placeholder="Amount (Required)" name="card_pay"
                                                    style="display:none;">
                                            </div>
                                            <div class="form-check form-check-inline card-payment"
                                                style="margin-right:3em">
                                                <select name="bank_reference" id="bank_reference"
                                                        class="form-control font-weight-normal" style="display:none;">
                                                        <option value="" disabled selected>Select a Bank
                                                        </option>
                                                        @foreach(explode(',', config('settings.card_bank_list')) as $bank) 
                                                        <option value="{{$bank}}">{{$bank}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="mobileBank_blk">
                                            <label style="cursor:pointer;margin-right:-1em" id="mobileBank_blk_lbl"><input type="checkbox"
                                                    class="radio-inline payments" name="mobileBank_check"
                                                    id="mobileBank_check" onclick="mobileBankCheck()"> Mobile Payment :
                                            </label>
                                            <div class="form-check form-check-inline mobileBank-payment">
                                                <input type="text" class="form-control" id="mobile_banking_pay"
                                                    placeholder="Amount (Required)" name="mobile_banking_pay"
                                                    style="display:none;">
                                            </div>
                                            <div class="form-check form-check-inline mobileBank-payment"
                                                style="margin-right:3em">
                                                <select name="mobibank_reference" id="mobibank_reference"
                                                        class="form-control font-weight-normal" style="display:none;">
                                                        <option value="" disabled selected>Select a Mobile Bank
                                                        </option>
                                                        @foreach(explode(',', config('settings.mobile_bank_list')) as $bank) 
                                                        <option value="{{$bank}}">{{$bank}}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                        </h5>
                                        <h4 class="text-right py-4 d-none text-danger" id="pay-more-block">Customer
                                            need to pay
                                            more
                                            <span id="pay-more"></span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h4>
                                        <h4 class="text-right py-4 d-none text-danger" id="pay-change-block">You
                                            need to pay
                                            change
                                            <span id="pay-change"></span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h4>
                                        <h4 class="text-right py-4 d-none text-danger" id="donot-change-block">
                                            Don't
                                            pay much as you can't change
                                            <span id="donot-change"></span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h4>
                                        <h5 class="text-right py-4 pr-5 d-none text-danger" id="total-pay-block">Payment
                                            Details:&nbsp;
                                            <span id="pay-details"></span>
                                        </h5>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <!--pos printing-->

                        <div class="border px-4 rounded pb-4 mb-4" style="border-color:rgb(182, 182, 182);">
                            <h4 class="text-center mt-3 mb-4">Customer Print Receipt</h4>

                            <div class="text-center">
                                <label class="checkbox">
                                    <input type="checkbox" id="useDefaultPrinter" /> <strong>Print to
                                        Default
                                        printer</strong>
                                </label>
                                <br>
                                <div id="installedPrinters">
                                    <label for="installedPrinterName">or Select an installed Printer:</label><br>
                                    <select name="installedPrinterName" id="installedPrinterName"
                                        class="form-control mt-1 mb-2"></select>
                                </div>

                                <button type="button" onclick="print();"
                                    class="btn btn-primary text-center text-uppercase"
                                    style="display:block; width:100%;"
                                    {{$order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'delivered' ? '' : 'disabled' }}>Print
                                    Receipt</button>
                            </div>
                        </div>

                        <!--end of pos print using javascript-->
                        <form method="POST" action="{{ route('admin.sales.orderupdate') }}">
                            @csrf
                            <input type="hidden" id="order_id" name="order_id" value="{{ $order_id ? $order_id : '' }}">
                            <input type="hidden" id="sub-total" name="subtotal"
                                value="{{ $order_id ? $total_taka : '' }}">
                            <input type="hidden" id="order_discount" name="order_discount" value="">
                            <input type="hidden" id="order_discount_reference" name="order_discount_reference" value="">
                            <input type="hidden" id="reward_point_discount" name="reward_discount" value="">
                            <input type="hidden" id="payment_method" name="payment_method[]" value="">
                            <input type="hidden" id="pay_cash" name="cash_pay" value="">
                            <input type="hidden" id="pay_card" name="card_pay" value="">
                            <input type="hidden" id="cardbank" name="card_bank" value="">
                            <input type="hidden" id="mobibank" name="mobile_bank" value="">
                            <input type="hidden" id="pay_mobile" name="mobile_banking_pay" value="">

                            <div class="border px-4 rounded" style="border-color:rgb(182, 182, 182);">
                                <h4 class="text-center mt-3 mb-4">Customer Details</h4>
                                {{-- <div class="input-group my-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="phone_number">Order Table No</span>
                                    </div>
                                    <input type="text" class="form-control @error('order_tableNo') is-invalid @enderror"
                                        id="order_tableNo" placeholder="" name="order_tableNo"
                                        value="{{ $order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'receive' ? App\Models\Ordersale::where('id', $order_id)->first()->order_tableNo : '' }}"
                                readonly>
                                @error('order_tableNo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> --}}
                            <div class="input-group my-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="phone_number">Points</span>
                                </div>
                                <input type="text" class="form-control @error('total_points') is-invalid @enderror"
                                    id="total_points" placeholder="" name="total_points" readonly>
                                @error('total_points')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group my-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="phone_number">+880</span>
                                </div>
                                <input type="text" class="form-control @error('customer_mobile') is-invalid @enderror"
                                    id="customer_mobile" placeholder="Phone no(e.g 017xxxxxxxx)" name="customer_mobile">
                                @error('customer_mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group my-2">
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                    id="customer_name" placeholder="Customer Name" name="customer_name">
                                @error('customer_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group my-2">
                                <textarea class="form-control" id="customer_address" rows="3" name="customer_address"
                                    placeholder="Customer Address"></textarea>
                            </div>

                            <div class="form-group my-2">
                                <textarea class="form-control" id="customer_notes" rows="3" name="customer_notes"
                                    placeholder="Customer Notes"></textarea>
                            </div>
                            <div class="form-group mt-2 mb-4">
                                <button type="submit" class="btn btn-primary text-uppercase"
                                    style="display:block; width:100%;"
                                    {{ $order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'receive' ? '' : 'disabled' }}
                                    id="submit">Update
                                    Order </button>
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
<script src="{{ asset('backend') }}/js/pos/zip.js"></script>
<script src="{{ asset('backend') }}/js/pos/zip-ext.js"></script>
<script src="{{ asset('backend') }}/js/pos/deflate.js"></script>
<script src="{{ asset('backend') }}/js/pos/JSPrintManager.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script type="text/javascript">
    var duePay = 0; //global declaration  
    var paymentMethod = 'notDefined';
    var moreDue = 0;
    var moreDueMethod = 'notDefined';  
    var methods = [];     


    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){
        
     // POS system starts here   
        $("#customer_mobile").autocomplete({
        //Using source option to send AJAX post request to route('employees.getEmployees') to fetch data
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{ route('admin.sales.customermobile') }}",
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
           $('#customer_mobile').val(ui.item.label); // display the selected text           
           fillCustomerData(ui.item.label);
           return false;
        }
      });

      function fillCustomerData(customerMobile){
        $.post("{{ route('admin.sales.customerInfo') }}", {        
            _token: CSRF_TOKEN,
            mobile: customerMobile            
        }).done(function(data) { 
            //console.log(data)
            $('#customer_name').val(data[0].name);
            $('#customer_address').val(data[0].address);        
            $('#customer_notes').val(data[0].notes); 
            $('#total_points').val(data[0].total_points);
            //reward points breaking rule:
            if(data[0].total_points >=100){
                $('#reward-point-blk').removeClass('d-none');
                $('#reward-discount').text((Math.round(data[0].total_points * '{{ config('settings.point_to_money') }}')).toFixed(2));
            }
        });
        }

        // pos system discount       
       $('#discount').on('input', function() {
           if($.isNumeric( $.trim($('#discount').val() ))){
            orderTotal = $('#total-tk').text();
            discount = $.trim($('#discount').val());
            //getting reward discount 
            var rewardDiscount = $("#reward-discount").text(); 
            //getting the director id value.
            directorId = $('#discount_reference :selected').val();
            if(directorId){
                //checking the discount slab of the corresponding director via ajax call
                checkDiscountSlab(discount, directorId, orderTotal);
            } 
            //calculating due amount  
            if($("#reward_point_check").prop("checked") == true) {
                dueAmount = orderTotal - (Number(discount) + Number(rewardDiscount));
            }else{
                dueAmount = orderTotal - discount;
            }
            //setting due amount 
            $('#due-tk').text(dueAmount); 
            // setting discount data to order_discount form hidden input field
            $('#order_discount').val(discount);
            // if the entered data is digit, we just clear the focus
            $('#discount').css({
                        "border": "",
                        "background": ""
            });
           }else{
               // if the entered data is not digit, we just focus on it
                    $('#discount').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
           }
        });


         // if discount reference is changed, checkDiscountSlab is called 
         $('#discount_reference').change(function() {            
            orderTotal = $('#total-tk').text();
            discount = $.trim($('#discount').val()); 
            //getting the director id value.
            directorId = $('#discount_reference :selected').val();
            // setting discount reference as director id to form hidden input field
            $('#order_discount_reference').val(directorId);             
            if(directorId){
                //checking the discount slab of the corresponding director via ajax call
                checkDiscountSlab(discount, directorId, orderTotal);
            }

        });

        
    
        
        //checking the discount slab of the corresponding director via ajax call
        function checkDiscountSlab(discount, directorId, orderTotal){
            $.post("{{ route('admin.sales.discountSlab') }}", {        
                _token: CSRF_TOKEN,
                discount: discount,
                directorId: directorId,
                orderTotal: orderTotal       
            }).done(function(data) { 
                data = JSON.parse(data);
                if(data.status == "success") {
                    if(data.discount > data.discountLimit){
                        $('#discount-limit').removeClass('d-none');                        
                    }else{
                        $('#discount-limit').addClass('d-none');                        
                    }
                }                
            });            
        }      


        // if discount checkbox or reward point checkbox anychange is made during the payment calculation, we will reset everything.
        // so in that case we need to do the calculation again.
        $("#discount_check").change(function(){
            resetPayment();
        });

        $("#reward_point_check").change(function(){
            resetPayment();
        });

        // $("#product_search").on('input', function() {
        //     resetPayment();
        // });

              
        // POS System: Cash payment:
        // note: duePay and paymentMethod are globally declared.
        $('#cash_pay').on('input', function() { 
            
            if($.isNumeric( $.trim($('#cash_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#cash_pay').css({
                        "border": "",
                        "background": ""
                });

                dueTotal = 0;
                cashPay = $.trim($('#cash_pay').val());
                // avoiding for cash payment 0 tk.
                if(cashPay != ''){
                    // when no moreDue exists, dueTotal would be duePay. 
                    // [ Note: after 1st payment if due exists, it is duePay, after 2nd payment if due exist. it is moreDue]               
                    dueTotal = (moreDueMethod == 'card' || moreDueMethod == 'mobile')  ? moreDue : $('#due-tk').text();  
                    if(dueTotal == moreDue){
                        dueTotal -= cashPay;
                            if(dueTotal == 0){ 
                                // when no dues after the payment, we will show the payment details               
                                noDueTotal();
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-change-block').removeClass('d-none');
                                $('#pay-change').html(~dueTotal+1);
                                // setting cash payment to form hidden input field
                                var afterChangePayCash =  cashPay - (~dueTotal+1);
                                $('#pay_cash').val( afterChangePayCash );
                            }
                    }else{                    
                        // when no payment is done, dueTotal will be DueAmount (#due-tk)
                        dueTotal = (paymentMethod == 'card' || paymentMethod == 'mobile')  ? duePay : $('#due-tk').text();                         
                        if(dueTotal == duePay){ // for due payment
                            dueTotal -= cashPay;
                            if(dueTotal == 0){ 
                                // when no dues after the payment, we will show the payment details               
                                noDueTotal();
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-change-block').removeClass('d-none');
                                $('#pay-change').html(~dueTotal+1);
                                // setting cash payment to form hidden input field
                                var afterChangePayCash =  cashPay - (~dueTotal+1);
                                $('#pay_cash').val( afterChangePayCash );
                            }else if(dueTotal > 0){ 
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#pay-more').html(dueTotal);
                                moreDue = dueTotal;
                                moreDueMethod = "cash";                             
                            }               
                        }else{
                                dueTotal -= cashPay; // 500-300 = 200
                                paymentMethod = 'cash';                
                            if(dueTotal == 0){ 
                                // when no dues after the payment, we will show the payment details               
                                noDueTotal();
                            }else if(dueTotal > 0){ 
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#pay-more').html(dueTotal);  
                                duePay = dueTotal;  // total due after cash payment.
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none'); 
                                $('#total-pay-block').addClass('d-none');  
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-change-block').removeClass('d-none');
                                $('#pay-change').html(~dueTotal+1);  
                                // setting cash payment to form hidden input field
                                var afterChangePayCash =  cashPay - (~dueTotal+1);
                                $('#pay_cash').val( afterChangePayCash );                                 
                            }

                        } //end of if

                    }  

                    // setting cash payment to form hidden input field
                    if(dueTotal >= 0){
                        $('#pay_cash').val($.trim($('#cash_pay').val()));
                    }
                    //setting payment method to form hidden input field
                    methods.push('cash');
                    //filtering only unique values
                    var uniqueMethods = methods.filter( onlyUnique );
                    $('#payment_method').val(uniqueMethods);
                    
                }else{ // if user does not any input or null on cash payment
                    
                $('#pay-more-block').addClass('d-none');
                $('#pay-change-block').addClass('d-none'); 
                $('#donot-change-block').addClass('d-none');
                $('#total-pay-block').addClass('d-none');                 
                }

            }else{
               // if the entered data is not digit, we just focus on it
                    $('#cash_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    resetPayment();
             }
            
        });

        // POS System: Card payment 
        $('#card_pay').on('input', function() {    

            if($.isNumeric( $.trim($('#card_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#card_pay').css({
                        "border": "",
                        "background": ""
                });                   
                dueTotal = 0;          
                cardPay = $.trim($('#card_pay').val());            
                // avoiding for card payment 0 tk.
                if(cardPay != ''){ 
                // [ Note: after 1st payment if due exists, it is duePay, after 2nd payment if due exist. it is moreDue]               
                dueTotal = (moreDueMethod == 'cash' || moreDueMethod == 'mobile')  ? moreDue : $('#due-tk').text(); 
                    
                    if(dueTotal == moreDue){
                        dueTotal -= cardPay;
                            if(dueTotal == 0){   
                                // when no dues after the payment, we will show the payment details             
                                noDueTotal();
                            }
                    }else{
                    // when no payment is done, dueTotal will be Due Amount (#due-tk)
                        dueTotal = (paymentMethod == 'cash' || paymentMethod == 'mobile')  ? duePay : $('#due-tk').text();             
                        if(dueTotal == duePay){ // for due payment
                            dueTotal -= cardPay;
                            if(dueTotal == 0){  
                                // when no dues after the payment, we will show the payment details              
                                noDueTotal();
                            }else if(dueTotal > 0){ 
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more').html(dueTotal); 
                                moreDue = dueTotal;
                                moreDueMethod = "card";  
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').removeClass('d-none');
                                $('#donot-change').html(~dueTotal+1);                                
                            }
                        }else{  
                            dueTotal -= cardPay;          
                            if(dueTotal == 0){ 
                                // when no dues after the payment, we will show the payment details               
                                noDueTotal();
                            }else if(dueTotal > 0){ // 500-300 = 200                            
                                $('#pay-change-block').addClass('d-none'); 
                                $('#total-pay-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more').html(dueTotal);                              
                                duePay = dueTotal; 
                                paymentMethod = 'card';            
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');                                   
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').removeClass('d-none');
                                $('#donot-change').html(~dueTotal+1);                                
                            }
                        } //end of if            
                    }

                    //setting payment method to form hidden input field
                    methods.push('card');                    
                    //filtering only unique values
                    var uniqueMethods = methods.filter( onlyUnique );
                    $('#payment_method').val(uniqueMethods);
                    // setting cash payment to form hidden input field
                    $('#pay_card').val($.trim($('#card_pay').val()));

                }else{                  
                    $('#pay-more-block').addClass('d-none');
                    $('#pay-change-block').addClass('d-none'); 
                    $('#donot-change-block').addClass('d-none');
                    $('#total-pay-block').addClass('d-none'); 
                }
            }else{
               // if the entered data is not digit, we just focus on it
                    $('#card_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    resetPayment();
             }
            
        });

        // mobile banking payment 
        $('#mobile_banking_pay').on('input', function() { 

            if($.isNumeric( $.trim($('#mobile_banking_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#mobile_banking_pay').css({
                        "border": "",
                        "background": ""
                });
                dueTotal = 0;
                mobilePay = $.trim($('#mobile_banking_pay').val());             
                //  avoiding for mobile banking payment 0 tk.
                if(mobilePay != ''){
                    // [ Note: after 1st payment if due exists, it is duePay, after 2nd payment if due exist. it is moreDue]               
                dueTotal = (moreDueMethod == 'cash' || moreDueMethod == 'card')  ? moreDue : $('#due-tk').text();  
                    if(dueTotal == moreDue){
                        dueTotal -= mobilePay;
                            if(dueTotal == 0){  
                                // when no dues after the payment, we will show the payment details              
                                noDueTotal();
                            }
                    }else{
                    // when no payment is done, dueTotal will be Due Amount (#due-tk)
                        dueTotal = (paymentMethod == 'cash' || paymentMethod == 'card')  ? duePay : $('#due-tk').text();
                        if(dueTotal == duePay){ // for due payment
                            dueTotal -= mobilePay;
                            if(dueTotal == 0){  
                                // when no dues after the payment, we will show the payment details              
                                noDueTotal();
                            }else if(dueTotal > 0){ 
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more').html(dueTotal); 
                                moreDue = dueTotal;
                                moreDueMethod = "mobile";  
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');   
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').removeClass('d-none');
                                $('#donot-change').html(~dueTotal+1);                                
                            }
                        }else{  
                            dueTotal -= mobilePay;          
                            if(dueTotal == 0){ 
                                // when no dues after the payment, we will show the payment details               
                                noDueTotal();
                            }else if(dueTotal > 0){ // 500-300 = 200  
                                $('#pay-change-block').addClass('d-none'); 
                                $('#total-pay-block').addClass('d-none');
                                $('#pay-more-block').removeClass('d-none');
                                $('#donot-change-block').addClass('d-none');
                                $('#pay-more').html(dueTotal);                              
                                duePay = dueTotal;  // total due after cash payment.
                                paymentMethod = 'mobile';            
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                $('#pay-more-block').addClass('d-none');   
                                $('#pay-change-block').addClass('d-none');
                                $('#total-pay-block').addClass('d-none');
                                $('#donot-change-block').removeClass('d-none');
                                $('#donot-change').html(~dueTotal+1);                                
                            }
                        } //end of if
                    }

                    //setting payment method to form hidden input field
                    methods.push('mobile banking');                    
                    //filtering : to get only unique values
                    var uniqueMethods = methods.filter( onlyUnique );
                    $('#payment_method').val(uniqueMethods);
                    
                    // setting cash payment to form hidden input field
                    $('#pay_mobile').val($.trim($('#mobile_banking_pay').val()));


                }else{              
                    $('#pay-more-block').addClass('d-none');
                    $('#pay-change-block').addClass('d-none'); 
                    $('#donot-change-block').addClass('d-none');
                    $('#total-pay-block').addClass('d-none'); 
                }
            }else{
               // if the entered data is not digit, we just focus on it
                    $('#mobile_banking_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    resetPayment();
             }
            
        });
        // when no dues after the payment, we will show the payment details
        function noDueTotal(){
            cash = $.trim($('#cash_pay').val());                
            card = $.trim($('#card_pay').val());
            mobile = $.trim($('#mobile_banking_pay').val()); 
            Tk = " {{ config('settings.currency_symbol') }}, "; // money symbol

            cashPay = cash ? 'Cash = ' + cash + Tk : '';
            cardPay = card ? 'Card = ' + card + Tk : '';
            mobilePay = mobile ? 'Mobile Banking = ' + mobile + Tk : '';             
            totalPay = cashPay + cardPay + mobilePay;

            $('#pay-more-block').addClass('d-none');
            $('#pay-change-block').addClass('d-none'); 
            $('#donot-change-block').addClass('d-none');
            $('#total-pay-block').removeClass('d-none'); 
            $('#pay-details').html(totalPay);
        }
        
        // while submit cilck, validating the discount_reference field.
        // this discount and discount reference fields are not inside form    
        $('#submit').click(function(e){
            var isValid = true;
            //when discount checkbox is true
            if($("#discount_check").prop("checked") == true) { 

                //when both field is empty.
                if($.trim($('#discount_reference').val()) == '' && $.trim($('#discount').val()) == ''){
                    isValid = false;
                    $('#discount_reference').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    });  
                    $('#discount').css({
                    "border": "2px solid #007065",
                    "background": "#e4f5f3"
                    });   
                }else{

                    $('#discount_reference').css({
                        "border": "",
                        "background": ""
                    });
                    $('#discount').css({
                        "border": "",
                        "background": ""
                    });

                } 

                //when discount field is not numeric.
                if(!$.isNumeric( $.trim( $('#discount').val() ) ) ){
                    isValid = false;
                    $('#discount').css({
                    "border": "2px solid #007065",
                    "background": "#e4f5f3"
                    });                 

                }else{
                    $('#discount').css({
                        "border": "",
                        "background": ""
                    });
                }
                //when discount reference field is empty.
                if ($.trim($('#discount_reference').val()) == '' && $.trim($('#discount').val()) != '') {
                    isValid = false;
                    $('#discount_reference').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    });               
                
                }else{
                    $('#discount_reference').css({
                        "border": "",
                        "background": ""
                    });
                }
                    
            } 

            // if no payments checkbox is checked
            if($('.payments:checkbox:checked').length == 0){
                isValid = false;
                $('.payments').css({
                    "outline": "1px solid #ff0000"                 
                });
            }else{

                $('.payments').css({
                    "outline": "none"                  
                });
                
            }
            // if payments checkbox is checked but all of them payments textbox are empty.
            if($('.payments:checkbox:checked').length != 0 && ( $.trim($('#cash_pay').val()) == '' &&            
            $.trim($('#card_pay').val()) == '' && $.trim($('#mobile_banking_pay').val()) == '')){
                isValid = false;
                    $('#cash_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    });   
            }else{
                $('#cash_pay').css({
                        "border": "",
                        "background": ""
                 });   
            }
            

            if (isValid == false)
                e.preventDefault();
        });

        
    });
        //pos system discount option is showing or hiding.
        function discountCheck(){             
            if($("#discount_check").prop("checked") == true) { 
                $('#discount-lbl').css('margin-right','5px');
                $('.cash-discount').addClass('discount-w');
                $('#discount_reference').show();
                $('#discount').show();  
                $('#discount-blk').removeClass('pt-1');                
            }else{
                //when unchecked chkbox we set discount to null and due amount to order total.
                $('#discount-lbl').css('margin-right','-15px');               
                $('#discount_reference').removeAttr("style").hide();
                $('#discount').removeAttr("style").hide(); 
                $('.cash-discount').removeClass('discount-w');                 
                $('#discount').val("");
                if($("#reward_point_check").prop("checked") == true){
                    $('#due-tk').html($("#total-tk").html() - $("#reward-discount").text());
                }else{
                    $('#due-tk').html($("#total-tk").html());
                } 
                $('#discount-blk').addClass('pt-1');           
            }           
        }

        

         function cashCheck(){             
            if($("#cash_check").prop("checked") == true) {                               
                $('.cash-payment').addClass('discount-w pl-1');               
                $('#cash_pay').show();  
                $('#cash_blk').removeClass('pt-1');               
            }else{
                // //when unchecked chkbox we set discount to null and due amount to order total.              
                $('#cash_pay').removeAttr("style").hide(); 
                $('.cash-payment').removeClass('discount-w pl-1');                 
                $('#cash_pay').val("");                
                $('#cash_blk').addClass('pt-1');                
            }           
        }

        function cardCheck(){             
            if($("#card_check").prop("checked") == true) {                               
                $('.card-payment').addClass('discount-w pl-1');               
                $('#card_pay').show();
                $('#bank_reference').show();   
                $('#card_blk').removeClass('pt-1');  
                $('#card_blk_lbl').css('margin-right', '');  
                                                     
            }else{
                // //when unchecked chkbox we set discount to null and due amount to order total.              
                $('#card_pay').removeAttr("style").hide();                 
                $('#bank_reference').removeAttr("style").hide();
                $('.card-payment').removeClass('discount-w pl-1');                 
                $('#card_pay').val("");                
                $('#card_blk').addClass('pt-1');    
                $('#card_blk_lbl').css('margin-right', '-1em');            
            }           
        }

        // if card bank_reference is changed 
        $('#bank_reference').change(function() { 
            cardBank = $('#bank_reference :selected').val();
            // setting card bank name to form hidden input field
            if(cardBank){
                $('#cardbank').val(cardBank);
            }  
        });

        

        function rewardPoint(){            
            var dueAmount = 0;     
            var discount =  $.trim($('#discount').val());
            var orderTotal = $("#total-tk").html();
            var rewardDiscount = $("#reward-discount").text();            
            if($("#reward_point_check").prop("checked") == true) {                 
                if(discount){
                    dueAmount = orderTotal - (Number(discount) + Number(rewardDiscount));
                }else{
                    dueAmount = orderTotal - rewardDiscount;
                }               
                $('#due-tk').text(dueAmount);
                // setting reward_point_discount to form hidden input field
                $('#reward_point_discount').val(rewardDiscount);
              
            }else{
                if(discount){
                    dueAmount = orderTotal - discount;
                }else{
                    dueAmount = orderTotal;
                }               
                $('#due-tk').text(dueAmount);
                // setting reward_point_discount to form hidden input field
                $('#reward_point_discount').val(0);
            }
        }

        function mobileBankCheck(){             
            if($("#mobileBank_check").prop("checked") == true) {                               
                $('.mobileBank-payment').addClass('discount-w pl-1');               
                $('#mobile_banking_pay').show();  
                $('#mobileBank_blk').removeClass('pt-1'); 
                $('#mobileBank_blk_lbl').css('margin-right', ''); 
                $('#mobibank_reference').show();
            }else{
                // //when unchecked chkbox we set discount to null and due amount to order total.              
                $('#mobile_banking_pay').removeAttr("style").hide(); 
                $('#mobibank_reference').removeAttr("style").hide();
                $('.mobileBank-payment').removeClass('discount-w pl-1');                 
                $('#mobile_banking_pay').val("");                  
                $('#mobileBank_blk').addClass('pt-1'); 
                $('#mobileBank_blk_lbl').css('margin-right', '-1em');
            }           
        }

        // if mobile bank_reference is changed 
        $('#mobibank_reference').change(function() { 
            mobiBank = $('#mobibank_reference :selected').val();
            // setting mobile bank name to form hidden input field
            if(mobiBank){
                $('#mobibank').val(mobiBank);
            }  
        });

        // whenever any change is made on product search or discount we use this function.
        function resetPayment(){
            // resetting all the global variables which are needed to calculate.
            duePay = 0; 
            paymentMethod = 'notDefined';
            moreDue = 0;
            moreDueMethod = 'notDefined';
            methods = [];
            // now resetting cashpay, cardpay, mobilePay amounts and the dues after the payemnts
            $('#pay-more').html('');
            $('#pay-change').html('');
            $('#pay-details').text('');  
            $('#pay-change-block').addClass('d-none'); 
            $('#pay-more-block').addClass('d-none');
            $('#total-pay-block').addClass('d-none');
            // hiding the cashPayment checbox and cashpay to null
            // $("#cash_check").prop("checked", false);
            // $('#cash_pay').removeAttr("style").hide(); 
            // $('.cash-payment').removeClass('discount-w pl-1');                 
            $('#cash_pay').val("");                
            //$('#cash_blk').addClass('pt-1'); 
            // hiding the cardPayment checbox and cardPay to null 
            // $("#card_check").prop("checked", false);
            // $('#card_pay').removeAttr("style").hide(); 
            // $('.card-payment').removeClass('discount-w pl-1');                 
            $('#card_pay').val("");                
           // $('#card_blk').addClass('pt-1'); 
            // hiding the mobileBankPayment checbox and mobilePay to null 
            // $("#mobileBank_check").prop("checked", false);
            // $('#mobile_banking_pay').removeAttr("style").hide(); 
            // $('.mobileBank-payment').removeClass('discount-w pl-1');                 
            $('#mobile_banking_pay').val("");                  
            //$('#mobileBank_blk').addClass('pt-1');


        }

        // Get all unique values in a JavaScript array (remove duplicates)
        function onlyUnique(value, index, self) { 
            return self.indexOf(value) === index;
        }

      
    

  //POS PRINT RECEIPT : https://www.neodynamic.com/articles/How-to-print-raw-ESC-POS-commands-from-Javascript/
  
  //WebSocket settings
  JSPM.JSPrintManager.auto_reconnect = true;
    JSPM.JSPrintManager.start();
    JSPM.JSPrintManager.WS.onStatusChanged = function () {
        if (jspmWSStatus()) {
            //get client installed printers
            JSPM.JSPrintManager.getPrinters().then(function (myPrinters) {
                var options = '';
                for (var i = 0; i < myPrinters.length; i++) {
                    options += '<option>' + myPrinters[i] + '</option>';
                }
                $('#installedPrinterName').html(options);
            });
        }
    };
 
    //Check JSPM WebSocket status
    function jspmWSStatus() {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
            return true;
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
            alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
            alert('JSPM has blocked this website!');
            return false;
        }
    }
 
    //Do printing...
    function print(o) {
        if (jspmWSStatus()) {
            //Create a ClientPrintJob
            var cpj = new JSPM.ClientPrintJob();
            //Set Printer type (Refer to the help, there many of them!)
            if ($('#useDefaultPrinter').prop('checked')) {
                cpj.clientPrinter = new JSPM.DefaultPrinter();
            } else {
                cpj.clientPrinter = new JSPM.InstalledPrinter($('#installedPrinterName').val());
            }
            //Set content to print...
            //Create ESP/POS commands for sample label
            var esc = '\x1B'; //ESC byte in hex notation
            var newLine = '\x0A'; //LF byte in hex notation            
            var cmds = esc + "@"; //Initializes the printer (ESC @)
            cmds += esc + '!' + '\x30'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            cmds += "   {{ config('settings.site_name') }}"; //text to print site name
            cmds += newLine;
            cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex            
            cmds += newLine;
            cmds += "{{ config('settings.site_title') }}"; //text to print site title
            cmds += newLine;
            cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
            cmds += "---------------------------------------";
            cmds += newLine;            
            cmds += "{{ config('settings.contact_address') }}"; //text to print site address
            cmds += newLine;
            cmds += "Contact no: {{ config('settings.phone_no') }}";
            cmds += newLine;
            cmds += "Date: {{ date('d-M-Y h:i:s A') }}";
            cmds += newLine;
            cmds += "---------------------------------------";
            cmds += newLine;
            cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            cmds += "Customer Order Table No: {{ $order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'delivered' ? App\Models\Salebackup::where('ordersale_id', $order_id)->first()->order_tbl_no : ''}}"
            cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
            cmds += newLine;
            cmds += "---------------------------------------";
            cmds += newLine;
            cmds += "#Item     #Qty     #Price     #subtotal";
            cmds += newLine;
            cmds += "---------------------------------------";
            cmds += newLine;
            @php $sub_tot_without_vat = 0.0;
                 $vat_percentage = config('settings.tax_percentage');
            @endphp
            @if($order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'delivered')
            @php $paid_amount = App\Models\Ordersale::find($order_id)->cash_pay + App\Models\Ordersale::find($order_id)->card_pay +
            App\Models\Ordersale::find($order_id)->mobile_banking_pay; @endphp
            @foreach(App\Models\Salebackup::where('ordersale_id', $order_id)->get() as $saleCart)        
                cmds += "{{ $saleCart->product_name }}" ;
                cmds += newLine;
                cmds += "           {{$saleCart->product_quantity}}" + '   X   ' + "{{ round($saleCart->unit_price,2) }}" + "      {{ round($saleCart->product_quantity *  $saleCart->unit_price,2) }} "
                cmds += newLine;                
                cmds += "---------------------------------------";
                cmds += newLine;            
                @php $sub_tot_without_vat += $saleCart->product_quantity *  $saleCart->unit_price; @endphp                
            @endforeach
            @endif 
            cmds += "Subtotal Without VAT:          {{ $sub_tot_without_vat }}";
            cmds += newLine;
            @php $food_vat = $sub_tot_without_vat * ($vat_percentage)/100; @endphp
            @if(config('settings.tax_percentage'))
            cmds += "        ++ VAT ({{ $vat_percentage }}%):           {{$food_vat}}";
            cmds += newLine;
            @endif            
            cmds += "                          -------------";
            cmds += '           Total Amount:          {{ $sub_tot_without_vat + $food_vat }}';
            cmds += newLine;
            cmds += '           -Discount:           {{ $order_id ? round(App\Models\Ordersale::find($order_id)->discount,2) : '' }}';
            cmds += newLine;
            @if($order_id && App\Models\Ordersale::find($order_id)->reward_discount)
            cmds += '           -Reward Discount:     {{ $order_id ? round(App\Models\Ordersale::find($order_id)->reward_discount,2) : '' }}';
            cmds += newLine;
            @endif
            cmds += "                          -------------";
            cmds += '             Amount Due:          {{ $order_id ? round(App\Models\Ordersale::find($order_id)->grand_total,2) : '' }}'; 
            cmds += newLine;           
            cmds += "                Paid:          {{ $order_id && App\Models\Ordersale::where('id', $order_id)->first()->status == 'delivered' ? $paid_amount : '' }}";
            cmds += newLine; 
            cmds += 'Payment Mode:          ';
            cmds += newLine; 
            cmds += "---------------------------------------";
            cmds += newLine;
            @if($order_id)
            @if(App\Models\Ordersale::find($order_id)->cash_pay)
            cmds += 'CASH:                          {{ $order_id ? round(App\Models\Ordersale::find($order_id)->cash_pay,2) : '' }}';
            cmds += newLine;
            @endif
            @if(App\Models\Ordersale::find($order_id)->card_pay)
            cmds += 'CARD:                          {{ $order_id ? round(App\Models\Ordersale::find($order_id)->card_pay,2) : '' }}';
            cmds += newLine;
            @endif
            @if(App\Models\Ordersale::find($order_id)->mobile_banking_pay)
            cmds += 'Mobile Banking:                {{ $order_id ? round(App\Models\Ordersale::find($order_id)->mobile_banking_pay,2) : '' }}';
            cmds += newLine;
            @endif
            @endif
            cmds += "---------------------------------------";
            cmds += newLine;
            @if($order_id)
            @if(App\Models\Ordersale::find($order_id)->discount)
            cmds += 'Reference Discount:            {{ $order_id ? round(App\Models\Ordersale::find($order_id)->discount,2) : '' }}';
            cmds += newLine;
            cmds += "---------------------------------------";
            @endif
            cmds += newLine;
            @if(App\Models\Ordersale::find($order_id)->reward_discount)
            cmds += 'Other Discount:                 {{ $order_id ? round(App\Models\Ordersale::find($order_id)->reward_discount,2) : '' }}';
            cmds += newLine;
            cmds += "---------------------------------------";
            cmds += newLine;
            @endif
            cmds += 'You Have Saved:                {{ $order_id ? round((App\Models\Ordersale::find($order_id)->discount + App\Models\Ordersale::find($order_id)->reward_discount),2)  : '' }}';            
            @endif
            cmds += newLine + newLine;
            cmds += 'Note: Sold food items can not be refunded';
            cmds += newLine;
            cmds += 'but may be exchanged only within 2 hours';
            cmds += newLine;
            cmds += 'with relevant receipt. For any inquiry,';
            cmds += newLine;
            cmds += 'please contact with us. Thanks for coming';
            cmds += newLine;
            cmds += 'to Amana Funville.';
            cmds += newLine + newLine;
            cmds += esc + '!' + '\x18'; //Emphasized + Double-height mode selected (ESC ! (16 + 8)) 24 dec => 18 hex
            
            cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
            cmds += newLine + newLine;
            cmds += newLine + newLine;
            cmds += newLine + newLine;
            cmds += "\x1b" + "\x69"; // cut command for pos print receipt.
            cpj.printerCommands = cmds;
            //Send print job to printer!
            cpj.sendToClient();
        }
    }

</script>
@endpush