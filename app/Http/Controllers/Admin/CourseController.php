<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $courses = [];

        try {
            $courses = \DB::table('courses')
                ->join('users', 'courses.instructor_id', '=', 'users.id')
                ->leftJoin('course_subscriptions', function($join) {
                    $join->on('courses.id', '=', 'course_subscriptions.course_id')
                         ->where('course_subscriptions.status', '=', 'pending');
                })
                ->select('courses.*', 'users.name as instructor_name', 'course_subscriptions.id as pending_subscription_id')
                ->orderBy('courses.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.courses.index', compact('settings', 'courses'));
    }

    public function approve($id)
    {
        try {
            \DB::table('courses')->where('id', $id)->update([
                'is_published' => true,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Course approved and published successfully.');
    }

    public function reject($id)
    {
        try {
            \DB::table('courses')->where('id', $id)->update([
                'is_published' => false,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Course unpublished successfully.');
    }

    public function approveSubscription($id)
    {
        try {
            \DB::table('course_subscriptions')
                ->where('course_id', $id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'active',
                    'updated_at' => now(),
                ]);

            \DB::table('courses')->where('id', $id)->update([
                'is_featured' => true,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Course subscription approved. Course is now featured.');
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
