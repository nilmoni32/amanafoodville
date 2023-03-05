@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.buffet.menu.index') }}">{{ __('Buffet Menu List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-2">
        <div class="tile p-0">
            @include('admin.buffets.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile px-5">
            <div>
                <h4 class="tile-title text-center">{{ $buffet->buffet_name }} Order Place & Customer Bill </h4>
            </div>
            <hr>
            <div class="tile-body mt-3">
                <div class="tile-body">  
                    <form action="{{ route('admin.buffet.menu.orderPlace')}}" method="post">
                        @csrf                       
                        <div class="row">              
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="guest_no">No. of Guest </label>
                                    <input class="form-control @error('guest_no') is-invalid @enderror" type="text"
                                        placeholder="Enter no of guests to be served" id="guest_no" name="guest_no" value="{{ old('guest_no') }}" required/>
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('guest_no')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="control-label" for="order_tableNo">Guest Table no.</label>
                                    <select name="order_tableNo" id="order_tableNo" class="form-control" @error('order_tableNo') is-invalid @enderror" required>
                                        <option></option>
                                        @for($i=1; $i<= config('settings.total_tbls'); $i++) <option value="T-{{ $i }}">
                                            Table
                                            No: {{ $i }}</option>
                                            @endfor
                                    </select>
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('order_tableNo')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="buffet_id" value="{{ $buffet->id }}">
                            <div class="col-md-4 col-12 mb-2 mt-4 pt-1">                            
                                <button type="submit" class="btn btn-primary w-100" {{ !$order_id ? '' : 'disabled' }}>Place the order</button>                            
                            </div>          
                        </div> 
                    </form> 
                    <hr> 
                    <div class="row">
                        <div class="offset-md-3 col-md-6 offset-md-3">
                            <div class="border px-4 rounded pb-4 mb-4 mt-4" style="border-color:rgb(182, 182, 182);">
                                <div class="text-center pt-4">
                                    <label class="checkbox">
                                        <input type="checkbox" id="useDefaultPrinter" /> {{ __('Print to Default printer') }}
                                    </label>
                                    <br>
                                    <div id="installedPrinters">
                                        <label for="installedPrinterName">or Select an installed Printer:</label><br>
                                        <select name="installedPrinterName" id="installedPrinterName"
                                            class="form-control mt-1 mb-2"></select>
                                    </div>

                                    <button type="button" onclick="print();"
                                        class="btn btn-primary text-center text-uppercase"
                                        style="display:block; width:100%;"
                                        {{ $order_id && App\Models\Buffetorder::where('id', $order_id)->first()->status == 'receive' ? '' : 'disabled' }}>Print Receipt</button>
                                </div>
                            </div>
                        </div>
                    </div>                      
                </div>
                <hr>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('backend') }}/js/pos/zip.js"></script>
