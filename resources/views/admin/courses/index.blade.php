@extends('layouts.admin')

@section('title', 'Manage Courses')
@section('page-title', 'Courses')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">All Course Listings</h3>
    </div>
    @if(count($courses) > 0)
    <table class="custom-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Instructor</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>{{ $course->instructor_name }}</td>
                <td>{{ $course->is_free ? 'Free' : '$'.number_format($course->price, 2) }}</td>
                <td>
                    <span class="badge" style="background:{{ $course->is_published ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $course->is_published ? '#34d399' : '#fca5a5' }}">
                        {{ $course->is_published ? 'Published' : 'Pending' }}
                    </span>
                </td>
                <td>
                    @if($course->is_published)
                    <form method="POST" action="{{ route('admin.courses.reject', $course->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;">Unpublish</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.courses.approve', $course->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--success); cursor:pointer; font-size:13px;">Publish</button>
                    </form>
                    @endif

                    @if($course->pending_subscription_id)
                    <br>
                    <form method="POST" action="{{ route('admin.courses.approve-subscription', $course->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:var(--primary); cursor:pointer; font-size:13px;">Approve Subscription</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center; padding:40px; color:var(--muted)">No courses have been created yet.</div>
    @endif
</div>
@endsection
