@extends('layouts.student')

@section('title', 'Shopping Cart')
@section('page-title', 'My Cart')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div style="display:grid; grid-template-columns:1fr 300px; gap:24px;">
    <div>
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">Cart Items ({{ count($cartItems) }})</h3>
            </div>
            @if(count($cartItems) > 0)
            <table class="custom-table" style="min-width:100%;">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th style="text-align:right;">Price</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td style="text-align:right; font-weight:600;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($item->price, 2) }}</td>
                        <td style="text-align:right;">
                            <form method="POST" action="{{ route('student.cart.remove', $item->cart_item_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;"><i class="fa fa-trash"></i> Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="text-align:center; padding:40px; color:var(--muted);">
                <div style="font-size:36px; margin-bottom:12px;">🛒</div>
                Your cart is empty. <a href="{{ url('/courses') }}" style="color:var(--primary-light);">Browse Courses</a>
            </div>
            @endif
        </div>
    </div>

    @if(count($cartItems) > 0)
    <aside class="panel-card" style="height:fit-content; text-align:center;">
        <div style="font-size:13px; color:var(--muted); margin-bottom:6px;">Total Price</div>
        <div style="font-size:26px; font-weight:800; color:var(--primary-light); margin-bottom:16px;">{{ $settings['currency_symbol'] ?? '$' }}{{ number_format($total, 2) }}</div>
        <a href="{{ route('student.checkout') }}" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; font-weight:700;"><i class="fa fa-credit-card"></i> Proceed to Checkout</a>
    </aside>
    @endif
</div>
@endsection
