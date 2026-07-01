<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup — {{ config('lms.name', 'Educve LMS') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-light: #a5b4fc; --success: #10b981; --error: #ef4444; --bg: #0f0f1a; --card: #1a1a2e; --card2: #16213e; --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8; --radius: 16px; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%); }
        .installer-wrap { width: 100%; max-width: 720px; padding: 24px; }
        .installer-header { text-align: center; margin-bottom: 40px; }
        .installer-logo { width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary), #8b5cf6); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; color: #fff; margin: 0 auto 20px; box-shadow: 0 0 40px rgba(99,102,241,0.4); }
        .installer-header h1 { font-size: 28px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px; }
        .installer-header p { color: var(--muted); font-size: 15px; }
        .steps { display: flex; align-items: center; justify-content: center; margin-bottom: 36px; }
        .step-item { display: flex; align-items: center; gap: 10px; }
        .step-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; border: 2px solid var(--border); background: var(--card2); color: var(--muted); flex-shrink: 0; }
        .step-item.active .step-circle { border-color: var(--primary); background: var(--primary); color: #fff; box-shadow: 0 0 20px rgba(99,102,241,0.5); }
        .step-item.done .step-circle { border-color: var(--success); background: var(--success); color: #fff; }
        .step-label { font-size: 13px; color: var(--muted); font-weight: 500; white-space: nowrap; }
        .step-item.active .step-label, .step-item.done .step-label { color: var(--text); }
        .step-connector { width: 60px; height: 2px; background: var(--border); margin: 0 8px; flex-shrink: 0; }
        .step-connector.done { background: var(--success); }
        .installer-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px; }
        .card-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .card-subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .section-divider { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin: 24px 0 16px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .form-label span { color: var(--error); }
        .form-control { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control::placeholder { color: var(--muted); }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px; }
        select.form-control option { background-color: var(--card); color: var(--text); }
        .favicon-preview { display: flex; align-items: center; gap: 16px; margin-top: 12px; }
        .favicon-box { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: #fff; background: var(--primary); transition: all 0.3s; flex-shrink: 0; }
        .favicon-hint { font-size: 12px; color: var(--muted); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-top: 16px; display: none; }
        .alert-success { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3); }
        .alert-error   { background: rgba(239,68,68,0.15); color: var(--error); border: 1px solid rgba(239,68,68,0.3); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.35); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 25px rgba(99,102,241,0.5); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .footer-actions { display: flex; align-items: center; justify-content: space-between; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .installer-card { padding: 24px 20px; } .step-connector { width: 30px; } .step-label { display: none; } }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="installer-wrap">
    <div class="installer-header">
        <div class="installer-logo" id="logoPreview">L</div>
        <h1>Install Educve LMS</h1>
        <p>Create your admin account and configure the site</p>
    </div>
    <div class="steps">
        <div class="step-item done"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Requirements</span></div>
        <div class="step-connector done"></div>
        <div class="step-item done"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Database</span></div>
        <div class="step-connector done"></div>
        <div class="step-item active"><div class="step-circle">3</div><span class="step-label">Admin Setup</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle">4</div><span class="step-label">Complete</span></div>
    </div>
    <div class="installer-card">
        <div class="card-title"><i class="fa fa-user-shield" style="color:var(--primary)"></i> &nbsp;Admin Account & Site Settings</div>
        <div class="card-subtitle">Set up your administrator credentials and basic site configuration</div>

        <!-- Site Info -->
        <div class="section-divider"><i class="fa fa-globe"></i> &nbsp;Site Information</div>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label">Site Title <span>*</span></label>
                <input type="text" id="site_title" class="form-control" placeholder="e.g. Educve LMS" oninput="updateFavicon(this.value)">
                <div class="favicon-preview">
                    <div class="favicon-box" id="faviconPreview">L</div>
                    <span class="favicon-hint">The first letter of your site title will be used as the favicon</span>
                </div>
            </div>
            <div class="form-group full">
                <label class="form-label">Site URL <span>*</span></label>
                <input type="url" id="site_url" class="form-control" placeholder="https://yourdomain.com" value="{{ config('app.url') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Default Language</label>
                <select id="language" class="form-control">
                    <option value="en">🇬🇧 English</option>
                    <option value="ar">🇸🇦 Arabic</option>
                    <option value="fr">🇫🇷 French</option>
                    <option value="de">🇩🇪 German</option>
                    <option value="es">🇪🇸 Spanish</option>
                    <option value="it">🇮🇹 Italian</option>
                    <option value="pt">🇵🇹 Portuguese</option>
                    <option value="zh">🇨🇳 Chinese</option>
                    <option value="ja">🇯🇵 Japanese</option>
                    <option value="ko">🇰🇷 Korean</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Default Currency</label>
                <select id="currency" class="form-control">
                    <option value="USD">🇺🇸 USD - US Dollar</option>
                    <option value="EUR">🇪🇺 EUR - Euro</option>
                    <option value="GBP">🇬🇧 GBP - British Pound</option>
                    <option value="NGN">🇳🇬 NGN - Nigerian Naira</option>
                    <option value="INR">🇮🇳 INR - Indian Rupee</option>
                    <option value="CAD">🇨🇦 CAD - Canadian Dollar</option>
                    <option value="AUD">🇦🇺 AUD - Australian Dollar</option>
                </select>
            </div>
        </div>

        <!-- Admin Account -->
        <div class="section-divider"><i class="fa fa-user"></i> &nbsp;Administrator Account</div>
        <div class="form-grid">
            <div class="form-group full">
                <label class="form-label">Full Name <span>*</span></label>
                <input type="text" id="admin_name" class="form-control" placeholder="John Doe">
            </div>
            <div class="form-group full">
                <label class="form-label">Email Address <span>*</span></label>
                <input type="email" id="admin_email" class="form-control" placeholder="admin@yourdomain.com">
            </div>
            <div class="form-group">
                <label class="form-label">Password <span>*</span></label>
                <input type="password" id="admin_password" class="form-control" placeholder="Min 8 characters">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password <span>*</span></label>
                <input type="password" id="admin_password_confirmation" class="form-control" placeholder="Repeat password">
            </div>
        </div>

        <div class="alert alert-success" id="setupSuccess"></div>
        <div class="alert alert-error" id="setupError"></div>

        <div class="footer-actions">
            <a href="{{ route('installer.database') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i> Back</a>
            <button class="btn btn-primary" id="setupBtn" onclick="saveSetup()">
                Finish Installation <i class="fa fa-rocket"></i>
            </button>
        </div>
    </div>
</div>
<script>
function updateFavicon(title) {
    const letter = title.trim() ? title.trim()[0].toUpperCase() : 'L';
    document.getElementById('faviconPreview').textContent = letter;
    document.getElementById('logoPreview').textContent = letter;
}
async function saveSetup() {
    const btn = document.getElementById('setupBtn');
    const successEl = document.getElementById('setupSuccess');
    const errorEl = document.getElementById('setupError');
    const pwd = document.getElementById('admin_password').value;
    const conf = document.getElementById('admin_password_confirmation').value;
    if (pwd !== conf) { show(errorEl, '<i class="fa fa-circle-xmark"></i> Passwords do not match.'); return; }
    btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Installing...';
    try {
        const res = await fetch('{{ route("installer.save-admin-setup") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({
                admin_name: document.getElementById('admin_name').value,
                admin_email: document.getElementById('admin_email').value,
                admin_password: pwd, admin_password_confirmation: conf,
                site_title: document.getElementById('site_title').value,
                site_url: document.getElementById('site_url').value,
                currency: document.getElementById('currency').value,
                language: document.getElementById('language').value,
            }),
        });
        const data = await res.json();
        if (data.success) { 
            show(successEl, '<i class="fa fa-check-circle"></i> ' + data.message); 
            hide(errorEl); 
            setTimeout(() => { window.location.href = '{{ route("installer.complete") }}'; }, 1200); 
        } else { 
            show(errorEl, '<i class="fa fa-circle-xmark"></i> ' + (data.message || 'Validation or installation error.')); 
            hide(successEl); 
            btn.disabled = false; 
            btn.innerHTML = 'Finish Installation <i class="fa fa-rocket"></i>'; 
        }
    } catch(e) { 
        show(errorEl, 'Server error (500). Details: ' + e.message); 
        btn.disabled = false; 
        btn.innerHTML = 'Finish Installation <i class="fa fa-rocket"></i>'; 
    }
}
function show(el, msg) { el.innerHTML = msg; el.style.display = 'block'; }
function hide(el) { el.style.display = 'none'; }
</script>
</body>
</html>
