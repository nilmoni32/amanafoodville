<div class="modal fade" id="userBankModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-money"></i> Customer
                    Payment Details</h5>
            </div>
            <div class="modal-body text-center">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <tbody>
                        <tr>
                            <td class="text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Order Number</td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Transaction Date</td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">{{ $order->tran_date }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">SSL-Commerze Trans Id
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">{{ $order->tran_id }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Currency Type</td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->currency_type }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Amount</td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">{{ $order->amount }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Store Amount
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->store_amount }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Bank Trans Id
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->bank_tran_id }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Card No
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">{{ $order->card_no }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Card Band
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->card_brand }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Card Issuer
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->card_issuer }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6" style="padding: 0.5rem; vertical-align: 0 ;">Transaction Error
                            </td>
                            <td class="text-left" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->error }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <div class=" modal-footer border-top-0">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>