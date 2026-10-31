<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CESO</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f6f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background-color: #0a3d62; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .body { padding: 40px; color: #333333; }
        .body h2 { margin-top: 0; font-size: 20px; color: #0a3d62; }
        .body p { line-height: 1.6; color: #555555; }
        .btn-wrapper { text-align: center; margin: 32px 0; }
        .btn { display: inline-block; padding: 14px 32px; background-color: #0a3d62; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 15px; }
        .note { background: #fff8e1; border-left: 4px solid #f9a825; padding: 12px 16px; border-radius: 0 4px 4px 0; font-size: 13px; color: #666666; }
        .footer { background-color: #f4f6f8; padding: 20px 40px; text-align: center; font-size: 12px; color: #999999; }
        .url-fallback { word-break: break-all; font-size: 12px; color: #888888; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Community Extension Services Office</h1>
        </div>

        <div class="body">
            <h2>Password Reset Request</h2>
            <p>Hello {{ $userName }},</p>
            <p>
                We received a request to reset the password for your CESO account.
                Click the button below to set a new password.
            </p>

            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn">Reset My Password</a>
            </div>

            <div class="note">
                <strong>Security Notice:</strong> This password reset link will expire in <strong>60 minutes</strong>.
                If you did not request a password reset, please ignore this email — your password will remain unchanged.
            </div>

            <p class="mt-4" style="font-size:13px;">
                If the button above doesn't work, copy and paste this URL into your browser:
            </p>
            <p class="url-fallback">{{ $resetUrl }}</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Community Extension Services Office. All rights reserved.
        </div>
    </div>
</body>
</html>
