@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User Account')

@section('content')
<div class="panel-card" style="max-width:500px;">
    <div class="panel-header">
        <h3 class="panel-title">Update Profile Details</h3>
    </div>
    
    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Full Name</label>
            <input type="text" name="name" value="{{ $user->name }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
        </div>
        
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Email Address</label>
            <input type="email" name="email" value="{{ $user->email }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Role</label>
            <select name="role" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none; cursor:pointer;" required>
                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                <option value="instructor" {{ $user->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Status</label>
            <select name="status" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none; cursor:pointer;" required>
                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>

        <button type="submit" style="padding:12px 24px; background:linear-gradient(135deg, var(--primary), #ec4899); border:none; border-radius:10px; color:#fff; font-weight:600; cursor:pointer;">Update User Details</button>
        <a href="{{ route('admin.users.index') }}" style="margin-left:12px; font-size:14px; color:var(--muted)">Cancel</a>
    </form>
</div>
@endsection
