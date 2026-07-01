@extends('layouts.student')

@section('title', 'My Wishlist')
@section('page-title', 'Favorites')

@section('content')
@if(session('success'))
<div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ session('success') }}
</div>
@endif

<div class="panel-card">
    <div class="panel-header">
        <h3 class="panel-title">Saved Course Listings</h3>
    </div>
    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Price</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($wishlistItems) > 0)
                    @foreach($wishlistItems as $item)
                    <tr>
                        <td style="font-weight:600;">{{ $item->title }}</td>
                        <td>{{ $item->is_free ? 'Free' : ($settings['currency_symbol'] ?? '$').number_format($item->price, 2) }}</td>
                        <td style="text-align:right; display:flex; gap:12px; justify-content:flex-end;">
                            <form method="POST" action="{{ route('student.cart.add', $item->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:12px;"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                            </form>
                            <form method="POST" action="{{ route('student.wishlist.toggle', $item->id) }}">
                                @csrf
                                <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;"><i class="fa fa-trash"></i> Remove</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" style="text-align:center; padding:30px; color:var(--muted)">Your wishlist is empty.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
