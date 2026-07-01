@extends('layouts.student')

@section('title', 'Learning Console')
@section('page-title', $course->title)

@section('content')
<!-- Anti-copy, Anti-screenshot, Anti-print style protection wrapper -->
<style>
    /* Prevent selection and copying */
    .protected-console {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    
    /* Prevent print preview leaks */
    @media print {
        body { display: none !important; }
    }
</style>

<div class="protected-console" oncontextmenu="return false;" style="display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start;">
    <!-- Left: Video / Lesson Player & Protected Materials Panel -->
    <div>
        <!-- Active Session Timer Header -->
        <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border); padding:10px 18px; border-radius:12px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
            <div style="font-size:13px; font-weight:600; color:var(--muted); display:flex; align-items:center; gap:8px;">
                <span style="width:8px; height:8px; background:var(--primary); border-radius:50%; display:inline-block; animation:pulse 1s infinite;"></span>
                Active Study Session
            </div>
            <div style="font-size:14px; font-weight:700; color:#fff;" id="studyTimer">00:00:00</div>
        </div>

        <!-- Video Player Frame -->
        <div id="videoContainer" style="background:#000; border:1px solid var(--border); border-radius:16px; aspect-ratio:16/9; display:flex; align-items:center; justify-content:center; flex-direction:column; margin-bottom:24px; box-shadow:0 8px 32px rgba(0,0,0,0.5); overflow:hidden;">
            @if(count($lessons) > 0)
                <!-- Dynamic script will inject player here based on source -->
            @else
                <div style="text-align:center; padding:40px;">
                    <div style="font-size:64px; margin-bottom:12px;">📺</div>
                    <h3 style="font-size:18px; font-weight:600; color:var(--muted)">No video lectures added yet.</h3>
                </div>
            @endif
        </div>

        <!-- Lesson Description & Protected Materials -->
        <div class="panel-card" style="margin-bottom:20px;">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:12px;" id="currentLessonTitle">
                {{ count($lessons) > 0 ? $lessons[0]->title : 'Course Syllabus' }}
            </h3>
            <p style="font-size:14px; color:var(--muted); line-height:1.6; margin-bottom:18px;" id="currentLessonDesc">
                {{ count($lessons) > 0 ? ($lessons[0]->description ?? 'Enjoy this lecture.') : ($course->description ?? 'No course description available.') }}
            </p>
        </div>

        <!-- Protected Course Materials Card -->
        <div class="panel-card" style="border-color:rgba(236,72,153,0.25);">
            <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--border); padding-bottom:12px; margin-bottom:12px;">
                <h3 class="panel-title" style="color:var(--primary-light); font-size:14px;"><i class="fa fa-lock"></i> Protected Lesson Materials</h3>
                <span style="font-size:11px; color:var(--muted);">Web View Only — Download Restricted</span>
            </div>
            
            <div id="materialContent" style="font-size:13px; color:var(--muted); line-height:1.7; background:rgba(0,0,0,0.2); padding:16px; border-radius:10px; border:1px solid var(--border);">
                Study contents and reference documentation for this lecture will display here. Right-clicks, highlights, and page printing are disabled to protect source content.
            </div>

            <!-- Optional download button for files instructors want downloadable -->
            <div id="downloadContainer" style="margin-top:16px; display:none;">
                <a href="#" id="downloadLink" class="btn btn-ghost" style="font-size:12px; padding:8px 16px; background:rgba(255,255,255,0.03);" download>
                    <i class="fa fa-download"></i> Download Reference Handout (Offline Study Allowed)
                </a>
            </div>
        </div>
    </div>

    <!-- Right: Syllabus lesson selector -->
    <aside class="panel-card" style="height:fit-content; padding:20px;">
        <h4 style="font-size:14px; font-weight:700; margin-bottom:16px; border-bottom:1px solid var(--border); padding-bottom:12px;">Syllabus Lectures</h4>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @if(count($lessons) > 0)
                @foreach($lessons as $index => $lesson)
                <div class="lesson-select-btn" 
                     data-video-url="{{ $lesson->video_url }}" 
                     data-title="{{ $lesson->title }}" 
                     data-desc="{{ $lesson->description ?? 'Enjoy this lecture.' }}"
                     data-material="{{ $lesson->materials ?? 'No additional text documentation provided for this lecture.' }}"
                     data-allow-download="{{ isset($lesson->allow_download) && $lesson->allow_download ? '1' : '0' }}"
                     data-download-url="{{ $lesson->download_path ?? '#' }}"
                     onclick="playLesson(this)"
                     style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:{{ $index === 0 ? 'rgba(236,72,153,0.1)' : 'rgba(255,255,255,0.02)' }}; border:1px solid {{ $index === 0 ? 'var(--primary)' : 'var(--border)' }}; border-radius:10px; font-size:13px; cursor:pointer; transition:all 0.2s;">
                     <div>
                        <div style="font-weight:600; color:var(--primary-light)">Lecture {{ $index + 1 }}</div>
                        <div class="lesson-list-title" style="color:#fff; margin-top:2px; font-weight:500;">{{ $lesson->title }}</div>
                     </div>
                     <i class="fa fa-play-circle" style="color:var(--primary-light); font-size:16px;"></i>
                </div>
                @endforeach
            @else
                <div style="text-align:center; color:var(--muted); font-size:13px; padding:12px;">Syllabus outline empty.</div>
            @endif
        </div>
    </aside>
