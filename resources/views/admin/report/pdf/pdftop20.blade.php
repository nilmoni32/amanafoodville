<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Top 20 Sale - {{ config('app.name', 'Funville') }}</title>
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
            font-size: 1.25rem;
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
            padding-top: 10px;
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
                <h4 class="text-uppercase">{{ config('app.name', 'Funville') }} Restaurant
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
            <div class="invoice-left-top float-left" style="width:100%;">
                <h5 style="margin-top:10px;">Top 20 Sale Reports</h5>
                <p style="margin-top:10px;">Time frame: <span class="font-normal">between {{ $start_date }} and
                        {{ $end_date }}</span></p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="product-description" style="margin-top:-10px;">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Food Name</th>
                        <th>Unit Price</th>
                        <th>Total Qty</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1; @endphp
                    @foreach($time_carts as $cart)
                    @php if($i == 21) break; @endphp
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td style="text-transform:capitalize">
                            @if($cart->product_attribute_id)
                            {{ App\Models\Product::find($cart->product_id)->name }}-({{ App\Models\ProductAttribute::find($cart->product_attribute_id)->size }})
                            @else
                            {{ App\Models\Product::find($cart->product_id)->name }}
                            @endif
                        </td>
                        <td>
                            {{ round( $cart->unit_price,0) }} {{ config('settings.currency_symbol') }}
                        </td>
                        <td style="text-transform:capitalize">
                            {{ $cart->total_qty}}
                        </td>
                        <td class="text-center">
                            {{ round($cart->subtotal,0) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>