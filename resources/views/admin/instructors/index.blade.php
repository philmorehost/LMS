@extends('layouts.admin')

@section('title', 'Manage Instructors')
@section('page-title', 'Instructors')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Instructor Accounts</h3>
    </div>
    @if(count($instructors) > 0)
    <table class="custom-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instructors as $instructor)
            <tr>
                <td>{{ $instructor->name }}</td>
                <td>{{ $instructor->email }}</td>
                <td>
                    <span class="badge" style="background:{{ $instructor->status==='active' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $instructor->status==='active' ? '#34d399' : '#fca5a5' }}">
                        {{ $instructor->status }}
                    </span>
                </td>
                <td>
                    @if($instructor->status !== 'active')
                    <form method="POST" action="{{ route('admin.instructors.approve', $instructor->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--success); cursor:pointer; font-size:13px;">Approve</button>
                    </form>
                    @else
                    N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center; padding:40px; color:var(--muted)">No instructors registered yet.</div>
    @endif
</div>
@endsection
