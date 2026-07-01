<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $settings = $this->getSettings();
        $categories = [];
        $courses = [];

        try {
            $categories = \DB::table('course_categories')->where('is_active', true)->orderBy('position')->get();
        } catch (\Exception $e) {}

        try {
            $query = \DB::table('courses')->where('is_published', true);

            if ($request->has('category')) {
                $category = \DB::table('course_categories')->where('slug', $request->category)->first();
                if ($category) {
                    $query->where('category_id', $category->id);
                }
            }

            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
            }

            $courses = $query->orderBy('created_at', 'desc')->paginate(12);
        } catch (\Exception $e) {}

        return view('frontend.courses.index', compact('settings', 'categories', 'courses'));
    }

    public function show($slug)
    {
        $settings = $this->getSettings();
        $course = null;
        $lessons = [];

        try {
            $course = \DB::table('courses')->where('slug', $slug)->first();
        } catch (\Exception $e) {}

        if (!$course) abort(404);

        try {
            $lessons = \DB::table('lessons')
                ->where('course_id', $course->id)
                ->orderBy('position')
                ->get();
        } catch (\Exception $e) {}

        return view('frontend.courses.show', compact('settings', 'course', 'lessons'));
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
