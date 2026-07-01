<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup — {{ config('lms.name', 'Educve LMS') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5; --primary-light: #a5b4fc;
            --success: #10b981; --error: #ef4444; --warning: #f59e0b;
            --bg: #0f0f1a; --card: #1a1a2e; --card2: #16213e;
            --border: rgba(255,255,255,0.08); --text: #e2e8f0; --muted: #94a3b8; --radius: 16px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%), radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%); }
        .installer-wrap { width: 100%; max-width: 680px; padding: 24px; }
        .installer-header { text-align: center; margin-bottom: 40px; }
        .installer-logo { width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary), #8b5cf6); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 28px; font-weight: 800; color: #fff; margin: 0 auto 20px; box-shadow: 0 0 40px rgba(99,102,241,0.4); }
        .installer-header h1 { font-size: 28px; font-weight: 700; background: linear-gradient(135deg, #fff, var(--primary-light)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 8px; }
        .installer-header p { color: var(--muted); font-size: 15px; }
        .steps { display: flex; align-items: center; justify-content: center; margin-bottom: 36px; }
        .step-item { display: flex; align-items: center; gap: 10px; }
        .step-circle { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 600; border: 2px solid var(--border); background: var(--card2); color: var(--muted); flex-shrink: 0; transition: all 0.3s; }
        .step-item.active .step-circle { border-color: var(--primary); background: var(--primary); color: #fff; box-shadow: 0 0 20px rgba(99,102,241,0.5); }
        .step-item.done .step-circle { border-color: var(--success); background: var(--success); color: #fff; }
        .step-label { font-size: 13px; color: var(--muted); font-weight: 500; white-space: nowrap; }
        .step-item.active .step-label, .step-item.done .step-label { color: var(--text); }
        .step-connector { width: 60px; height: 2px; background: var(--border); margin: 0 8px; flex-shrink: 0; }
        .step-connector.done { background: var(--success); }
        .installer-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px; }
        .card-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .card-subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 8px; }
        .form-label span { color: var(--error); }
        .form-control { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 10px; color: var(--text); font-size: 14px; font-family: inherit; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .form-control::placeholder { color: var(--muted); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 14px; margin-top: 12px; display: none; }
        .alert-success { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3); }
        .alert-error   { background: rgba(239,68,68,0.15); color: var(--error); border: 1px solid rgba(239,68,68,0.3); }
        .alert-info    { background: rgba(99,102,241,0.15); color: var(--primary-light); border: 1px solid rgba(99,102,241,0.3); }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), #8b5cf6); color: #fff; box-shadow: 0 4px 20px rgba(99,102,241,0.35); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 25px rgba(99,102,241,0.5); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--muted); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .footer-actions { display: flex; align-items: center; justify-content: space-between; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
        .progress-wrap { display: none; margin-top: 20px; }
        .progress-bar-outer { background: rgba(255,255,255,0.08); border-radius: 8px; height: 8px; overflow: hidden; }
        .progress-bar-inner { height: 100%; background: linear-gradient(90deg, var(--primary), #8b5cf6); border-radius: 8px; transition: width 0.5s; width: 0%; }
        .progress-label { font-size: 13px; color: var(--muted); margin-top: 8px; }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } .installer-card { padding: 24px 20px; } .step-connector { width: 30px; } .step-label { display: none; } }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="installer-wrap">
    <div class="installer-header">
        <div class="installer-logo">L</div>
        <h1>Install Educve LMS</h1>
        <p>Configure your database connection</p>
    </div>
    <div class="steps">
        <div class="step-item done"><div class="step-circle"><i class="fa fa-check"></i></div><span class="step-label">Requirements</span></div>
        <div class="step-connector done"></div>
        <div class="step-item active"><div class="step-circle">2</div><span class="step-label">Database</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle">3</div><span class="step-label">Admin Setup</span></div>
        <div class="step-connector"></div>
        <div class="step-item"><div class="step-circle">4</div><span class="step-label">Complete</span></div>
    </div>
    <div class="installer-card">
        <div class="card-title"><i class="fa fa-database" style="color:var(--primary)"></i> &nbsp;Database Configuration</div>
        <div class="card-subtitle">Enter your MySQL/MariaDB database credentials</div>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Host <span>*</span></label>
                <input type="text" id="db_host" class="form-control" value="127.0.0.1" placeholder="127.0.0.1">
            </div>
            <div class="form-group">
                <label class="form-label">Port <span>*</span></label>
                <input type="number" id="db_port" class="form-control" value="3306" placeholder="3306">
            </div>
            <div class="form-group full">
                <label class="form-label">Database Name <span>*</span></label>
                <input type="text" id="db_database" class="form-control" placeholder="lms_db">
            </div>
            <div class="form-group">
                <label class="form-label">Username <span>*</span></label>
                <input type="text" id="db_username" class="form-control" placeholder="root">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="db_password" class="form-control" placeholder="Leave empty if none">
            </div>
        </div>
        <button class="btn btn-outline" onclick="testConnection()" id="testBtn">
            <i class="fa fa-plug"></i> Test Connection
        </button>
        <div class="alert alert-success" id="testSuccess"></div>
        <div class="alert alert-error" id="testError"></div>
        <div class="progress-wrap" id="progressWrap">
            <div class="progress-bar-outer">
                <div class="progress-bar-inner" id="progressBar"></div>
            </div>
            <div class="progress-label" id="progressLabel">Installing database tables...</div>
        </div>
        <div class="alert alert-error" id="installError"></div>
        <div class="footer-actions">
            <a href="{{ route('installer.welcome') }}" class="btn btn-outline"><i class="fa fa-arrow-left"></i> Back</a>
            <button class="btn btn-primary" id="installBtn" disabled onclick="installDatabase()">
                Install Database <i class="fa fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>
<script>
async function testConnection() {
    const btn = document.getElementById('testBtn');
    const successEl = document.getElementById('testSuccess');
    const errorEl = document.getElementById('testError');
    btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Testing...';
    try {
        const res = await fetch('{{ route("installer.test-connection") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(getDbData()),
        });
        const data = await res.json();
        if (data.success) {
            show(successEl, '<i class="fa fa-check-circle"></i> ' + data.message);
            hide(errorEl);
            document.getElementById('installBtn').disabled = false;
        } else { show(errorEl, '<i class="fa fa-circle-xmark"></i> ' + data.message); hide(successEl); document.getElementById('installBtn').disabled = true; }
    } catch(e) { show(errorEl, 'Network error. Please try again.'); }
    finally { btn.disabled = false; btn.innerHTML = '<i class="fa fa-plug"></i> Test Connection'; }
}
async function installDatabase() {
    const btn = document.getElementById('installBtn');
    const progressWrap = document.getElementById('progressWrap');
    const progressBar = document.getElementById('progressBar');
    const progressLabel = document.getElementById('progressLabel');
    const installError = document.getElementById('installError');
    btn.disabled = true; btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Installing...';
    progressWrap.style.display = 'block';
    let progress = 0;
    const interval = setInterval(() => { if (progress < 85) { progress += Math.random() * 8; progressBar.style.width = Math.min(progress, 85) + '%'; } }, 300);
    try {
        const res = await fetch('{{ route("installer.install-database") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(getDbData()),
        });
        const data = await res.json();
        clearInterval(interval);
        if (data.success) {
            progressBar.style.width = '100%';
            progressLabel.textContent = '✓ Database installed successfully!';
            setTimeout(() => { window.location.href = '{{ route("installer.admin-setup") }}'; }, 1000);
        } else { 
            progressWrap.style.display = 'none'; 
            show(installError, '<i class="fa fa-circle-xmark"></i> ' + (data.message || 'Database installation failed.')); 
            btn.disabled = false; 
            btn.innerHTML = 'Install Database <i class="fa fa-arrow-right"></i>'; 
        }
    } catch(e) { 
        clearInterval(interval); 
        progressWrap.style.display = 'none'; 
        show(installError, 'Server error (500). Please check your server PHP logs or database configuration. Details: ' + e.message); 
        btn.disabled = false; 
        btn.innerHTML = 'Install Database <i class="fa fa-arrow-right"></i>'; 
    }
}
function getDbData() { return { db_host: document.getElementById('db_host').value, db_port: document.getElementById('db_port').value, db_database: document.getElementById('db_database').value, db_username: document.getElementById('db_username').value, db_password: document.getElementById('db_password').value }; }
function show(el, msg) { el.innerHTML = msg; el.style.display = 'block'; }
function hide(el) { el.style.display = 'none'; }
</script>
</body>
</html>
