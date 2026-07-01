<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --secondary: #8b5cf6;
            --bg: #0a0a1a; --card: #12122a; --border: rgba(255,255,255,0.07);
            --text: #e2e8f0; --muted: #94a3b8;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); padding-top: 72px; }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 0 40px; height: 72px; display: flex; align-items: center; justify-content: space-between; background: rgba(10,10,26,0.8); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .nav-logo { display: flex; align-items: center; gap: 12px; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; }
        .logo-text { font-size: 18px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--muted)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .btn { padding: 9px 18px; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; }

        /* MAIN CONTENT */
        .content-container { max-width: 800px; margin: 60px auto; padding: 0 24px; }
        h1 { font-size: 32px; font-weight: 800; margin-bottom: 40px; text-align: center; }
        .faq-item { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 16px; }
        .faq-question { font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #a5b4fc; }
        .faq-answer { font-size: 14px; color: var(--muted); line-height: 1.6; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="logo-icon">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
        <span class="logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
    </a>
    <div>
        <a href="{{ url('/') }}" class="btn btn-primary">Home</a>
    </div>
</nav>

<div class="content-container">
    <h1>Frequently Asked Questions</h1>

    @if(count($faqs) > 0)
        @foreach($faqs as $faq)
        <div class="faq-item">
            <div class="faq-question">{{ $faq->question }}</div>
            <div class="faq-answer">{{ $faq->answer }}</div>
        </div>
        @endforeach
    @else
        <div class="faq-item">
            <div class="faq-question">How do I enroll in a course?</div>
            <div class="faq-answer">Simply browse our courses page, select a course, and click "Enroll Now". You will be guided through checkout or registered directly if the course is free.</div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Are the certificates accredited?</div>
            <div class="faq-answer">Our certificates verify that you have successfully completed all lessons and tests in a course. They make great additions to your resume or LinkedIn profile!</div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Can I become an instructor?</div>
            <div class="faq-answer">Yes! You can register as an instructor during account creation or request an upgrade from your student settings panel.</div>
        </div>
    @endif
</div>
</body>
</html>
