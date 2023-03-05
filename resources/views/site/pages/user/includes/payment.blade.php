<div class="user-payment">
    <div class="table-responsive-md">
        {{-- checking if the user has made any transaction or not --}}
        @if(App\Models\Order::where('user_id', auth()->user()->id)->first())
        <div class="row pb-3">
            <div class="col-xl-2 col-md-3 col-5">
                @php
                $Startyear=date('Y');
                $endYear=$Startyear-3;
                // set start and end year range i.e the start year
                $yearArray = range($Startyear,$endYear);
                @endphp
                <form action="" method="">
                    <div>
                        {{-- populating year for year based payment data using ajax call --}}
                        <select name="year_order" class="px-3 mb-3 py-1"
                            style="border: 1px solid #d3d2d2; color: #495057;">
                            @php
                            foreach ($yearArray as $year)
                            {
                            // this allows you to select a particular year
                            $selected = ($year == $Startyear) ? 'selected' : '';
                            echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
                            }
                            @endphp
                        </select>
                    </div>
                </form>
            </div>
            <!-- modal for refund -->
            <div class="offset-xl-8 col-xl-2 offset-md-6 col-md-3 col-7">

                <a href="#" class="btn btn-theme btn-wide" data-toggle="modal" data-target="#exampleModal"
                    style="height:37px; line-height: 24px; float:right">Refund</a>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header justify-content-center border-bottom-0">
                                <h5 class="modal-title text-right" id="exampleModalLabel"><img
                                        src="{{ asset('frontend')}}/images/refunds.png" alt="Refund" width="200px"></h5>
                            </div>
                            <div class="modal-body text-center h5">
                                <p>Please call to our customer service for refund.</p>
                                <p class="h3"><i class="icofont icofont-phone"></i>{{ config('settings.phone_no') }}</p>

                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn bg-gradient-secondary"
                                    data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered text-center" id="paymentHistory">
            <thead>
                <tr>
                    <th>Order Id</th>
                    <th>Date</th>
                    <th>Paid Amount</th>
                    <th>Payment via</th>
                    <th>Bank Transaction Id</th>
                </tr>
            </thead>
            <tbody>
                @foreach(App\Models\Order::where('user_id', auth()->user()->id)->where('order_date', 'like',
                date("Y").'%')->orderBy('created_at', 'desc')->get() as $order)
                <tr>
                    <td class="text-center">
                        {{ $order->order_number }}
                    </td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                    </td>
                    <td class="text-center" style="text-transform:capitalize">
                        {{ config('settings.currency_symbol') }} {{ round($order->grand_total,0) }}
                    </td>
                    <td class="text-center" style="text-transform:capitalize">
                        {{ $order->payment_method }}
                    </td>
                    <td class="text-center">
                        {{ $order->bank_tran_id }}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="col-12 text-center">
            <h4 class="p-5">
                {{ __( 'No Transaction has been made' )}}
            </h4>
        </div>
        @endif
    </div>
</div>