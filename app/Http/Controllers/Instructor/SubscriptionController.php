<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $user = Auth::user();

        $course = \DB::table('courses')->where('id', $request->course_id)->where('instructor_id', $user->id)->first();
        if (!$course) abort(403);

        $existing = \DB::table('course_subscriptions')
            ->where('course_id', $course->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'A feature request is already pending for this course.');
        }

        \DB::table('course_subscriptions')->insert([
            'course_id' => $course->id,
            'status' => 'pending',
            'type' => 'business_level_monthly',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Feature status requested successfully. Admin approval pending.');
    }
}
