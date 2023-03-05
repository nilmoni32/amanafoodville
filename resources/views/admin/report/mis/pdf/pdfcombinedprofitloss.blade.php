<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Combined Profit-Loss Report - {{ config('app.name', 'Funville') }}</title>
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
        h6{
            font-size: 0.9rem;
        }

        p {
            font-weight: 400;
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
        td{
            border-bottom: .8pt dashed #000;
        }
        tr:last-child td{
            border-bottom: none;
            border-top: .8pt solid #000;
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
        tr:last-child td{
            font-weight: bold;
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
    {{-- in order to display footer page number in each page we need to add the following #footer div --}}
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
                <h6 style="margin-top:5px; text-align: left;">Report: Combined Profit Loss Report </h6>
                <p style="margin-top:-25px; float:right; font-weight: bold;">From: <span class="font-normal">{{ $start_date }} To: {{ $end_date }}</span></p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description">
            <div class="invoice-left-top float-left" style="width:100%;">                
                @if($kot_sales->count() > 0 || $ecom_sales->count() > 0 || $buffet_sales->count() > 0)
                {{-- Calculating total sales of kot  --}}
                @php $kot_totalSalesCost=0.0; $ecom_totalSalesCost=0.0; $buffet_totalSalesCost=0.0; 
                     $ecom_totalSales=0.0; $kot_totalSales= $discount->total_sales - $discount->total_vat; $buffet_totalSales= $discountBuffet->total_sales - $discountBuffet->total_vat;
                @endphp
                @foreach($kot_sales as $cart)
                    @php $kot_totalSalesCost += $cart->salesCost; 
                    @endphp
                @endforeach 
                {{-- Calculating total sales of buffet  --}}
                @foreach($buffet_sales as $cart)
                    @php $buffet_totalSalesCost += $cart->salesCost; 
                    @endphp
                @endforeach 

                {{-- Calculating total sales of ecommerce  --}}
                @foreach($ecom_sales as $cart)
                    @php $ecom_totalSalesCost +=$cart->salesCost; $ecom_totalSales += $cart->sales;
                    @endphp                    
                @endforeach
                
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="product-description" style="margin-top:-10px;">
            <table>
                <thead>
                    <tr>
                        <th class="text-center"> # </th>                                
                        <th class="text-center"> Sales Type </th>
                        <th class="text-center"> Net Sales</th>
                        <th class="text-center"> Net Sales Cost </th>                            
                        <th class="text-center"> Net Profit/Loss </th>
                    </tr>
                </thead>                
                @php $i=1; @endphp
                <tbody>  
                    @if($ecom_sales->count() > 0)         
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>                               
                        <td class="text-center">
                            {{ _('E-commerce profit-loss') }}
                        </td>
                        <td class="text-center">{{ round( $ecom_totalSales,2) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                        <td class="text-center">{{ round( $ecom_totalSalesCost,2) }}
                            {{ config('settings.currency_symbol') }}                                  
                        </td>
                        <td class="text-center">
                            {{ round(($ecom_totalSales - $ecom_totalSalesCost),2) }} {{ config('settings.currency_symbol')  }}  
                            &nbsp; {{ "( " . round((($ecom_totalSales - $ecom_totalSalesCost) / $ecom_totalSalesCost)*100,2) ."% )" }}
                        </td>
                    </tr>
                    @endif
                    @if($kot_sales->count() > 0)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>                               
                        <td class="text-center">
                            {{  _('MIS KOT profit-loss') }}
                        </td>
                            {{-- Already deducted Reference Discount & Customer Points Discount from KOT order sales. --}}
                        <td class="text-center">{{ round($kot_totalSales,2) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                            {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                        <td class="text-center">{{ round(($kot_totalSalesCost + $complimentary_sales_cost),2) }}
                            {{ config('settings.currency_symbol') }}                                  
                        </td>
                        <td class="text-center">
                            {{ round($kot_totalSales -($kot_totalSalesCost + $complimentary_sales_cost),2) }} {{ config('settings.currency_symbol')  }}  
                            &nbsp; {{ "( " . round((($kot_totalSales-($kot_totalSalesCost + $complimentary_sales_cost)) / 
                            ($kot_totalSalesCost + $complimentary_sales_cost))*100,2) ."% )" }}
                        </td>
                    </tr>
                    @endif
                    @if($buffet_sales->count() > 0)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>                               
                        <td class="text-center">
                            {{  _('MIS Buffet profit-loss') }}
                        </td>
                        {{-- Already deducted Reference Discount & Customer Points Discount from buffet order sales. --}}
                        <td class="text-center">{{ round($buffet_totalSales,2) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                            {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                        <td class="text-center">{{ round(($buffet_totalSalesCost),2) }}
                            {{ config('settings.currency_symbol') }}                                  
                        </td>
                        <td class="text-center">
                            {{ round($buffet_totalSales-($buffet_totalSalesCost),2) }} {{ config('settings.currency_symbol')  }}  
                            &nbsp; {{ "( " . round((($buffet_totalSales-($buffet_totalSalesCost)) /                             
                            ($buffet_totalSalesCost))*100,2) ."% )" }}
                        </td>
                    </tr>
                    @endif
                    @php 
                        $total_sales = $kot_totalSales + $ecom_totalSales + $buffet_totalSales;
                        $total_sales_cost = $ecom_totalSalesCost + ($kot_totalSalesCost + $complimentary_sales_cost) + $buffet_totalSalesCost;
                        $net_profit_loss = $total_sales - $total_sales_cost;
                        $percentile_net_profit_loss = round(($net_profit_loss / $total_sales_cost)*100,2);
                    @endphp
                                        
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>                               
                        <td class="text-center">
                            {{  _('Combined profit-loss') }}
                        </td>
                        {{-- Already deducted Reference Discount & Customer Points Discount from KOT order sales. --}}
                        <td class="text-center">{{ round($total_sales,2) }}
                            {{ config('settings.currency_symbol') }}
                        </td>
                            {{-- Adding complimentary food sales cost with KOT total sales cost --}}
                        <td class="text-center">{{ round($total_sales_cost,2) }}
                            {{ config('settings.currency_symbol') }}                                  
                        </td>
                        <td class="text-center">
                            {{ round($net_profit_loss,2) }} {{ config('settings.currency_symbol')  }}  
                            &nbsp; {{ "( " . $percentile_net_profit_loss ."% )" }}
                        </td>
                    </tr>  
                </tbody>
                @endif
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