<div class="modal fade" id="editCartModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0 pb-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-edit"></i>
                    Change Buffet Menu Order Status: 
                </h5>
            </div>            
            <div class="modal-body text-center">
                <div class="border px-2 rounded pb-3 mb-4 mt-2" style="border-color:rgb(182, 182, 182);">
                    <label class="col-12 font-weight-bold text-left text-center mt-2">Change Order
                        Status:</label>                            
                    <div class="col-12 font-weight-bold text-center text-uppercase">
                        <input type="checkbox" data-toggle="toggle" data-on="Order Receive"
                            data-off="Order Cancel" {{ $order->order_tableNo ? 'checked' : 'disabled'}}
                            data-onstyle="primary" data-offstyle="danger" data-id={{ $order->id }}
                            class="buffetStatus" data-width="100%">
                    </div>
                </div>                
            </div>
            <div class=" modal-footer border-top-0">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
