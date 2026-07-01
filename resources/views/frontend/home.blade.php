<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['meta_title'] ?? ($settings['site_title'] ?? 'Educve LMS') }}</title>
    <meta name="description" content="{{ $settings['meta_description'] ?? 'Learn from the world\'s best instructors.' }}">
    <meta name="keywords" content="{{ $settings['meta_keywords'] ?? 'education, learn, courses' }}">
    {!! $settings['google_verification'] ?? '' !!}
    <link rel="icon" href="{{ url('/favicon.svg') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #a5b4fc;
            --secondary: #8b5cf6; --accent: #06b6d4;
            --success: #10b981; --error: #ef4444;
            --bg: #0a0a1a; --bg2: #0d0d22; --card: #12122a; --card2: #1a1a3a;
            --border: rgba(255,255,255,0.07); --text: #e2e8f0; --muted: #94a3b8;
            --radius: 16px;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }

        /* ── NAVBAR ──────────────────────────────────────────────────── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 0 max(24px, calc((100% - 1280px) / 2));
            height: 72px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(10,10,26,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; }
        .nav-logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 800; color: #fff; }
        .nav-logo-text { font-size: 20px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { font-size: 14px; font-weight: 500; color: var(--muted); transition: color 0.2s; }
        .nav-links a:hover { color: var(--text); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-ghost { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .btn-ghost:hover { border-color: var(--primary); color: var(--text); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 30px rgba(99,102,241,0.5); }

        /* ── HERO ────────────────────────────────────────────────────── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 100px max(24px, calc((100% - 1280px) / 2)) 80px;
            position: relative; overflow: hidden;
            background: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.18) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 100%, rgba(6,182,212,0.08) 0%, transparent 60%);
        }
        .hero-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; width: 100%; }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); border-radius: 100px; padding: 6px 16px; font-size: 13px; color: var(--primary-light); font-weight: 500; margin-bottom: 24px; }
        .hero-badge .dot { width: 8px; height: 8px; background: var(--primary); border-radius: 50%; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }
        .hero h1 { font-size: clamp(36px, 5vw, 58px); font-weight: 900; line-height: 1.1; margin-bottom: 20px; }
        .hero h1 span { background: linear-gradient(135deg, var(--primary-light), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 17px; color: var(--muted); line-height: 1.7; margin-bottom: 36px; max-width: 480px; }
        .hero-actions { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 48px; }
        .hero-stats { display: flex; gap: 32px; }
        .hero-stat-num { font-size: 24px; font-weight: 700; color: var(--text); }
        .hero-stat-label { font-size: 12px; color: var(--muted); }
        /* Search bar */
        .search-bar { position: relative; margin-bottom: 28px; }
        .search-input { width: 100%; padding: 16px 20px 16px 52px; background: var(--card); border: 1px solid var(--border); border-radius: 14px; color: var(--text); font-size: 15px; font-family: inherit; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .search-input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .search-input::placeholder { color: var(--muted); }
        .search-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 16px; }
        .search-btn { position: absolute; right: 8px; top: 50%; transform: translateY(-50%); }
        /* Hero visual */
        .hero-visual { position: relative; }
        .hero-cards-wrap { position: relative; }
        .hero-card-main { background: var(--card); border: 1px solid var(--border); border-radius: 20px; padding: 28px; overflow: hidden; position: relative; }
        .hero-card-main::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent)); }
        .course-preview { display: flex; flex-direction: column; gap: 16px; }
        .course-thumb { height: 160px; background: linear-gradient(135deg, #1e1b4b, #4c1d95); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 48px; }
        .course-info h3 { font-size: 15px; font-weight: 600; margin-bottom: 6px; }
        .course-meta { display: flex; align-items: center; gap: 12px; font-size: 12px; color: var(--muted); }
        .stars { color: #fbbf24; }
        .progress-wrap { margin-top: 12px; }
        .progress-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-bottom: 6px; }
        .progress-bar { height: 6px; background: rgba(255,255,255,0.1); border-radius: 3px; overflow: hidden; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 3px; transition: width 1s ease; }
        .floating-badge { position: absolute; background: var(--card2); border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); animation: floatBadge 4s ease-in-out infinite; }
        .floating-badge:nth-child(2) { top: -20px; right: -20px; animation-delay: 0s; }
        .floating-badge:nth-child(3) { bottom: -20px; left: -20px; animation-delay: 2s; }
        @keyframes floatBadge { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .badge-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .badge-icon.green { background: rgba(16,185,129,0.2); }
        .badge-icon.blue { background: rgba(6,182,212,0.2); }
        .badge-text .label { font-size: 11px; color: var(--muted); }
        .badge-text .value { font-size: 14px; font-weight: 600; }

        /* ── SECTIONS ─────────────────────────────────────────────────── */
        section { padding: 80px max(24px, calc((100% - 1280px) / 2)); }
        .section-header { text-align: center; margin-bottom: 56px; }
        .section-tag { display: inline-block; padding: 4px 14px; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); border-radius: 100px; font-size: 12px; font-weight: 600; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 14px; }
        .section-title { font-size: clamp(26px, 3.5vw, 40px); font-weight: 800; margin-bottom: 14px; }
        .section-subtitle { color: var(--muted); font-size: 16px; max-width: 520px; margin: 0 auto; }

        /* Categories */
        .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; }
        .cat-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 24px 20px; text-align: center; cursor: pointer; transition: all 0.3s; }
        .cat-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: 0 12px 40px rgba(99,102,241,0.15); }
        .cat-icon { font-size: 32px; margin-bottom: 12px; }
        .cat-name { font-size: 13px; font-weight: 600; margin-bottom: 4px; }
        .cat-count { font-size: 11px; color: var(--muted); }

        /* Course cards */
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
        .course-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; transition: all 0.3s; cursor: pointer; }
        .course-card:hover { border-color: var(--primary); transform: translateY(-4px); box-shadow: 0 16px 48px rgba(99,102,241,0.15); }
        .course-card-thumb { height: 180px; background: linear-gradient(135deg, #1e1b4b, #312e81); display: flex; align-items: center; justify-content: center; font-size: 52px; position: relative; }
        .course-badge { position: absolute; top: 12px; left: 12px; background: var(--primary); color: #fff; font-size: 10px; font-weight: 600; padding: 3px 10px; border-radius: 100px; text-transform: uppercase; }
        .course-card-body { padding: 20px; }
        .course-card-cat { font-size: 11px; color: var(--primary-light); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 8px; }
        .course-card-title { font-size: 15px; font-weight: 600; line-height: 1.4; margin-bottom: 10px; }
        .course-card-instructor { font-size: 12px; color: var(--muted); margin-bottom: 12px; }
        .course-card-meta { display: flex; align-items: center; justify-content: space-between; }
        .course-rating { font-size: 13px; }
        .course-rating .stars { font-size: 11px; }
        .course-price { font-size: 17px; font-weight: 700; color: var(--primary-light); }
        .course-price.free { color: var(--success); }

        /* Feature blocks */
        .features-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px; }
        .feature-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; transition: all 0.3s; }
        .feature-card:hover { border-color: var(--primary); }
        .feature-icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 16px; }
        .feature-icon.purple { background: rgba(99,102,241,0.15); }
        .feature-icon.cyan { background: rgba(6,182,212,0.15); }
        .feature-icon.green { background: rgba(16,185,129,0.15); }
        .feature-icon.orange { background: rgba(245,158,11,0.15); }
        .feature-title { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
        .feature-desc { font-size: 13px; color: var(--muted); line-height: 1.6; }

        /* Testimonials */
        .testimonials-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
        .testimonial-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 28px; position: relative; }
        .testimonial-quote { font-size: 48px; color: var(--primary); line-height: 1; margin-bottom: 12px; font-family: serif; }
        .testimonial-text { font-size: 14px; color: var(--muted); line-height: 1.7; margin-bottom: 20px; font-style: italic; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; }
        .testimonial-avatar { width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .testimonial-name { font-size: 14px; font-weight: 600; }
        .testimonial-role { font-size: 12px; color: var(--muted); }

        /* CTA */
        .cta-section { background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.15)); border: 1px solid rgba(99,102,241,0.2); border-radius: 24px; padding: 64px; text-align: center; margin: 0 max(24px, calc((100% - 1280px) / 2)) 80px; }
        .cta-section h2 { font-size: clamp(28px, 3.5vw, 42px); font-weight: 800; margin-bottom: 16px; }
        .cta-section p { color: var(--muted); font-size: 16px; margin-bottom: 32px; }
        .cta-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }

        /* Footer */
        footer { border-top: 1px solid var(--border); padding: 48px max(24px, calc((100% - 1280px) / 2)) 32px; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px; margin-bottom: 48px; }
        .footer-desc { font-size: 14px; color: var(--muted); line-height: 1.7; margin-top: 12px; }
        .footer-heading { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; color: var(--muted); margin-bottom: 16px; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a { font-size: 14px; color: var(--muted); transition: color 0.2s; }
        .footer-links a:hover { color: var(--text); }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; padding-top: 24px; border-top: 1px solid var(--border); font-size: 13px; color: var(--muted); }
        @media (max-width: 900px) {
            .hero-grid { grid-template-columns: 1fr; }
            .hero-visual { display: none; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>
@if(session('original_admin_id'))
<div style="position:fixed; bottom:20px; right:20px; left:20px; max-width:440px; margin:0 auto; background:rgba(24,24,27,0.85); border:1px solid rgba(219,39,119,0.3); backdrop-filter:blur(16px); border-radius:16px; padding:12px 16px; display:flex; align-items:center; justify-content:between; gap:16px; box-shadow:0 10px 30px rgba(0,0,0,0.5), 0 0 10px rgba(219,39,119,0.1); z-index:9999;">
    <div style="display:flex; align-items:center; gap:10px; min-width:0; flex:1;">
        <div style="width:10px; height:10px; background:#db2777; border-radius:50%; flex-shrink:0; animation:pulse 1.5s infinite;"></div>
        <div style="font-size:12px; font-weight:500; color:#e4e4e7; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; line-height:1.4;">
            Impersonating <strong style="color:#db2777">{{ auth()->user()->name }}</strong>
        </div>
    </div>
    <a href="{{ route('users.stop-impersonating') }}" style="background:#db2777; color:#fff; font-size:11px; font-weight:600; padding:8px 14px; border-radius:10px; text-decoration:none; white-space:nowrap; transition:opacity 0.2s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
        <i class="fa fa-arrow-right-to-bracket"></i> Switch back
    </a>
</div>
<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.5; }
    }
