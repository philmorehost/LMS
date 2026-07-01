<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — {{ $settings['site_title'] ?? 'Educve LMS' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #8b5cf6; --primary-light: #c084fc;
            --success: #10b981; --error: #ef4444; --warning: #f59e0b;
            --bg: #09090b; --bg2: #18181b; --card: #18181b; --card2: #27272a;
            --border: rgba(255,255,255,0.08); --text: #f4f4f5; --muted: #a1a1aa;
            --sidebar-w: 260px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; overflow-x: hidden; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }

        /* SIDEBAR */
        .sidebar { width: var(--sidebar-w); background: var(--card); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; left: 0; top: 0; bottom: 0; z-index: 100; overflow-y: auto; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-logo { padding: 24px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
        .logo-details { display: flex; align-items: center; gap: 12px; }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), #ec4899); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; }
        .logo-text { font-size: 16px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sidebar-close-btn { display: none; background: none; border: none; color: var(--muted); font-size: 18px; cursor: pointer; }

        .sidebar-nav { flex: 1; padding: 16px 12px; }
        .nav-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--muted); padding: 16px 8px 8px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 10px; font-size: 14px; font-weight: 500; color: var(--muted); transition: all 0.2s; margin-bottom: 2px; }
        .nav-link:hover { background: rgba(255,255,255,0.05); color: var(--text); }
        .nav-link.active { background: rgba(139,92,246,0.15); color: var(--primary-light); }
        .nav-link i { width: 18px; text-align: center; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }

        /* MAIN */
        .main-wrap { flex: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-height: 100vh; width: calc(100% - var(--sidebar-w)); transition: margin-left 0.3s, width 0.3s; }
        .topbar { background: var(--bg); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .sidebar-toggle-btn { display: none; background: none; border: none; color: var(--text); font-size: 20px; cursor: pointer; }
        .topbar-title { font-size: 18px; font-weight: 600; }
        .page-content { flex: 1; padding: 32px; overflow-x: auto; }

        /* BACKDROP FOR MOBILE SIDEBAR */
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 90; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; position: relative; }
        .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 14px; }
        .stat-icon.purple { background: rgba(139,92,246,0.15); color: var(--primary-light); }
        .stat-icon.green { background: rgba(16,185,129,0.15); color: #34d399; }
        .stat-icon.orange { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .stat-icon.blue { background: rgba(6,182,212,0.15); color: #67e8f9; }
        .stat-value { font-size: 28px; font-weight: 700; margin-bottom: 4px; }
        .stat-label { font-size: 13px; color: var(--muted); }

        /* GRID / TABLES */
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .panel-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; overflow-x: auto; }
        .panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .panel-title { font-size: 16px; font-weight: 700; }
        .table-responsive { width: 100%; overflow-x: auto; }
        .custom-table { width: 100%; border-collapse: collapse; text-align: left; min-width: 500px; }
        .custom-table th, .custom-table td { padding: 12px; border-bottom: 1px solid var(--border); font-size: 13px; }
        .custom-table th { color: var(--muted); font-weight: 500; }
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; text-transform: capitalize; }
        .badge-admin { background: rgba(139,92,246,0.15); color: var(--primary-light); }
        .badge-instructor { background: rgba(6,182,212,0.15); color: #67e8f9; }
        .badge-student { background: rgba(16,185,129,0.15); color: #34d399; }

        @media (max-width: 1024px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
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

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-details">
            <div class="logo-icon">👑</div>
            <span class="logo-text">{{ $settings['site_title'] ?? 'Educve LMS' }}</span>
        </div>
        <button class="sidebar-close-btn" id="closeSidebar"><i class="fa fa-xmark"></i></button>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Core</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-gauge"></i> Dashboard
        </a>
        <div class="nav-label">Management</div>
        <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}"><i class="fa fa-book"></i> Courses</a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fa fa-users"></i> Users</a>
        <a href="{{ route('admin.enrollments.index') }}" class="nav-link {{ request()->routeIs('admin.enrollments*') ? 'active' : '' }}"><i class="fa fa-list-check"></i> Enrollments</a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link"><i class="fa fa-credit-card"></i> Payments</a>
        <a href="{{ route('admin.withdrawals.index') }}" class="nav-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}"><i class="fa fa-sack-dollar"></i> Withdrawals</a>
        <div class="nav-label">System</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}"><i class="fa fa-gear"></i> Settings</a>
        <a href="/clear-cache" class="nav-link" target="_blank"><i class="fa fa-broom"></i> Clear Cache</a>
    </nav>
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link" style="width:100%;background:none;border:none;text-align:left;cursor:pointer;">
                <i class="fa fa-arrow-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle-btn" id="toggleSidebar"><i class="fa fa-bars"></i></button>
            <h1 class="topbar-title">@yield('page-title', 'Admin Dashboard')</h1>
        </div>
        <div>
            <a href="{{ url('/') }}" class="nav-link" target="_blank"><i class="fa fa-eye"></i> View Site</a>
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