<script src="{{ asset('backend') }}/js/pos/zip-ext.js"></script>
<script src="{{ asset('backend') }}/js/pos/deflate.js"></script>
<script src="{{ asset('backend') }}/js/pos/JSPrintManager.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#order_tableNo').select2({
            placeholder: "Select Order Table No",
            // allowClear: true,
            multiple: false,  
            width: '100%',                       
        });

    });
     
    
  //POS PRINT RECEIPT : https://www.neodynamic.com/articles/How-to-print-raw-ESC-POS-commands-from-Javascript/
  
  
  //WebSocket settings
  JSPM.JSPrintManager.auto_reconnect = true;
    JSPM.JSPrintManager.start();
    JSPM.JSPrintManager.WS.onStatusChanged = function () {
        if (jspmWSStatus()) {
            //get client installed printers
            JSPM.JSPrintManager.getPrinters().then(function (myPrinters) {
                var options = '';
                for (var i = 0; i < myPrinters.length; i++) {
                    options += '<option>' + myPrinters[i] + '</option>';
                }
                $('#installedPrinterName').html(options);
            });
        }
    };
 
    //Check JSPM WebSocket status
    function jspmWSStatus() {
        if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Open)
            return true;
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Closed) {
            alert('JSPrintManager (JSPM) is not installed or not running! Download JSPM Client App from https://neodynamic.com/downloads/jspm');
            return false;
        }
        else if (JSPM.JSPrintManager.websocket_status == JSPM.WSStatus.Blocked) {
            alert('JSPM has blocked this website!');
            return false;
        }
    }
 
    //Do printing...
    function print(o) {        

        if(jspmWSStatus()) {
                //Create a ClientPrintJob
                var cpj = new JSPM.ClientPrintJob();
                //Set Printer type (Refer to the help, there many of them!)
                if ($('#useDefaultPrinter').prop('checked')) {
                    cpj.clientPrinter = new JSPM.DefaultPrinter();
                } else {
                    cpj.clientPrinter = new JSPM.InstalledPrinter($('#installedPrinterName').val());
                }
                //Set content to print...
                //Create ESP/POS commands for sample label
                var esc = '\x1B'; //ESC byte in hex notation
                var newLine = '\x0A'; //LF byte in hex notation     
                var centerAlign = '\x1B' + '\x61' + '\x31';
                var leftAlign =  '\x1B' + '\x61' + '\x30'; // left align       
                var cmds = esc + "@"; //Initializes the printer (ESC @)
                cmds += esc + '!' + '\x30'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                cmds += "   {{ config('settings.site_name') }}"; //text to print site name
                cmds += newLine;
                cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex            
                cmds += newLine;
                cmds += "{{ __('The Restaurant, Party center and Kids zone.') }}"; //text to print site title
                cmds += newLine;
                cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
                cmds += centerAlign;
                cmds += "---------------------------------------";
                cmds += leftAlign;
                cmds += newLine;            
                cmds += "Location: {{ config('settings.contact_address') }}"; //text to print site address
                cmds += newLine;
                cmds += "Contact no: {{ config('settings.phone_no') }}";
                cmds += newLine;         
                cmds += "Operator Name: {{ auth()->user()->name }}";
                cmds += newLine;
                cmds += "Date: {{ date('d-M-Y h:i:s A') }}";
                cmds += newLine;
                cmds += "---------------------------------------";
                cmds += newLine;
                cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex     
                @if ($order_id && App\Models\Buffetorder::find($order_id)->status == 'receive')
                @php $buffet_sale = App\Models\Buffetsale::where('buffetorder_id', $order_id)->first()@endphp
                cmds += "Customer Order Table No: " + "{{  $buffet_sale->order_tbl_no }}";                
                cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
                cmds += newLine;
                cmds += "---------------------------------------";
                cmds += newLine;
                cmds += "#Item     #Qty     #Price     #subtotal";
                cmds += newLine;
                cmds += "---------------------------------------";
                cmds += newLine;
                let vatPercentage = "{{config('settings.tax_percentage')}}";                        
                //calculating paid amount
                let paidAmount = "{{ $buffet_sale->product_quantity }}" * "{{ $buffet_sale->unit_price }}";                            
                cmds += "{{ $buffet_sale->product_name }}" ;
                cmds += newLine;
                cmds += "          "+ "{{ $buffet_sale->product_quantity }}" + '   X   ' +"{{round($buffet_sale->unit_price,2) }}" + "       "+ paidAmount;
                cmds += newLine;                
                cmds += "---------------------------------------";
                cmds += newLine;            
                cmds += "Subtotal Without VAT:          "+ paidAmount;
                cmds += newLine;
                foodVat = paidAmount * (vatPercentage/100);
                @if(config('settings.tax_percentage'))
                cmds += "        ++ VAT ("+ vatPercentage +"%)           "+foodVat;
                cmds += newLine;
                @endif            
                cmds += "                          -------------";
                cmds += '         Total Payable Amount:          '+ (paidAmount+ foodVat);
                cmds += newLine;
                @endif
                cmds += "---------------------------------------";
                cmds += newLine;
                cmds += newLine;
                cmds += 'Note: Sold food items can not be refunded';
                cmds += newLine;
                cmds += 'but may be exchanged only within 2 hours';
                cmds += newLine;
                cmds += 'with relevant receipt. For any inquiry,';
                cmds += newLine;
                cmds += 'please contact with us. Thanks for coming';
                cmds += newLine;
                cmds += 'to Amana Funville.';
                cmds += newLine + newLine;
                cmds += esc + '!' + '\x18'; //Emphasized + Double-height mode selected (ESC ! (16 + 8)) 24 dec => 18 hex
                
                cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
                cmds += newLine + newLine;
                cmds += newLine + newLine;
                cmds += newLine + newLine;
                cmds += "\x1b" + "\x69"; // cut command for pos print receipt.
                cpj.printerCommands = cmds;
                //Send print job to printer!
                cpj.sendToClient();
            }
        }
        
</script>
@endpush


