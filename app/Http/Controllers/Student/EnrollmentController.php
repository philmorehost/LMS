<?php

namespace App\Http\Controllers\Student;

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
            $enrollments = \DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.user_id', $user->id)
                ->select('enrollments.*', 'courses.title', 'courses.price', 'courses.is_free')
                ->orderBy('enrollments.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('student.enrollments', compact('user', 'settings', 'enrollments'));
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
