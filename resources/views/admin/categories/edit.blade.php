@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@section('content')
<div class="panel-card" style="max-width: 600px;">
    <div class="panel-header">
        <h3 class="panel-title">Edit Category</h3>
    </div>

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf
        @method('PUT')
        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Icon (FontAwesome class)</label>
            <input type="text" name="icon" value="{{ $category->icon }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" placeholder="e.g. fa fa-code">
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Parent Category</label>
            <select name="parent_id" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;">
                <option value="">None (Top Level)</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Position</label>
            <input type="number" name="position" value="{{ $category->position }}" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" required>
        </div>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; color:var(--muted); margin-bottom:6px;">Description</label>
            <textarea name="description" style="width:100%; padding:12px; background:#0c0c0e; border:1px solid var(--border); border-radius:10px; color:#fff;" rows="3">{{ $category->description }}</textarea>
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="is_active" style="width:18px; height:18px;" {{ $category->is_active ? 'checked' : '' }}>
                <span style="font-size:14px; font-weight:600;">Active</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="padding:12px 24px;">Update Category</button>
        <a href="{{ route('admin.categories.index') }}" style="margin-left:12px; font-size:14px; color:var(--muted);">Cancel</a>
    </form>
</div>
@endsection
