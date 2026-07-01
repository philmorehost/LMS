<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $settings['site_title'] ?? config('lms.name', 'Educve LMS') }}</title>
    <meta name="description" content="Sign in to access your courses and learning dashboard.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #a5b4fc;
            --success: #10b981; --error: #ef4444; --warning: #f59e0b;
            --bg: #0a0a1a; --card: #12122a; --card2: #1a1a3a;
            --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8;
            --radius: 16px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }
        /* Left: hero panel */
        .auth-hero {
            flex: 1;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4c1d95 60%, #1e1b4b 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }
        .auth-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(99,102,241,0.3) 0%, transparent 60%),
                        radial-gradient(ellipse at 70% 20%, rgba(139,92,246,0.2) 0%, transparent 50%);
        }
        .hero-floating {
            position: absolute;
            border-radius: 50%;
            background: rgba(99,102,241,0.15);
            animation: float 6s ease-in-out infinite;
        }
        .hero-floating:nth-child(1) { width: 300px; height: 300px; top: -80px; left: -80px; animation-delay: 0s; }
        .hero-floating:nth-child(2) { width: 200px; height: 200px; bottom: 10%; right: 5%; animation-delay: 2s; }
        .hero-floating:nth-child(3) { width: 120px; height: 120px; top: 40%; right: 20%; animation-delay: 4s; }
        @keyframes float { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-20px) scale(1.05); } }
        .hero-content { position: relative; text-align: center; max-width: 400px; }
        .hero-logo { width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary), #8b5cf6); border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 800; color: #fff; margin: 0 auto 32px; box-shadow: 0 0 60px rgba(99,102,241,0.5); }
        .hero-content h1 { font-size: 36px; font-weight: 800; color: #fff; margin-bottom: 16px; line-height: 1.2; }
        .hero-content p { font-size: 16px; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 40px; }
        .hero-stats { display: flex; gap: 32px; justify-content: center; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 28px; font-weight: 700; color: #fff; }
        .stat-label { font-size: 12px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.05em; }
        /* Right: form panel */
        .auth-panel {
            width: 480px;
            flex-shrink: 0;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            overflow-y: auto;
        }
        .auth-form-wrap { width: 100%; max-width: 380px; }
        .auth-title { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
        .auth-subtitle { color: var(--muted); font-size: 14px; margin-bottom: 32px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; pointer-events: none; }
        .form-control {
            width: 100%; padding: 12px 16px 12px 40px;
            background: var(--card); border: 1px solid var(--border);
            border-radius: 10px; color: var(--text); font-size: 14px;
            font-family: inherit; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control::placeholder { color: var(--muted); }
        .password-wrap .toggle-pwd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--muted); font-size: 14px; background: none; border: none; padding: 0; }
        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check input[type=checkbox] { accent-color: var(--primary); width: 16px; height: 16px; cursor: pointer; }
        .form-check label { font-size: 13px; color: var(--muted); cursor: pointer; }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
        .link { color: var(--primary-light); font-size: 13px; text-decoration: none; }
        .link:hover { text-decoration: underline; }
        .btn-primary {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: #fff; font-size: 15px; font-weight: 600;
            border: none; border-radius: 10px; cursor: pointer;
            transition: all 0.2s; box-shadow: 0 4px 20px rgba(99,102,241,0.35);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 30px rgba(99,102,241,0.5); }
        .btn-primary:active { transform: translateY(0); }
        .divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .divider span { font-size: 12px; color: var(--muted); white-space: nowrap; }
        .social-btns { display: flex; gap: 10px; margin-bottom: 24px; }
        .btn-social { flex: 1; padding: 10px; background: var(--card); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: all 0.2s; }
        .btn-social:hover { border-color: var(--primary); background: var(--card2); }
        .auth-footer { text-align: center; margin-top: 24px; font-size: 13px; color: var(--muted); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 18px; }
        .alert-error { background: rgba(239,68,68,0.1); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
        .alert-success { background: rgba(16,185,129,0.1); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); }
        @media (max-width: 768px) {
            .auth-hero { display: none; }
            .auth-panel { width: 100%; padding: 32px 24px; }
        }
    </style>
</head>
<body>
<div class="auth-hero">
    <div class="hero-floating"></div>
    <div class="hero-floating"></div>
    <div class="hero-floating"></div>
    <div class="hero-content">
        <div class="hero-logo">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
        <h1>Transform Your Learning Journey</h1>
        <p>Access thousands of courses, connect with expert instructors, and earn certificates that matter.</p>
        <div class="hero-stats">
            <div class="stat-item">
                <div class="stat-num">50K+</div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">1200+</div>
                <div class="stat-label">Courses</div>
            </div>
            <div class="stat-item">
                <div class="stat-num">300+</div>
                <div class="stat-label">Instructors</div>
            </div>
        </div>
    </div>
</div>
<div class="auth-panel">
    <div class="auth-form-wrap">
        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to continue your learning journey</p>

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fa fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">
            <i class="fa fa-check-circle"></i>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="input-icon fa fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="admin@yourdomain.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap password-wrap">
                    <i class="input-icon fa fa-lock"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Your password" required>
                    <button type="button" class="toggle-pwd" onclick="togglePwd()">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <div class="form-row">
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="{{ url('/forgot-password') }}" class="link">Forgot password?</a>
            </div>
            <button type="submit" class="btn-primary">
                <i class="fa fa-arrow-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ url('/register') }}" class="link">Create one free</a>
        </div>
        <div class="auth-footer" style="margin-top:12px;">
            <a href="{{ url('/') }}" class="link"><i class="fa fa-arrow-left"></i> Back to homepage</a>
        </div>
    </div>
</div>
<script>
function togglePwd() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'fa fa-eye';
    }
}
</script>
</body>
</html>
