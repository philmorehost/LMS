<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Complete — {{ config('lms.name', 'Educve LMS') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-light: #a5b4fc; --success: #10b981; --error: #ef4444; --bg: #0f0f1a; --card: #1a1a2e; --card2: #16213e; --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8; --radius: 16px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%); }
        .wrap { width: 100%; max-width: 620px; padding: 24px; text-align: center; }
        .success-icon { width: 100px; height: 100px; border-radius: 50%; background: rgba(16,185,129,0.15); border: 3px solid var(--success); display: flex; align-items: center; justify-content: center; font-size: 44px; color: var(--success); margin: 0 auto 32px; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); } 50% { box-shadow: 0 0 0 20px rgba(16,185,129,0); } }
        h1 { font-size: 32px; font-weight: 800; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 12px; }
        .subtitle { color: var(--muted); font-size: 16px; margin-bottom: 40px; }
        .steps-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 32px; text-align: left; margin-bottom: 28px; }
        .steps-card h2 { font-size: 16px; font-weight: 700; margin-bottom: 20px; color: var(--text); }
        .next-step { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 16px; }
        .next-step:last-child { margin-bottom: 0; }
        .step-num { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; margin-top: 2px; }
        .step-info strong { display: block; font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .step-info span { font-size: 13px; color: var(--muted); }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.35); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(99,102,241,0.5); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .warning-box { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.3); border-radius: 12px; padding: 16px 20px; margin-bottom: 28px; font-size: 13px; color: #fbbf24; text-align: left; display: flex; gap: 12px; align-items: flex-start; }
        .steps-bar { display: flex; align-items: center; justify-content: center; margin-bottom: 36px; }
        .step-item { display: flex; align-items: center; gap: 10px; }
        .step-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; border: 2px solid var(--success); background: var(--success); color: #fff; flex-shrink: 0; }
        .step-label { font-size: 13px; color: var(--text); font-weight: 500; white-space: nowrap; }
        .step-connector { width: 60px; height: 2px; background: var(--success); margin: 0 8px; flex-shrink: 0; }
        @media (max-width: 600px) { .step-label { display: none; } .step-connector { width: 30px; } }
    </style>
</head>
<body>
<div class="wrap">
    <!-- Steps (all done) -->
    <div class="steps-bar">
        <div class="step-item"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Requirements</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Database</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Admin Setup</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Complete</span></div>
    </div>

    <div class="success-icon"><i class="fa fa-check"></i></div>
    <h1>🎉 Installation Complete!</h1>
    <p class="subtitle">Your LMS has been successfully installed and is ready to use.</p>

    <!-- Security Warning -->
    <div class="warning-box">
        <i class="fa fa-triangle-exclamation" style="margin-top:2px;flex-shrink:0"></i>
        <div>
            <strong>Security Notice:</strong>
            The installer directory is still accessible. For security reasons, please delete the <code>/install</code> directory or the <code>app/Http/Controllers/Installer</code> folder from your server after logging into the admin panel.
        </div>
    </div>

    <!-- Next Steps -->
    <div class="steps-card">
        <h2><i class="fa fa-list-check" style="color:var(--primary)"></i> &nbsp;What to Do Next</h2>
        <div class="next-step">
            <div class="step-num">1</div>
            <div class="step-info">
                <strong>Configure Email (SMTP)</strong>
                <span>Go to Admin → Settings → Email to set up your SMTP server for sending notifications</span>
            </div>
        </div>
        <div class="next-step">
            <div class="step-num">2</div>
            <div class="step-info">
                <strong>Set Up Payment Gateways</strong>
                <span>Go to Admin → Settings → Payments to enable Stripe, PayPal and other gateways</span>
            </div>
        </div>
        <div class="next-step">
            <div class="step-num">3</div>
            <div class="step-info">
                <strong>Choose a Theme</strong>
                <span>Go to Admin → Settings → Theme to select from 4 available themes</span>
            </div>
        </div>
        <div class="next-step">
            <div class="step-num">4</div>
            <div class="step-info">
                <strong>Create Course Categories</strong>
                <span>Go to Admin → Courses → Categories to create your first course categories</span>
            </div>
        </div>
        <div class="next-step">
            <div class="step-num">5</div>
            <div class="step-info">
                <strong>Configure Security (Bruteforce Protection)</strong>
                <span>Go to Admin → Security to configure anti-bruteforce and country access rules</span>
            </div>
        </div>
        <div class="next-step">
            <div class="step-num">6</div>
            <div class="step-info">
                <strong>Set Up SEO</strong>
                <span>Go to Admin → Settings → SEO to configure Google Analytics, sitemap, and meta tags</span>
            </div>
        </div>
    </div>

    <div class="btn-group">
        <a href="{{ $admin_url }}" class="btn btn-primary">
            <i class="fa fa-gauge"></i> Go to Admin Dashboard
        </a>
        <a href="{{ url('/') }}" class="btn btn-outline">
            <i class="fa fa-house"></i> View Site
        </a>
    </div>
</div>
</body>
</html>
