@extends('layouts.instructor')

@section('title', 'Instructor Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="panel-card" style="max-width: 600px;">
    <div class="panel-header">
        <h3 class="panel-title">Update Profile & Bank Details</h3>
    </div>

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

    <form method="POST" action="{{ route('instructor.profile.update') }}">
        @csrf

        <h4 style="color:var(--primary); margin-bottom:12px; font-size:14px;">Basic Information</h4>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Full Name</label>
            <input type="text" name="name" value="{{ $user->name }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Professional Title</label>
            <input type="text" name="title" value="{{ $profile->title ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. Senior Software Engineer">
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Expertise / Bio</label>
            <textarea name="expertise" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" rows="4">{{ $profile->expertise ?? '' }}</textarea>
        </div>

        <h4 style="color:var(--primary); margin-bottom:12px; font-size:14px; padding-top:16px; border-top:1px solid var(--border);">Bank Details for Withdrawals</h4>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Bank Name</label>
            <input type="text" name="bank_name" value="{{ $profile->bank_name ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. Chase Bank">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Number</label>
            <input type="text" name="account_number" value="{{ $profile->account_number ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;">
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Account Name</label>
            <input type="text" name="account_name" value="{{ $profile->account_name ?? '' }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;">
        </div>

        <button type="submit" class="btn btn-primary" style="padding:12px 24px;"><i class="fa fa-save"></i> Save Changes</button>
    </form>
</div>
@endsection
