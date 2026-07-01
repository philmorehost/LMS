<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-light: #a5b4fc; --bg: #0a0a1a; --card: #12122a; --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-box { width: 100%; max-width: 420px; background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 40px; }
        .auth-icon { width: 64px; height: 64px; background: rgba(99,102,241,0.15); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 24px; }
        h1 { font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 8px; }
        p { color: var(--muted); font-size: 14px; text-align: center; margin-bottom: 28px; line-height: 1.6; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 14px; }
        .form-control { width: 100%; padding: 12px 16px 12px 40px; background: #0d0d22; border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; outline: none; transition: border-color .2s; }
        .form-control:focus { border-color: var(--primary); }
        .form-control::placeholder { color: var(--muted); }
        .btn-primary { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; font-size: 15px; font-weight: 600; border: none; border-radius: 10px; cursor: pointer; transition: all .2s; }
        .btn-primary:hover { opacity: .9; }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 13px; color: var(--muted); }
        .link { color: var(--primary-light); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
        .alert-success { background: rgba(16,185,129,0.1); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); }
        .alert-error { background: rgba(239,68,68,0.1); color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
    </style>
</head>
<body>
<div class="auth-box">
    <div class="auth-icon">🔐</div>
    <h1>Forgot Password?</h1>
    <p>Enter your email address and we'll send you a link to reset your password.</p>

    @if(session('status'))
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('status') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-error"><i class="fa fa-circle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <i class="input-icon fa fa-envelope"></i>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>
        <button type="submit" class="btn-primary"><i class="fa fa-paper-plane"></i> Send Reset Link</button>
    </form>
    <div class="auth-footer">
        Remembered it? <a href="{{ url('/login') }}" class="link">Back to login</a>
    </div>
</div>
</body>
</html>
