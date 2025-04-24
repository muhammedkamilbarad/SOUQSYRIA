<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق من البريد الإلكتروني</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;600&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans Arabic', sans-serif;
            line-height: 1.6;
            color: #1E1E1E;
            background: #F9F9F9;
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
            text-align: right;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
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
            right: 0;
            width: 100%;
            height: 5px;
            background: #FEE800;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }

        h2 {
            color: #1E1E1E;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 28px;
            text-align: center;
        }

        .greeting {
            color: #1E1E1E;
            font-size: 16px;
            margin-bottom: 20px;
            text-align: right;
        }

        .info {
            color: #1E1E1E;
            font-size: 16px;
            margin: 15px 0;
            text-align: right;
        }

        .otp-box {
            background: #FFF9C4;
            color: #1E1E1E;
            font-size: 24px;
            font-weight: 600;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 5px;
        }

        .warning {
            color: #1E1E1E;
            font-size: 14px;
            background: #FFF9C4;
            padding: 10px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: right;
        }

        .signature {
            color: #1E1E1E;
            font-size: 15px;
            margin-top: 30px;
            text-align: center;
        }

        .footer {
            font-size: 12px;
            color: #1E1E1E;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #FEE800;
            text-align: center;
        }

        .footer a {
            color: #1E1E1E;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #FEE800;
        }

        a {
            unicode-bidi: embed;
        }

        p, div {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <img src="https://i.ibb.co/k6yXNyLs/logo.png" alt="شعار سوريا سوق">
            </div>
            <h2>رمز التحقق من البريد الإلكتروني</h2>
            <p class="greeting">عزيزي {{ $userName }}،</p>
            <p class="info">شكرًا لاختيارك سوريا سوق! لإكمال عملية التحقق من بريدك الإلكتروني، يرجى استخدام رمز المرور لمرة واحدة (OTP) التالي:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p class="info">هذا الرمز صالح لمدة {{ $timeAmount }} دقيقة. يرجى إدخاله على الفور للتحقق من حسابك.</p>
            <p class="warning">إذا لم تطلب هذا الرمز، يرجى تجاهل هذا البريد الإلكتروني أو التواصل مع فريق الدعم الخاص بنا على الفور.</p>
            <p class="signature">مع أطيب التحيات<br>فريق سوريا سوق</p>
        </div>
        <div class="footer">
            <p>هذه رسالة آلية، يرجى عدم الرد مباشرة على هذا البريد الإلكتروني.</p>
            <p>© {{ date('Y') }} سوريا سوق. جميع الحقوق محفوظة.</p>
            <p><a href="#">سياسة الخصوصية</a> | <a href="#">اتصل بنا</a></p>
        </div>
    </div>
</body>
</html>