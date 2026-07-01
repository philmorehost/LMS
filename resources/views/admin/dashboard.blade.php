@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa fa-users"></i></div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-label">Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-user-tie"></i></div>
        <div class="stat-value">{{ $totalInstructors }}</div>
        <div class="stat-label">Instructors</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa fa-book"></i></div>
        <div class="stat-value">{{ $totalCourses }}</div>
        <div class="stat-label">Courses</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-money-bill-wave"></i></div>
        <div class="stat-value">${{ number_format($totalEarnings, 2) }}</div>
        <div class="stat-label">Total Earnings</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Recent Users</h3>
        </div>
        @if(count($recentUsers) > 0)
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $ru)
                <tr>
                    <td>{{ $ru->name }}<br><span style="font-size:11px;color:var(--muted)">{{ $ru->email }}</span></td>
                    <td><span class="badge badge-{{ $ru->role }}">{{ $ru->role }}</span></td>
                    <td>{{ $ru->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:20px;color:var(--muted)">No registered users.</div>
        @endif
    </div>

    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Recent Courses</h3>
        </div>
        @if(count($recentCourses) > 0)
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCourses as $rc)
                <tr>
                    <td>{{ $rc->title }}</td>
                    <td>{{ $rc->is_free ? 'Free' : '$'.number_format($rc->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align:center;padding:20px;color:var(--muted)">No courses created yet.</div>
        @endif
    </div>
</div>
@endsection
