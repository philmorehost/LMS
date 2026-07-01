<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructors — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
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
        .content-container { max-width: 1200px; margin: 60px auto; padding: 0 24px; }
        h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; text-align: center; }
        .subtitle { color: var(--muted); text-align: center; margin-bottom: 48px; font-size: 15px; }

        .instructors-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 24px; }
        .instructor-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 32px 24px; text-align: center; transition: transform 0.2s; }
        .instructor-card:hover { transform: translateY(-2px); border-color: var(--primary); }
        .avatar { width: 96px; height: 96px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 800; color: #fff; margin: 0 auto 20px; }
        .name { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
        .bio { font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 20px; height: 45px; overflow: hidden; }
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
    <h1>Our Instructors</h1>
    <div class="subtitle">Learn from industry veterans with years of hands-on experience.</div>

    <div class="instructors-grid">
        @if(count($instructors) > 0)
            @foreach($instructors as $instructor)
            <a href="{{ url('/instructors/'.$instructor->id) }}" class="instructor-card">
                <div class="avatar">{{ strtoupper(substr($instructor->name, 0, 1)) }}</div>
                <div class="name">{{ $instructor->name }}</div>
                <div class="bio">{{ $instructor->bio ?? 'Expert Instructor' }}</div>
                <span style="font-size:12px;color:var(--primary-light)">View Profile →</span>
            </a>
            @endforeach
        @else
            @foreach([['Sarah Connor', 'Frontend architect'], ['Alex Rivera', 'DevOps Team Lead'], ['John Doe', 'Full Stack Developer']] as $dummy)
            <a href="#" class="instructor-card">
                <div class="avatar">{{ substr($dummy[0], 0, 1) }}</div>
                <div class="name">{{ $dummy[0] }}</div>
                <div class="bio">{{ $dummy[1] }}</div>
                <span style="font-size:12px;color:var(--primary-light)">View Profile →</span>
            </a>
            @endforeach
        @endif
    </div>
</div>
</body>
</html>
