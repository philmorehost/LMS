@extends('layouts.admin')

@section('title', 'System Settings')
@section('page-title', 'Settings')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:32px;">
        
        <!-- General & Currency -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title"><i class="fa fa-sliders"></i> General & Currency Settings</h3>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Site Name / Title</label>
                <input type="text" name="site_title" value="{{ $settings['site_title'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Currency Code (e.g. NGN, USD)</label>
                <input type="text" name="currency_code" value="{{ $settings['currency_code'] ?? 'USD' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Currency Symbol (e.g. ₦, $)</label>
                <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Default Language</label>
                <input type="text" name="default_language" value="{{ $settings['default_language'] ?? 'en' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;">
            </div>
        </div>

        <!-- 100% SEO Settings -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title"><i class="fa fa-search"></i> 100% SEO Optimization Settings</h3>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">SEO Meta Title</label>
                <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="Optimized site title for search engines">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">SEO Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="lms, education, learn, coding, online courses">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">SEO Meta Description</label>
                <textarea name="meta_description" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none; font-family:inherit;" rows="4" placeholder="Compelling meta description... (recommended 150-160 characters)">{{ $settings['meta_description'] ?? '' }}</textarea>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Google Search Console Verification HTML Tag</label>
                <input type="text" name="google_verification" value="{{ $settings['google_verification'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder='<meta name="google-site-verification" content="..." />'>
            </div>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:32px;">
        
        <!-- Paystack Gateways API -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title"><i class="fa fa-credit-card"></i> Paystack Payment API Keys</h3>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Paystack Public Key</label>
                <input type="text" name="paystack_public_key" value="{{ $settings['paystack_public_key'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="pk_live_... or pk_test_...">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Paystack Secret Key</label>
                <input type="password" name="paystack_secret_key" value="{{ $settings['paystack_secret_key'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="sk_live_... or sk_test_...">
            </div>
        </div>

        <!-- Bank Transfer Details -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title"><i class="fa fa-building-columns"></i> Manual Bank Transfer Details</h3>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Bank Name</label>
                <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="e.g. Zenith Bank">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Number</label>
                <input type="text" name="bank_account_number" value="{{ $settings['bank_account_number'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="e.g. 1012345678">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Name</label>
                <input type="text" name="bank_account_name" value="{{ $settings['bank_account_name'] ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="e.g. Educve Global Limited">
            </div>
        </div>
    </div>

    <button type="submit" style="padding:14px 28px; background:linear-gradient(135deg, var(--primary), #ec4899); border:none; border-radius:10px; color:#fff; font-weight:700; cursor:pointer; display:block; margin:0 auto; box-shadow: 0 4px 20px rgba(139,92,246,0.35);"><i class="fa fa-save"></i> Save All Settings</button>
</form>
@endsection
