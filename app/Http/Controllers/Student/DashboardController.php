<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $enrollments = [];
        $recentActivity = [];

        try {
            $enrollments = \DB::table('enrollments')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->where('enrollments.user_id', $user->id)
                ->select('enrollments.*', 'courses.title', 'courses.slug', 'courses.thumbnail')
                ->orderBy('enrollments.created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {}

        return view('student.dashboard', compact('user', 'settings', 'enrollments'));
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

    public function __call($method, $args)
    {
        return response()->json(['status' => 'stub', 'method' => $method, 'class' => __CLASS__]);
    }
}
