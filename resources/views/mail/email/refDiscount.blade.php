<!DOCTYPE html>
<html lang="en">

<style>
    table,
    th,
    td {
        border: 1px solid black;
    }

    /* make the table a 100% wide by default */
    table {
        width: 100%;
    }

    /* if the browser window is at least 800px-s wide: */
    @media screen and (min-width: 800px) {
        table {
            width: 80%;
        }
    }

    /* if the browser window is at least 1000px-s wide: */
    @media screen and (min-width: 1000px) {
        table {
            width: 60%;
        }
    }
</style>

<body>
        
    <h3>{{config('app.name')}} POS Reference Discount Deatils:</h3>
    <p>Dear Admin,</p>
    <p>Please have the details info regarding daily POS reference discount dated on {{ date('d-m-Y',strtotime("-1 days")) }}:</p>
    <br>
    <table border= "1" style="background-repeat:no-repeat;margin:0;" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="height:40px; width:50px; margin:0;">#</th>
                <th style="height:40px; width:200px; margin:0;">Date</th>
                <th style="height:40px; width:150px; margin:0;">Order No</th>
                <th style="height:40px; width:150px; margin:0;">Order Total</th>                
                <th style="height:40px; width:150px; margin:0;">Ref Name</th>
                <th style="height:40px; width:100px; margin:0;">Ref Type</th>                
                <th style="height:40px; width:200px; margin:0;">Discount Received</th>
                <th style="height:40px; width:200px; margin:0;">Discount Upper Limit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            {{-- sending email discount notification to the authority [Director, Asst. Director, etc]. --}}
            @if($order->director_id)
            @php $referee = App\Models\Director::where('id', $order->director_id)->first(); 
                 $total_discount = $order->discount + $order->reward_discount + $order->card_discount + $order->gpstar_discount + $order->fraction_discount;  @endphp
            <tr>
                <td style="height:40px; width:50px; margin:0; text-align:center;">{{ $loop->index + 1 }}</td>
                <td style="height:40px; width:200px; margin:0; text-align:center;">{{ $order->order_date }}</td>
                <td style="height:40px; width:150px; margin:0; text-align:center;">{{ $order->order_number }}</td>
                <td style="height:40px; width:150px; margin:0; text-align:center;">{{ round(($order->grand_total + $total_discount),2) }} {{ config('settings.currency_symbol') }}</td>
                <td style="height:40px; width:150px; margin:0; text-align:center;">{{ $referee->name }}</td>
                <td style="height:40px; width:100px; margin:0; text-align:center;">{{ $referee->ref_type }}</td>
                <td style="height:40px; width:200px; margin:0; text-align:center;">{{ round($order->discount,2) }} {{ config('settings.currency_symbol') }}</td>
                <td style="height:40px; width:200px; margin:0; text-align:center;">{{ round($referee->discount_upper_limit,2) }} {{ config('settings.currency_symbol') }}</td>                
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    <br><br>
    <span>Thanks</span>,<br />
    <p>{{config('app.name')}} auto generated mail</p>


</body>

</html>