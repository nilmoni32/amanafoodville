<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash register wise sale - {{ config('app.name', 'Funville') }}</title>
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
        .text-right{
            text-align: right;
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
                <h5 style="margin-top:5px; text-align: left;">Report: Cash register wise sales [buffet]</h5>
                <p style="margin-top:-48px; float:right; font-weight: bold;">From: <span class="font-normal">{{ $start_date }} To: {{ $end_date }}</span></p>
            </div>            
            <div class="clearfix"></div>
        </div>
        <div class="mt-5" style="width:100%;">
            <div class="text-left" style="margin-top:5px; width:60%; float:left;">                
                <div style="line-height: 20px; font-weight:bold">
                    <span>Net Total Sales</span><span style="padding-left: 68px;">:</span>  {{ round($net_sales,2) }} {{ config('settings.currency_symbol') }}
                </div>
                <div style="line-height: 20px;">
                    <span>Net Cash Sales</span><span style="padding-left: 71px;">:</span>  {{ round($net_cash_sales,2) }} {{ config('settings.currency_symbol') }}
                </div>
                <div style="line-height: 20px;">
                    <span>Net Card Sales</span><span style="padding-left: 73px;">:</span>  {{ round($net_card_sales,2) }} {{ config('settings.currency_symbol') }}
                </div>
                <div style="line-height: 20px;">
                    <span>Net Mobile Bank Sales</span><span style="padding-left: 30px;">:</span>  {{ round($net_mobile_sales,2) }} {{ config('settings.currency_symbol') }}
                </div>
            </div>
            <div class="text-right" style="margin-top:-1px; float:right; width:40%;">  
                <table style='border: none; margin-top:-1px; cellspacing="0" cellpadding="0"; padding-bottom:20px;'>
                    <tr style="font-weight:bold;">
                        <td style="width:60%;text-align:left; height:20px;">Net Total Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round(($net_ref_discount + $net_points_discount + $net_card_discount + $net_gpstar_discount + $net_fraction_discount) ,2) }} 
                            {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%;text-align:left; height:20px;">Reference Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round($net_ref_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%;text-align:left; height:20px;">Customer Points Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round($net_points_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                    </tr><tr>
                        <td style="width:60%;text-align:left; height:20px;">Card Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round($net_card_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%;text-align:left; height:20px;">GP Star Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round($net_gpstar_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%;text-align:left; height:20px;">Fraction Discount</td>
                        <td style="width:40%;text-align:left; height:20px;"><span style="padding-left: -5px;">:</span>
                            {{ round($net_fraction_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div class="product-description" style="margin-top:-10px;">
            <table>
                <thead>
                    <tr>
                        <th class="text-left"> Receipt No </th>
                        <th class="text-left"> Time</th>                                                     
                        <th class="text-left"> Payment Method </th>
                        <th class="text-left"> Received Amount </th> 
                        <th class="text-left"> Total Discount </th>                         
                    </tr>
                </thead>
                <tbody>
                    @foreach($cash_register as $cash)  
                    @php $total_discount = $cash->discount + $cash->reward_discount + $cash->card_discount + $cash->gpstar_discount + $cash->fraction_discount;  @endphp                 
                    <tr>
                        <td>
                            {{ $cash->order_number }}
                        </td>
                        <td>
                            {{  date("h:i:s A", strtotime($cash->order_date))}}
                        </td>
                        <td>
                            {{ $cash->payment_method }}
                        </td>
                        <td>
                            {{ round($cash->grand_total,2) }} {{ config('settings.currency_symbol') }}
                        </td>
                        <td>
                            {{ round( $total_discount,2) }} {{ config('settings.currency_symbol') }}
                        </td>                       
                    </tr>
                    @endforeach

                </tbody>
            </table>
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