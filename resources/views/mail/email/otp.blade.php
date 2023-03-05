<!DOCTYPE html>
<html lang="en">

<body>

    <p>Dear {{ $user->name }},</p>
    <p>You are receiving this email because we received a password reset request for your account. Please use this
        OTP for verify.</p>
    <h2 class="text-center">
        {{ $user->verify_token }}
    </h2>
    <span>Thanks</span>,<br />
    <p>{{config('app.name')}}</p>

</body>

</html>