@extends('layouts.admin')

@section('title', 'Manage Enrollments')
@section('page-title', 'Enrollments')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Student Course Enrollments</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Course Title</th>
                    <th>Reference / Sender</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($enrollments) > 0)
                    @foreach($enrollments as $e)
                    <tr>
                        <td>{{ $e->student_name }}<br><span style="font-size:11px;color:var(--muted)">{{ $e->student_email }}</span></td>
                        <td>{{ $e->course_title }}</td>
                        <td><strong style="color:var(--primary-light)">{{ $e->bank_reference ?? 'Paystack Instant' }}</strong></td>
                        <td>
                            <span class="badge" style="background:{{ $e->enrollment_status === 'active' ? 'rgba(16,185,129,0.15)' : ($e->enrollment_status === 'rejected' ? 'rgba(239,68,68,0.15)' : 'rgba(245,158,11,0.15)') }}; color:{{ $e->enrollment_status === 'active' ? '#34d399' : ($e->enrollment_status === 'rejected' ? '#fca5a5' : '#fcd34d') }}">
                                {{ $e->enrollment_status === 'active' ? 'Active' : ($e->enrollment_status === 'rejected' ? 'Rejected' : 'Awaiting Approval') }}
                            </span>
                        </td>
                        <td>{{ $e->enrolled_at }}</td>
                        <td>
                            @if($e->enrollment_status === 'pending_approval')
                            <div style="display:flex; gap:10px;">
                                <form method="POST" action="{{ route('admin.enrollments.approve', $e->enrollment_id) }}">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:var(--success); font-weight:600; cursor:pointer; font-size:13px;"><i class="fa fa-circle-check"></i> Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.enrollments.reject', $e->enrollment_id) }}">
                                    @csrf
                                    <button type="submit" style="background:none; border:none; color:var(--error); font-weight:600; cursor:pointer; font-size:13px;"><i class="fa fa-circle-xmark"></i> Reject</button>
                                </form>
                            </div>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" style="text-align:center; padding:30px; color:var(--muted)">No course enrollment applications.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
