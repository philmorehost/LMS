<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #a5b4fc;
            --secondary: #8b5cf6; --accent: #06b6d4;
            --success: #10b981; --error: #ef4444; --warning: #f59e0b;
            --bg: #0a0a1a; --bg2: #0d0d22; --card: #12122a; --card2: #1a1a3a;
            --border: rgba(255,255,255,0.07); --text: #e2e8f0; --muted: #94a3b8;
            --sidebar-w: 260px;
        }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; overflow-x: hidden; }
        a { text-decoration: none; color: inherit; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w); flex-shrink: 0;
            background: var(--card);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; left: 0; top: 0; bottom: 0;
            overflow-y: auto; z-index: 100;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-logo { padding: 24px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
        .logo-details { display: flex; align-items: center; gap: 12px; }
        .sidebar-logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0; }
        .sidebar-logo-text { font-size: 16px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sidebar-close-btn { display: none; background: none; border: none; color: var(--muted); font-size: 18px; cursor: pointer; }

        .sidebar-user { padding: 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; }
        .sidebar-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .sidebar-user-name { font-size: 14px; font-weight: 600; }
        .sidebar-user-role { font-size: 11px; color: var(--primary-light); text-transform: capitalize; }
        .sidebar-nav { flex: 1; padding: 16px 12px; }
        .sidebar-section-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); padding: 16px 8px 8px; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px;
            font-size: 14px; font-weight: 500; color: var(--muted);
            transition: all 0.2s; margin-bottom: 2px; cursor: pointer;
        }
        .sidebar-link:hover { background: rgba(99,102,241,0.1); color: var(--text); }
        .sidebar-link.active { background: rgba(99,102,241,0.15); color: var(--primary-light); }
        .sidebar-link i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }

        /* MAIN */
        .main-wrap { flex: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-height: 100vh; width: calc(100% - var(--sidebar-w)); transition: margin-left 0.3s, width 0.3s; }
        .topbar { background: var(--bg); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .sidebar-toggle-btn { display: none; background: none; border: none; color: var(--text); font-size: 20px; cursor: pointer; }
        .topbar-title { font-size: 18px; font-weight: 600; }
        .topbar-actions { display: flex; align-items: center; gap: 16px; }
        .topbar-btn { width: 36px; height: 36px; background: var(--card); border: 1px solid var(--border); border-radius: 9px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--muted); font-size: 14px; transition: all 0.2s; }
        .topbar-btn:hover { border-color: var(--primary); color: var(--primary-light); }
        .page-content { flex: 1; padding: 32px; overflow-x: auto; }

        /* BACKDROP FOR MOBILE SIDEBAR */
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 90; }

        /* CARDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.blue::before { background: linear-gradient(90deg, var(--primary), var(--accent)); }
        .stat-card.green::before { background: linear-gradient(90deg, var(--success), #34d399); }
        .stat-card.orange::before { background: linear-gradient(90deg, var(--warning), #fcd34d); }
        .stat-card.purple::before { background: linear-gradient(90deg, var(--secondary), var(--primary-light)); }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 16px; }
        .stat-icon.blue { background: rgba(99,102,241,0.15); }
        .stat-icon.green { background: rgba(16,185,129,0.15); }
        .stat-icon.orange { background: rgba(245,158,11,0.15); }
        .stat-icon.purple { background: rgba(139,92,246,0.15); }
        .stat-value { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .stat-label { font-size: 13px; color: var(--muted); }

        /* COURSE GRID */
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .course-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; transition: all 0.25s; }
        .course-card:hover { border-color: var(--primary); transform: translateY(-2px); }
        .course-thumb { height: 140px; background: linear-gradient(135deg, #1e1b4b, #312e81); display: flex; align-items: center; justify-content: center; font-size: 42px; }
        .course-body { padding: 16px; }
        .course-title { font-size: 14px; font-weight: 600; margin-bottom: 8px; line-height: 1.4; }
        .progress-bar { height: 5px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; margin-bottom: 6px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 3px; }
        .progress-text { font-size: 11px; color: var(--muted); }

        .empty-state { text-align: center; padding: 48px 24px; }
        .empty-icon { font-size: 48px; margin-bottom: 16px; }
        .empty-title { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
        .empty-desc { color: var(--muted); font-size: 14px; margin-bottom: 24px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px; border-radius: 9px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: #fff; box-shadow: 0 4px 16px rgba(99,102,241,0.3); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(99,102,241,0.5); }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-close-btn { display: block; }
            .sidebar-toggle-btn { display: block; }
            .main-wrap { margin-left: 0; width: 100%; }
            .page-content { padding: 20px; }
            .topbar { padding: 0 20px; }
        }
    </style>
</head>
<body>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

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

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-details">
            <div class="sidebar-logo-icon">{{ strtoupper(substr($settings['site_title'] ?? 'E', 0, 1)) }}</div>
            <span class="sidebar-logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
        </div>
        <button class="sidebar-close-btn" id="closeSidebar"><i class="fa fa-xmark"></i></button>
    </div>
    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
        <div>
            <div class="sidebar-user-name">{{ $user->name ?? 'Student' }}</div>
            <div class="sidebar-user-role">{{ $user->role ?? 'student' }}</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Main</div>
        <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <i class="fa fa-gauge-high"></i> Dashboard
        </a>
        <a href="{{ route('student.courses') }}" class="sidebar-link {{ request()->routeIs('student.courses*') ? 'active' : '' }}">
            <i class="fa fa-play-circle"></i> My Courses
        </a>
        <a href="{{ route('student.enrollments') }}" class="sidebar-link">
            <i class="fa fa-list-check"></i> Enrollments
        </a>
        <a href="{{ route('student.certificates') }}" class="sidebar-link">
            <i class="fa fa-certificate"></i> Certificates
        </a>
        <div class="sidebar-section-label">Shopping</div>
        <a href="{{ route('student.wishlist') }}" class="sidebar-link">
            <i class="fa fa-heart"></i> Wishlist
        </a>
        <a href="{{ route('student.cart') }}" class="sidebar-link">
            <i class="fa fa-cart-shopping"></i> Cart
        </a>
        <div class="sidebar-section-label">Account</div>
        <a href="{{ route('student.profile') }}" class="sidebar-link">
            <i class="fa fa-user-circle"></i> Profile
        </a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link" style="width:100%;background:none;border:none;text-align:left;">
                <i class="fa fa-arrow-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="toggleSidebar"><i class="fa fa-bars"></i></button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-actions">
            <a href="{{ url('/courses') }}" class="topbar-btn" title="Browse Courses"><i class="fa fa-compass"></i></a>
            <a href="{{ route('student.profile') }}" class="topbar-btn" title="Profile"><i class="fa fa-user"></i></a>
        </div>
    </header>
    <div class="page-content">
        @yield('content')
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('toggleSidebar');
    const closeBtn = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    function toggleMenu() {
        sidebar.classList.toggle('active');
        backdrop.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleMenu);
    if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
    if (backdrop) backdrop.addEventListener('click', toggleMenu);
</script>
</body>
</html>
