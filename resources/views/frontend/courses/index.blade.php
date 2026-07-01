<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --secondary: #8b5cf6;
            --bg: #0a0a1a; --bg2: #0d0d22; --card: #12122a;
            --border: rgba(255,255,255,0.07); --text: #e2e8f0; --muted: #94a3b8;
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
        .main-layout { display: grid; grid-template-columns: 280px 1fr; gap: 32px; padding: 40px; max-width: 1280px; margin: 0 auto; }
        .sidebar-filters { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; height: fit-content; }
        .filter-section { margin-bottom: 24px; }
        .filter-title { font-size: 14px; font-weight: 600; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); }
        .filter-list { list-style: none; }
        .filter-list li { margin-bottom: 10px; }
        .filter-link { font-size: 14px; color: var(--muted); display: block; padding: 6px 0; }
        .filter-link:hover, .filter-link.active { color: var(--primary-light); }

        .search-box { position: relative; margin-bottom: 24px; }
        .search-input { width: 100%; padding: 12px 16px 12px 40px; background: var(--card); border: 1px solid var(--border); border-radius: 10px; color: var(--text); outline: none; }
        .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); }

        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
        .course-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: transform 0.2s; }
        .course-card:hover { transform: translateY(-2px); border-color: var(--primary); }
        .course-thumb { height: 160px; background: linear-gradient(135deg, #1e1b4b, #312e81); display: flex; align-items: center; justify-content: center; font-size: 48px; }
        .course-body { padding: 20px; }
        .course-title { font-size: 15px; font-weight: 600; margin-bottom: 8px; line-height: 1.4; }
        .course-price { font-size: 16px; font-weight: 700; color: #a5b4fc; }

        @media (max-width: 900px) {
            .main-layout { grid-template-columns: 1fr; }
        }
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

<div class="main-layout">
    <aside class="sidebar-filters">
        <div class="filter-section">
            <h3 class="filter-title">Search</h3>
            <form action="{{ url('/courses') }}" method="GET">
                <div class="search-box">
                    <i class="search-icon fa fa-search"></i>
                    <input type="text" name="search" class="search-input" placeholder="Type keywords..." value="{{ request('search') }}">
                </div>
            </form>
        </div>
        <div class="filter-section">
            <h3 class="filter-title">Categories</h3>
            <ul class="filter-list">
                <li><a href="{{ url('/courses') }}" class="filter-link {{ !request('category') ? 'active' : '' }}">All Categories</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ url('/courses?category='.$cat->slug) }}" class="filter-link {{ request('category')===$cat->slug ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <main>
        <div class="courses-grid">
            @if(count($courses) > 0)
                @foreach($courses as $course)
                <a href="{{ url('/courses/'.$course->slug) }}" class="course-card">
                    <div class="course-thumb">🎓</div>
                    <div class="course-body">
                        <h3 class="course-title">{{ $course->title }}</h3>
                        <div class="course-price">{{ $course->is_free ? 'Free' : '$'.number_format($course->price, 2) }}</div>
                    </div>
                </a>
                @endforeach
            @else
                @foreach([['💻','Frontend React Course', '$29.99'], ['📊','Data Analytics Course', '$49.99'], ['🎨','Figma Design BootCamp', 'Free'], ['🐍','Python Automation Scripting', '$19.99']] as $dummy)
                <a href="#" class="course-card">
                    <div class="course-thumb">{{ $dummy[0] }}</div>
                    <div class="course-body">
                        <h3 class="course-title">{{ $dummy[1] }}</h3>
                        <div class="course-price">{{ $dummy[2] }}</div>
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </main>
</div>
</body>
</html>
