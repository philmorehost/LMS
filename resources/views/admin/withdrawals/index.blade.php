@extends('layouts.admin')

@section('title', 'Payout Requests')
@section('page-title', 'Withdrawals')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Instructor Bank Payout Requests</h3>
    </div>
    
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Instructor</th>
                    <th>Requested Payout</th>
                    <th>Bank details</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($withdrawals) > 0)
                    @foreach($withdrawals as $w)
                    @php $details = json_decode($w->account_details); @endphp
                    <tr>
                        <td>
                            <strong>{{ $w->instructor_name }}</strong><br>
                            <span style="font-size:11px;color:var(--muted)">{{ $w->instructor_email }}</span>
                        </td>
                        <td style="font-weight:700; color:var(--primary-light)">
                            {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($w->amount, 2) }}
                        </td>
                        <td style="font-size:12px; line-height:1.4;">
                            <strong>{{ $details->bank_name ?? 'N/A' }}</strong> (Acc: {{ $details->account_number ?? 'N/A' }})<br>
                            <span style="color:var(--muted)">Name: {{ $details->account_name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background:{{ $w->status==='approved' ? 'rgba(16,185,129,0.15)' : ($w->status==='rejected' ? 'rgba(239,68,68,0.15)' : 'rgba(245,158,11,0.15)') }}; color:{{ $w->status==='approved' ? '#34d399' : ($w->status==='rejected' ? '#fca5a5' : '#fcd34d') }}">
                                {{ $w->status }}
                            </span>
                        </td>
                        <td style="text-align:right; display:flex; gap:12px; justify-content:flex-end; align-items:center; height:50px;">
                            @if($w->status === 'pending')
                            <form method="POST" action="{{ route('admin.withdrawals.approve', $w->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:11px;"><i class="fa fa-circle-check"></i> Approve & Paid</button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.withdrawals.reject', $w->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:11px;"><i class="fa fa-circle-xmark"></i> Reject</button>
                            </form>
                            @else
                            <span style="color:var(--muted); font-size:11px;">Completed ({{ $w->updated_at }})</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:var(--muted)">No instructor payout requests submitted.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
