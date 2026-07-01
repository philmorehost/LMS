@extends('layouts.student')

@section('title', 'Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue">🎓</div>
        <div class="stat-value">{{ count($enrollments) }}</div>
        <div class="stat-label">Enrolled Courses</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">✅</div>
        <div class="stat-value">0</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon orange">📜</div>
        <div class="stat-value">0</div>
        <div class="stat-label">Certificates</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon purple">⏱️</div>
        <div class="stat-value">0h</div>
        <div class="stat-label">Hours Learned</div>
    </div>
</div>

<div class="section-title">Continue Learning</div>

@if(count($enrollments) > 0)
<div class="courses-grid">
    @foreach($enrollments as $enrollment)
    <a href="{{ route('student.courses.learn', $enrollment->course_id) }}" class="course-card">
        <div class="course-thumb">🎓</div>
        <div class="course-body">
            <div class="course-title">{{ $enrollment->title }}</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $enrollment->progress ?? 0 }}%;"></div>
            </div>
            <div class="progress-text">{{ $enrollment->progress ?? 0 }}% complete</div>
        </div>
    </a>
    @endforeach
</div>
@else
<div class="empty-state">
    <div class="empty-icon">📚</div>
    <div class="empty-title">No courses yet</div>
    <div class="empty-desc">Browse our catalog and enroll in your first course today!</div>
    <a href="{{ url('/courses') }}" class="btn btn-primary">
        <i class="fa fa-compass"></i> Explore Courses
    </a>
</div>
@endif
@endsection
