<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} | Online Course — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #a855f7; --primary-dark: #7e22ce;
            --bg: #09090b; --bg-banner: #18181b; --card: #18181b;
            --border: rgba(255,255,255,0.08); --text: #f4f4f5; --muted: #a1a1aa;
            --radius: 12px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); padding-top: 72px; }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar { position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 0 40px; height: 72px; display: flex; align-items: center; justify-content: space-between; background: rgba(9,9,11,0.9); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .nav-logo { display: flex; align-items: center; gap: 12px; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), #ec4899); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; }
        .logo-text { font-size: 18px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--muted)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; transition: opacity 0.2s; text-align: center; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary { background: rgba(255,255,255,0.05); color: #fff; border: 1px solid var(--border); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .btn-block { width: 100%; }

        /* BANNER */
        .udemy-banner { background: var(--bg-banner); border-bottom: 1px solid var(--border); padding: 48px max(24px, calc((100% - 1200px) / 2)); position: relative; }
        .banner-content { max-width: 800px; }
        .category-tag { font-size: 12px; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 16px; display: inline-block; }
        .course-title { font-size: clamp(24px, 3.5vw, 36px); font-weight: 800; line-height: 1.25; margin-bottom: 16px; }
        .course-subtitle { font-size: 16px; color: var(--muted); line-height: 1.5; margin-bottom: 20px; }
        .rating-row { display: flex; align-items: center; gap: 10px; font-size: 14px; margin-bottom: 16px; flex-wrap: wrap; }
        .stars { color: #fbbf24; font-weight: 700; }
        .meta-row { display: flex; align-items: center; gap: 16px; font-size: 13px; color: var(--muted); flex-wrap: wrap; }
        .meta-row i { color: var(--muted); }

        /* GRID CONTENT */
        .udemy-grid { max-width: 1200px; margin: 40px auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 360px; gap: 40px; }
        
        /* OUTCOME BOX */
        .outcome-box { border: 1px solid var(--border); border-radius: 12px; padding: 24px; background: rgba(255,255,255,0.01); margin-bottom: 32px; }
        .outcome-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; }
        .outcome-list { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; list-style: none; }
        .outcome-item { display: flex; gap: 10px; font-size: 13px; line-height: 1.5; }
        .outcome-item i { color: var(--success); flex-shrink: 0; margin-top: 3px; }

        /* SYLLABUS */
        .syllabus-section { margin-bottom: 40px; }
        .syllabus-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .syllabus-title { font-size: 20px; font-weight: 700; }
        .syllabus-info { font-size: 13px; color: var(--muted); }
        
        .syllabus-accordion { border: 1px solid var(--border); border-radius: 12px; overflow: hidden; background: var(--card); }
        .accordion-header { padding: 18px 24px; background: rgba(255,255,255,0.02); display: flex; justify-content: space-between; align-items: center; cursor: pointer; border-bottom: 1px solid var(--border); }
        .accordion-header h4 { font-size: 14px; font-weight: 600; }
        .accordion-content { padding: 8px 24px 18px; list-style: none; }
        .accordion-item { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; font-size: 13px; border-bottom: 1px dashed var(--border); }
        .accordion-item:last-child { border-bottom: none; }
        
        /* SIDEBAR CARD */
        .floating-sidebar { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); height: fit-content; position: sticky; top: 96px; }
        .sidebar-thumb { aspect-ratio: 16/9; background: #000; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 48px; border: 1px solid var(--border); margin-bottom: 20px; }
        .sidebar-price { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 20px; }
        .includes-title { font-weight: 700; font-size: 13px; margin: 24px 0 12px; }
        .includes-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .includes-item { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
        .includes-item i { width: 14px; text-align: center; }

        @media (max-width: 900px) {
            .udemy-grid { grid-template-columns: 1fr; }
            .floating-sidebar { position: static; }
            .outcome-list { grid-template-columns: 1fr; }
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
        <a href="{{ url('/courses') }}" class="btn btn-secondary">Browse All Courses</a>
    </div>
</nav>

<section class="udemy-banner">
    <div class="banner-content">
        <span class="category-tag">Featured Course</span>
        <h1 class="course-title">{{ $course->title }}</h1>
        <p class="course-subtitle">{{ $course->description ?? 'Obtain professional expertise with this comprehensive training lecture.' }}</p>
        
        <div class="rating-row">
            <span class="stars">★ 4.8</span>
            <span style="color:var(--muted)">(1,438 ratings) · 12,492 students</span>
        </div>

        <div class="meta-row">
            <span>Created by <strong>Instructor</strong></span>
            <span><i class="fa fa-circle-exclamation"></i> Last updated 2026</span>
            <span><i class="fa fa-globe"></i> English</span>
        </div>
    </div>
</section>

<div class="udemy-grid">
    <!-- Left Column: Syllabus and Details -->
    <main>
        <!-- What you'll learn outcome panel -->
        <div class="outcome-box">
            <h3 class="outcome-title">What you'll learn</h3>
            <ul class="outcome-list">
                <li class="outcome-item"><i class="fa fa-check"></i> Master the core conceptual mechanics step by step.</li>
                <li class="outcome-item"><i class="fa fa-check"></i> Build realistic production-grade deployment programs.</li>
                <li class="outcome-item"><i class="fa fa-check"></i> Obtain structural certification credentials instantly.</li>
                <li class="outcome-item"><i class="fa fa-check"></i> Align workflows with modern industrial best practices.</li>
            </ul>
        </div>

        <!-- Syllabus Outline Accordion -->
        <div class="syllabus-section">
            <div class="syllabus-header">
                <h3 class="syllabus-title">Course content</h3>
                <span class="syllabus-info">{{ count($lessons) }} lectures · 5h 32m total length</span>
            </div>
            
            <div class="syllabus-accordion">
                <div class="accordion-header">
                    <h4>Syllabus Lecture Timeline</h4>
                    <i class="fa fa-chevron-down" style="font-size:12px;"></i>
                </div>
                <ul class="accordion-content">
                    @if(count($lessons) > 0)
                        @foreach($lessons as $index => $lesson)
                        <li class="accordion-item">
                            <span><i class="fa fa-play-circle" style="margin-right:8px; color:var(--primary);"></i> Lecture {{ $index + 1 }}: {{ $lesson->title }}</span>
                            <span style="color:var(--muted)">05:00</span>
                        </li>
                        @endforeach
                    @else
                        <li class="accordion-item">
                            <span><i class="fa fa-play-circle" style="margin-right:8px; color:var(--primary);"></i> Introduction and outline summary</span>
                            <span style="color:var(--muted)">04:12</span>
                        </li>
                        <li class="accordion-item">
                            <span><i class="fa fa-play-circle" style="margin-right:8px; color:var(--primary);"></i> Setup & Environment parameters</span>
                            <span style="color:var(--muted)">12:45</span>
                        </li>
                        <li class="accordion-item">
                            <span><i class="fa fa-play-circle" style="margin-right:8px; color:var(--primary);"></i> Advanced practices and operations</span>
                            <span style="color:var(--muted)">28:00</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </main>

    <!-- Right Column: Sticky Checkout Sidebar -->
    <aside>
        <div class="floating-sidebar">
            <div class="sidebar-thumb">🎓</div>
            <div class="sidebar-price">
                @if($course->is_free)
                    Free
                @else
                    {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($course->price, 2) }}
                @endif
            </div>
            
            <div style="display:flex; flex-direction:column; gap:12px;">
                <form method="POST" action="{{ route('student.cart.add', $course->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> Add to cart</button>
                </form>
                <form method="POST" action="{{ route('student.cart.add', $course->id) }}">
                    @csrf
                    <input type="hidden" name="buy_now" value="1">
                    <button type="submit" class="btn btn-secondary btn-block">Buy now</button>
                </form>
            </div>

            <div class="includes-title">This course includes:</div>
            <ul class="includes-list">
                <li class="includes-item"><i class="fa fa-video"></i> 5.5 hours on-demand video</li>
                <li class="includes-item"><i class="fa fa-file"></i> Protected read-only materials</li>
                <li class="includes-item"><i class="fa fa-infinity"></i> Full lifetime access</li>
                <li class="includes-item"><i class="fa fa-mobile-screen"></i> Access on mobile and TV</li>
                <li class="includes-item"><i class="fa fa-certificate"></i> Certificate of completion</li>
            </ul>
        </div>
    </aside>
</div>
</body>
</html>
