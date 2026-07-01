@extends('layouts.instructor')

@section('title', 'Instructor Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fa fa-book"></i></div>
        <div class="stat-value">{{ $totalCourses }}</div>
        <div class="stat-label">Active Courses</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa fa-users"></i></div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-label">Total Enrolled Students</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa fa-sack-dollar"></i></div>
        <div class="stat-value">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($totalEarnings, 2) }}</div>
        <div class="stat-label">Net Instructor Earnings (80%)</div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Recent Student Enrollments</h3>
        </div>
        <div class="table-responsive">
            <table class="custom-table" style="min-width:100%;">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($recentEnrollments) > 0)
                        @foreach($recentEnrollments as $re)
                        <tr>
                            <td>{{ $re->student_name }}<br><span style="font-size:11px;color:var(--muted)">{{ $re->student_email }}</span></td>
                            <td>{{ $re->course_title }}</td>
                            <td>
                                <span class="badge" style="background:{{ $re->status==='active' ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color:{{ $re->status==='active' ? '#34d399' : '#fcd34d' }}">
                                    {{ $re->status }}
                                </span>
                            </td>
                            <td>{{ $re->created_at }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align:center; padding:30px; color:var(--muted)">No student enrollments in your courses yet.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
