<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Verification Code</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background: #f9fafb; /* Lighter background */
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff; /* White background */
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #e2e8f0; /* Lighter gradient for a cleaner look */
        }

        h2 {
            color: #333; /* Darker text color for better contrast */
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .greeting {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .otp-box {
            background: #ffffff; /* White background for OTP box */
            color: #4a5568; /* Darker text for better visibility */
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: 600;
            letter-spacing: 8px;
            margin: 30px 0;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1); /* Lighter box shadow */
        }

        .info {
            color: #4a5568;
            text-align: center;
            font-size: 16px;
            margin: 15px 0;
        }

        .warning {
            color: #e53e3e;
            font-size: 14px;
            text-align: center;
            background: #fef2f2; /* Light pink for warning */
            padding: 10px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .signature {
            color: #718096;
            text-align: center;
            font-size: 15px;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #718096;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0; /* Lighter border for footer */
        }

        .footer a {
            color: #4a5568; /* Darker color for footer links */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #667eea; /* Light blue hover color for links */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Your Verification Code</h2>
            <p class="greeting">Hello {{ $userName }},</p>
            <p class="info">Please use this One-Time Password (OTP) to complete your verification:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p class="info">This code expires in {{ $timeAmount }} minutes</p>
            <p class="warning">Didn't request this code? Please ignore this email or contact our support team immediately.</p>
            <p class="signature">Warm regards,<br>The Movie App Team</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Movie App. All rights reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Contact Us</a></p>
        </div>
    </div>
</body>
</html>
