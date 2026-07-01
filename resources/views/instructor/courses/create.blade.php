@extends('layouts.instructor')

@section('title', 'Create Course')
@section('page-title', 'Create Course')

@section('content')
@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<div class="panel-card" style="max-width:600px;">
    <div class="panel-header">
        <h3 class="panel-title">Add Course Details</h3>
    </div>
    
    <form method="POST" action="{{ route('instructor.courses.store') }}">
        @csrf
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Course Title</label>
            <input type="text" name="title" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="e.g. Master React in 30 Days" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Category</label>
            <select name="category_id" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:16px; display:grid; grid-template-columns:1fr 120px; gap:16px; align-items:end;">
            <div>
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Price ({{ $settings['currency_symbol'] ?? '$' }})</label>
                <input type="number" name="price" id="priceInput" step="0.01" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" placeholder="e.g. 19.99">
            </div>
            <label style="display:flex; align-items:center; gap:8px; height:44px; margin-bottom:2px; cursor:pointer;">
                <input type="checkbox" name="is_free" id="isFreeCheckbox" style="accent-color:var(--primary); width:18px; height:18px;" onclick="togglePriceField(this)">
                <span style="font-size:14px; font-weight:600;">Free</span>
            </label>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Course Summary / Description</label>
            <textarea name="description" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none; font-family:inherit;" rows="5" placeholder="Write details about what students will learn..."></textarea>
        </div>

        <button type="submit" style="padding:12px 24px; background:linear-gradient(135deg, var(--primary), #8b5cf6); border:none; border-radius:10px; color:#fff; font-weight:600; cursor:pointer;">Save & Create</button>
        <a href="{{ route('instructor.courses.index') }}" style="margin-left:12px; font-size:14px; color:var(--muted)">Cancel</a>
    </form>
</div>

<script>
    function togglePriceField(checkbox) {
        const priceInput = document.getElementById('priceInput');
        if (checkbox.checked) {
            priceInput.value = '';
            priceInput.setAttribute('disabled', 'disabled');
            priceInput.style.opacity = 0.5;
        } else {
            priceInput.removeAttribute('disabled');
            priceInput.style.opacity = 1;
        }
    }
</script>
@endsection
