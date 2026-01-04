<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Password Reset Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; border-left: 5px solid #343a40;">
        <h2 style="color: #343a40; text-align: center; margin-bottom: 30px;">🔐 Admin Password Reset Request</h2>
        
        <p>Hello Admin,</p>
        
        <p>You are receiving this email because we received a password reset request for your admin account: <strong>{{ $email }}</strong></p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" style="background: #343a40; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Admin Password</a>
        </div>
        
        <p><strong>⚠️ Security Notice:</strong> This password reset link will expire in 24 hours for security reasons.</p>
        
        <p>If you did not request a password reset, please contact the system administrator immediately.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #666;">
            If you're having trouble clicking the "Reset Admin Password" button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $resetUrl }}" style="color: #343a40;">{{ $resetUrl }}</a>
        </p>
        
        <p style="font-size: 12px; color: #666; text-align: center; margin-top: 30px;">
            © {{ date('Y') }} {{ config('app.name') }} - Admin Panel. All rights reserved.
        </p>
    </div>
</body>
</html>