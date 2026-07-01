@extends('layouts.student')

@section('title', 'Enrollment History')
@section('page-title', 'My Enrollments')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Registration History Logs</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Enrolled Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($enrollments) > 0)
                    @foreach($enrollments as $e)
                    <tr>
                        <td style="font-weight:600;">{{ $e->title }}</td>
                        <td>{{ $e->is_free ? 'Free' : ($settings['currency_symbol'] ?? '$').number_format($e->price, 2) }}</td>
                        <td>
                            <span class="badge" style="background:{{ $e->status==='active' ? 'rgba(16,185,129,0.15)' : ($e->status==='rejected' ? 'rgba(239,68,68,0.15)' : 'rgba(245,158,11,0.15)') }}; color:{{ $e->status==='active' ? '#34d399' : ($e->status==='rejected' ? '#fca5a5' : '#fcd34d') }}">
                                {{ $e->status==='active' ? 'Active' : ($e->status==='rejected' ? 'Rejected' : 'Pending Verification') }}
                            </span>
                        </td>
                        <td>{{ $e->created_at }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align:center; padding:30px; color:var(--muted)">You have no enrollment records.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
