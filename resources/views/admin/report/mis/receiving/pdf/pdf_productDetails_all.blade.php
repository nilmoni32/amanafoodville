<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receiving Details Report - {{ config('app.name', 'Funville') }}</title>
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

        .record{
            border-top: .8pt dashed rgb(147, 136, 136);
            border-bottom: .8pt dashed rgb(147, 136, 136);
            font-weight:700;
            font-size:0.75rem;
        }

        tr {
            width: 100%;
        }

        /* th {
            height: 30px;
            color: #333;
            text-transform: capitalize;
        } */
        th{
            border-bottom: .8pt solid black;
            text-align: center; 
            font-size: 0.75rem;
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
        .font-weight-bold{
            font-weight: 700;
            font-size: 0.75rem;
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
                <h6 style="margin-top:5px; text-align: left;">Report: All Supplier Receiving Report Details</h6>                
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="invoice-description" style="margin-top:2px;  border-top: .8pt solid #5e5e5e;">
        </div>
        <div class="invoice-description">            
            <p style="width:50%; float:left;"><span style="font-weight:bold;">Supplier : </span>All</p>            
            <p style="width:50%; float:right; direction: rtl;"><span style="font-weight:bold;">From: </span>{{  date('d-m-Y', strtotime($start_date)) }} <span style="font-weight:bold;">To: </span>{{ date('d-m-Y', strtotime($end_date)) }}</p>
            <div class="clearfix"></div>
        </div>
        
        <div class="product-description" style="margin-top:-10px;">
            @php $grnd_total_qty = 0; $grnd_total_amount = 0.0; $tot_req =0.0; $req_no = 0; @endphp
                @foreach($challans as $challan)
            <table  style="margin-bottom:-15px;">
                <tbody>
                    <tr>
                        <td class="record text-center" colspan="5">Supplier: {{ App\Models\Supplier::find($challan->supplier_id)->name }}</td>                        
                    </tr>
                    <tr>
                        <td class="record">Rec. No:  #{{ $challan->id }}</td>
                        <td class="record">Req. Date:  {{ date('d-m-Y', strtotime(explode(' ', $challan->requisition_date)[0])) }}</td>
                        <td class="record">Receiving Date:  {{ date('d-m-Y', strtotime(explode(' ', $challan->payment_date)[0])) }}</td>
                        <td class="record">Total Qty:  {{ $challan->total_quantity }}</td>
                        <td class="record">Total Amount:  {{ round($challan->total_amount,2) }} {{ config('settings.currency_symbol') }}</td>                        
                    </tr>
                </tbody>                
            </table>            
            <table>
                <thead>
                    <tr>
                        <th class="text-center"> # </th>                            
                        <th class="text-center"> Product Name </th>
                        <th class="text-center"> Product Unit </th>
                        <th class="text-center"> Stock Qty </th>
                        <th class="text-center"> Receive Qty </th>
                        <th class="text-center"> Unit Cost </th>                            
                        <th class="text-center"> Total Cost </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(App\Models\ReceiveIngredientList::where('receive_from_supplier_id', $challan->id)->get() as $challan_product)
                    <tr>
                        <td class="text-center">{{ $loop->index + 1  }}</td>
                        <td class="text-center">{{ $challan_product->name }}</td>
                        <td class="text-center">{{ $challan_product->unit }}</td>
                        <td class="text-center">{{ $challan_product->stock }}</td>
                        <td class="text-center">{{ $challan_product->quantity }}</td>
                        <td class="text-center">{{ $challan_product->unit_cost }}</td>
                        <td class="text-center">{{ round($challan_product->total,2) }} {{ config('settings.currency_symbol') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @endforeach
        </div>
        <div class="product-description" style="margin-top:50px;">
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