@extends('layouts.student')

@section('title', 'My Enrolled Courses')
@section('page-title', 'My Courses')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

@if(count($courses) > 0)
<div class="courses-grid">
    @foreach($courses as $course)
    <div class="course-card">
        <div class="course-thumb">🎓</div>
        <div class="course-body">
            <h3 class="course-title">{{ $course->title }}</h3>
            
            <div style="margin-bottom:14px;">
                <span class="badge" style="background:{{ $course->status==='active' ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color:{{ $course->status==='active' ? '#34d399' : '#fcd34d' }}">
                    {{ $course->status==='active' ? 'Active' : 'Pending Approval' }}
                </span>
            </div>

            @if($course->status === 'active')
            <a href="{{ route('student.courses.learn', $course->course_id) }}" class="btn btn-primary" style="width:100%; justify-content:center; padding:10px; font-size:13px;"><i class="fa fa-play"></i> Start Learning</a>
            @else
            <button class="btn btn-ghost" style="width:100%; justify-content:center; padding:10px; font-size:13px; cursor:not-allowed;" disabled><i class="fa fa-clock"></i> Awaiting Verification</button>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="empty-state">
    <div class="empty-icon">📚</div>
    <div class="empty-title">You haven't enrolled in any courses yet</div>
    <div class="empty-desc">Explore our catalog and find the right learning path for you.</div>
    <a href="{{ url('/courses') }}" class="btn btn-primary">
        <i class="fa fa-compass"></i> Browse Courses
    </a>
</div>
@endif
@endsection
