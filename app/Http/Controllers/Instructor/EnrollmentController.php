<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $enrollments = [];

        try {
            $courseIds = \DB::table('courses')->where('instructor_id', $user->id)->pluck('id')->toArray();
            
            if (count($courseIds) > 0) {
                $enrollments = \DB::table('enrollments')
                    ->join('users', 'enrollments.user_id', '=', 'users.id')
                    ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                    ->whereIn('enrollments.course_id', $courseIds)
                    ->select('enrollments.*', 'users.name as student_name', 'users.email as student_email', 'courses.title as course_title')
                    ->orderBy('enrollments.created_at', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {}

        return view('instructor.enrollments', compact('user', 'settings', 'enrollments'));
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
