@extends('layouts.admin')

@section('title', 'Manage Students')
@section('page-title', 'Students')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Student Accounts</h3>
    </div>
    @if(count($students) > 0)
    <table class="custom-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Joined At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center; padding:40px; color:var(--muted)">No students registered yet.</div>
    @endif
</div>
@endsection
