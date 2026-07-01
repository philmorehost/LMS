<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();

        // Let's count key metrics with fallback
        $totalStudents = 0;
        $totalInstructors = 0;
        $totalCourses = 0;
        $totalEarnings = 0.00;

        try {
            $totalStudents = \DB::table('users')->where('role', 'student')->count();
        } catch (\Exception $e) {}

        try {
            $totalInstructors = \DB::table('users')->where('role', 'instructor')->count();
        } catch (\Exception $e) {}

        try {
            $totalCourses = \DB::table('courses')->count();
        } catch (\Exception $e) {}

        try {
            $totalEarnings = \DB::table('payments')->where('status', 'completed')->sum('amount');
        } catch (\Exception $e) {}

        // Load recent users
        $recentUsers = [];
        try {
            $recentUsers = \DB::table('users')
                ->select('name', 'email', 'role', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {}

        // Load recent courses
        $recentCourses = [];
        try {
            $recentCourses = \DB::table('courses')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {}

        return view('admin.dashboard', compact(
            'settings',
            'totalStudents',
            'totalInstructors',
            'totalCourses',
            'totalEarnings',
            'recentUsers',
            'recentCourses'
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
