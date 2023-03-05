<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daily Ingredient Purchase List - {{ config('app.name', 'Funville') }}</title>
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
            <div class="invoice-left-top float-left">
                <h5 style="margin-top:10px;">Daily Ingredient Purchase list</h5>
            </div>
            <div class="invoice-right-top float-right">
                <p style="margin-top:10px;">Date: <span class="font-normal">{{ date('Y-m-d') }}</span></p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="product-description">
            <table>
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th style="text-align:center;">Ingredient Name</th>
                        <th style="text-align:center;">Stock Qty</th>
                        <th style="text-align:center;">Alert Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ingredient)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td style="text-transform:capitalize">
                            {{ $ingredient->name }}
                        </td>
                        <td>
                            {{ $ingredient->total_quantity }} {{ $ingredient->measurement_unit }}
                        </td>
                        <td style="text-transform:capitalize">
                            {{ $ingredient->alert_quantity }} {{ $ingredient->measurement_unit }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>