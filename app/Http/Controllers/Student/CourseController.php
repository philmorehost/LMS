<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $courses = [];

        try {
            $courses = \DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.user_id', $user->id)
                ->select('enrollments.*', 'courses.title', 'courses.slug', 'courses.description')
                ->orderBy('enrollments.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('student.courses', compact('user', 'settings', 'courses'));
    }

    public function learn($id)
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $course = null;
        $lessons = [];

        try {
            $course = \DB::table('courses')->where('id', $id)->first();
            if ($course) {
                $lessons = \DB::table('lessons')
                    ->where('course_id', $course->id)
                    ->orderBy('position')
                    ->get();
            }
        } catch (\Exception $e) {}

        if (!$course) abort(404);

        return view('student.learn', compact('user', 'settings', 'course', 'lessons'));
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
