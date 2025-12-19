<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <div style="background: white; padding: 20px; border-radius: 8px;">
        <h2>Welcome to FABLAB, {{ $user->name }}!</h2>
        <p>Thank you for registering. Please verify your email using the code below:</p>
        <h3 style="color: #007bff;">{{ $code }}</h3>
        <p>Enter this code on the verification page to activate your account.</p>
        <br>
        <p>If you did not register, you can ignore this email.</p>
    </div>
</body>
</html>
