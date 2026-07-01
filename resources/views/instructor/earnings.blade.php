@extends('layouts.instructor')

@section('title', 'Earnings & Revenue')
@section('page-title', 'Revenue')

@section('content')
<div class="stats-grid" style="grid-template-columns:1fr; max-width:400px; margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fa fa-sack-dollar"></i></div>
        <div class="stat-value">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($totalEarned, 2) }}</div>
        <div class="stat-label">Net Sales Revenue (Commission Deducted)</div>
    </div>
</div>

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Completed Sales Sales</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th>Gross Price</th>
                    <th>Your Share (80%)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($payments) > 0)
                    @foreach($payments as $p)
                    <tr>
                        <td>{{ $p->student_name }}</td>
                        <td>{{ $p->course_title }}</td>
                        <td>{{ ($settings['currency_symbol'] ?? '$').number_format($p->amount, 2) }}</td>
                        <td style="font-weight:600; color:var(--primary-light)">
                            {{ ($settings['currency_symbol'] ?? '$').number_format($p->amount * (1 - (floatval($settings['commission_rate'] ?? 20) / 100)), 2) }}
                        </td>
                        <td>{{ $p->created_at }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--muted)">No transactions recorded yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
