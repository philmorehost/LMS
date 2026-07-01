<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $courses = [];

        try {
            $courses = \DB::table('courses')
                ->where('instructor_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('instructor.courses.index', compact('user', 'settings', 'courses'));
    }

    public function create()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $categories = [];

        try {
            $categories = \DB::table('course_categories')->where('is_active', true)->get();
        } catch (\Exception $e) {}

        return view('instructor.courses.create', compact('user', 'settings', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'promo_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        $user = Auth::user();
        $isFree = $request->has('is_free') || !$request->price;
        $price = $isFree ? 0.00 : floatval($request->price);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $promoVideoPath = null;
        if ($request->hasFile('promo_video')) {
            $promoVideoPath = $request->file('promo_video')->store('courses/promo', 'public');
        }

        try {
            $courseId = \DB::table('courses')->insertGetId([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . rand(100, 999),
                'description' => $request->description,
                'price' => $price,
                'is_free' => $isFree,
                'is_featured' => false,
                'is_published' => false, // Requires admin approval
                'instructor_id' => $user->id,
                'category_id' => $request->category_id,
                'thumbnail' => $thumbnailPath,
                'promo_video' => $promoVideoPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $moduleId = \DB::table('course_modules')->insertGetId([
                'course_id' => $courseId,
                'title' => 'Introduction',
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Seed a default lesson to get started
            \DB::table('course_lessons')->insert([
                'module_id' => $moduleId,
                'course_id' => $courseId,
                'title' => 'Introduction Lecture',
                'content' => 'Course outline overview.',
                'video_source' => 'youtube',
                'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY',
                'duration_minutes' => 5,
                'position' => 1,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Error saving course: ' . $e->getMessage()]);
        }

        return redirect()->route('instructor.courses.index')->with('success', 'Course created successfully. Awaiting administrator approval to publish.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        
        $course = \DB::table('courses')->where('id', $id)->where('instructor_id', $user->id)->first();
        if (!$course) abort(404);

        $categories = [];
        $modules = [];
        try {
            $categories = \DB::table('course_categories')->where('is_active', true)->get();
            $modules = \DB::table('course_modules')->where('course_id', $course->id)->orderBy('position')->get();
            foreach ($modules as $module) {
                $module->lessons = \DB::table('course_lessons')->where('module_id', $module->id)->orderBy('position')->get();
            }
        } catch (\Exception $e) {}

        return view('instructor.courses.edit', compact('user', 'settings', 'course', 'categories', 'modules'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $user = Auth::user();
        $isFree = $request->has('is_free') || !$request->price;
        $price = $isFree ? 0.00 : floatval($request->price);

        try {
            \DB::table('courses')
                ->where('id', $id)
                ->where('instructor_id', $user->id)
                ->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'price' => $price,
                    'is_free' => $isFree,
                    'category_id' => $request->category_id,
                    'updated_at' => now(),
                ]);

            if ($request->has('modules')) {
                foreach ($request->modules as $moduleId => $modData) {
                    \DB::table('course_modules')->where('id', $moduleId)->update([
                        'title' => $modData['title'],
                        'updated_at' => now(),
                    ]);
                }
            }

            // Handle lesson lecture updates
            if ($request->has('lessons')) {
                foreach ($request->lessons as $lessonId => $lesData) {
                    $updateData = [
                        'title' => $lesData['title'],
                        'video_url' => $lesData['video_url'] ?? null,
                        'content' => $lesData['content'] ?? null,
                        'type' => $lesData['type'] ?? 'video',
                        'updated_at' => now(),
                    ];

                    if (isset($lesData['file']) && $request->hasFile("lessons.{$lessonId}.file")) {
                        $filePath = $request->file("lessons.{$lessonId}.file")->store('courses/lessons/files', 'public');
                        $updateData['file_path'] = $filePath;
                    }

                    \DB::table('course_lessons')->where('id', $lessonId)->update($updateData);
                }
            }
        } catch (\Exception $e) {
            return back()->withErrors(['title' => 'Error updating course details.']);
        }

        return redirect()->route('instructor.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        try {
            \DB::table('courses')->where('id', $id)->where('instructor_id', $user->id)->delete();
        } catch (\Exception $e) {}

        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted successfully.');
    }

    protected function getSettings(): array
    {
        $settings = [];
        try {
            $rows = \DB::table('settings')->get();
            foreach ($rows as $row) {
                $settings[$row->key] = $row->value;
            }
        } catch (\Exception $e) {}
        return $settings;
    }
}
