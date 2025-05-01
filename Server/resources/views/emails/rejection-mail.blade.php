<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث حالة الإعلان</title>
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
                <img src="{{ asset('images/logo.png') }}" alt="شعار سوق سوريا">
            </div>
            <h1>تحديث حالة الإعلان</h1>
            <p class="greeting">عزيزي {{ $name }}،</p>
            <p class="info">نأمل أن تكون بخير. نكتب إليك لإبلاغك بحالة طلب الإعلان الأخير الذي قدمته لدينا. بعد مراجعة دقيقة، نأسف لإبلاغك بأن إعلانك قد تم رفضه.</p>
            <p class="warning"><strong>سبب الرفض:</strong> {{ $reason ?? 'لم يتم تقديم سبب.' }}</p>
            <p class="info">نحن نقدر اهتمامك بالإعلان في موقعنا ونشجعك على مراجعة إرشاداتنا وإعادة التقديم إذا كان ذلك ممكنًا. شكرًا لتفهمك، ونتمنى لك النجاح في جهودك الإعلانية المستقبلية.</p>
            <p class="signature">مع أطيب التحيات،<br>فريق مراجعة الإعلانات</p>
        </div>
        <div class="footer">
            <p>هذه رسالة آلية. يرجى عدم الرد مباشرة على هذا البريد الإلكتروني.</p>
            <p>هل لديك أسئلة؟ <a href="mailto:support@syr-souq.com">اتصل بفريق دعم الإعلانات لدينا</a></p>
            <p>© {{ date('Y') }} اسم شركتك. جميع الحقوق محفوظة.</p>
            <p><a href="#">سياسة الخصوصية</a> | <a href="#">اتصل بنا</a></p>
        </div>
    </div>
</body>
</html>