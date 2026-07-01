@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="panel-card">
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3 class="panel-title">Course Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="padding:8px 16px; font-size:13px; font-weight:600;"><i class="fa fa-plus"></i> Add Category</a>
    </div>

    @if(session('success'))
    <div style="background:rgba(16,185,129,0.1); color:#6ee7b7; border:1px solid rgba(16,185,129,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-responsive">
        <table class="custom-table" style="min-width:100%;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td style="font-weight:600;">{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->icon }}</td>
                    <td>{{ $category->position }}</td>
                    <td>
                        <span class="badge" style="background:{{ $category->is_active ? 'rgba(16,185,129,0.15)' : 'rgba(245,158,11,0.15)' }}; color:{{ $category->is_active ? '#34d399' : '#fcd34d' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" style="color:#67e8f9; font-size:13px; margin-right:12px;"><i class="fa fa-pen-to-square"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; color:var(--error); cursor:pointer; font-size:13px;"><i class="fa fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