</div>

<script>
    // Timer functionality
    let totalSeconds = 0;
    const timerElement = document.getElementById('studyTimer');
    
    setInterval(() => {
        totalSeconds++;
        let hrs = Math.floor(totalSeconds / 3600);
        let mins = Math.floor((totalSeconds - (hrs * 3600)) / 60);
        let secs = totalSeconds % 60;
        
        timerElement.textContent = 
            (hrs < 10 ? "0" + hrs : hrs) + ":" + 
            (mins < 10 ? "0" + mins : mins) + ":" + 
            (secs < 10 ? "0" + secs : secs);
            
        // Udemy break warning every 15 minutes
        if (totalSeconds > 0 && totalSeconds % 900 === 0) {
            alert("Break reminder: You have been studying continuously for " + (totalSeconds / 60) + " minutes. Take a brief stretch!");
        }
    }, 1000);

    function playLesson(element) {
        // Reset button styling
        document.querySelectorAll('.lesson-select-btn').forEach(btn => {
            btn.style.background = 'rgba(255,255,255,0.02)';
            btn.style.borderColor = 'var(--border)';
        });

        // Set active button styling
        element.style.background = 'rgba(236,72,153,0.1)';
        element.style.borderColor = 'var(--primary)';

        const videoUrl = element.getAttribute('data-video-url');
        const title = element.getAttribute('data-title');
        const desc = element.getAttribute('data-desc');
        const material = element.getAttribute('data-material');
        const allowDownload = element.getAttribute('data-allow-download');
        const downloadUrl = element.getAttribute('data-download-url');

        // Render appropriate player (YouTube vs Direct Link)
        const container = document.getElementById('videoContainer');
        if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be') || videoUrl.includes('embed')) {
            container.innerHTML = `<iframe id="videoPlayer" width="100%" height="100%" src="${videoUrl}" title="Lesson Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="border:none;"></iframe>`;
        } else {
            container.innerHTML = `<video id="videoPlayer" width="100%" height="100%" controls style="object-fit:contain; background:#000;"><source src="${videoUrl}" type="video/mp4">Your browser does not support the video tag.</video>`;
        }

        // Load details
        document.getElementById('currentLessonTitle').textContent = title;
        document.getElementById('currentLessonDesc').textContent = desc;
        document.getElementById('materialContent').innerHTML = material;

        // Render download options
        const dlContainer = document.getElementById('downloadContainer');
        if (allowDownload === '1') {
            document.getElementById('downloadLink').setAttribute('href', downloadUrl);
            dlContainer.style.display = 'block';
        } else {
            dlContainer.style.display = 'none';
        }
    }

    // Auto-load first lesson on load
    window.addEventListener('DOMContentLoaded', () => {
        const firstBtn = document.querySelector('.lesson-select-btn');
        if (firstBtn) firstBtn.click();
    });
</script>
@endsection
