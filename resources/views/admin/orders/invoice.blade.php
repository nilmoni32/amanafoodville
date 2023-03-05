<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/css/main.css" /> --}}
    <title>Invoice - {{ config('app.name', 'Funville') }}</title>
    <style>
        .content-wrapper {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
        }

        .invoice-header {
            padding-bottom: 5px;
            border-bottom: 2px solid #5e5e5e;
            width: 100%;
        }

        .funville-address h4 {
            font-size: 1.25rem;
            text-transform: uppercase;
            margin-top: -10px;
        }

        h4 {
            font-size: 1.22rem;
            text-transform: inherit;
        }

        h5 {
            font-size: 1.2rem;
            text-transform: inherit;
        }

        p {
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
            padding: 0px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        h4,
        h6,
        .h6 {
            margin-bottom: 0.5rem;
            font-family: inherit;
            font-weight: bold;
            line-height: 1.2;
            color: inherit;
        }

        .funville-logo {
            float: left;
            width: 20%;
        }

        .funville-logo img {
            width: 90%;
        }

        .funville-address {
            width: 40%;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }

        .clearfix {
            display: block;
            clear: both;
            content: "";
        }

        .font-normal {
            font-weight: 400;
        }

        .invoice-left-top {
            width: 40%;
        }

        .invoice-right-top {
            width: 40%;
        }

        .invoice-description {
            width: 100%;
            padding-top: 30px;
        }

        .product-description {
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
        }

        table {
            border-collapse: collapse;
        }

        table {
            width: 100%;
            margin-top: 30px;
        }

        tr {
            width: 100%;
        }

        th {
            background-color: #8c8c8c;
            height: 40px;
            color: #fff;
            text-transform: uppercase;
        }

        td {
            height: 30px;
        }

        .colspantd {
            text-align: right;
            border: unset;
            padding-right: 50px;
            height: 25px;
        }

        .font-bold {
            font-weight: 500;
        }

        .invoice-footer,
        .footer-thanks {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="invoice-header">
            <div class="funville-logo float-left">
                <img src="{{ asset('backend/images') }}/logo_funville.png" alt="Funville" ;>
            </div>
            <div class="funville-address float-right">
                <h4 class="text-uppercase">{{ config('app.name', 'Funville') }} Limited
                </h4>
                <p><span class="font-normal">{{ config('settings.contact_address') }}</span></p>
                <p style="margin-top:-5px;">Phone: <span class="font-normal">{{ config('settings.phone_no') }}</span>
                </p>
                <p style="margin-top:-10px;">E-mail: <span
                        class="font-normal">{{ config('settings.default_email_address') }}</span>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description">
            <div class="invoice-left-top float-left">
                <h5>Ship to,</h5>
                <p> {{ $order->user->name }}</p>
                <p style="margin-top:-10px;"><span class="font-normal">{{ $order->address }}</p>
                <p style="margin-top:-10px;">Phone: <span class="font-normal"> {{ $order->user->phone_number }}</span>
                </p>
                <p style="margin-top:-10px;">E-mail: <span class="font-normal">{{ $order->user->email }}</span>
            </div>
            <div class="invoice-right-top float-right">
                <h4 class="text-uppercase">Invoice No: {{ $order->order_number }}</h4>
                <p>Date:<span class="font-normal">
                        {{  date('Y-m-d', strtotime($order->delivery_date )) }}</span></p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="product-description">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal=0.0; $cart_model = 'App\Models\Cart'; @endphp
                    @if($order->status == 'delivered')
                    @php $cart_model = 'App\Models\Cartbackup';@endphp
                    @endif
                    @foreach( $cart_model::where('order_id',
                    $order->id)->get() as $cart)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td style="text-transform:capitalize">
                            @if($cart->has_attribute)
                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                            {{ $cart->product->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})
                            @else
                            {{ $cart->product->name }}
                            @endif
                        </td>
                        <td>
                            {{ $cart->product_quantity }}
                        </td>
                        <td style="text-transform:capitalize">
                            @if($cart->has_attribute)
                            {{-- we face data from product attribute table --}}
                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                            @if(
                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price,0) }}
                            {{ config('settings.currency_symbol') }}
                            @else
                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->price,0) }}
                            {{ config('settings.currency_symbol') }}
                            @endif
                            @else
                            @if($cart->product->discount_price)
                            {{ round($cart->product->discount_price,0) }} {{ config('settings.currency_symbol') }}
                            @else
                            {{ round($cart->product->price,0) }} {{ config('settings.currency_symbol') }}
                            @endif
                            @endif

                        </td>
                        <td style="text-transform:capitalize">
                            @if($cart->has_attribute)
                            {{-- we face data from product attribute table --}}
                            {{-- if this condition is true then $cart product_id is product_attribute id --}}
                            @if(
                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price)
                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price * $cart->product_quantity,0) }}
                            {{ config('settings.currency_symbol') }}
                            @php $subtotal +=
                            App\Models\ProductAttribute::find($cart->product_attribute_id)->special_price *
                            $cart->product_quantity; @endphp
                            @else
                            {{ round(App\Models\ProductAttribute::find($cart->product_attribute_id)->price * $cart->product_quantity,0) }}
                            {{ config('settings.currency_symbol') }}
                            @php $subtotal +=
                            App\Models\ProductAttribute::find($cart->product_attribute_id)->price *
                            $cart->product_quantity; @endphp
                            @endif
                            @else
                            @if($cart->product->discount_price)
                            {{ round($cart->product->discount_price * $cart->product_quantity,0) }}
                            {{ config('settings.currency_symbol') }}
                            @php $subtotal += $cart->product->discount_price * $cart->product_quantity;@endphp
                            @else
                            {{ round($cart->product->price * $cart->product_quantity,0) }}
                            {{ config('settings.currency_symbol') }}
                            @php $subtotal += $cart->product->price * $cart->product_quantity; @endphp
                            @endif
                            @endif

                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="colspantd" style="padding-top:10px;">
                            <span class="font-bold">Subtotal:</span>&nbsp;
                            {{ round($subtotal,0) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    @if(config('settings.tax_percentage'))
                    <tr>
                        <td colspan="5" class="colspantd">
                            <span class="font-bold">Vat ({{ config('settings.tax_percentage')}}%):</span>
                            @if(strlen(round($order->grand_total,0)) == 4)
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            @else
                            &nbsp;&nbsp;
                            @endif
                            {{ round($subtotal* (config('settings.tax_percentage')/100),0)  }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="5" class="colspantd">
                            <span class="font-bold">Delivery
                                Cost:</span>
                            @if(strlen(round($order->grand_total,0)) == 4)
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            @else
                            &nbsp;&nbsp;
                            @endif
                            {{ round(config('settings.delivery_charge'),0) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-footer">
            <div class="float-right">
                <p style="margin-top:-10px; margin-bottom:10px;">{{__('__________________________')}}</p>
                <p style="margin-top:-10px; margin-left:0px;"><span class="font-bold">Grand Total:</span>
                    @if(strlen(round($order->grand_total,0)) == 4)
                    &nbsp;&nbsp;
                    @else
                    &nbsp;&nbsp;&nbsp;
                    @endif
                    {{ round($order->grand_total,0) }}
                    {{ config('settings.currency_symbol') }}</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="invoice-footer">
            <div class="float-left">
                <p style="margin-top:70px; margin-bottom:10px;">{{__('__________________________')}}</p>
                <p style="margin-left: 50px;">[ Received by ]</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="footer-thanks" style="margin-top:100px;">
            <p class="font-normal" style="border-top:2px dotted #444; padding-top:7px;text-align:center;">Thank you for
                purchasing food
                items from Funville Restaurant & Party Center & Kids Zone. </p>


        </div>

    </div>
</body>

</html>
