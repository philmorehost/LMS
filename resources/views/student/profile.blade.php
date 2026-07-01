@extends('layouts.student')

@section('title', 'Profile Settings')
@section('page-title', 'My Profile')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1fr; gap:32px;">
    <!-- Edit Profile Details -->
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Personal Information</h3>
        </div>
        <form method="POST" action="{{ route('student.profile.update') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Email Address (cannot be changed)</label>
                <input type="email" value="{{ $user->email }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:var(--muted); cursor:not-allowed;" readonly>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Phone Number</label>
                <input type="text" name="phone" value="{{ $user->phone }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. +2348012345678">
            </div>
            <button type="submit" class="btn btn-primary" style="padding:12px 24px; font-weight:600;"><i class="fa fa-save"></i> Save Changes</button>
        </form>
    </div>

    <!-- Edit Password details -->
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Update Password Credentials</h3>
        </div>
        <form method="POST" action="{{ route('student.profile.password') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Current Password</label>
                <input type="password" name="current_password" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">New Password (min 8 characters)</label>
                <input type="password" name="password" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
            </div>
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Confirm New Password</label>
                <input type="password" name="password_confirmation" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
            </div>
            <button type="submit" class="btn btn-primary" style="padding:12px 24px; font-weight:600;"><i class="fa fa-lock"></i> Update Password</button>
        </form>
    </div>
</div>
@endsection
