@extends('layouts.instructor')

@section('title', 'Edit Course Syllabus')
@section('page-title', 'Edit Course Syllabus')

@section('content')
@if($errors->any())
<div style="background:rgba(239,68,68,0.1); color:#fca5a5; border:1px solid rgba(239,68,68,0.2); padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:13px;">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('instructor.courses.update', $course->id) }}" enctype="multipart/form-data">
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

        <!-- Right: Syllabus module and lectures list editor -->
        <div class="panel-card">
            <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h3 class="panel-title">Syllabus Content (Modules & Lessons)</h3>
                <button type="button" onclick="addModule()" style="padding:6px 12px; background:rgba(255,255,255,0.1); border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px; cursor:pointer;">+ Add Module</button>
            </div>
            
            <div id="modules-container" style="display:flex; flex-direction:column; gap:16px;">
                @foreach($modules as $mIndex => $module)
                <div class="module-item" data-module-id="{{ $module->id }}" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:12px; padding:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <div style="font-weight:700; font-size:14px; color:var(--primary);">Module {{ $mIndex + 1 }}</div>
                        <button type="button" onclick="addLesson('existing_{{ $module->id }}', this)" style="padding:4px 8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:4px; color:#34d399; font-size:11px; cursor:pointer;">+ Add Lesson</button>
                    </div>
                    
                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Module Title</label>
                        <input type="text" name="modules[{{ $module->id }}][title]" value="{{ $module->title }}" style="width:100%; padding:8px 12px; background:#000; border:1px solid var(--border); border-radius:8px; color:#fff; font-size:13px;" required>
                    </div>
                    
                    <div class="lessons-container" style="margin-left:20px; display:flex; flex-direction:column; gap:12px;">
                        @foreach($module->lessons as $lIndex => $lesson)
                        <div class="lesson-item" style="background:rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:12px;">
                            <div style="font-weight:600; font-size:12px; color:var(--primary-light); margin-bottom:10px;">Lesson {{ $lIndex + 1 }}</div>

                            <div style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Lesson Title</label>
                                <input type="text" name="lessons[{{ $lesson->id }}][title]" value="{{ $lesson->title }}" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" required>
                            </div>

                            <div style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Type</label>
                                <select name="lessons[{{ $lesson->id }}][type]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" required>
                                    <option value="video" {{ $lesson->type == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="text" {{ $lesson->type == 'text' ? 'selected' : '' }}>Rich Text</option>
                                    <option value="file" {{ $lesson->type == 'file' ? 'selected' : '' }}>File Download</option>
                                </select>
                            </div>

                            <div style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Streaming Video URL</label>
                                <input type="text" name="lessons[{{ $lesson->id }}][video_url]" value="{{ $lesson->video_url }}" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" placeholder="e.g. https://www.youtube.com/embed/...">
                            </div>

                            <div style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Rich Text Content</label>
                                <textarea name="lessons[{{ $lesson->id }}][content]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" rows="3">{{ $lesson->content }}</textarea>
                            </div>

                            <div style="margin-bottom:10px;">
                                <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">File Upload (if type is File)</label>
                                <input type="file" name="lessons[{{ $lesson->id }}][file]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;">
                                @if($lesson->file_path)
                                    <div style="margin-top: 4px; font-size: 11px; color: var(--primary);">Current file: {{ basename($lesson->file_path) }}</div>
                                @endif
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <button type="submit" style="padding:14px 28px; background:linear-gradient(135deg, var(--primary), #8b5cf6); border:none; border-radius:10px; color:#fff; font-weight:700; cursor:pointer; display:block; margin:0 auto; box-shadow:0 4px 20px rgba(236,72,153,0.35);"><i class="fa fa-save"></i> Save Course Syllabus</button>
</form>

<script>
    let newModuleCount = 0;
    let newLessonCount = 0;

    function addModule() {
        const container = document.getElementById('modules-container');
        const moduleIndex = 'new_' + newModuleCount;
        newModuleCount++;

        const moduleHtml = `
            <div class="module-item" data-module-id="${moduleIndex}" style="background:rgba(255,255,255,0.05); border:1px solid var(--border); border-radius:12px; padding:16px; margin-top:16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <div style="font-weight:700; font-size:14px; color:var(--primary);">New Module</div>
                    <button type="button" onclick="addLesson('${moduleIndex}', this)" style="padding:4px 8px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:4px; color:#34d399; font-size:11px; cursor:pointer;">+ Add Lesson</button>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Module Title</label>
                    <input type="text" name="new_modules[${moduleIndex}][title]" style="width:100%; padding:8px 12px; background:#000; border:1px solid var(--border); border-radius:8px; color:#fff; font-size:13px;" required>
                </div>

                <div class="lessons-container" style="margin-left:20px; display:flex; flex-direction:column; gap:12px;">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', moduleHtml);
    }

    function addLesson(moduleId, btn) {
        const container = btn.closest('.module-item').querySelector('.lessons-container');
        const lessonIndex = 'new_' + newLessonCount;
        newLessonCount++;

        const lessonHtml = `
            <div class="lesson-item" style="background:rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.08); border-radius:8px; padding:12px;">
                <div style="font-weight:600; font-size:12px; color:var(--primary-light); margin-bottom:10px;">New Lesson</div>

                <input type="hidden" name="new_lessons[${lessonIndex}][module_id]" value="${moduleId}">

                <div style="margin-bottom:10px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Lesson Title</label>
                    <input type="text" name="new_lessons[${lessonIndex}][title]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" required>
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Type</label>
                    <select name="new_lessons[${lessonIndex}][type]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" required>
                        <option value="video">Video</option>
                        <option value="text">Rich Text</option>
                        <option value="file">File Download</option>
                    </select>
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Streaming Video URL</label>
                    <input type="text" name="new_lessons[${lessonIndex}][video_url]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" placeholder="e.g. https://www.youtube.com/embed/...">
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">Rich Text Content</label>
                    <textarea name="new_lessons[${lessonIndex}][content]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;" rows="3"></textarea>
                </div>

                <div style="margin-bottom:10px;">
                    <label style="display:block; font-size:11px; color:var(--muted); margin-bottom:4px;">File Upload</label>
                    <input type="file" name="new_lessons[${lessonIndex}][file]" style="width:100%; padding:8px 12px; background:#111; border:1px solid var(--border); border-radius:6px; color:#fff; font-size:12px;">
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', lessonHtml);
    }

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
