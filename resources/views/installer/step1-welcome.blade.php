<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install — {{ config('lms.name', 'Educve LMS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #a5b4fc;
            --success: #10b981;
            --error: #ef4444;
            --warning: #f59e0b;
            --bg: #0f0f1a;
            --card: #1a1a2e;
            --card2: #16213e;
            --border: rgba(255,255,255,0.08);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --radius: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(99,102,241,0.15) 0%, transparent 50%),
                              radial-gradient(ellipse at 80% 20%, rgba(139,92,246,0.1) 0%, transparent 50%);
        }

        .installer-wrap {
            width: 100%;
            max-width: 860px;
            padding: 24px;
        }

        /* Header */
        .installer-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .installer-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin: 0 auto 20px;
            box-shadow: 0 0 40px rgba(99,102,241,0.4);
        }
        .installer-header h1 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, var(--primary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .installer-header p {
            color: var(--muted);
            font-size: 15px;
        }

        /* Steps Progress */
        .steps {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 36px;
            gap: 0;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            border: 2px solid var(--border);
            background: var(--card2);
            color: var(--muted);
            flex-shrink: 0;
            transition: all 0.3s;
        }
        .step-item.active .step-circle {
            border-color: var(--primary);
            background: var(--primary);
            color: #fff;
            box-shadow: 0 0 20px rgba(99,102,241,0.5);
        }
        .step-item.done .step-circle {
            border-color: var(--success);
            background: var(--success);
            color: #fff;
        }
        .step-label {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
            white-space: nowrap;
        }
        .step-item.active .step-label { color: var(--text); }
        .step-connector {
            width: 60px;
            height: 2px;
            background: var(--border);
            margin: 0 8px;
            flex-shrink: 0;
        }
        .step-connector.done { background: var(--success); }

        /* Card */
        .installer-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px;
            backdrop-filter: blur(10px);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .card-subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 28px;
        }

        /* Check Table */
        .check-section {
            margin-bottom: 28px;
        }
        .check-section h3 {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            margin-bottom: 14px;
        }
        .check-table {
            width: 100%;
            border-collapse: collapse;
        }
        .check-table td {
            padding: 10px 14px;
            font-size: 14px;
            border-bottom: 1px solid var(--border);
        }
        .check-table tr:last-child td { border-bottom: none; }
        .check-table .name { color: var(--text); }
        .check-table .status { text-align: right; }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: rgba(16,185,129,0.15); color: var(--success); }
        .badge-error   { background: rgba(239,68,68,0.15); color: var(--error); }
        .badge-warning { background: rgba(245,158,11,0.15); color: var(--warning); }

        /* PHP version row */
        .php-version-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px;
            background: var(--card2);
            border-radius: 12px;
            margin-bottom: 28px;
        }
        .php-version-label { font-size: 14px; color: var(--muted); }
        .php-version-value { font-size: 16px; font-weight: 700; }

        /* License Form */
        .license-section {
            background: var(--card2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-top: 28px;
        }
        .license-section h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .license-section p {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 16px;
        }
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .form-control::placeholder { color: var(--muted); }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-top: 12px;
            display: none;
        }
        .alert-success { background: rgba(16,185,129,0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3); }
        .alert-error   { background: rgba(239,68,68,0.15); color: var(--error); border: 1px solid rgba(239,68,68,0.3); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: #fff;
            box-shadow: 0 4px 20px rgba(99,102,241,0.35);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 25px rgba(99,102,241,0.5);
        }
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        .footer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .can-proceed-msg {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }
        .can-proceed-msg.ok { color: var(--success); }
        .can-proceed-msg.fail { color: var(--error); }

        /* Responsive */
        @media (max-width: 600px) {
            .installer-card { padding: 24px 20px; }
            .steps { gap: 4px; }
            .step-connector { width: 30px; }
            .step-label { display: none; }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="installer-wrap">

    <!-- Header -->
    <div class="installer-header">
        <div class="installer-logo">L</div>
        <h1>Install Educve LMS</h1>
        <p>Let's get your learning management system up and running</p>
    </div>

    <!-- Steps Progress -->
    <div class="steps">
        <div class="step-item active">
            <div class="step-circle">1</div>
            <span class="step-label">Requirements</span>
        </div>
        <div class="step-connector"></div>
        <div class="step-item">
            <div class="step-circle">2</div>
            <span class="step-label">Database</span>
        </div>
        <div class="step-connector"></div>
        <div class="step-item">
            <div class="step-circle">3</div>
            <span class="step-label">Admin Setup</span>
        </div>
        <div class="step-connector"></div>
        <div class="step-item">
            <div class="step-circle">4</div>
            <span class="step-label">Complete</span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="installer-card">
        <div class="card-title">System Requirements</div>
        <div class="card-subtitle">Verify your server meets all requirements before proceeding</div>

        <!-- PHP Version -->
        <div class="php-version-row">
            <span class="php-version-label"><i class="fa-brands fa-php"></i> &nbsp;PHP Version (Required: 8.2+)</span>
            <span class="php-version-value">
                {{ $phpVersion }}
                @if($phpOk)
                    <span class="badge badge-success" style="font-size:11px"><i class="fa fa-check"></i> OK</span>
                @else
                    <span class="badge badge-error" style="font-size:11px"><i class="fa fa-times"></i> Upgrade Required</span>
                @endif
            </span>
        </div>

        <!-- Extensions -->
        <div class="check-section">
            <h3><i class="fa fa-puzzle-piece"></i> &nbsp;PHP Extensions</h3>
            <table class="check-table">
                @foreach($extensions as $ext)
                <tr>
                    <td class="name"><code>{{ $ext['name'] }}</code></td>
                    <td class="status">
                        @if($ext['ok'])
                            <span class="badge badge-success"><i class="fa fa-check"></i> Enabled</span>
                        @else
                            <span class="badge badge-error"><i class="fa fa-times"></i> Missing</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <!-- Permissions -->
        <div class="check-section">
            <h3><i class="fa fa-lock-open"></i> &nbsp;File Permissions</h3>
            <table class="check-table">
                @foreach($permissions as $perm)
                <tr>
                    <td class="name"><code>{{ $perm['path'] }}</code></td>
                    <td class="status">
                        @if($perm['writable'])
                            <span class="badge badge-success"><i class="fa fa-check"></i> Writable</span>
                        @else
                            <span class="badge badge-error"><i class="fa fa-times"></i> Not Writable</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <!-- License Verification -->
        <div class="license-section">
            <h3><i class="fa fa-key"></i> &nbsp;License Verification</h3>
            <p>Enter your purchase / license key to continue installation.</p>

            <div class="form-group">
                <label class="form-label">License Key</label>
                <input type="text" id="licenseKey" class="form-control" placeholder="XXXX-XXXX-XXXX-XXXX" autocomplete="off">
            </div>

            <button class="btn btn-outline" id="verifyBtn" onclick="verifyLicense()">
                <i class="fa fa-shield-halved"></i> Verify License
            </button>

            <div class="alert alert-success" id="licenseSuccess"></div>
            <div class="alert alert-error" id="licenseError"></div>
        </div>

        <!-- Footer Actions -->
        <div class="footer-actions">
            <div class="can-proceed-msg {{ $canProceed ? 'ok' : 'fail' }}">
                @if($canProceed)
                    <i class="fa fa-circle-check"></i> All requirements met
                @else
                    <i class="fa fa-circle-xmark"></i> Fix issues above before continuing
                @endif
            </div>

            <button class="btn btn-primary" id="continueBtn"
                @if(!$canProceed) disabled @endif
                onclick="proceedToDatabase()">
                Continue to Database <i class="fa fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>

<script>
    let licenseVerified = false;

    async function verifyLicense() {
        const key = document.getElementById('licenseKey').value.trim();
        const btn = document.getElementById('verifyBtn');
        const successEl = document.getElementById('licenseSuccess');
        const errorEl = document.getElementById('licenseError');

        if (!key) {
            showAlert(errorEl, 'Please enter your license key.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Verifying...';

        try {
            const res = await fetch('{{ route("installer.verify-license") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ license_key: key }),
            });

            const data = await res.json();

            if (data.success) {
                showAlert(successEl, '<i class="fa fa-check-circle"></i> ' + data.message);
                hideAlert(errorEl);
                licenseVerified = true;
                document.getElementById('continueBtn').disabled = false;
            } else {
                showAlert(errorEl, '<i class="fa fa-circle-xmark"></i> ' + data.message);
                hideAlert(successEl);
                licenseVerified = false;
            }
        } catch (e) {
            showAlert(errorEl, 'Network error. Please try again.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-shield-halved"></i> Verify License';
        }
    }

    function proceedToDatabase() {
        if (!licenseVerified) {
            document.getElementById('licenseError').style.display = 'block';
            document.getElementById('licenseError').innerHTML = '<i class="fa fa-circle-xmark"></i> Please verify your license key first.';
            return;
        }
        window.location.href = '{{ route("installer.database") }}';
    }

    function showAlert(el, msg) { el.innerHTML = msg; el.style.display = 'block'; }
    function hideAlert(el) { el.style.display = 'none'; }
</script>
</body>
</html>
