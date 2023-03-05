@extends('site.app')
@section('title', 'Shopping cart')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Checkout</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Checkout</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<!-- Cart Start  -->
<div class="mycart">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @if (session('error'))
                <div class="alert alert-error alert-block bg-danger text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('error') }}</strong>
                </div>
                @endif
            </div>
        </div>
        <form action="{{ route('checkout.place.order') }}" method="POST" role="form">
            @csrf
            <div class="row" id="tab-info">
                <div class="col-lg-7 col-md-8 col-12 pb-5">
                    <h6 class="card-title mt-2">Billing Details</h6>
                    <fieldset>
                        <div class="form-group">
                            <label>Full name:</label>
                            <input name="name" value="{{ auth()->user()->name }}" id="name"
                                class="form-control @error('name') is-invalid @enderror" type="text" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email Address:</label>
                            <input name="email" value="{{ auth()->user()->email }}" placeholder="Email" id="email"
                                class="form-control @error('email') is-invalid @enderror" type="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input name="phone_no" value="{{ auth()->user()->phone_number }}" id="phone_no"
                                class="form-control  @error('phone_no') is-invalid @enderror" type="text" required>
                            @error('phone_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="col form-group">
                                <label>Select District:</label>
                                <select class="form-control @error('district') is-invalid @enderror" name="district"
                                    id="district" required>
                                    <option selected='false' value="">---District---</option>
                                    @foreach(App\Models\District::where('status', 1)->get() as $district)
                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                @error('district')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                            <div class="col form-group">
                                <label>Select Area:</label>
                                <select name="zone" class="form-control @error('zone') is-invalid @enderror" required>
                                    <option selected='false' value="">---Area---</option>
                                </select>
                                @error('zone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group">
                            <label>Shipping Address:</label>
                            <textarea class="form-control @error('address_txt') is-invalid @enderror" id="address_txt"
                                name="address_txt" style="height:70px;" required></textarea>
                            <div class="links" style="margin-top:-10px;">
                                <label><input type="checkbox" class="checkbox-inline" id="address_chk"
                                        name="address_chk"> Use default address for my shipping Address? </label>
                            </div>
                            @error('address_txt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <h6 class="text-danger mt-5 mb-3">We are delivering order 11:59 AM to 9:00 PM Only. If your
                            order after 9:00 PM, It will be processed for next day.</h6>
                        <div class="form-group">
                            <label>Current Date & Time :</label>
                            <p>{{ \Carbon\Carbon::now() }}</p>
                        </div>
                        <div class="form-group">
                            <label>Delivery Date & Time :</label>
                            @php
                            $time = \Carbon\Carbon::now();
                            $morning = \Carbon\Carbon::create($time->year, $time->month, $time->day, 0, 0, 0);
                            $evening = \Carbon\Carbon::create($time->year, $time->month, $time->day, 21, 0, 0);
                            if($time->between($morning, $evening, true)) {
                            $deliver_time = $time->toDateString();
                            }else{
                            $tom = \Carbon\Carbon::tomorrow();
                            $deliver_time = $tom->toDateString();
                            }
                            @endphp
                            <p>{{ $deliver_time }}</p>
                            <input type="hidden" value="{{ $deliver_time }}" name="delivery_timings">
                        </div>
                    </fieldset>
                </div>
                <div class="col-lg-1 d-none d-lg-block"></div>
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="card">
                        <div class="card-header align-middle">
                            <h5 class="text-center">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach(App\Models\Cart::totalCarts() as $cart)
                                    <tr>
                                        <td>
                                            @if($cart->has_attribute)
                                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                            {{ $cart->product->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})
                                            x {{ $cart->product_quantity }}
                                            @else
                                            {{ $cart->product->name }} x {{ $cart->product_quantity }}
                                            @endif
                                        </td>
                                        <td class="px-0">
                                            @if($cart->has_attribute)
                                            {{-- we face data from product attribute table --}}
                                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                            @if(
                                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                                            {{ config('settings.currency_symbol') }}
                                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price,0) *  $cart->product_quantity }}
                                            @else
                                            {{ config('settings.currency_symbol') }}
                                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->price,0) *  $cart->product_quantity }}
                                            @endif
                                            @else
                                            @if($cart->product->discount_price)
                                            {{ config('settings.currency_symbol') }}
                                            {{ round($cart->product->discount_price,0) *  $cart->product_quantity }}
                                            @else
                                            {{ config('settings.currency_symbol') }}
                                            {{ round($cart->product->price,0) *  $cart->product_quantity }}
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td>Subtotal</td>
                                        @php $subtotal = App\Models\Cart::calculateSubtotal(); @endphp
                                        <td class="px-0">
                                            {{ config('settings.currency_symbol') }}
                                            {{ $subtotal }}</td>
                                    </tr>
                                    @if(config('settings.tax_percentage'))
                                    <tr>
                                        <td>Vat Percentage ({{config('settings.tax_percentage')}}%)</td>
                                        <td class="px-0">
                                            {{ config('settings.currency_symbol') }}
                                            {{ round($subtotal * (config('settings.tax_percentage')/100),0) }}
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>Shipping Cost</td>
                                        <td class="px-0">{{ config('settings.currency_symbol') }}
                                            {{ config('settings.delivery_charge') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Total</td>
                                        <td class="px-0">{{ config('settings.currency_symbol') }}
                                            {{  $subtotal + config('settings.delivery_charge') + ($subtotal* (config('settings.tax_percentage')/100))}}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        @if(App\Models\Cart::totalCarts()->count())
                        <input type="submit" value="Place Order" class="btn btn-theme btn-md btn-block mt-5 mb-5" />
                        @else
                        <input type="submit" value="Place Order"
                            class="btn btn-theme btn-md btn-block mt-5 mb-5 disabled" />
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Cart End  -->

@endsection
@push('scripts')
<script type="text/javascript">
    // var $tb = $("#address_txt");

    $(function() { 
        $('form').submit(function(){
            $(this).find('input[type=submit]').prop('disabled', true);
        });

        $('input[name="address_chk"]').on("change", function () {       
            if (this.checked) {
                $.ajax({
                url: "/checkout/user/address/",
                type: "GET",
                dataType: "json",
                success: function(data) {               
                    if (data.status == "success") {                    
                    $('textarea[name="address_txt"]').html(data.address); 
                    }
                    }
                });
            }else{
                $('textarea[name="address_txt"]').html("");
            }
        });
    });
   


</script>
@endpush