@extends('layouts.instructor')

@section('title', 'Payout Withdrawals')
@section('page-title', 'Withdrawals')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:32px; align-items:start;">
    <!-- Left: Request Form -->
    <div>
        <div class="stats-grid" style="grid-template-columns:1fr; margin-bottom:20px;">
            <div class="stat-card" style="border-color:rgba(236,72,153,0.3)">
                <div class="stat-icon pink"><i class="fa fa-wallet"></i></div>
                <div class="stat-value">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($balance, 2) }}</div>
                <div class="stat-label">Available Unpaid Balance</div>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">Request Bank Withdrawal</h3>
            </div>
            <form method="POST" action="{{ route('instructor.withdrawals.request') }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Payout Amount ({{ $settings['currency_symbol'] ?? '$' }})</label>
                    <input type="number" name="amount" min="1" step="0.01" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. 150.00" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Bank Name</label>
                    <input type="text" name="bank_name" value="{{ $profile->bank_name ?? '' }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. Access Bank" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Account Number</label>
                    <input type="text" name="account_number" value="{{ $profile->account_number ?? '' }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="10 digits" required>
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Account Name</label>
                    <input type="text" name="account_name" value="{{ $profile->account_name ?? '' }}" style="width:100%; padding:12px; background:#0c0c1e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="Full account name" required>
                </div>
                <p style="font-size:11px; color:var(--muted); margin-bottom:16px;">To save these details for future withdrawals, please update your <a href="{{ route('instructor.profile') }}" style="color:var(--primary);">Profile</a>.</p>
                <button type="submit" class="btn btn-primary" style="padding:12px 24px; font-weight:600;"><i class="fa fa-paper-plane"></i> Submit Request</button>
            </form>
        </div>
    </div>

    <!-- Right: History List -->
    <div class="panel-card">
        <div class="panel-header">
            <h3 class="panel-title">Payout Log History</h3>
        </div>
        <div class="table-responsive">
            <table class="custom-table" style="min-width:100%;">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Bank Details</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($withdrawals) > 0)
                        @foreach($withdrawals as $w)
                        @php $details = json_decode($w->account_details); @endphp
                        <tr>
                            <td style="font-weight:600; color:var(--primary-light)">
                                {{ $settings['currency_symbol'] ?? '$' }}{{ number_format($w->amount, 2) }}
                            </td>
                            <td style="font-size:12px; line-height:1.4;">
                                <strong>{{ $details->bank_name ?? 'N/A' }}</strong><br>
                                {{ $details->account_number ?? 'N/A' }}<br>
                                <span style="color:var(--muted)">{{ $details->account_name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background:{{ $w->status==='approved' ? 'rgba(16,185,129,0.15)' : ($w->status==='rejected' ? 'rgba(239,68,68,0.15)' : 'rgba(245,158,11,0.15)') }}; color:{{ $w->status==='approved' ? '#34d399' : ($w->status==='rejected' ? '#fca5a5' : '#fcd34d') }}">
                                    {{ $w->status }}
                                </span>
                            </td>
                            <td>{{ $w->created_at }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align:center; padding:30px; color:var(--muted)">No payout requests filed.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