</style>
@endif

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-logo">
        <div class="nav-logo-icon">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
        <span class="nav-logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
    </div>
    <div class="nav-links">
        <a href="{{ url('/courses') }}">Courses</a>
        <a href="{{ url('/instructors') }}">Instructors</a>
        <a href="{{ url('/blog') }}">Blog</a>
        <a href="{{ url('/about') }}">About</a>
        <a href="{{ url('/contact') }}">Contact</a>
    </div>
    <div class="nav-actions">
        @auth
            <a href="{{ url('/'.auth()->user()->role.'/dashboard') }}" class="btn btn-ghost">Dashboard</a>
        @else
            <a href="{{ url('/login') }}" class="btn btn-ghost">Sign In</a>
            <a href="{{ url('/register') }}" class="btn btn-primary">Get Started</a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-grid">
        <div>
            <div class="hero-badge">
                <span class="dot"></span>
                🚀 Now with AI-powered learning paths
            </div>
            <h1>Learn Without <span>Limits</span>. Grow Without Boundaries.</h1>
            <p>Access thousands of expert-led courses, earn verified certificates, and advance your career — all in one powerful platform.</p>
            <div class="search-bar">
                <i class="search-icon fa fa-search"></i>
                <input type="text" class="search-input" placeholder="Search for courses, instructors, or topics...">
                <a href="{{ url('/courses') }}" class="btn btn-primary search-btn">Search</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">50K+</div>
                    <div class="hero-stat-label">Students Enrolled</div>
                </div>
                <div>
                    <div class="hero-stat-num">1,200+</div>
                    <div class="hero-stat-label">Courses Available</div>
                </div>
                <div>
                    <div class="hero-stat-num">98%</div>
                    <div class="hero-stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-cards-wrap">
                <div class="floating-badge" style="top:-24px;right:-24px;">
                    <div class="badge-icon green">🏆</div>
                    <div class="badge-text">
                        <div class="label">Certificate Earned</div>
                        <div class="value">Web Development</div>
                    </div>
                </div>
                <div class="hero-card-main">
                    <div class="course-preview">
                        <div class="course-thumb">🎓</div>
                        <div class="course-info">
                            <h3>Full Stack Web Development</h3>
                            <div class="course-meta">
                                <span class="stars">★★★★★</span>
                                <span>4.9 (2.4k reviews)</span>
                                <span>· 42 lessons</span>
                            </div>
                        </div>
                        <div class="progress-wrap">
                            <div class="progress-label"><span>Your progress</span><span>68%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:68%;"></div></div>
                        </div>
                    </div>
                </div>
                <div class="floating-badge" style="bottom:-24px;left:-24px;">
                    <div class="badge-icon blue">📈</div>
                    <div class="badge-text">
                        <div class="label">New Enrollment</div>
                        <div class="value">+24 this week</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section style="background:var(--bg2);">
    <div class="section-header">
        <div class="section-tag">Explore</div>
        <h2 class="section-title">Browse by Category</h2>
        <p class="section-subtitle">Find courses in topics you love and build skills that matter.</p>
    </div>
    @if(count($categories) > 0)
    <div class="cat-grid">
        @foreach($categories as $cat)
        <a href="{{ url('/courses?category='.$cat->slug) }}" class="cat-card">
            <div class="cat-icon">{{ $cat->icon ?? '📚' }}</div>
            <div class="cat-name">{{ $cat->name }}</div>
            <div class="cat-count">Explore courses</div>
        </a>
        @endforeach
    </div>
    @else
    <div class="cat-grid">
        @foreach([['💻','Programming'],['🎨','Design'],['📊','Business'],['📱','Mobile Dev'],['🤖','AI & ML'],['🔒','Cybersecurity'],['📸','Photography'],['🎵','Music'],['✍️','Writing'],['📈','Marketing'],['🌐','Web Dev'],['📐','Architecture']] as $cat)
        <a href="{{ url('/courses') }}" class="cat-card">
            <div class="cat-icon">{{ $cat[0] }}</div>
            <div class="cat-name">{{ $cat[1] }}</div>
            <div class="cat-count">Browse courses</div>
        </a>
        @endforeach
    </div>
    @endif
