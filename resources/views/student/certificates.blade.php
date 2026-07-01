@extends('layouts.student')

@section('title', 'My Certificates')
@section('page-title', 'Earned Certificates')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Completed Courses Credentials</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Certificate Code</th>
                    <th>Issue Date</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($certificates) > 0)
                    @foreach($certificates as $c)
                    <tr>
                        <td style="font-weight:600;">{{ $c->course_title }}</td>
                        <td><strong style="color:var(--primary-light)">{{ $c->certificate_code }}</strong></td>
                        <td>{{ $c->created_at }}</td>
                        <td style="text-align:right;">
                            <a href="{{ route('student.certificates.download', $c->id) }}" class="btn btn-primary" style="padding:6px 12px; font-size:12px;" target="_blank"><i class="fa fa-print"></i> View / Print</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:var(--muted)">You haven't earned any certificates yet. Complete all syllabus tasks to claim credentials.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
