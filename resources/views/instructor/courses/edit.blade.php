@extends('layouts.instructor')

@section('title', 'Edit Course Syllabus')
@section('page-title', 'Edit Course Syllabus')

@section('content')
@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('instructor.courses.update', $course->id) }}">
    @csrf
    @method('PUT')
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:32px; align-items:start; margin-bottom:24px;">
        <!-- Left: Basic info -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">Basic Course Information</h3>
            </div>
            
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Course Title</label>
                <input type="text" name="title" value="{{ $course->title }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Category</label>
                <select name="category_id" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $course->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:16px; display:grid; grid-template-columns:1fr 120px; gap:16px; align-items:end;">
                <div>
                    <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Price ({{ $settings['currency_symbol'] ?? '$' }})</label>
                    <input type="number" name="price" id="priceInput" value="{{ $course->is_free ? '' : $course->price }}" step="0.01" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none;" {{ $course->is_free ? 'disabled' : '' }}>
                </div>
                <label style="display:flex; align-items:center; gap:8px; height:44px; margin-bottom:2px; cursor:pointer;">
                    <input type="checkbox" name="is_free" id="isFreeCheckbox" style="accent-color:var(--primary); width:18px; height:18px;" onclick="togglePriceField(this)" {{ $course->is_free ? 'checked' : '' }}>
                    <span style="font-size:14px; font-weight:600;">Free</span>
                </label>
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Course Summary / Description</label>
                <textarea name="description" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff; outline:none; font-family:inherit;" rows="4" placeholder="Write details about what students will learn...">{{ $course->description }}</textarea>
            </div>
        </div>

        <!-- Right: Syllabus lesson lectures list editor -->
        <div class="panel-card">
            <div class="panel-header">
                <h3 class="panel-title">Syllabus Lecture Content</h3>
            </div>
            
            <div style="display:flex; flex-direction:column; gap:16px;">
                @foreach($lessons as $index => $lesson)
                <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:12px; padding:16px;">
                    <div style="font-weight:700; font-size:13px; color:var(--primary-light); margin-bottom:10px;">Lecture {{ $index + 1 }}</div>
                    
                    <div style="margin-bottom:10px;">
                        <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Lecture Title</label>
                        <input type="text" name="lessons[{{ $lesson->id }}][title]" value="{{ $lesson->title }}" style="width:100%; padding:8px 12px; background:#000; border:1px solid var(--border); border-radius:8px; color:#fff; font-size:13px;" required>
                    </div>
                    
                    <div>
                        <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Streaming Video URL (YouTube embed link)</label>
                        <input type="text" name="lessons[{{ $lesson->id }}][video_url]" value="{{ $lesson->video_url }}" style="width:100%; padding:8px 12px; background:#000; border:1px solid var(--border); border-radius:8px; color:#fff; font-size:13px;" placeholder="e.g. https://www.youtube.com/embed/..." required>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <button type="submit" style="padding:14px 28px; background:linear-gradient(135deg, var(--primary), #8b5cf6); border:none; border-radius:10px; color:#fff; font-weight:700; cursor:pointer; display:block; margin:0 auto; box-shadow:0 4px 20px rgba(236,72,153,0.35);"><i class="fa fa-save"></i> Save Course Syllabus</button>
</form>

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