</section>

<!-- FEATURED COURSES -->
<section>
    <div class="section-header">
        <div class="section-tag">Featured</div>
        <h2 class="section-title">Top Courses This Month</h2>
        <p class="section-subtitle">Hand-picked by our team for the best learning experience.</p>
    </div>
    @if(count($featuredCourses) > 0)
    <div class="courses-grid">
        @foreach($featuredCourses as $course)
        <a href="{{ url('/courses/'.$course->slug) }}" class="course-card">
            <div class="course-card-thumb">
                🎓
                @if($course->is_free)
                <span class="course-badge" style="background:var(--success);">Free</span>
                @else
                <span class="course-badge">Featured</span>
                @endif
            </div>
            <div class="course-card-body">
                <div class="course-card-cat">Course</div>
                <h3 class="course-card-title">{{ $course->title }}</h3>
                <div class="course-card-instructor"><i class="fa fa-user"></i> Instructor</div>
                <div class="course-card-meta">
                    <div class="course-rating"><span class="stars">★★★★★</span> {{ number_format($course->average_rating, 1) }}</div>
                    <div class="course-price {{ $course->is_free ? 'free' : '' }}">{{ $course->is_free ? 'Free' : '$'.number_format($course->price, 2) }}</div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="courses-grid">
        @foreach([['🎓','Full Stack Web Development','$49.99'],['📊','Data Science Masterclass','$59.99'],['🤖','Machine Learning A-Z','$44.99'],['📱','iOS App Development','$39.99'],['🎨','UI/UX Design Complete Guide','Free'],['🔒','Ethical Hacking Bootcamp','$54.99'],['📈','Digital Marketing Pro','$34.99'],['🐍','Python for Beginners','Free']] as $c)
        <a href="{{ url('/courses') }}" class="course-card">
            <div class="course-card-thumb">{{ $c[0] }}<span class="course-badge">Featured</span></div>
            <div class="course-card-body">
                <div class="course-card-cat">Course</div>
                <h3 class="course-card-title">{{ $c[1] }}</h3>
                <div class="course-card-instructor"><i class="fa fa-user"></i> Expert Instructor</div>
                <div class="course-card-meta">
                    <div class="course-rating"><span class="stars">★★★★★</span> 4.9</div>
                    <div class="course-price {{ $c[2]==='Free'?'free':'' }}">{{ $c[2] }}</div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
    <div style="text-align:center;margin-top:40px;">
        <a href="{{ url('/courses') }}" class="btn btn-primary" style="font-size:15px;padding:13px 32px;">View All Courses <i class="fa fa-arrow-right"></i></a>
    </div>
