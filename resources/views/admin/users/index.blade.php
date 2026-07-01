@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Users')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3 class="panel-title">Registered Accounts</h3>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.users.index') }}" class="btn {{ !$role ? 'btn-primary' : 'btn-ghost' }}" style="padding:6px 12px; font-size:12px;">All</a>
            <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="btn {{ $role === 'student' ? 'btn-primary' : 'btn-ghost' }}" style="padding:6px 12px; font-size:12px;">Students</a>
            <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="btn {{ $role === 'instructor' ? 'btn-primary' : 'btn-ghost' }}" style="padding:6px 12px; font-size:12px;">Instructors</a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="btn {{ $role === 'admin' ? 'btn-primary' : 'btn-ghost' }}" style="padding:6px 12px; font-size:12px;">Admins</a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="font-weight:600;">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge badge-{{ $user->role }}">{{ $user->role }}</span></td>
                    <td>
                        <span class="badge" style="background:{{ $user->status==='active' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $user->status==='active' ? '#34d399' : '#fca5a5' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td style="text-align:right; display:flex; gap:12px; justify-content:flex-end; align-items:center; height:50px;">
                        <!-- Login As (only if not self and not another admin) -->
                        @if($user->id !== auth()->id() && $user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none; border:none; color:var(--primary-light); cursor:pointer; font-size:13px;" title="Login to account"><i class="fa fa-right-to-bracket"></i> Login As</button>
                        </form>
                        @endif

                        <!-- Edit -->
                        <a href="{{ route('admin.users.edit', $user->id) }}" style="color:#67e8f9; font-size:13px;"><i class="fa fa-pen-to-square"></i> Edit</a>

                        <!-- Block/Unblock -->
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-block', $user->id) }}" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none; border:none; color:var(--warning); cursor:pointer; font-size:13px;">
                                <i class="fa {{ $user->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                            </button>
                        </form>

                        <!-- Delete -->
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
