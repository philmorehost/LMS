<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();

        $totalCourses = 0;
        $totalStudents = 0;
        $totalEarnings = 0.00;

        try {
            // Count courses owned by instructor
            $totalCourses = \DB::table('courses')->where('instructor_id', $user->id)->count();
            
            // Get IDs of instructor courses
            $courseIds = \DB::table('courses')->where('instructor_id', $user->id)->pluck('id')->toArray();
            
            if (count($courseIds) > 0) {
                // Count unique students enrolled
                $totalStudents = \DB::table('enrollments')
                    ->whereIn('course_id', $courseIds)
                    ->where('status', 'active')
                    ->distinct('user_id')
                    ->count();

                // Sum earnings matching instructor courses (subtracting commission if configured, else sum)
                $commissionRate = floatval($settings['commission_rate'] ?? 20) / 100;
                $grossEarnings = \DB::table('payments')
                    ->whereIn('course_id', $courseIds)
                    ->where('status', 'completed')
                    ->sum('amount');
                
                $totalEarnings = $grossEarnings * (1 - $commissionRate);
            }
        } catch (\Exception $e) {}

        // Load recent enrollments
        $recentEnrollments = [];
        try {
            if (count($courseIds) > 0) {
                $recentEnrollments = \DB::table('enrollments')
                    ->join('users', 'enrollments.user_id', '=', 'users.id')
                    ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                    ->whereIn('enrollments.course_id', $courseIds)
                    ->select('enrollments.*', 'users.name as student_name', 'users.email as student_email', 'courses.title as course_title')
                    ->orderBy('enrollments.created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {}

        return view('instructor.dashboard', compact(
            'user',
            'settings',
            'totalCourses',
            'totalStudents',
            'totalEarnings',
            'recentEnrollments'
        ));
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
