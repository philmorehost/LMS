@extends('layouts.student')

@section('title', 'Checkout')
@section('page-title', 'Secure Checkout')

@section('content')
@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 340px; gap:32px;">
    <!-- Left: Cart details and payment selection -->
    <div>
        <div class="panel-card" style="margin-bottom:24px;">
            <div class="panel-header">
                <h3 class="panel-title">Your Order Summary</h3>
            </div>
            <div class="table-responsive">
                <table class="custom-table" style="min-width:100%;">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th style="text-align:right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td style="text-align:right; font-weight:600;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->price, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">Choose Payment Method</h3>
            </div>
            <form method="POST" action="{{ route('student.checkout.process') }}" id="checkoutForm">
                @csrf
                
                <!-- Payment options -->
                <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:24px;">
                    <!-- Paystack Option -->
                    <label style="display:flex; align-items:center; gap:12px; padding:16px; border:1px solid var(--border); border-radius:12px; background:rgba(255,255,255,0.02); cursor:pointer;">
                        <input type="radio" name="payment_method" value="paystack" checked style="accent-color:var(--primary); width:18px; height:18px;" onclick="togglePaymentDetails('paystack')">
                        <div>
                            <div style="font-weight:600; font-size:14px;">Paystack (Credit Card / Bank Transfer / USSD)</div>
                            <div style="font-size:12px; color:var(--muted)">Instant secure payment activation</div>
                        </div>
                    </label>

                    <!-- Bank Transfer Option -->
                    <label style="display:flex; align-items:center; gap:12px; padding:16px; border:1px solid var(--border); border-radius:12px; background:rgba(255,255,255,0.02); cursor:pointer;">
                        <input type="radio" name="payment_method" value="bank_transfer" style="accent-color:var(--primary); width:18px; height:18px;" onclick="togglePaymentDetails('bank_transfer')">
                        <div>
                            <div style="font-weight:600; font-size:14px;">Direct Bank Transfer (Manual Verification)</div>
                            <div style="font-size:12px; color:var(--muted)">Pay to our bank account and submit reference</div>
                        </div>
                    </label>
                </div>

                <!-- Bank Transfer Details (Hidden by default) -->
                <div id="bankDetails" style="display:none; background:#0c0c1e; border:1px solid var(--border); border-radius:12px; padding:20px; margin-bottom:24px;">
                    <h4 style="font-size:14px; font-weight:600; margin-bottom:12px; color:var(--primary-light)"><i class="fa fa-building-columns"></i> Make Payment to:</h4>
                    <div style="font-size:13px; display:flex; flex-direction:column; gap:6px; color:var(--muted); margin-bottom:16px;">
                        <div>Bank Name: <strong style="color:#fff">{{ $settings['bank_name'] ?? 'N/A' }}</strong></div>
                        <div>Account Number: <strong style="color:#fff">{{ $settings['bank_account_number'] ?? 'N/A' }}</strong></div>
                        <div>Account Name: <strong style="color:#fff">{{ $settings['bank_account_name'] ?? 'N/A' }}</strong></div>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="display:block; font-size:12px; color:var(--muted); margin-bottom:6px;">Your Payment Reference / Sender Name</label>
                        <input type="text" name="bank_reference" id="bank_reference" class="form-control" style="width:100%; padding:10px; background:#000; border:1px solid var(--border); border-radius:8px; color:#fff;" placeholder="e.g. John Doe - Zen Transfer">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; font-weight:700;"><i class="fa fa-lock"></i> Pay & Complete Enrollment</button>
            </form>
        </div>
    </div>

    <!-- Right: Totals sidebar widget -->
    <aside class="panel-card" style="height:fit-content; text-align:center;">
        <div style="font-size:12px; text-transform:uppercase; letter-spacing:0.05em; color:var(--muted); margin-bottom:8px;">Total to Pay</div>
        <div style="font-size:32px; font-weight:800; color:var(--primary-light); margin-bottom:16px;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($total, 2) }}</div>
        <div style="font-size:12px; color:var(--muted); line-height:1.5;"><i class="fa fa-shield-halved"></i> 256-bit SSL Secure Checkout. Your connection is fully encrypted.</div>
    </aside>
</div>

<script>
    function togglePaymentDetails(method) {
        const bankDetails = document.getElementById('bankDetails');
        const refInput = document.getElementById('bank_reference');
        if (method === 'bank_transfer') {
            bankDetails.style.display = 'block';
            refInput.setAttribute('required', 'required');
        } else {
            bankDetails.style.display = 'none';
            refInput.removeAttribute('required');
        }
    }
</script>
@endsection
