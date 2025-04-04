<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $name }},</p>
            
            <p>We received a request to reset your password. You can reset your password by clicking the button below:</p>
            
            <a href="{{ $resetLink }}" class="button">Reset Password</a>
            
            <p>This link will expire in {{ $expires }} minutes. If you didn't request a password reset, please ignore this email or contact our support team.</p>
            
            <p>If you're having trouble clicking the button, copy and paste the following URL into your browser:</p>
            <p><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>
        </div>
        
        <div class="footer">
            <p>This is an automated message, please do not reply directly to this email.</p>
            <p>&copy; {{ date('Y') }} Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>