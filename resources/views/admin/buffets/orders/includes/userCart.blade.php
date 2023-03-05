<div class="modal fade" id="userCartModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0 pb-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-shopping-basket"></i>
                    Customer Buffet Order Details
                </h5>
            </div>            
            <div class="modal-body text-center">
                <p class="text-center h6 mt-2">[ Order Number:&nbsp;
                    {{ $order->order_number }} ]</p>
                <p class="text-center h6 py-2">[ Payment Details:&nbsp;
                    @if((float)$order->cash_pay)<span>Cash:
                        {{ round($order->cash_pay,2)  }} {{ config('settings.currency_symbol') }}</span>@endif
                    @if((float)$order->card_pay)<span>, Card:
                        {{ round($order->card_pay,2)  }} {{ config('settings.currency_symbol') }}</span>@endif
                    @if((float)$order->mobile_banking_pay)<span>, Mobile Banking:
                        {{ round($order->mobile_banking_pay,2) }} {{ config('settings.currency_symbol') }}</span>@endif
                    ]</p>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <th>#</th>
                        <th>Food Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; $cart_model = 'App\Models\Buffetsale'; @endphp
                        @if($order->status == 'delivered' || $order->status == 'cancel' )
                        @php $cart_model = 'App\Models\Buffetsalebackup';@endphp
                        @endif
                        @foreach( $cart_model::where('buffetorder_id',
                        $order->id)->get() as $buffetCart)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="text-center" style="text-transform:capitalize">                          
                                {{ $buffetCart->product_name }}
                            </td>
                            <td class="text-center">
                                {{ $buffetCart->product_quantity }}
                            </td>
                            <td class="text-center" style="text-transform:capitalize">                                
                                {{ round($buffetCart->unit_price,2) }}                                
                            </td>
                            <td class="text-center" style="text-transform:capitalize">                               
                                {{ round( ($buffetCart->unit_price * $buffetCart->product_quantity),2) }}
                                @php $subtotal += $buffetCart->unit_price * $buffetCart->product_quantity; @endphp
                                
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <p class="text-right my-2 ">Subtotal:
                                    {{ round($subtotal,2) }} {{ config('settings.currency_symbol') }}
                                </p>
                                @if(config('settings.tax_percentage'))
                                <p class="text-right my-2 ">Vat ({{ config('settings.tax_percentage')}}%):
                                    {{ round($subtotal* (config('settings.tax_percentage')/100),2)  }}
                                    {{ config('settings.currency_symbol') }}
                                </p>
                                @endif
                                <p class="text-right my-2 ">Order Total:
                                    {{ round( ($subtotal + $subtotal * (config('settings.tax_percentage')/100)), 2)}}
                                    {{ config('settings.currency_symbol') }}
                                </p> 
                                @if((float)$order->fraction_discount)
                                <p class="text-right my-2 ">Fraction Discount:
                                    {{ round($order->fraction_discount,2) }} {{ config('settings.currency_symbol') }}
                                </p>
                                @endif                               
                                @if((float)$order->discount)
                                <p class="text-right my-2 ">Reference Discount:
                                    {{ round($order->discount,2) }} {{ config('settings.currency_symbol') }}                                   
                                </p>
                                @endif
                                @if((float)$order->reward_discount)
                                <p class="text-right my-2 ">Reward Points Discount:
                                    {{ round($order->reward_discount,2) }} {{ config('settings.currency_symbol') }}
                                </p>
                                @endif
                                @if((float)$order->gpstar_discount)
                                <p class="text-right my-2 ">GP Star Discount:
                                    {{ round($order->gpstar_discount,2) }} {{ config('settings.currency_symbol') }}
                                </p>
                                @endif  
                                @if((float)$order->card_discount)
                                <p class="text-right my-2 ">Card Discount:
                                    {{ round($order->card_discount,2) }} {{ config('settings.currency_symbol') }}
                                </p>
                                @endif                              
                                <p class="text-right mb-0 h6 mt-2">
                                    @if($order->status == 'delivered')
                                    {{ __('Paid Amount:') }} {{ round($order->grand_total,2) }}
                                    @else
                                    {{ __('Due Amount:') }} {{ round( ($subtotal + $subtotal * (config('settings.tax_percentage')/100)), 2) }}
                                    @endif
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
