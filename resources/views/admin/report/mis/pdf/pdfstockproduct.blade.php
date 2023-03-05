<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Analysis - {{ config('app.name', 'Funville') }}</title>
    <style>
        body{
            font-family: sans-serif,Tahoma,Verdana; 
            font-size:13px;
        }
        .content-wrapper {
            margin-left: auto;
            margin-right: auto;
            padding: 10px;
        }

        .invoice-header {
            padding-bottom: 5px;
            border-bottom: .8pt solid #5e5e5e;
            width: 100%;
        }

        .funville-address h4 {
            font-size: 1.25rem;
            text-transform: uppercase;
            margin-top: -10px;
        }

        h4 {
            font-size: 1.15rem;
            text-transform: inherit;
        }

        h5 {
            font-size: 1rem;
            text-transform: inherit;
        }

        p {
            font-weight: 400;
            line-height: 20px;
            padding: 0px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }
        .text-center{
            text-align:center;
        }
        .text-left{
            text-align: left;
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

        th{
            
            border-bottom: .8pt solid black;
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
            height: 30px;
            color: #333;
            text-transform: capitalize;
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
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
        }
        #footer {
            bottom: 0;
            border-top: 0.1pt solid #aaa;
        }
        .page-number:before {
            content: "Page " counter(page);
        }
        
    </style>
</head>

<body>
    <div id="footer">
        <div class="page-number" style="margin-top:2px;"></div>
        <div style="margin-top:-12px; text-align:right">Printed on {{  date("F j, Y", strtotime(now()))}}</div>
    </div> 
    <div class="content-wrapper">
        <div class="invoice-header">
            <div class="funville-logo float-left">
                <img src="{{ asset('backend/images') }}/logo_funville.png" alt="Funville" ;>
            </div>
            <div class="funville-address float-right">
                <h4 style="text-transform:capitalize;">{{ config('app.name', 'Funville') }} Limited
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
                <h5 style="margin-top:5px; text-align: left;">Report: Stock Analysis ({{ $stockoption ? 'Ingredient Category Wise' : 'Product Wise' }})</h5>
                <p style="margin-top:-45px; float:right; font-weight: bold;">Date: <span class="font-normal">{{ date("d-m-Y") }}</span></p>
            </div>            
            <div class="clearfix"></div>
        </div>
        @if($stockoption)
        <div style="width:100%; margin-bottom:-20px; margin-top:-10px;">
            <p class="text-left" style="font-size:15px;">
                <span>Ingredient Category :</span><span style="padding-left: 6px;">{{ App\Models\Typeingredient::where('id', $stockoption)->first()->name }}</span><br/>
            </p>
            <div class="clearfix"></div>
        </div>
        @endif
        <div class="product-description" style="margin-top:-10px;">
            <table>
                <thead>
                    <tr>
                        <th class="text-left"> # </th>
                        <th class="text-left"> Product Name </th>
                        <th class="text-left"> Qty</th>
                        <th class="text-left"> Threshold Qty</th>
                        <th class="text-left"> Unit </th>
                        <th class="text-left"> Amount </th>
                    </tr>
                </thead>
                <tbody>
                    @php $sum_total_qty = 0.0; $sum_total_price =0.0; @endphp
                    @foreach($ingredients as $ingredient)                   
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $ingredient->name }}
                        </td>
                        <td>
                            {{ round($ingredient->total_quantity,2) }}
                            @php $sum_total_qty += $ingredient->total_quantity;  @endphp
                        </td>
                        <td>
                            {{ round($ingredient->alert_quantity,2) }} 
                        </td>
                        <td>
                            {{ $ingredient->measurement_unit }} 
                        </td>
                        <td>
                            {{ round($ingredient->total_price, 2)}} {{ config('settings.currency_symbol') }}
                            @php $sum_total_price += $ingredient->total_price;  @endphp
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="invoice-header" style="margin-top:10px;">                        
        </div> 
        <div style="width:100%;">
            <div style="width:50%; float:left; margin-top:5px;">
                <span style="padding-left:120px; margin-top:30px; font-weight: bold;">Total Quantity: {{ $sum_total_qty }} </span>
            </div>
            <div style="width:50%; float:left; margin-top:5px;">
                <span style="padding-left:120px; margin-top:30px; font-weight: bold;">Total Amount: {{ $sum_total_price }} {{ config('settings.currency_symbol') }}</span>
            </div> 
            <div class="clearfix"></div>   
        </div>    
    </div>
 
    <script type="text/php">
        if (isset($pdf)) {
            $text = "page {PAGE_NUM} of {PAGE_COUNT}";            
            $size = 10;
            $font = $fontMetrics->getFont("Tahoma,Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>     
</body>

</html>