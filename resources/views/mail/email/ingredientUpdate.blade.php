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

    <p>Dear Concern,</p>
    <p>Please find the following ingredient lists need to be purchased for {{config('app.name')}} Restaurant.</p>
    <br>
    <table style=" background-repeat:no-repeat;margin:0;" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="height:40px; width:400px; margin:0;">#</th>
                <th style="height:40px; width:400px; margin:0;">Ingredient Name</th>
                <th style="height:40px; width:400px; margin:0;">Stock Qty</th>
                <th style="height:40px; width:400px; margin:0;">Alert Qty</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ingredients as $ingredient)
            <tr>
                <td style="height:40px; width:400px; margin:0; text-align:center;">{{ $loop->index + 1  }}</td>
                <td style="height:40px; width:400px; margin:0; text-align:center;">{{ $ingredient->name }}</td>
                <td style="height:40px; width:400px; margin:0; text-align:center;">{{ $ingredient->total_quantity }} {{ $ingredient->measurement_unit }}
                </td>
                <td style="height:40px; width:400px; margin:0; text-align:center;">{{ $ingredient->alert_quantity }} {{ $ingredient->measurement_unit }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p></p>
    <span>Thanks</span>,<br />
    <p>{{config('app.name')}} auto generated mail</p>

</body>

</html>