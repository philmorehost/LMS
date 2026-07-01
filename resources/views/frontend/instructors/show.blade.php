<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $instructor->name }} — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
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

        /* DETAILS */
        .instructor-details { max-width: 1000px; margin: 40px auto; padding: 0 24px; display: grid; grid-template-columns: 280px 1fr; gap: 40px; }
        .avatar-panel { text-align: center; }
        .avatar { width: 140px; height: 140px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: 800; color: #fff; margin: 0 auto 20px; }
        .name { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
        .title { font-size: 14px; color: var(--primary-light); font-weight: 500; }

        .info-panel h2 { font-size: 24px; font-weight: 800; margin-bottom: 16px; }
        .bio { color: var(--muted); font-size: 15px; line-height: 1.7; margin-bottom: 40px; }

        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .course-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
        .course-thumb { height: 120px; background: linear-gradient(135deg, #1e1b4b, #312e81); display: flex; align-items: center; justify-content: center; font-size: 36px; }
        .course-body { padding: 16px; }
        .course-title { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="logo-icon">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
        <span class="logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
    </a>
    <div>
        <a href="{{ url('/instructors') }}" class="btn btn-primary">Instructors</a>
    </div>
</nav>

<div class="instructor-details">
    <aside class="avatar-panel">
        <div class="avatar">{{ strtoupper(substr($instructor->name, 0, 1)) }}</div>
        <h1 class="name">{{ $instructor->name }}</h1>
        <div class="title">Expert Instructor</div>
    </aside>

    <main class="info-panel">
        <h2>Biography</h2>
        <p class="bio">{{ $instructor->bio ?? 'No biography details provided.' }}</p>

        <h3 style="font-size:18px; font-weight:700; margin-bottom:20px;">Courses by this Instructor</h3>
        <div class="courses-grid">
            @if(count($courses) > 0)
                @foreach($courses as $course)
                <a href="{{ url('/courses/'.$course->slug) }}" class="course-card">
                    <div class="course-thumb">🎓</div>
                    <div class="course-body">
                        <h4 class="course-title">{{ $course->title }}</h4>
                    </div>
                </a>
                @endforeach
            @else
                <div style="color:var(--muted); font-size:14px;">No active courses listed yet.</div>
            @endif
        </div>
    </main>
</div>
</body>
</html>
