<!DOCTYPE html>
<html lang="en">

<body>
    <h3>Customer Details:</h3>
    <p>Name: {{ $user['name'] }} <br /><br />
        Email: {{ $user['email'] }} <br /><br />
        Phone:{{ $user['mobile'] }} <br /><br />
    </p>
    <p>Dear Concern,</p>
    <p>{{ $user['enquiry'] }}</p>

    <p>You can reach me using the contact information listed above.</p>

    <p>Thank you.</p>

    <p>Sincerely,</p>
    <p>{{ $user['name'] }}</p>


</body>

</html>