</section>

<!-- FEATURES -->
<section style="background:var(--bg2);">
    <div class="section-header">
        <div class="section-tag">Why Choose Us</div>
        <h2 class="section-title">Everything You Need to Succeed</h2>
        <p class="section-subtitle">A complete learning ecosystem designed for modern professionals.</p>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon purple">🎥</div>
            <div class="feature-title">HD Video Lessons</div>
            <div class="feature-desc">Crystal clear video content with multi-quality streaming, offline downloads, and closed captions.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon cyan">🏆</div>
            <div class="feature-title">Verified Certificates</div>
            <div class="feature-desc">Earn industry-recognized certificates that you can add to your LinkedIn profile and resume.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon green">💬</div>
            <div class="feature-title">Expert Instructors</div>
            <div class="feature-desc">Learn from industry veterans with real-world experience and outstanding teaching track records.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon orange">📱</div>
            <div class="feature-title">Learn Anywhere</div>
            <div class="feature-desc">Access your courses from any device — desktop, tablet, or mobile — at any time.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon purple">🤖</div>
            <div class="feature-title">AI Learning Paths</div>
            <div class="feature-desc">Personalized course recommendations powered by AI based on your goals and progress.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon cyan">♾️</div>
            <div class="feature-title">Lifetime Access</div>
            <div class="feature-desc">Once enrolled, access the course material for life including all future updates.</div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section>
    <div class="section-header">
        <div class="section-tag">Success Stories</div>
        <h2 class="section-title">What Our Students Say</h2>
        <p class="section-subtitle">Join thousands of learners who have transformed their careers.</p>
    </div>
    @if(count($testimonials) > 0)
    <div class="testimonials-grid">
        @foreach($testimonials as $t)
        <div class="testimonial-card">
            <div class="testimonial-quote">"</div>
            <p class="testimonial-text">{{ $t->content }}</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">{{ strtoupper(substr($t->name,0,1)) }}</div>
                <div>
                    <div class="testimonial-name">{{ $t->name }}</div>
                    <div class="testimonial-role">{{ $t->position }}</div>
                    <div class="stars" style="font-size:12px;margin-top:2px;">{{ str_repeat('★', $t->rating) }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="testimonials-grid">
        @foreach([['Sarah Johnson','Frontend Developer','★★★★★','This platform completely transformed my career. The courses are incredibly detailed and the instructors are world-class.'],['Michael Chen','Data Scientist','★★★★★','The AI & ML courses here are the best I have found online. Got my dream job at a top tech company after completing just 3 courses.'],['Amara Nwachukwu','UX Designer','★★★★★','I love how the platform tracks my progress and recommends what to study next. The certificate I earned helped me get a promotion.']] as $t)
        <div class="testimonial-card">
            <div class="testimonial-quote">"</div>
            <p class="testimonial-text">{{ $t[3] }}</p>
            <div class="testimonial-author">
                <div class="testimonial-avatar">{{ substr($t[0],0,1) }}</div>
                <div>
                    <div class="testimonial-name">{{ $t[0] }}</div>
                    <div class="testimonial-role">{{ $t[1] }}</div>
                    <div class="stars" style="font-size:12px;margin-top:2px;">{{ $t[2] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</section>

<!-- CTA -->
<div class="cta-section">
    <h2>Ready to Start Learning?</h2>
    <p>Join over 50,000 students already learning on our platform. Start your journey today — it's free to sign up.</p>
    <div class="cta-actions">
        <a href="{{ url('/register') }}" class="btn btn-primary" style="font-size:15px;padding:14px 32px;">
            <i class="fa fa-rocket"></i> Start Learning Free
        </a>
        <a href="{{ url('/courses') }}" class="btn btn-ghost" style="font-size:15px;padding:14px 32px;">
            Browse Courses <i class="fa fa-arrow-right"></i>
        </a>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <div class="footer-grid">
        <div>
            <div class="nav-logo">
                <div class="nav-logo-icon">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
                <span class="nav-logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
            </div>
            <p class="footer-desc">The modern learning management system built for educators and learners who demand excellence.</p>
        </div>
        <div>
            <div class="footer-heading">Learn</div>
            <ul class="footer-links">
                <li><a href="{{ url('/courses') }}">All Courses</a></li>
                <li><a href="{{ url('/instructors') }}">Instructors</a></li>
                <li><a href="{{ url('/certificate/verify/demo') }}">Verify Certificate</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-heading">Company</div>
            <ul class="footer-links">
                <li><a href="{{ url('/about') }}">About Us</a></li>
                <li><a href="{{ url('/blog') }}">Blog</a></li>
                <li><a href="{{ url('/contact') }}">Contact</a></li>
                <li><a href="{{ url('/faq') }}">FAQ</a></li>
            </ul>
        </div>
        <div>
            <div class="footer-heading">Legal</div>
            <ul class="footer-links">
                <li><a href="{{ url('/page/privacy-policy') }}">Privacy Policy</a></li>
                <li><a href="{{ url('/page/terms-of-service') }}">Terms of Service</a></li>
                <li><a href="{{ url('/page/refund-policy') }}">Refund Policy</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} {{ $settings['site_title'] ?? 'Educve LMS' }}. All rights reserved.</span>
        <span>Built with ❤️ for learners everywhere</span>
    </div>
</footer>

</body>
</html>
