<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Advertisement Status Update</h2>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>We hope this message finds you well. We’re writing to inform you about the status of your recent advertisement submission with us. After a thorough review, we regret to inform you that your advertisement has been rejected.</p>
            <div>
                {{-- <p><strong>Reason for rejection:</strong> {{ $message }}</p> --}}
                <p><strong>Reason for rejection:</strong> {{ $reason ?? 'No reason provided.' }}</p>
            </div>
            <p>We value your interest in advertising with us and encourage you to review our guidelines and resubmit if applicable. Thank you for your understanding, and we wish you success in your future advertising efforts.</p>
            <p>Best regards,<br>The Advertisement Review Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
            <p>Questions? <a href="mailto:ads-support@company.com">Contact our Ad Support Team</a></p>
        </div>
    </div>
</body>
</html>