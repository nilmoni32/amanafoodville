<div class="modal fade" id="customerPrintModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0 pb-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-shopping-basket"></i>
                    Customer Print Receipt
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="text-center">
                    <label class="checkbox">
                        <input type="checkbox" id="useDefaultPrinter" /> <strong>Print to
                            Default
                            printer</strong>
                    </label>
                    <br>
                    <div id="installedPrinters">
                        <label for="installedPrinterName">or Select an installed Printer:</label><br>
                        <select name="installedPrinterName" id="installedPrinterName"
                            class="form-control mt-1 mb-2"></select>
                    </div>
                    <button type="button" onclick="print();"
                        class="btn btn-primary text-center text-uppercase"
                        style="display:block; width:100%;">Print
                        Receipt</button>
                </div>
            </div>
            <div class=" modal-footer border-top-0">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
@push('scripts')
<script src="{{ asset('backend') }}/js/pos/zip.js"></script>
<script src="{{ asset('backend') }}/js/pos/zip-ext.js"></script>
<script src="{{ asset('backend') }}/js/pos/deflate.js"></script>
<script src="{{ asset('backend') }}/js/pos/JSPrintManager.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script type="text/javascript">

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
                if (jspmWSStatus()) {
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
                    var cmds = esc + "@"; //Initializes the printer (ESC @)
                    var center = '\x1B' + '\x61' + '\x31'; //center align
                    var left = '\x1B' + '\x61' + '\x30'; // left align
                    var right = '\x1B' + '\x61' + '\x32'; // right align
                    cmds += esc + '!' + '\x22'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                    cmds += center;
                    cmds += '\x1B' + '\x45' + '\x0D'; //bold on
                    cmds += "{{ strtoupper(config('settings.site_name')) }}"; //text to print site name
                    cmds += newLine;
                    cmds += esc + '!' + '\x08';                            
                    cmds += newLine;
                    cmds += "{{ __('The Best Restaurant, Party center and Kids zone in Rajshahi.') }}"; //text to print site title
                    cmds += newLine;
                    cmds += '\x1B' + '\x45' + '\x0A';
                    cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)
                    cmds += "----------------------------------------";
                    cmds += left;
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
                    //cmds += esc + '!' + '\x08'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                    cmds += '\x1B' + '\x45' + '\x0D'; //bold on
                    cmds += 'Customer Order Details';
                    cmds += newLine;
                    cmds += '\x1B' + '\x45' + '\x0A'; //bold off
                    cmds += "---------------------------------------";
                    cmds += esc + '!' + '\x00'; //Character font A selected (ESC ! 0)                                        
                    cmds += newLine;
                    cmds += "Customer Name: {{ $order->client->name }}"
                    cmds += newLine;
                    cmds += "Contact No: {{ $order->client->mobile }}"      
                    cmds += newLine;
                    cmds += "Order No: {{ $order->order_number }}"                                                                        
                    @if(App\Models\Duesale::where('dueordersale_id', $order->id)->first())
                    cmds += newLine; 
                    cmds += "---------------------------------------"; 
                    cmds += newLine; 
                    cmds += "#Item     #Qty     #Price     #subtotal";
                    cmds += newLine;
                    cmds += "---------------------------------------";
                    cmds += newLine;
                    @php $sub_tot_without_vat = 0.0;
                    $vat_percentage = config('settings.tax_percentage');
                    $discount =0; 
                    @endphp                    
                    @foreach(App\Models\Duesale::where('dueordersale_id', $order->id)->get() as $saleCart)        
                        cmds += "{{ $saleCart->product_name }}" ;
                        cmds += newLine;
                        cmds += "           {{ $saleCart->product_quantity}}" + '   X   ' + "{{ round($saleCart->unit_price,2) }}" + "      {{ round($saleCart->product_quantity *  $saleCart->unit_price,2) }} "
                        cmds += newLine;                
                        cmds += "---------------------------------------";
                        cmds += newLine;            
                        @php $sub_tot_without_vat += $saleCart->product_quantity *  $saleCart->unit_price; @endphp                
                    @endforeach                   
                    cmds += "Subtotal Without VAT:          {{ $sub_tot_without_vat }}";
                    cmds += newLine;
                    @php $food_vat = $sub_tot_without_vat * ($vat_percentage)/100; @endphp
                    @if(config('settings.tax_percentage'))
                    cmds += "        ++ VAT ({{ $vat_percentage }}%):          {{$food_vat}}";
                    cmds += newLine;
                    @endif            
                    cmds += "                          -------------";
                    cmds += '                 Total Amount:          {{ $sub_tot_without_vat + $food_vat }}';
                    @endif
                    @php $dueorder_sale = App\Models\Dueordersale::find($order->id); @endphp
                    cmds += newLine;                    
                    @if(!$dueorder_sale->grand_total)
                    cmds += newLine; 
                    cmds += 'Booking Amount:                {{ round($dueorder_sale->booked_money,2) }}'; 
                    @endif
                    cmds += newLine;          
                    cmds += "Paid Amount:                   {{ $dueorder_sale->grand_total ? round($dueorder_sale->grand_total,2) : round($dueorder_sale->booked_money,2)   }}";
                    cmds += newLine; 
                    cmds += 'Amount Due:                    {{ App\Models\Duesale::where('dueordersale_id', $order->id)->first() ? round((($sub_tot_without_vat + $food_vat) - ($dueorder_sale->grand_total ?? $dueorder_sale->booked_money)),2) : '' }}';                                                             
                    @if($order->id && $dueorder_sale->grand_total)
                    cmds += newLine; 
                    cmds += newLine;
                    cmds += 'Payment Mode Details:          ';
                    cmds += newLine; 
                    cmds += "---------------------------------------";
                    cmds += newLine;                    
                    cmds += 'Booking Amount:                {{ round($dueorder_sale->booked_money,2) }}';
                    cmds += newLine;
                    @if((float)App\Models\Dueordersale::find($order->id)->cash_pay)
                    cmds += 'CASH:                          {{ round($dueorder_sale->cash_pay,2) }}';
                    cmds += newLine;
                    @endif
                    @if((float)App\Models\Dueordersale::find($order->id)->card_pay)
                    cmds += 'CARD:                          {{ round($dueorder_sale->card_pay,2)  }}';
                    cmds += newLine;
                    @endif
                    @if((float)App\Models\Dueordersale::find($order->id)->mobile_banking_pay)
                    cmds += 'Mobile Banking:                {{ round($dueorder_sale->mobile_banking_pay,2) }}';
                    cmds += newLine;
                    @endif
                    @php
                    //calculating total discount amount received.
                    $discount = App\Models\Dueordersale::find($order->id)->discount + App\Models\Dueordersale::find($order->id)->reward_discount +
                    App\Models\Dueordersale::find($order->id)->card_discount + App\Models\Dueordersale::find($order->id)->gpstar_discount;
                    @endphp                  
                    @if($discount)
                    cmds += 'Discount:                      {{ $order->id ? round($discount,2) : '' }}';
                    cmds += newLine;
                    @endif 
                    @if((float)App\Models\Dueordersale::find($order->id)->fraction_discount)
                    cmds += 'Other Discount:                {{ $order->id ? round(App\Models\Dueordersale::find($order->id)->fraction_discount,2) : '' }}';
                    cmds += newLine;                    
                    @endif
                    cmds += "---------------------------------------";                                 
                    @endif                  
                    cmds += newLine + newLine;
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

