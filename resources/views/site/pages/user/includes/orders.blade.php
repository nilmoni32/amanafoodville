<div class="user-payment">
    <div class="table-responsive-md">
        @if(App\Models\Order::where('user_id', auth()->user()->id)->first())
        <div class="row">
            <div class="col-12">
                @php $orders = App\Models\Order::orderBy('created_at', 'desc')->where('user_id',
                auth()->user()->id)->paginate(5); @endphp
                @foreach($orders as $order)
                <div class="item-entry">
                    <span class="order-id">Order ID: {{ $order->order_number }}</span>
                    <div class="item-content">
                        <div class="item-body">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Food Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $cart_model = 'App\Models\Cart'; @endphp
                                    @if($order->status == 'delivered' || $order->status == 'cancel' )
                                    @php $cart_model = 'App\Models\Cartbackup';@endphp
                                    @endif
                                    @foreach( $cart_model::where('order_id', $order->id)->get() as $cart)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td class="text-left" style="text-transform:capitalize">
					   @if($cart->product->images->first())
                                            <img src="{{ asset('storage/'.$cart->product->images->first()->full) }}"
                                                title="{{ $cart->product->name }}"
                                                class="img-responsive d-inline-block pr-2 rounded" width="70px" />
					   @endif
                                            @if($cart->has_attribute)
                                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                            {{ $cart->product->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})
                                            @else
                                            {{ $cart->product->name }}
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $cart->product_quantity }}
                                        </td>

                                        <td class="text-center" style="text-transform:capitalize">
                                            @if($cart->has_attribute)
                                            {{-- we face data from product attribute table --}}
                                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                            @if(
                                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
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
                                        <td class="text-center" style="text-transform:capitalize">
                                            @if($cart->has_attribute)
                                            {{-- we face data from product attribute table --}}
                                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                                            @if(
                                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                                            {{ App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price *  $cart->product_quantity }}
                                            @else
                                            {{ App\Models\ProductAttribute::find($cart->product_attribute_id)->price *  $cart->product_quantity  }}
                                            @endif
                                            @else
                                            @if($cart->product->discount_price)
                                            {{ $cart->product->discount_price *  $cart->product_quantity }}
                                            @else
                                            {{ $cart->product->price  *  $cart->product_quantity }}
                                            @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($order->status != 'cancel' && $order->status != 'failed' )
                            <p class="p-0 m-0"><strong class="ml-0">Status:</strong></p>
                            <div class="cart-status d-flex cart-status-direction mt-1">
                                @if($order->status == 'pending')
                                <div class="cart-flex-item-active">Pending</div>
                                @else
                                <div class="cart-flex-item-nonactive">Pending</div>
                                @endif
                                @if($order->status == 'accept')
                                <div class="cart-flex-item-active">Accept</div>
                                @else
                                <div class="cart-flex-item-nonactive">Accept</div>
                                @endif
                                @if($order->status == 'cooking')
                                <div class="cart-flex-item-active">Cooking</div>
                                @else
                                <div class="cart-flex-item-nonactive">Cooking</div>
                                @endif
                                @if($order->status == 'packing')
                                <div class="cart-flex-item-active">Packing</div>
                                @else
                                <div class="cart-flex-item-nonactive">Packing</div>
                                @endif
                                @if($order->status == 'delivered')
                                <div class="cart-flex-item-active">Delivered</div>
                                @else
                                <div class="cart-flex-item-nonactive">Delivered</div>
                                @endif
                            </div>
                            @else
                            @if($order->status == 'cancel')
                            <p class="p-0 m-0"><strong class="ml-0">Status:</strong></p>
                            <div class="cart-status d-flex cart-status-direction mt-1">
                                <div class="cart-flex-item-cancel">Cancelled</div>
                            </div>
                            @else
                            <p class="p-0 m-0"><strong class="ml-0">Status:</strong></p>
                            <div class="cart-status d-flex cart-status-direction mt-1">
                                <div class="cart-flex-item-cancel">Failed</div>
                            </div>
                            @endif
                            @endif

                        </div>
                        <div class="item-footer">
                            <p>
                                <strong class="ml-0">Expected
                                    Date:</strong>{{  date('d-m-Y', strtotime($order->delivery_date )) }}
                                <strong>Grand Total:</strong>{{ config('settings.currency_symbol') }}
                                {{ round($order->grand_total,0) }}
                            </p>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>

        <div class="pt-4 text-center">
            {{ $orders->links() }}
        </div>

        @else
        <div class="col-12 text-center">
            <h4 class="p-5">
                {{ __( 'No Transaction has been made' )}}
            </h4>
        </div>
        @endif
    </div>
</div>
