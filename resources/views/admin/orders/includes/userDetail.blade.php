<div class="modal fade" id="userModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center border-bottom-0">
                <h5 class="modal-title text-right mt-3" id="exampleModalLabel"><i class="fa fa-user"></i> Customer
                    Details</h5>
            </div>
            <div class="modal-body text-center">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <tbody>
                        <tr>
                            <td class="text-left h6">Order Number</td>
                            <td class="text-left">{{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Name</td>
                            <td class="text-left">{{ $order->user->name }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Email Address </td>
                            <td class="text-left">{{ $order->user->email }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Phone Number</td>
                            <td class="text-left">{{ $order->user->phone_number }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">District</td>
                            <td class="text-left">{{ $order->district }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Area</td>
                            <td class="text-left">{{ $order->zone }}
                            </td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Delivery Address
                            </td>
                            <td class="text-left">{{ $order->address }}</td>
                        </tr>
                        <tr>
                            <td class=" text-left h6">Delivery Date
                            </td>
                            <td class="text-left">
                                {{  date('Y-m-d', strtotime($order->delivery_date )) }}
                            </td>
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