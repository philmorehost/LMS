@extends('layouts.instructor')

@section('title', 'My Courses')
@section('page-title', 'My Courses')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Owned Course Listings</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($courses) > 0)
                    @foreach($courses as $course)
                    <tr>
                        <td style="font-weight:600;">{{ $course->title }}</td>
                        <td>{{ $course->is_free ? 'Free' : ($settings['currency_symbol'] ?? '$').number_format($course->price, 2) }}</td>
                        <td>
                            <span class="badge" style="background:{{ $course->is_published ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $course->is_published ? '#34d399' : '#fca5a5' }}">
                                {{ $course->is_published ? 'Published' : 'Pending Review' }}
                            </span>
                        </td>
                        <td>{{ $course->created_at }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:var(--muted)">You have not created any courses.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
