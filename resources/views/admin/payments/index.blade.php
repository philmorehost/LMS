@extends('layouts.admin')

@section('title', 'Payments Audit')
@section('page-title', 'Payments')

@section('content')
<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Transactions</h3>
    </div>
    @if(count($payments) > 0)
    <table class="custom-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Gateway</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->user_name }}<br><span style="font-size:11px;color:var(--muted)">{{ $payment->user_email }}</span></td>
                <td>{{ $payment->gateway }}</td>
                <td>${{ number_format($payment->amount, 2) }}</td>
                <td>
                    <span class="badge" style="background:{{ $payment->status==='completed' ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $payment->status==='completed' ? '#34d399' : '#fca5a5' }}">
                        {{ $payment->status }}
                    </span>
                </td>
                <td>{{ $payment->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align:center; padding:40px; color:var(--muted)">No transactions recorded yet.</div>
    @endif
</div>
@endsection
