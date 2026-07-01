@extends('layouts.instructor')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3 class="panel-title">Owned Course Listings</h3>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary" style="padding:8px 16px; font-size:13px; font-weight:600;"><i class="fa fa-plus"></i> Create Course</a>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($courses) > 0)
                    @foreach($courses as $course)
                    <tr>
                        <td style="font-weight:600;">{{ $course->title }}</td>
                        <td>{{ $course->is_free ? 'Free' : ($settings['currency_symbol'] ?? '$').number_format($course->price, 2) }}</td>
                        <td>
                            <span class="badge" style="background:{{ $course->is_published ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color:{{ $course->is_published ? '#34d399' : '#fcd34d' }}">
                                {{ $course->is_published ? 'Published' : 'Awaiting Approval' }}
                            </span>
                        </td>
                        <td>{{ $course->created_at }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('instructor.courses.edit', $course->id) }}" style="color:#67e8f9; font-size:13px; margin-right:12px;"><i class="fa fa-pen-to-square"></i> Edit Syllabus</a>
                            <form method="POST" action="{{ route('instructor.courses.destroy', $course->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--muted)">You have not created any courses. Click 'Create Course' to get started!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
