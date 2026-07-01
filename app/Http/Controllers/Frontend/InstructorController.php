<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $instructors = [];

        try {
            $instructors = \DB::table('users')
                ->where('role', 'instructor')
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('frontend.instructors.index', compact('settings', 'instructors'));
    }

    public function show($id)
    {
        $settings = $this->getSettings();
        $instructor = null;
        $courses = [];

        try {
            $instructor = \DB::table('users')->where('id', $id)->where('role', 'instructor')->first();
        } catch (\Exception $e) {}

        if (!$instructor) abort(404);

        try {
            $courses = \DB::table('courses')
                ->where('instructor_id', $instructor->id)
                ->where('is_published', true)
                ->get();
        } catch (\Exception $e) {}

        return view('frontend.instructors.show', compact('settings', 'instructor', 'courses'));
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
