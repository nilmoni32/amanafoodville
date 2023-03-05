<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Return Report - {{ config('app.name', 'Funville') }}</title>
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
            padding-bottom: 15px;
            border-bottom: .8pt solid #5e5e5e;
            width: 100%;
        }

        .funville-address h4 {
            font-size: 1.25rem;
            text-transform: uppercase;
            margin-top: -10px;
        }

        h4 {
            font-size: 1.56rem;
            text-transform: inherit;
        }

        h5 {
            font-size: 1.05rem;
            text-transform: inherit;
        }
        h6{
            font-size: 0.9rem;
        }

        p {
            font-weight: 400;
            font-size: 0.748rem;
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
            font-size: 0.75rem;
        }

        table {
            border-collapse: collapse;
        }

        table {
            width: 100%;
            margin-top: 30px;
        }

        table td{
            
            border-bottom: .8pt dashed rgb(147, 136, 136);
            font-size: 0.7rem;
        }

        th {
            height: 30px;
            color: #333;
            text-transform: capitalize;
        }

        table td {
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
            <div class=" float-left" style="width:100%;">
                <h5 style="margin-top: 0px; padding-bottom:10px; text-align:left; border-bottom: .8pt solid #5e5e5e;">Product Return Invoice</h5>
            </div>           
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description" style="margin-top:-18px;">
            <div class="float-left" style="width:100%;">
                <h6 style="margin:0; text-align:left;">Invoice Details:</h6>
            </div>            
            <p style="width:50%; padding-top:25px; float:left;"><span style="font-weight:bold;">Return No : </span>#{{ $return_challan->id }} </p>            
            <p style="width:50%;  padding-top:25px; float:right; direction: rtl;"><span style="font-weight:bold;">Return Date: </span> {{  date('d-m-Y', strtotime($return_challan->chalan_date)) }}</p>
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description" style="margin-top:-25px;">
            <p style="width:50%;  float:left;"><span style="font-weight:bold;">Request To :</span> {{ $return_challan->supplier->name }}</p>            
            <p style="width:50%;  float:right; direction: rtl;"><span style="font-weight:bold;">Purpose: </span>{{ $return_challan->purpose }}</p>
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description" style="margin-top:-25px;">
            <p style="width:50%;  float:left;"><span style="font-weight:bold;">Address: </span>{{ $return_challan->supplier->address }}</p>
            <div class="clearfix"></div>
        </div>
        
        <div class="invoice-description" style="margin-top:2px;  border-top: .8pt solid #5e5e5e;">
        </div>
        <div class="product-description" style="margin-top:-15px;">
            <table>
                <thead>
                    <tr>
                        <th style="text-align:left; width:10px;"> # </th>                                             
                        <th style="text-align:left; width:45%"> Product Details </th>
                        <th style="text-align:right"> Stock Unit </th>
                        <th style="text-align:right"> Stock Qty </th>                        
                        <th style="text-align:right"> Quantity </th>
                        <th style="text-align:right"> Cost Price </th>                            
                        <th style="text-align:right"> Total Amount </th>
                    </tr>
                </thead>
                <tbody>
                    @php $qty = 0; $total=0.0;@endphp
                    @foreach($return_items as $item)
                    @php 
                    $qty += $item->quantity; 
                    $total += $item->total;
                    @endphp                   
                    <tr>
                        <td class="text-left" style="width:10px;">{{ $loop->index + 1  }}</td>
                        <td class="text-left" style="width:45%">{{ $item->supplier_stock->supplier_product_name }}</td>                        
                        <td style="text-align:right">{{ $item->unit }}</td>
                        <td style="text-align:right">{{ $item->stock }}</td>
                        <td style="text-align:right">{{ $item->quantity }}</td>
                        <td style="text-align:right">{{ number_format($item->unit_cost , 2, '.', '') }} {{ config('settings.currency_symbol') }}</td>
                        <td style="text-align:right">{{ number_format($item->total , 2, '.', '') }} {{ config('settings.currency_symbol') }}</td>                 
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-left" style="font-weight:bold;">Total: </td>
                        <td></td><td></td><td></td>
                        <td style="text-align:right; font-weight:bold;">{{ number_format($qty , 2, '.', '')}}</td><td></td>
                        <td style="text-align:right; font-weight:bold;">{{ number_format($total , 2, '.', '') }} {{ config('settings.currency_symbol') }}</td>
                    </tr>                   
                    
                </tbody>
            </table>
        </div>
        <div class="product-description" style="margin-top:25px;">
            <p style="width:100%;">
                <span style="border-bottom:0.8pt solid #000; width:25%;text-align:center;float:left; padding-bottom:3px;">
                    {{ $return_challan->admin->name}}</span>
                <span style="border-bottom:0.8pt solid #000; width:25%; direction:rtl; padding-bottom:3px;float:right;">&nbsp;</span>
            </p>      
            <div class="clearfix"></div>
        </div>
        <div class="product-description" style="margin-top:-12px;">
            <p style="width:100%;">
                <span style="width:25%;text-align:center;float:left; padding-bottom:3px;">Created by</span>
                <span style="width:25%; direction:rtl; padding-bottom:3px;float:right; text-align:center">Authorized signature </span>
            </p>      
            <div class="clearfix"></div>
        </div>

        <div class="product-description" style="margin-top:40px;">
            <p style="width:100%;">
                <span style="width:100%;text-align:center;float:left; padding-bottom:3px; font-weight:bold;">
               {{ __('This is a computer generated report, hence no signature is required') }}</span>
            </p>      
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