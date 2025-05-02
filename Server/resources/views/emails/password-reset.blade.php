<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب إعادة تعيين كلمة المرور</title>
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

        h1 {
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

        .button {
            display: inline-block;
            background: #FEE800;
            color: #1E1E1E !important; /* Explicitly set to black */
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #FFD700;
            color: #1E1E1E !important; /* Ensure hover state also uses black */
        }

        .info {
            color: #1E1E1E;
            font-size: 16px;
            margin: 15px 0;
            text-align: right;
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

        /* Explicit RTL adjustments */
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
                <img src="https://syr-souq.fra1.cdn.digitaloceanspaces.com/logos/logo.png" alt="شعار سوق سوريا">
            </div>
            <h1>طلب إعادة تعيين كلمة المرور</h1>
            <p class="greeting">مرحبًا {{ $name }}،</p>
            <p class="info">لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بك. يمكنك إعادة تعيين كلمة المرور بالنقر على الزر أدناه:</p>
            <a href="{{ $resetLink }}" class="button">إعادة تعيين كلمة المرور</a>
            <p class="info">ستنتهي صلاحية هذا الرابط خلال {{ $expires }} دقيقة.</p>
            <p class="warning">إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذا البريد الإلكتروني أو التواصل مع فريق الدعم لدينا.</p>
            <p class="info">إذا كنت تواجه مشكلة في النقر على الزر، انسخ والصق الرابط التالي في متصفحك:</p>
            <p class="info"><a href="{{ $resetLink }}">{{ $resetLink }}</a></p>
            <p class="signature">مع أطيب التحيات،<br>فريق شركتك</p>
        </div>
        <div class="footer">
            <p>هذه رسالة آلية، يرجى عدم الرد مباشرة على هذا البريد الإلكتروني.</p>
            <p>© {{ date('Y') }} اسم شركتك. جميع الحقوق محفوظة.</p>
            <p><a href="#">سياسة الخصوصية</a> | <a href="#">اتصل بنا</a></p>
        </div>
    </div>
</body>
</html>