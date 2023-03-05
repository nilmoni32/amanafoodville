@extends('site.app')
@section('title', 'Shopping cart')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Shopping Cart</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Shopping Cart</a></li>
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
                <p id="total-cart-paragraph">You have total <span id="cart-total"
                        style="color:#e9457a;">{{ App\Models\Cart::totalCarts()->count() }}</span> items in
                    your order.</p>
                <div class="table-responsive-md">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>SubTotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total_taka= 0.0; $i=1 @endphp
                            @foreach(App\Models\Cart::totalCarts() as $cart)
                            <tr>
                                <td class="text-left">
                                    {{-- getting the product image from cart to product image table using relatioship --}}
                                    @if($cart->product->images->first())
                                    <img src="{{ asset('storage/'.$cart->product->images->first()->full) }}"
                                        title="{{ $cart->product->name }}"
                                        class="img-responsive pr-2 rounded d-inline-block" width="100px" />
                                    @endif                                  
                                    @if($cart->has_attribute)
                                    {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                    {{ $cart->product->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})

                                    @else
                                    {{ $cart->product->name }}
                                    @endif
                                </td>
                                <td class="text-center px-2">
                                    <p class="qtypara">
                                        <span id="minus{{$i}}" class="minus"
                                            onclick="updateAddtoCart({{ $cart->id }}, 'minus{{$i}}' )"><i
                                                class="icofont icofont-minus"></i></span>
                                        <input type="text" name="product_quantity" id="input-quantity{{$i}}"
                                            value="{{ $cart->product_quantity }}" size="2" class="form-control qty"
                                            readonly />
                                        <span id="add{{$i}}" class="add"
                                            onclick="updateAddtoCart({{ $cart->id }}, 'add{{$i}}' )"><i
                                                class="icofont icofont-plus"></i></span>
                                    </p>
                                </td>

                                <td class="text-center">
                                    @if($cart->has_attribute)
                                    {{-- we face data from product attribute table --}}
                                    {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                    @if( App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                                    {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price,0) }}
                                    @else
                                    {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->price,0) }}
                                    @endif
                                    @else
                                    @if($cart->product->discount_price)
                                    {{ round($cart->product->discount_price,0) }}
                                    @else
                                    {{ round($cart->product->price,0) }}
                                    @endif
                                    @endif
                                </td>

                                <td class="text-center" id="price{{$i}}">
                                    @if($cart->has_attribute)
                                    {{-- we face data from product attribute table --}}
                                    {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                    @if( App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                                    {{ App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price *  $cart->product_quantity }}
                                    @php $total_taka +=
                                    App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price *
                                    $cart->product_quantity @endphp
                                    @else
                                    {{ App\Models\ProductAttribute::find($cart->product_attribute_id)->price *  $cart->product_quantity  }}
                                    @php $total_taka +=
                                    App\Models\ProductAttribute::find($cart->product_attribute_id)->price *
                                    $cart->product_quantity @endphp
                                    @endif
                                    @else
                                    @if($cart->product->discount_price)
                                    {{ $cart->product->discount_price *  $cart->product_quantity }}
                                    @php $total_taka += $cart->product->discount_price * $cart->product_quantity @endphp
                                    @else
                                    {{ $cart->product->price  *  $cart->product_quantity }}
                                    @php $total_taka += $cart->product->price * $cart->product_quantity @endphp
                                    @endif
                                    @endif
                                </td>
                                <td class="close-cart">
                                    <button type="button" id="cart-close{{$i}}"
                                        onclick="cartClose({{ $cart->id }}, 'cart-close{{$i}}')"><i
                                            class="icofont icofont-close-line"></i></button>
                                </td>
                            </tr>
                            @php $i+=1 @endphp
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    {{-- @if(config('settings.tax_percentage'))
                                    <h4 class="text-right pb-3 h5 right-margin">SUBTOTAL-
                                        <span id="sub-total-tk" class="">{{ $total_taka }}</span><span
                                        class="ml-1">{{ config('settings.currency_symbol') }}</span>
                                    </h4>
                                    <h4 class="text-right pb-3 h5 right-margin">VAT-
                                        <span id="sub-total-tk"
                                            class="">{{ $total_taka * (config('settings.tax_percentage')/100) }}</span><span
                                            class="ml-1">{{ config('settings.currency_symbol') }}</span>
                                    </h4>
                                    @endif --}}
                                    <h4 class="text-right pb-5 h5 right-margin">TOTAL-
                                        <span id="sub-total-tk" class="">{{ $total_taka }}</span><span
                                            class="ml-1">{{ config('settings.currency_symbol') }}</span>
                                    </h4>
                                    <div class="buttons float-left">
                                        <a href="{{ route('products.index') }}"
                                            class="btn btn-theme btn-md btn-wide">Continue
                                            Shopping</a>
                                    </div>
                                    <div class="buttons float-right">
                                        <a href="{{ route('checkout.index') }}"
                                            class="btn btn-theme btn-md btn-wide">Checkout</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart End  -->

@endsection