<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #1976d2, #FF9B00); color: #fff; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #e0e0e0; }
        .greeting { margin-bottom: 20px; }
        .greeting p { margin: 0 0 10px 0; }
        .button { display: inline-block; background: #1976d2; color: #fff; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; margin: 20px 0; }
        .button:hover { background: #0d47a1; }
        .footer { background: #f0f0f0; padding: 20px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 8px 8px; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin: 20px 0; color: #856404; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                <p>Hi {{ $userName }},</p>
                <p>You requested a password reset for your BM Vehicle Monitoring System account. Click the button below to reset your password.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>

            <p>Or copy and paste this link in your browser:</p>
            <p style="word-break: break-all; background: #fff; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $resetUrl }}</p>

            <div class="warning">
                <strong>Security Notice:</strong> This link will expire in 1 hour. If you did not request this password reset, please ignore this email and your account will remain secure.
            </div>

            <p style="margin-top: 30px; color: #666; font-size: 13px;">
                If you have any questions, please contact your administrator.
            </p>
        </div>

        <div class="footer">
            <p>BM Vehicle Monitoring System — Sangguniang Panlalawigan</p>
            <p style="margin: 5px 0 0 0;">© {{ date('Y') }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
