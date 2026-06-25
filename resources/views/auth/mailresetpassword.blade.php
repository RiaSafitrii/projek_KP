<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>Hi {{ $user->name }},</p>
    <p>To reset your password, click the link below:</p>
    <a href="{{ $resetUrl }}">Reset Password</a>
    <p>If you did not request a password reset, no further action is required.</p>
</body>
</html>
