<div class="modal fade" id="userCartModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-shopping-basket"></i>
                    Customer Order Details
                </h5>
            </div>
            <div class="modal-body text-center">
                <p class="text-center h6 pb-2">[ Order Number:&nbsp;
                    {{ $order->order_number }} ]</p>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <th>#</th>
                        <th>Food Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; $cart_model = 'App\Models\Cart'; @endphp
                        @if($order->status == 'delivered' || $order->status == 'cancel' )
                        @php $cart_model = 'App\Models\Cartbackup';@endphp
                        @endif
                        @foreach($cart_model::where('order_id',
                        $order->id)->get() as $cart)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="text-left" style="text-transform:capitalize">
				@if($cart->product->images->first())
                                <img src="{{ asset('storage/'.$cart->product->images->first()->full) }}"
                                    title="{{ $cart->product->name }}" class="img-responsive pr-2 rounded"
                                    width="70px" />
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
                                @php $subtotal +=
                                App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price *
                                $cart->product_quantity; @endphp
                                @else
                                {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->price,0) }}
                                @php $subtotal +=
                                App\Models\ProductAttribute::find($cart->product_attribute_id)->price *
                                $cart->product_quantity; @endphp
                                @endif
                                @else
                                @if($cart->product->discount_price)
                                {{ round($cart->product->discount_price,0) }}
                                @php $subtotal += $cart->product->discount_price * $cart->product_quantity;@endphp
                                @else
                                {{ round($cart->product->price,0) }}
                                @php $subtotal += $cart->product->price * $cart->product_quantity; @endphp
                                @endif
                                @endif

                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4">
                                <p class="text-right mb-0 ">Subtotal:
                                    {{ round($subtotal,0) }}
                                    {{ config('settings.currency_symbol') }}
                                </p>
                                @if(config('settings.tax_percentage'))
                                <p class="text-right mb-0 ">Vat ({{ config('settings.tax_percentage')}}%):
                                    {{ round($subtotal* (config('settings.tax_percentage')/100),0)  }}
                                    {{ config('settings.currency_symbol') }}
                                </p>
                                @endif
                                <p class="text-right mb-0 ">Delivery
                                    Cost:
                                    {{ round(config('settings.delivery_charge'),0) }}
                                    {{ config('settings.currency_symbol') }}
                                </p>
                                <p class="text-right mb-0 h6 mt-2">
                                    Grand Total:
                                    {{ round($order->grand_total,0) }}
                                    {{ config('settings.currency_symbol') }}
                                </p>

                            </td>
                        </tr>
                    </tbody>
                </table>


            </div>
            <div class=" modal-footer border-top-0">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
