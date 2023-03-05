<!DOCTYPE html>
<html lang="en">

<body>
    <h3>Customer & Reservation Details:</h3>
    <p> Name: {{ $user['fname'] }} {{ $user['lname'] }} <br /><br />
        Email: {{ $user['email'] }} <br /><br />
        Phone:{{ $user['mobile'] }} <br /><br />
        Date & Time : {{ $user['appointment_dt'] }} <br /><br />
        No. of Persons: {{ $user['persons'] }} <br /><br />
    </p>
    <p>Dear Concern,</p>
    <p>I would like to make a reservation at {{config('app.name')}} Restaurant dated on
        {{ $user['appointment_dt'] }}. So, please book a table for our
        {{ $user['persons'] }}
        members.</p>

    <p>Please contact me as soon as possible to confirm these arrangements. You can reach me using the contact
        information listed above.</p>
    <p>Thank you.</p>

    <p>Sincerely,</p>
    <p>{{ $user['fname'] }} {{ $user['lname'] }}</p>


</body>

</html>