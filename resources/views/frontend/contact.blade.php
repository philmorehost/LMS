<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
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
        .content-container { max-width: 600px; margin: 60px auto; padding: 0 24px; }
        h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; text-align: center; }
        .subtitle { color: var(--muted); text-align: center; margin-bottom: 40px; font-size: 15px; }

        .form-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 32px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px 16px; background: #0d0d22; border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-family: inherit; font-size: 14px; outline: none; }
        .form-control:focus { border-color: var(--primary); }
        .btn-block { width: 100%; display: block; padding: 12px; font-weight: 600; }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 20px; }
        .alert-success { background: rgba(16,185,129,0.1); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.2); }
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
    <h1>Contact Us</h1>
    <div class="subtitle">Have any questions? We'd love to hear from you.</div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fa fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="form-card">
        <form method="POST" action="{{ url('/contact') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        </form>
    </div>
</div>
</body>
</html>
