<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <meta name="description" content="Create your free account and start learning today.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #a5b4fc;
            --success: #10b981; --error: #ef4444;
            --bg: #0a0a1a; --card: #12122a; --card2: #1a1a3a;
            --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8;
            --radius: 16px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; overflow: hidden; }
        .auth-hero { flex: 1; background: linear-gradient(135deg, #0c4a6e 0%, #075985 30%, #0369a1 60%, #0c4a6e 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 48px; position: relative; overflow: hidden; }
        .auth-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 30% 50%, rgba(6,182,212,0.3) 0%, transparent 60%); }
        .hero-floating { position: absolute; border-radius: 50%; background: rgba(6,182,212,0.12); animation: float 6s ease-in-out infinite; }
        .hero-floating:nth-child(1) { width: 300px; height: 300px; top: -80px; left: -80px; }
        .hero-floating:nth-child(2) { width: 200px; height: 200px; bottom: 10%; right: 5%; animation-delay: 2s; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .hero-content { position: relative; text-align: center; max-width: 400px; }
        .hero-logo { width: 80px; height: 80px; background: linear-gradient(135deg, #06b6d4, #0284c7); border-radius: 22px; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 800; color: #fff; margin: 0 auto 32px; box-shadow: 0 0 60px rgba(6,182,212,0.5); }
        .hero-content h1 { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 14px; }
        .hero-content p { font-size: 15px; color: rgba(255,255,255,0.7); line-height: 1.6; margin-bottom: 36px; }
        .hero-checklist { text-align: left; list-style: none; }
        .hero-checklist li { display: flex; align-items: center; gap: 10px; font-size: 14px; color: rgba(255,255,255,0.85); margin-bottom: 12px; }
        .hero-checklist li::before { content: '✓'; width: 22px; height: 22px; background: rgba(16,185,129,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #6ee7b7; flex-shrink: 0; font-weight: 700; }
        .auth-panel { width: 520px; flex-shrink: 0; background: var(--bg); display: flex; align-items: center; justify-content: center; padding: 48px 40px; overflow-y: auto; }
        .auth-form-wrap { width: 100%; max-width: 420px; }
        .auth-title { font-size: 24px; font-weight: 700; margin-bottom: 6px; }
        .auth-subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .form-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; pointer-events: none; }
        .form-control { width: 100%; padding: 12px 16px 12px 40px; background: var(--card); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control::placeholder { color: var(--muted); }
        .toggle-pwd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--muted); font-size: 14px; background: none; border: none; padding: 0; }
        .select-control { width: 100%; padding: 12px 16px 12px 40px; background: var(--card); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; outline: none; cursor: pointer; }
        .select-control option { background: #12122a; }
        .btn-primary { width: 100%; padding: 13px; background: linear-gradient(135deg, #06b6d4, #0284c7); color: #fff; font-size: 15px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 20px rgba(6,182,212,0.35); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 30px rgba(6,182,212,0.5); }
        .terms-text { font-size: 12px; color: var(--muted); text-align: center; margin-bottom: 16px; }
        .terms-text a { color: var(--primary-light); }
        .auth-footer { text-align: center; margin-top: 18px; font-size: 13px; color: var(--muted); }
        .link { color: var(--primary-light); }
        .link:hover { text-decoration: underline; }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
        .alert-error { background: rgba(239,68,68,0.1); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
        @media (max-width: 768px) { .auth-hero { display: none; } .auth-panel { width: 100%; } .form-row2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="auth-hero">
    <div class="hero-floating"></div>
    <div class="hero-floating"></div>
    <div class="hero-content">
        <div class="hero-logo">🎓</div>
        <h1>Start Your Learning Journey Today</h1>
        <p>Join thousands of students mastering new skills every day.</p>
        <ul class="hero-checklist">
            <li>Access to 1,200+ expert-led courses</li>
            <li>Earn verified industry certificates</li>
            <li>Learn at your own pace, on any device</li>
            <li>Get 30-day money-back guarantee</li>
            <li>Free courses available — no credit card needed</li>
        </ul>
    </div>
</div>
<div class="auth-panel">
    <div class="auth-form-wrap">
        <h1 class="auth-title">Create your account</h1>
        <p class="auth-subtitle">Free to join. No credit card required.</p>

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fa fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="form-row2">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <div class="input-wrap">
                        <i class="input-icon fa fa-user"></i>
                        <input type="text" name="first_name" class="form-control" placeholder="John" value="{{ old('first_name') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <div class="input-wrap">
                        <i class="input-icon fa fa-user"></i>
                        <input type="text" name="last_name" class="form-control" placeholder="Doe" value="{{ old('last_name') }}" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="input-icon fa fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="john@example.com" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">I want to join as</label>
                <div class="input-wrap">
                    <i class="input-icon fa fa-user-tag"></i>
                    <select name="role" class="select-control" required>
                        <option value="student" {{ old('role','student')==='student'?'selected':'' }}>Student – I want to learn</option>
                        <option value="instructor" {{ old('role')==='instructor'?'selected':'' }}>Instructor – I want to teach</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="input-icon fa fa-lock"></i>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                    <button type="button" class="toggle-pwd" onclick="togglePwd('password','eye1')"><i class="fa fa-eye" id="eye1"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrap">
                    <i class="input-icon fa fa-lock"></i>
                    <input type="password" name="password_confirmation" id="password2" class="form-control" placeholder="Re-enter your password" required>
                    <button type="button" class="toggle-pwd" onclick="togglePwd('password2','eye2')"><i class="fa fa-eye" id="eye2"></i></button>
                </div>
            </div>
            <p class="terms-text">By creating an account, you agree to our <a href="{{ url('/page/terms-of-service') }}">Terms of Service</a> and <a href="{{ url('/page/privacy-policy') }}">Privacy Policy</a>.</p>
            <button type="submit" class="btn-primary"><i class="fa fa-user-plus"></i> Create My Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ url('/login') }}" class="link">Sign in</a>
        </div>
        <div class="auth-footer" style="margin-top:10px;">
            <a href="{{ url('/') }}" class="link"><i class="fa fa-arrow-left"></i> Back to homepage</a>
        </div>
    </div>
</div>
<script>
function togglePwd(id, iconId) {
    const el = document.getElementById(id);
    const icon = document.getElementById(iconId);
    el.type = el.type === 'password' ? 'text' : 'password';
    icon.className = el.type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
}
</script>
</body>
</html>
