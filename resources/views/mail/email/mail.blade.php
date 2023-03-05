<!DOCTYPE html>
<html lang="en">

<body>

    <p>Dear {{ $user->name }},</p>
    <p>Your account has been created, please activate your account with this email verification code.</p>
    <h2 class="text-center">
        {{ $user->verify_token }}
    </h2>
    <span>Thanks</span>,<br />
    <p>{{config('app.name')}}</p>

</body>

</html>