@extends('layouts.instructor')

@section('title', 'Students Enrolled')
@section('page-title', 'Students Enrolled')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Active Class Roster</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Course Enrolled</th>
                    <th>Status</th>
                    <th>Enrolled Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($enrollments) > 0)
                    @foreach($enrollments as $e)
                    <tr>
                        <td style="font-weight:600;">{{ $e->student_name }}</td>
                        <td>{{ $e->student_email }}</td>
                        <td>{{ $e->course_title }}</td>
                        <td>
                            <span class="badge" style="background:{{ $e->status==='active' ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color:{{ $e->status==='active' ? '#34d399' : '#fcd34d' }}">
                                {{ $e->status }}
                            </span>
                        </td>
                        <td>{{ $e->created_at }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--muted)">No students are currently enrolled in your courses.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
