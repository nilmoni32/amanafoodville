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
                        <form action="{{ route('admin.due.sales.search') }}" method="get" autocomplete="off">
                            @csrf
                            <div class="row mt-2">
                                <div class="col-md-4 text-right">
                                    <label class="col-form-label font-weight-bold text-uppercase">Order No:</label>
                                </div>
                                <div class="col-md-5 ml-0">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Search by Order No"
                                            name="search"
                                            value="{{ $order_id ? App\Models\Dueordersale::where('id', $order_id)->first()->order_number : '' }}">
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
                                    @if($order_id && App\Models\Duesale::where('dueordersale_id', $order_id)->first() && App\Models\Dueordersale::where('id',
                                    $order_id)->first()->status == 'receive')
                                    <tbody>
                                        @foreach( App\Models\Duesale::where('dueordersale_id',
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
                                    <div class="col-sm-12" style="visibility:{{ $order_id && App\Models\Duesale::where('dueordersale_id', $order_id)->first() && App\Models\Dueordersale::where('id',
                                    $order_id)->first()->status == 'receive' ? 'visible': 'hidden' }};" id="total">
                                        <h5 class="text-right pb-3 border-bottom">Subtotal :
                                            <span id="sub-total-tk">{{ $total_taka }}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        @if(config('settings.tax_percentage'))
                                        <h5 class="text-right pb-3 pt-1 border-bottom">
                                            <div class="row mr-1">
                                                <div class="col-8 pr-0 text-right">Vat
                                                    ({{ config('settings.tax_percentage')}}%):
                                                    <span
                                                        id="vat-percent">{{ $total_taka * (config('settings.tax_percentage')/100) }}</span><span
                                                        class="pr-2 pl-1">{{ config('settings.currency_symbol') }}, </span>
                                                </div>
                                                <div class="col-4 pr-0 text-right">Subtotal with Vat:
                                                    <span
                                                        id="vat-subtotal">{{ $total_taka + $total_taka * (config('settings.tax_percentage')/100) }}</span><span
                                                        class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                                </div>
                                            </div>
                                            
                                        </h5>
                                        @endif
                                        <h5 class="text-right pb-3 pt-1 border-bottom">
                                            @php $number = floatval($total_taka + $total_taka * (config('settings.tax_percentage')/100)) @endphp
                                            @if(!(floor($number) == $number))
                                            <div class="row mr-1">
                                                <div class="col-5 pr-0 text-right">
                                                    Received Amount: <span id="amount-receive" class="py-2 mt-1">{{  $order_id ? round(App\Models\Dueordersale::where('id',
                                                        $order_id)->first()->receive_total,2) : 0 }} ,</span>
                                                </div>
                                                <div class="col-3 pr-0 text-right">
                                                    Fraction Discount: <span id="fraction-due" class="py-2 mt-1">{{ round(($number - floor($number)),2) }} ,</span>
                                                </div>
                                                <div class="col-4 pr-0 pl-0">
                                                    Total Amount Payable: <span id="total-tk">
                                                        {{ floor($number) - ($order_id ? round(App\Models\Dueordersale::where('id',
                                                        $order_id)->first()->receive_total,2) : 0)  }}</span><span
                                                        class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span></span>
                                                </div>
                                            </div>
                                            @else 
                                            <div class="row mr-1">
                                                <div class="col-8 pr-0 text-right">
                                                    Received Amount: <span id="amount-receive" class="py-2 mt-1">{{  $order_id ? round(App\Models\Dueordersale::where('id',
                                                        $order_id)->first()->receive_total,2) : 0 }}</span>
                                                </div>
                                                
                                                <div class="col-4 pr-0 pl-0">
                                                    Total Amount Payable : <span
                                                id="total-tk">{{ $total_taka + ($total_taka * (config('settings.tax_percentage')/100)) - ($order_id ? round(App\Models\Dueordersale::where('id',
                                                $order_id)->first()->receive_total,2) : 0) }}</span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                                </div>                                                
                                            </div>                                            
                                            @endif
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="discount-blk">
                                            <label id="discount-lbl" style="margin-right:-15px; cursor:pointer;"><input
                                                    type="checkbox" class="radio-inline" name="discount"
                                                    id="discount_check" onclick="discountCheck()">
                                                Reference Discount :
                                            </label>
                                            <div class="form-check form-check-inline cash-discount">                                                
                                                <select name="director_id" id="discount_reference"
                                                    class="form-control font-weight-normal" style="display:none;">
                                                    {{-- <option value="" disabled selected>Select Discount Reference
                                                    </option>                                                     --}}
                                                    @foreach( App\Models\Director::orderBy('name', 'asc')->get() as
                                                    $director)  
                                                    <option></option>                                                 
                                                    <option value="{{ $director->id }}">{{ $director->mobile }}</option>
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
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="gpstar-blk">
                                            <label id="gpstar-lbl" style="margin-right:-15px; cursor:pointer;"><input
                                                    type="checkbox" class="radio-inline" name="gpstar"
                                                    id="gpstar_check" onclick="gpstarCheck()">{{ __(' GP Star Discount : ') }}
                                            </label>                                             
                                            <div class="form-check form-check-inline star-discount">
                                                <input type="text" class="form-control" id="gpstarmobile_no"
                                                    placeholder="GP Star Mobile no" name="gpstarmobile_no" style="display:none;">
                                            </div>
                                            <div class="form-check form-check-inline star-discount mr-5" id="gp-ref-blk">                                                
                                                <select name="gpstar_id" id="gpstar_ref"
                                                    class="form-control font-weight-normal" style="display:none;">
                                                    <option value="" disabled selected>Select GP Star Reference</option>
                                                    @foreach( App\Models\Gpstardiscount::where('status', 'Active')->orderBy('gp_star_name', 'asc')->get() as
                                                    $gpstar)                                                                                           
                                                    <option value="{{ $gpstar->id }}">{{ $gpstar->gp_star_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                            
                                            <div class="form-check form-check-inline d-none" id="gp-discount-blk">
                                                <label id="gpstar-discount-lbl" style="cursor:pointer; margin-right:2.3em;">
                                                    {{ __('Amount: ') }}<span id="gp-discount"></span>
                                                    {{ config('settings.currency_symbol') }}
                                                </label>
                                            </div>                                           
                                        </h5>                                                                               
                                        <h5 class="text-right pb-2 pt-1 border-bottom d-none" id="reward-point-blk">
                                            <label id="discount-point-lbl"
                                                style="margin-right:-15px; cursor:pointer; margin-right:3em;"><input
                                                    type="checkbox" class="radio-inline" name="reward_discount"
                                                    id="reward_point_check" onclick="rewardPoint()"> Use Reward points:
                                                <span class="ml-1" id="reward-discount"></span>
                                                {{ config('settings.currency_symbol') }}
                                            </label>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom d-none" id="card-discount-blk">
                                            <label id="card-discount-lbl" style="margin-right:-15px; cursor:pointer; margin-right:3em;">
                                                {{ __('Card Discount: ') }}<span class="ml-1" id="card-discount"></span>
                                                {{ config('settings.currency_symbol') }}
                                            </label>
                                        </h5>
                                        <h5 class="text-right pb-3 pt-1 border-bottom">Net Amount Payable: <span id="due-tk">
                                            {{ floor($total_taka + $total_taka * (config('settings.tax_percentage')/100) - ($order_id ? round(App\Models\Dueordersale::where('id',
                                            $order_id)->first()->receive_total,2) : 0))  }}</span><span
                                            class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="cash_blk">
                                            <label style="cursor:pointer;"><input type="checkbox"
                                                    class="radio-inline payments" name="cash_check" id="cash_check"
                                                    onclick="cashCheck()"> Cash Payment :
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
                                                    onclick="cardCheck()"> Card Payment :
                                            </label>                                           
                                            <div class="form-check form-check-inline card-payment">
                                                <select name="bank_reference" id="bank_reference"
                                                        class="form-control font-weight-normal" style="display:none;">
                                                        <option value="" disabled selected>Select a CardBank
                                                        </option>
                                                        @foreach( App\Models\Paymentgw::where('bank_type', 'card')->orderBy('bank_name', 'asc')->get() as
                                                        $payment_gw)
                                                        <option value="{{ $payment_gw->id }}">{{ $payment_gw->bank_name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                            <div class="form-check form-check-inline card-payment" 
                                            style="margin-right:3em">
                                                <input type="text" class="form-control" id="card_pay"
                                                    placeholder="Amount (Required)" name="card_pay"
                                                    style="display:none;">
                                            </div>
                                        </h5>
                                        <h5 class="text-right pb-2 pt-1 border-bottom" id="mobileBank_blk">
                                            <label style="cursor:pointer;margin-right:-1em" id="mobileBank_blk_lbl"><input type="checkbox"
                                                    class="radio-inline payments" name="mobileBank_check"
                                                    id="mobileBank_check" onclick="mobileBankCheck()"> Mobile Payment :
                                            </label>
                                            <div class="form-check form-check-inline mobileBank-payment">
                                                <select name="mobibank_reference" id="mobibank_reference"
                                                        class="form-control font-weight-normal" style="display:none;">
                                                        <option value="" disabled selected>Select a Mobile Bank
                                                        </option>
                                                        @foreach( App\Models\Paymentgw::where('bank_type', 'mobile')->orderBy('bank_name', 'asc')->get() as
                                                        $payment_gw)
                                                        <option value="{{ $payment_gw->id }}">{{ $payment_gw->bank_name }}</option>
                                                        @endforeach
                                                </select>
                                            </div>
                                            <div class="form-check form-check-inline mobileBank-payment" 
                                            style="margin-right:3em">
                                                <input type="text" class="form-control" id="mobile_banking_pay"
                                                    placeholder="Amount (Required)" name="mobile_banking_pay"
                                                    style="display:none;">
                                            </div>
                                            
                                        </h5>
                                        <h5 class="text-right py-2 pr-5 d-none text-danger" id="total-pay-block">Payment Details:&nbsp;
                                            <div class="row mr-1">
                                                <div class="col-11 pr-1"><div id="pay-details" class="py-2"></div></div>
                                                <div class="col-1 pl-0 text-left"><div id="pay-totals" class="py-2 mt-1"></div></div>                                                
                                            </div>                                            
                                        </h5>
                                        <h4 class="text-right py-4 d-none text-danger" id="pay-more-block">Customer Due Amount
                                            <span id="pay-more"></span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h4>
                                        <h4 class="text-right py-4 d-none text-danger" id="pay-change-block">You need to pay change
                                            <span id="pay-change"></span><span
                                                class="pr-5 pl-1">{{ config('settings.currency_symbol') }}</span>
                                        </h4>
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
                                    <input type="checkbox" id="useDefaultPrinter" /> <strong>{{ __('Print to Default printer') }}</strong>
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
                                    {{$order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'delivered' ? '' : 'disabled' }}>Print
                                    Receipt</button>
                            </div>
                        </div>

                        <!--end of pos print using javascript-->
                        <form method="POST" action="{{ route('admin.due.sales.orderupdate') }}">
                            @csrf
                            <input type="hidden" id="order_id" name="order_id" value="{{ $order_id ? $order_id : '' }}">
                            <input type="hidden" id="sub-total" name="subtotal"
                                value="{{ $order_id ? $total_taka : '' }}">
                            <input type="hidden" id="order_discount" name="order_discount" value=""> <!-- reference discount -->
                            <input type="hidden" id="order_discount_reference" name="order_discount_reference" value="">
                            <input type="hidden" id="reward_point_discount" name="reward_discount" value="">
                            <input type="hidden" id="payment_method" name="payment_method[]" value="">
                            <input type="hidden" id="payment_details" name="payment_details" value="">
                            <input type="hidden" id="gpstarmobile" name="gpstarmobile" value="">
                            <input type="hidden" id="gpstar_discount" name="gpstar_discount" value="">
                            {{-- <input type="hidden" id="customer_payable_due" name="customer_due" value=""> --}}
                            
                            <div class="border px-4 rounded" style="border-color:rgb(182, 182, 182);">
                                <h4 class="text-center mt-3 mb-4">Customer Details</h4>
                                {{-- <div class="input-group my-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="phone_number">Order Table No</span>
                                    </div>
                                    <input type="text" class="form-control @error('order_tableNo') is-invalid @enderror"
                                        id="order_tableNo" placeholder="" name="order_tableNo"
                                        value="{{ $order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'receive' ? App\Models\Dueordersale::where('id', $order_id)->first()->order_tableNo : '' }}"
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
                                    {{ $order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'receive' ? '' : 'disabled' }}
                                    id="submit">Update Order </button>
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
    //global declaration
    var methods = []; 
    //var OrderTotal = $('#total-tk').text();  
    var allPayments=[]; // Holds all the paid amounts & methods. 
    var cardDiscountFlag = 0; // used to allow only one card discount

    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

         //Getting Branch Name 
        $('#discount_reference').select2({
                placeholder: "Select a reference",              
                multiple: false, 
                width: '100%',
                containerCssClass : 'd-none',
                maximumSelectionLength: 5,               
               // minimumResultsForSearch: -1,                        
        });

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

        // pos system reference discount       
       $('#discount').on('input', function() {
           if($.isNumeric( $.trim($('#discount').val() ))){
            let orderTotal = $('#total-tk').text();
            let discount = $.trim($('#discount').val());
            //getting reward discount 
            let rewardDiscount = $("#reward-discount").text(); 
            //getting GP star discount
            let gpStarDiscount = $('#gp-discount').text();
            //getting the director id value.
            let directorId = $('#discount_reference option:selected').val();
            if(directorId){
                //checking the discount slab of the corresponding director via ajax call
                checkDiscountSlab(discount, directorId, orderTotal);
            } 
            //calculating due amount  
            if($("#reward_point_check").prop("checked") == true) {
                if(gpStarDiscount){
                    dueAmount = orderTotal - (Number(discount) + Number(rewardDiscount) + Number(gpStarDiscount));
                }else{
                    dueAmount = orderTotal - (Number(discount) + Number(rewardDiscount));
                }                
            }else{
                if(gpStarDiscount){
                    dueAmount = orderTotal - (Number(discount) + Number(gpStarDiscount));
                }else{
                    dueAmount = orderTotal - discount;
                }
                
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

        // setting gp star mobile no to form hidden input field.
        $('#gpstarmobile_no').change(function(){
            $('#gpstarmobile').val($.trim($('#gpstarmobile_no').val()));
        });


        // if discount reference is changed, checkDiscountSlab is called 
        $('#discount_reference').change(function() {            
            var orderTotal = $('#total-tk').text();
            var discount = $.trim($('#discount').val()); 
            //getting the director id value.
            var directorId = $('#discount_reference :selected').val();
            // setting discount reference as director id to form hidden input field
            $('#order_discount_reference').val(directorId);             
            if(directorId){
                //checking the discount slab of the corresponding director via ajax call
                checkDiscountSlab(discount, directorId, orderTotal);
            }

        });

        //when gp star reference has a value and it is changed then calculate the discount value.
        $('#gpstar_ref').change(function(){
            //calculate gpstar discount and showing
            $('#gp-discount-blk').removeClass('d-none');
            $('#gp-ref-blk').removeClass('mr-5');

            // to calculate totaldue we need to check other discount options availability.
            var orderTotal = $('#total-tk').text(); // order total
            var discount = $.trim($('#discount').val()); // reference discount            
            var rewardDiscount = $("#reward-discount").text(); //reward discount [clients points]
            //at first we need getting the director id value.
            var gpstarId = $('#gpstar_ref option:selected').val();
            var gpstarDiscount = 0;

            $.post("{{ route('admin.sales.gpStarDiscount') }}", {        
                _token: CSRF_TOKEN,
                gpstarId: gpstarId,                    
            }).done(function(data) { 
                data = JSON.parse(data);
                if(data.status == "success") {
                    var discountPercent = Number(data.discountPercent);
                    var discountUpperLimit = Number(data.discountUpperLimit);
                    //getting the total dueAmount before calculating gpstar discount amount.
                    if($("#reward_point_check").prop("checked") == true) { 
                        if(discount){
                            dueAmount = Number(orderTotal) - (Number(discount) + Number(rewardDiscount));
                        }else{
                            dueAmount =  Number(orderTotal) - Number(rewardDiscount);
                        }
                    }else{
                        if(discount){
                            dueAmount =  Number(orderTotal) - Number(discount);
                        }else{
                            dueAmount =  Number(orderTotal);
                        }
                    }
                    //finding the gpstardiscount.    
                    gpstarDiscount = dueAmount * (discountPercent/100);
                    gpstarDiscount = Math.ceil(gpstarDiscount); // remove the fractional portion of the card discount.
                    //setting gpstarDiscount = upper limit, if it cross the upper limit.
                    if(gpstarDiscount > discountUpperLimit){
                        gpstarDiscount = discountUpperLimit;
                    }

                    //setting due amount 
                    dueAmount -= gpstarDiscount;
                    $('#due-tk').text(dueAmount);
                    // setting gp star discount.
                    $('#gp-discount').text(gpstarDiscount);
                    // setting gpstar discount data to form hidden input field.
                    $('#gpstar_discount').val(gpstarDiscount);
                }                
            });
           
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
                if(data.status == "success"){
                    //if percentage limit cross the discount upper limit, then we will assign discount upper limit to discountLimit.
                    let discountLimit =  data.discountLimit > data.discountUpperLimit ? data.discountUpperLimit : data.discountLimit;
                    if(data.discount > discountLimit){
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

        $("#gpstar_check").change(function(){
            resetPayment();
        });
        
        // POS System: Cash payment:      
        $('#cash_pay').change(function() { 
            
            if($.isNumeric( $.trim($('#cash_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#cash_pay').css({
                        "border": "",
                        "background": ""
                });

                // if total due > calculateTotalPaidByCustomer
                if($('#due-tk').text() > calculateTotalPaidByCustomer()){

                    // getting the total dueAmount
                    dueTotal = allPayments.length && allPayments.find(obj => obj.due > 0) ? allPayments.find(obj => obj.due > 0 ).due : $('#due-tk').text();
                    //dueTotal =  allPayments.length && allPayments[allPayments.length - 1].due > 0 ? allPayments[allPayments.length - 1].due : $('#due-tk').text();
                    cashPay = $.trim($('#cash_pay').val());               
                    // avoiding for cash payment 0 tk.
                    if(cashPay != ''){
                        dueTotal -= cashPay; 
                        dueTotal = parseFloat(dueTotal).toFixed(4);
                        if(dueTotal == 0){
                            //storeEachPayment(paymentMethod, customerPaid, due, bankName='')  
                            storeEachPayment('cash',cashPay, 0);
                            noDueDisplay();
                        }else if(dueTotal > 0){ // total due after cash payment.// 500-300 = 200 
                            storeEachPayment('cash',cashPay, dueTotal);
                            duePayDisplay(cashPay, 'cash', '', dueTotal);
                        }else if(dueTotal < 0){ // 200 - 300 = -100                       
                            storeEachPayment('cash', cashPay, dueTotal);
                            exchangeDisplay(cashPay, dueTotal, 'cash', '');
                            
                        }
                        //setting payment method to form hidden input field
                        methods.push('cash');
                        //filtering only unique values
                        var uniqueMethods = methods.filter( onlyUnique );
                        $('#payment_method').val(uniqueMethods);
                        
                    }else{ // if user does not any input or null on cash payment
                        
                    $('#pay-more-block').addClass('d-none');
                    $('#pay-change-block').addClass('d-none');
                    $('#total-pay-block').addClass('d-none');                 
                    }

                } //end if total due > calculateTotalPaidByCustomer

            }else{
               // if the entered data is not digit, we just focus on it
                    $('#cash_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    //resetPayment();
             }
            
        });

        // POS System: Card payment 
        $('#card_pay').change(function() {

            if($.isNumeric( $.trim($('#card_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#card_pay').css({
                        "border": "",
                        "background": ""
                });

                // if total due > calculateTotalPaidByCustomer
                if($('#due-tk').text() > calculateTotalPaidByCustomer()){

                    //getting the card bank name.
                    cardBank= $('#bank_reference option:selected').text();                            
                    //dueTotal =  allPayments.length && allDues > 0 ? allDues : $('#due-tk').text();
                    dueTotal = allPayments.length && allPayments.find(obj => obj.due > 0) ? allPayments.find(obj => obj.due > 0 ).due : $('#due-tk').text();
                                                                                    
                    cardPay = $.trim($('#card_pay').val());
                    //getting card discount promise object
                    cardDiscountPromise = getCardDiscount(cardBank);

                    cardDiscountPromise.then(val => {
                        //debugger
                        //Calculating Card Discount Amount from the total payable.
                        cardDiscount = parseFloat(cardPay) * parseFloat(val.cardDiscount)/100; 
                        cardDiscount = Math.ceil(cardDiscount); // remove the fractional portion of the card discount.
                        //setting card discount = upper limit, if it cross the upper limit.
                        if(cardDiscount > parseFloat(val.upperLimit)){
                            cardDiscount = parseFloat(val.upperLimit).toFixed(2);
                        }

                        //to check card have discount or not 
                        // here cardFlag is used to allow to take only one card discount 
                        if(cardDiscount && !cardDiscountFlag){
                            // displaying avaiable card discount.
                            $('#card-discount-blk').removeClass('d-none');
                            $('#card-discount').html(cardDiscount); 
                            cardDiscountFlag = 1;
                            //resetting the cardpayment after subtracting card discount.
                            $('#card_pay').val(cardPay - cardDiscount);
                            // setting total payble due after having card discount. [only for user view]                         
                            $('#due-tk').text(Number($('#due-tk').text()) - cardDiscount);
                            //finding due after the card payment with card discount.
                            dueTotal -= cardPay; 
                            //getting the card payment after having card discount.
                            cardPay = cardPay - cardDiscount;
                            dueTotal = parseFloat(dueTotal).toFixed(4);         
                            if(dueTotal == 0){ 
                            //storeEachPayment(paymentMethod, customerPaid, due, cardDiscount, bankName) 
                                storeEachPayment('card', cardPay, 0, cardDiscount,cardBank);
                                noDueDisplay();
                            }else if(dueTotal > 0){ // 500-300 = 200                            
                                storeEachPayment('card', cardPay, dueTotal, cardDiscount, cardBank);                           
                                duePayDisplay(cardPay, 'card', cardBank, dueTotal); 
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                storeEachPayment('card', cardPay, dueTotal, cardDiscount, cardBank); 
                                exchangeDisplay(cardPay, dueTotal, 'card', cardDiscount, cardBank);
                            }                                                
                        }else{

                           // $('#card-discount-blk').addClass('d-none');
                            //finding due after the card payment.
                            dueTotal -= cardPay; 
                            dueTotal = parseFloat(dueTotal).toFixed(4);         
                            if(dueTotal == 0){ 
                            //storeEachPayment(paymentMethod, customerPaid, due, cardDiscount, bankName) 
                                storeEachPayment('card', cardPay, 0, 0, cardBank);
                                noDueDisplay();
                            }else if(dueTotal > 0){ // 500-300 = 200                            
                                storeEachPayment('card', cardPay, dueTotal, 0, cardBank);                           
                                duePayDisplay(cardPay, 'card', cardBank, dueTotal); 
                            }else if(dueTotal < 0){ // 200 - 300 = -100
                                storeEachPayment('card', cardPay, dueTotal, 0, cardBank); 
                                exchangeDisplay(cardPay, dueTotal, 'card', cardBank);
                            }
                        } 
                    });                    

                    //setting payment method to form hidden input field
                    methods.push('card');                    
                    //filtering only unique values
                    var uniqueMethods = methods.filter( onlyUnique );
                    $('#payment_method').val(uniqueMethods);
                    
                }//end if total due > calculateTotalPaidByCustomer

            }else{
               // if the entered data is not digit, we just focus on it
                    $('#card_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    //resetPayment();
             }
            
        });

        // mobile banking payment
        $('#mobile_banking_pay').change(function() {

            if($.isNumeric( $.trim($('#mobile_banking_pay').val() ))){
                // avoiding alphanumeric input and make input text normal.
                $('#mobile_banking_pay').css({
                        "border": "",
                        "background": ""
                });

                // if total due > calculateTotalPaidByCustomer
                if($('#due-tk').text() > calculateTotalPaidByCustomer()){
                        //getting the card bank name.
                    mobiBank= $('#mobibank_reference option:selected').text();              
                    //dueTotal =  allPayments.length && allPayments[allPayments.length - 1].due > 0 ? allPayments[allPayments.length - 1].due : $('#due-tk').text();
                    dueTotal = allPayments.length && allPayments.find(obj => obj.due > 0) ? allPayments.find(obj => obj.due > 0 ).due : $('#due-tk').text();
                    mobilePay = $.trim($('#mobile_banking_pay').val()); 

                    dueTotal -= mobilePay;   
                    dueTotal = parseFloat(dueTotal).toFixed(4);       
                        if(dueTotal == 0){ 
                            //storeEachPayment(paymentMethod, customerPaid, due, bankName) 
                            storeEachPayment('mobile', mobilePay, 0, 0, mobiBank);
                            noDueDisplay();
                        }else if(dueTotal > 0){ // 500-300 = 200 
                            storeEachPayment('mobile', mobilePay, dueTotal, 0, mobiBank); 
                            //duePayDisplay(customerPaid,paymentMethod,bankName,due)                            
                            duePayDisplay(mobilePay, 'mobile', mobiBank, dueTotal);                                                             
                        }else if(dueTotal < 0){ // 200 - 300 = -100
                            storeEachPayment('mobile', mobilePay, dueTotal, 0, mobiBank); 
                            //exchangeDisplay(customerPaid, due, paymentMethod, bankName)                           
                            exchangeDisplay(mobilePay, dueTotal, 'mobile', mobiBank);               
                        }

                    //setting payment method to form hidden input field
                    methods.push('mobile-banking');                    
                    //filtering : to get only unique values
                    var uniqueMethods = methods.filter( onlyUnique );
                    $('#payment_method').val(uniqueMethods);

                }// end if total due > calculateTotalPaidByCustomer
                
            }else{
               // if the entered data is not digit, we just focus on it
                    $('#mobile_banking_pay').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"                    
                    }); 
                    //resetPayment();
             }
            
        });

        
        function storeEachPayment(paymentMethod, customerPaid, due, cardDiscount=0, bankName=''){

            fractionDiscount = $('#fraction-due').text() ? parseFloat($('#fraction-due').text()) : 0;           

            // to store other payment records we need to check dues                
            // getting the total paid by Customer
            totalPaid = calculateTotalPaidByCustomer();  
            //getting the total due.           
            totalDue = parseFloat($('#due-tk').text()) - totalPaid;

            // checking any dues [avoiding store payments when dues such as -10 or 0]
            if(totalDue > 0){                
                // if payment is done for a particular method
                objSale = allPayments.find(obj => obj.paymentMethod == paymentMethod);                                     
                if(objSale){
                    // here, we are avioding the same type bank or mobile banking payment 
                    // creating two or more card payments or mobile payments
                    if(allPayments.find(obj => (obj.paymentMethod == paymentMethod && obj.bankName != bankName))){
                        payment = {
                            'saleOrderId' : {{ $order_id }},
                            'paymentMethod' : paymentMethod,
                            'customerPaid': customerPaid,
                            'bankName': bankName,
                            'due': due,
                            'cardDiscount': cardDiscount,
                            'fractionDiscount': fractionDiscount                   
                        };
                        allPayments.push(payment);  // array of objects holds all the payment details 
                    }           
                                
                }else{  //we will not store any payment history if there no dues
                    payment = {
                        'saleOrderId' : {{ $order_id }},
                        'paymentMethod' : paymentMethod,
                        'customerPaid': customerPaid,
                        'bankName': bankName,
                        'due': due, 
                        'cardDiscount': cardDiscount,
                        'fractionDiscount': fractionDiscount                       
                    };

                    allPayments.push(payment);  // array of objects holds all the payment details 
                }
            }
            
            //Resetting all the dues & fraction part to zero except the last object due.        
            for(let i = allPayments.length - 2; i >= 0; i--){ 
                allPayments[i].due = 0;
                allPayments[i].fractionDiscount = 0;
            }
            console.log(allPayments);
            
            //converting array of objects to json string to pass data to controller.
            jsonStringAllPayments = JSON.stringify(allPayments);
            //setting all payment details to form hidden input field           
            $('#payment_details').val(jsonStringAllPayments);

        }

        function calculateTotalPaidByCustomer(){
            totalPaid = 0;
            for(let i = 0; i < allPayments.length; i++){
                totalPaid += parseFloat(allPayments[i].customerPaid);
            }
            return totalPaid;
        }
        
        // Deleting the particular payment history if user clicked close button
        // We are using document here due to .pay-close is added after the page load.
        $(document).on('click', '.pay-close', function(){
            $(this).parent().addClass('d-none');
            // get deleted obj index from the id value
            getDeletedObjIndex = $(this).attr('id').slice(-1);
            //resetting the card discount flag if the deleted payment object has card discount
            cardDiscount = 0
            if(allPayments[getDeletedObjIndex].cardDiscount){
                cardDiscountFlag = 0;
                cardDiscount = allPayments[getDeletedObjIndex].cardDiscount;
                $('#card-discount-blk').addClass('d-none');
                // setting total payble due after adding deleted card discount.                       
                $('#due-tk').text(Number($('#due-tk').text()) + cardDiscount);
            }
            // delete the obj from all payments.
            allPayments.splice(getDeletedObjIndex, 1);
           
            // After delete the obj getting the total payments
            totalPaid = calculateTotalPaidByCustomer();            
            //getting the total due.           
            totalDue = parseFloat($('#due-tk').text()) - totalPaid;           
            // updating the total due at last obj
            if(totalPaid){
                allPayments[allPayments.length-1].due = totalDue;
            }

            $('#pay-change-block').addClass('d-none');
            $('#pay-more-block').removeClass('d-none');
            $('#pay-more').html(totalDue);  
            $('#pay-totals').html( '=' + totalPaid);   

            // Resetting all the payment textbox.    
            $('#cash_pay').val("");
            $('#card_pay').val("");
            $('#mobile_banking_pay').val("");  

            if(!allPayments.length){
                $('#pay-change-block').addClass('d-none');
                $('#pay-more-block').addClass('d-none');
                $('#total-pay-block').addClass('d-none'); 
            }      
        });

     
        function noDueDisplay(){
                let tk = " {{ config('settings.currency_symbol') }}, "; // money symbol
                let totalPay = '';

                for(let i = 0; i < allPayments.length; i++){
                    if(allPayments[i].bankName){ // for bank
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].bankName + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }else{ // for cash
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].paymentMethod + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }
                }                         
                $('#pay-more-block').addClass('d-none');
                $('#pay-change-block').addClass('d-none');                
                $('#total-pay-block').removeClass('d-none'); 
                $('#pay-details').html(totalPay);

                totalPaid = calculateTotalPaidByCustomer();
                $('#pay-totals').html( '=' + totalPaid);               
                
        }

        function duePayDisplay(customerPaid,paymentMethod,bankName,due){
            let tk = " {{ config('settings.currency_symbol') }}, "; // money symbol
            let totalPay = '';

            for(let i = 0; i < allPayments.length; i++){
                if(allPayments[i].bankName){ // for bank
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].bankName + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }else{ // for cash
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].paymentMethod + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }
            }
            $('#pay-change-block').addClass('d-none');
            $('#pay-more-block').removeClass('d-none');
            $('#pay-more').html(parseFloat(due).toFixed(2));
            $('#total-pay-block').removeClass('d-none'); 
            $('#pay-details').html(totalPay);

            totalPaid = calculateTotalPaidByCustomer();
            $('#pay-totals').html( '=' + totalPaid);
            

        }

        function exchangeDisplay(customerPaid, due, paymentMethod, bankName){
            let tk = " {{ config('settings.currency_symbol') }}, "; // money symbol
            let totalPay = '';

            for(let i = 0; i < allPayments.length; i++){
                if(allPayments[i].bankName){ // for bank
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].bankName + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }else{ // for cash
                        totalPay += '<span class="badge badge-info text-uppercase ml-1 pl-1"><span style="line-height:21px;">' + allPayments[i].paymentMethod + ' = ' + allPayments[i].customerPaid+ 
                '</span><button id="pay-close'+ i + '" type="button" class="btn close text-white pay-close" aria-label="Close"><span aria-hidden="true">&nbsp;×</span></button></span>'
                    }
            }

            $('#pay-more-block').addClass('d-none');
            $('#pay-change-block').removeClass('d-none');
            $('#pay-change').html(parseFloat(Math.abs(due)).toFixed(2));
            $('#total-pay-block').removeClass('d-none'); 
            $('#pay-details').html(totalPay);

            totalPaid = calculateTotalPaidByCustomer();
            $('#pay-totals').html( '=' + totalPaid);
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

            // when GP Star checkbox is true
            if($("#gpstar_check").prop("checked") == true) {
                //when GP Discount have a value but GP star mobileno isnot present then.
                if($.trim($('#gpstarmobile_no').val()) == ''){
	                isValid = false;
                    $('#gpstarmobile_no').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    });
                }else{
                    $('#gpstarmobile_no').css({
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

            //when card or mobile payment checkbox is true
            if($("#card_check").prop("checked") == true || $("#mobileBank_check").prop("checked") == true ) { 
                //when card bank reference field is empty but card payment is not empty.
                if ($.trim($('#bank_reference').val()) == '' && $.trim($('#card_pay').val()) != '') {
                    isValid = false;
                    $('#bank_reference').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    }); 
                }else{
                    $('#bank_reference').css({
                        "border": "",
                        "background": ""
                    });
                }

                //when mobile bank reference field is empty but mobile payment is not empty.
                if ($.trim($('#mobibank_reference').val()) == '' && $.trim($('#mobile_banking_pay').val()) != '') {
                    isValid = false;
                    $('#mobibank_reference').css({
                        "border": "2px solid #007065",
                        "background": "#e4f5f3"
                    }); 
                }else{
                    $('#mobibank_reference').css({
                        "border": "",
                        "background": ""
                    });
                }


            }

            //if the payment due is exists or due is greater than zero, we will prevent submit data.
            // if(allPayments.length && allPayments[allPayments.length - 1].due > 0){
            //     isValid = false;
            // }
            

            if (isValid == false)
                e.preventDefault();
        });//end of submit.

        
    });
        //pos system reference discount option is showing or hiding.
        function discountCheck(){             
            if($("#discount_check").prop("checked") == true) { 
                $('#discount-lbl').css('margin-right','5px');
                $('.cash-discount').addClass('discount-w');
                $('#discount_reference').show();
                $('#discount').show();
                $('#discount-blk').removeClass('pt-1'); 
                $('.select2-selection').removeClass('d-none');       
                $('.select2-selection__placeholder').addClass('font-weight-normal');       
            }else{
                //when unchecked chkbox we set discount to null and due amount to order total.
                $('#discount-lbl').css('margin-right','-15px');               
                $('#discount_reference').removeAttr("style").hide();
                $('#discount').removeAttr("style").hide(); 
                $('.cash-discount').removeClass('discount-w');
                $('#discount').val("");
                $('.select2-selection').addClass('d-none');
                $('#discount-blk').addClass('pt-1');
                $('#discount-limit').addClass('d-none');
                $("#discount_reference").select2("val", "");

                //restoring the dueAmmount
                var orderTotal = $('#total-tk').text(); // order total
                var gpStarDiscount = $('#gp-discount').text(); // GP star discount            
                var rewardDiscount = $("#reward-discount").text(); //reward discount [clients points]  

                if($("#reward_point_check").prop("checked") == true ){
                    if(gpStarDiscount){
                        dueAmount = orderTotal - (Number(gpStarDiscount) + Number(rewardDiscount));
                    }else{
                        dueAmount = orderTotal - Number(rewardDiscount);
                    }  
                }else{
                    if(gpStarDiscount){
                        dueAmount = orderTotal - Number(gpStarDiscount);
                    }else{
                        dueAmount = orderTotal;
                    }  
                }
                $('#due-tk').text(dueAmount); 
                           
            }           
        }

        //pos system GP star discount option is showing or hiding.
        function gpstarCheck(){             
            if($("#gpstar_check").prop("checked") == true) { 
                $('#gpstar-lbl').css('margin-right','0px');
                $('.star-discount').addClass('discount-w');
                $('#gpstarmobile_no').show();
                $('#gpstar_ref').show();
                $('#gpstar-discount-lbl').show();
                $('#gpstar-blk').removeClass('pt-1');  
                $('#gp-ref-blk').addClass('mr-5');                 
            }else{
                //when unchecked chkbox we set discount to null and due amount to order total.
                $('#gpstar-lbl').css('margin-right','20px');               
                $('#gpstar_ref').removeAttr("style").hide();
                $('#gpstarmobile_no').removeAttr("style").hide(); 
                $('.star-discount').removeClass('discount-w');
                $('.star-discount').removeClass('mr-5');
                $('#gpstarmobile_no').val("");
                $('#gp-discount').text('');
                $('#gpstar-blk').addClass('pt-1');
                $('#gp-discount-blk').addClass('d-none');
                $("#gpstar_ref").val("");
                //restoring the dueAmmount
                var orderTotal = $('#total-tk').text(); // order total
                var discount = $.trim($('#discount').val()); // reference discount            
                var rewardDiscount = $("#reward-discount").text(); //reward discount [clients points]  

                if($("#reward_point_check").prop("checked") == true ){
                    if(discount){
                        dueAmount = orderTotal - (Number(discount) + Number(rewardDiscount));
                    }else{
                        dueAmount = orderTotal - rewardDiscount;
                    }  
                }else{
                    if(discount){
                        dueAmount = orderTotal - discount;
                    }else{
                        dueAmount = orderTotal;
                    }  
                }
                $('#due-tk').text(dueAmount);
                           
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
                //resetPayment();              
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
                //resetPayment();           
            }           
        }


        // getting card discount via ajax call
        async function getCardDiscount(cardBank){
            let result = await $.ajax({
            url:"{{ route('admin.sales.card.discount') }}",
            type: 'post',
            dataType: "json",
            // passing CSRF_TOKEN along with search value in the data
            data: {
               _token: CSRF_TOKEN,
               cardBank: cardBank
            }
          });

        return result; // returns a promise object.
                 
        } 

        function rewardPoint(){            
            var dueAmount = 0;     
            var discount =  $.trim($('#discount').val());
            var orderTotal = $("#total-tk").html();
            var rewardDiscount = $("#reward-discount").text(); 
            var gpStarDiscount = $('#gp-discount').text();

            if($("#reward_point_check").prop("checked") == true) { 
                if(gpStarDiscount){
                    if(discount){
                        dueAmount = orderTotal - (Number(rewardDiscount) + Number(discount) + Number(gpStarDiscount));
                    }else{
                        dueAmount = orderTotal - (Number(rewardDiscount) + Number(gpStarDiscount));
                    }
                }else{
                    if(discount){
                        dueAmount = orderTotal - (Number(rewardDiscount) + Number(discount));
                    }else{
                        dueAmount = orderTotal - Number(rewardDiscount);
                    } 
                }               
                                  
                $('#due-tk').text(dueAmount);
                // setting reward_point_discount to form hidden input field
                $('#reward_point_discount').val(rewardDiscount);
              
            }else{
                if(gpStarDiscount){
                    if(discount){
                        dueAmount = orderTotal - (Number(discount) + Number(gpStarDiscount));
                    }else{
                        dueAmount = orderTotal - Number(gpStarDiscount);
                    }
                }else{
                    if(discount){
                        dueAmount = orderTotal - Number(discount);
                    }else{
                        dueAmount = orderTotal;
                    }
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
               // resetPayment();  
            }           
        }

        // // if mobile bank_reference is changed 
        // $('#mobibank_reference').change(function() { 
        //     mobiBank = $('#mobibank_reference :selected').val();
        //     // setting mobile bank name to form hidden input field
        //     if(mobiBank){
        //         $('#mobibank').val(mobiBank);
        //     }  
        // });

        // whenever any change is made on product search or discount we use this function.
        function resetPayment(){
            // resetting all the global variables which are needed to calculate.
            methods = []; 
            //OrderTotal = $('#total-tk').text();  
            allPayments=[]; // Holds all the paid amounts & methods. 
            cardDiscountFlag = 0; // used to allow only one card discount

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
            $('#card-discount-blk').addClass('d-none');
            $('#card-discount').html('');  

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
            var center = '\x1B' + '\x61' + '\x31'; //center align
            var left = '\x1B' + '\x61' + '\x30'; // left align
            var right = '\x1B' + '\x61' + '\x32'; // right align
            cmds += esc + '!' + '\x22'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            cmds += center;
            cmds += "{{ strtoupper(config('settings.site_name')) }}"; //text to print site name
            cmds += newLine;
            cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex            
            cmds += newLine;
            cmds += "{{ __('The Best Restaurant, and Party center in Dhaka.') }}"; //text to print site title
            cmds += newLine;
            cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
            cmds += "------------------------------------------";
            cmds += left;
            cmds += newLine;            
            cmds += "Location: {{ config('settings.contact_address') }}"; //text to print site address
            cmds += newLine;
            cmds += "Contact no: {{ config('settings.phone_no') }}";
            cmds += newLine;         
            cmds += "Operator Name: {{ auth()->user()->name }}";
            cmds += newLine;
            cmds += "Date: {{ date('d-M-Y h:i:s A') }}";
            cmds += newLine;
            cmds += "---------------------------------------";
            cmds += newLine;
            cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
            cmds += "Customer Order No: {{ $order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'delivered' ? App\Models\Dueordersale::where('id', $order_id)->first()->order_number : ''}}"
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
                 $discount =0;
            @endphp
            @if($order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'delivered')
            //calculating paid amount
            @php $paid_amount = App\Models\Dueordersale::find($order_id)->cash_pay + App\Models\Dueordersale::find($order_id)->card_pay +
            App\Models\Dueordersale::find($order_id)->mobile_banking_pay; 
            //calculating total discount amount received.
            $discount = App\Models\Dueordersale::find($order_id)->discount + App\Models\Dueordersale::find($order_id)->reward_discount +
            App\Models\Dueordersale::find($order_id)->card_discount + App\Models\Dueordersale::find($order_id)->gpstar_discount;            
            @endphp
            @foreach(App\Models\Salebackup::where('dueordersale_id', $order_id)->get() as $saleCart)        
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
            cmds += "        ++ VAT ({{ $vat_percentage }}%):          {{$food_vat}}";
            cmds += newLine;
            @endif            
            cmds += "                          -------------";
            cmds += newLine;
            cmds += 'Total Amount:                  {{ $sub_tot_without_vat + $food_vat }}';
            cmds += newLine;
            @if($order_id && (float)$discount)
            cmds += '-Discount:                     {{ $order_id ? round($discount,2) : '' }}';
            @endif
            cmds += newLine;            
            cmds += "                          -------------";
            cmds += newLine;
            cmds += 'Amount Payable:                {{ $order_id ? round(App\Models\Dueordersale::find($order_id)->grand_total - $discount,2) : '' }}'; 
            cmds += newLine;                    
            cmds += "Paid Amount:                   {{ $order_id && App\Models\Dueordersale::where('id', $order_id)->first()->status == 'delivered' ? round((App\Models\Dueordersale::find($order_id)->receive_total - $discount - App\Models\Dueordersale::find($order_id)->fraction_discount),2) : '' }}";
            cmds += newLine; 
            cmds += newLine; 
            cmds += 'Payment Mode Details:          ';
            cmds += newLine; 
            cmds += "---------------------------------------";
            cmds += newLine;
            @if($order_id)
            cmds += 'Booking Amount:                {{ round(App\Models\Dueordersale::where('id', $order_id)->first()->booked_money,2) }}';
            cmds += newLine;
            @if((float)App\Models\Dueordersale::find($order_id)->cash_pay)
            cmds += 'CASH:                          {{ $order_id ? round(App\Models\Dueordersale::find($order_id)->cash_pay,2) : '' }}';
            cmds += newLine;
            @endif
            @if((float)App\Models\Dueordersale::find($order_id)->card_pay)
            cmds += 'CARD:                          {{ $order_id ? round(App\Models\Dueordersale::find($order_id)->card_pay,2) : '' }}';
            cmds += newLine;
            @endif
            @if((float)App\Models\Dueordersale::find($order_id)->mobile_banking_pay)
            cmds += 'Mobile Banking:                {{ $order_id ? round(App\Models\Dueordersale::find($order_id)->mobile_banking_pay,2) : '' }}';
            cmds += newLine;            
            @endif
            @if($discount)
            cmds += 'Total Discount:                {{ $order_id ? round($discount,2) : '' }}';
            cmds += newLine;
            @endif
            @if((float)App\Models\Dueordersale::find($order_id)->fraction_discount)
            cmds += 'Other Discount:                {{ $order_id ? round(App\Models\Dueordersale::find($order_id)->fraction_discount,2) : '' }}';
            cmds += newLine;
            @endif            
            @endif
            cmds += "---------------------------------------";
            cmds += newLine;
            @if($order_id &&($discount || App\Models\Dueordersale::find($order_id)->fraction_discount))
            cmds += 'You Have Saved:                {{ $order_id ? round(($discount + App\Models\Dueordersale::find($order_id)->fraction_discount),2)  : '' }}';            
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