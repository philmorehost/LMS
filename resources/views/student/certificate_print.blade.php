<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion — {{ $certificate->certificate_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #fafafa; color: #111; padding: 40px; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .cert-border { width: 840px; border: 16px double #d4af37; background: #fff; padding: 40px; text-align: center; box-shadow: 0 4px 30px rgba(0,0,0,0.05); position: relative; }
        .cert-watermark { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 140px; color: rgba(212,175,55,0.03); font-family: 'Cinzel', serif; pointer-events: none; }
        .logo { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 800; color: #d4af37; margin-bottom: 24px; }
        h1 { font-family: 'Cinzel', serif; font-size: 36px; font-weight: 700; color: #222; margin-bottom: 12px; }
        h2 { font-size: 16px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.1em; color: #666; margin-bottom: 32px; }
        .presented-to { font-size: 14px; font-style: italic; color: #555; margin-bottom: 8px; }
        .student-name { font-family: 'Cinzel', serif; font-size: 32px; font-weight: 800; color: #111; border-bottom: 2px solid #eee; width: 60%; margin: 0 auto 20px; padding-bottom: 10px; }
        .statement { font-size: 15px; color: #555; max-width: 600px; margin: 0 auto 32px; line-height: 1.7; }
        .course-title { font-weight: 700; color: #222; }
        .footer-metrics { display: flex; justify-content: space-between; align-items: center; width: 80%; margin: 0 auto; border-top: 1px solid #eee; padding-top: 24px; }
        .metric-item { text-align: center; }
        .metric-val { font-weight: 600; font-size: 14px; color: #111; }
        .metric-lbl { font-size: 11px; text-transform: uppercase; color: #777; margin-top: 4px; }
        @media print {
            body { padding: 0; background: #fff; }
            .cert-border { box-shadow: none; border-width: 12px; }
        }
    </style>
</head>
<body>
<div class="cert-border">
    <div class="cert-watermark">VERIFIED</div>
    <div class="logo">★ {{ $settings['site_title'] ?? 'Educve LMS' }} ★</div>
    <h1>Certificate of Completion</h1>
    <h2>Honorary Academic Credential</h2>
    
    <div class="presented-to">This is proudly presented to</div>
    <div class="student-name">{{ $user->name }}</div>
    
    <div class="statement">
        for successfully completing all syllabus modules, tests, and training lectures required for the comprehensive program:
        <br><strong class="course-title">{{ $certificate->course_title }}</strong>
    </div>

    <div class="footer-metrics">
        <div class="metric-item">
            <div class="metric-val">{{ $certificate->created_at }}</div>
            <div class="metric-lbl">Issue Date</div>
        </div>
        <div class="metric-item">
            <div class="metric-val" style="color:#d4af37">{{ $certificate->certificate_code }}</div>
            <div class="metric-lbl">Verification Code</div>
        </div>
    </div>
</div>
<script>
    window.print();
</script>
</body>
</html>
