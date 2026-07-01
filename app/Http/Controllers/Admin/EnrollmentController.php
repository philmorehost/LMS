<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $enrollments = [];

        try {
            $enrollments = \DB::table('enrollments')
                ->join('users', 'enrollments.user_id', '=', 'users.id')
                ->join('courses', 'enrollments.course_id', '=', 'courses.id')
                ->leftJoin('payments', function ($join) {
                    $join->on('enrollments.user_id', '=', 'payments.user_id')
                         ->on('enrollments.course_id', '=', 'payments.course_id');
                })
                ->select(
                    'enrollments.id as enrollment_id',
                    'enrollments.status as enrollment_status',
                    'enrollments.created_at as enrolled_at',
                    'users.name as student_name',
                    'users.email as student_email',
                    'courses.title as course_title',
                    'payments.transaction_id as bank_reference'
                )
                ->orderBy('enrollments.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.enrollments.index', compact('settings', 'enrollments'));
    }

    public function approve($id)
    {
        try {
            $enrollment = \DB::table('enrollments')->where('id', $id)->first();
            if ($enrollment) {
                // Update enrollment to active
                \DB::table('enrollments')->where('id', $id)->update([
                    'status' => 'active',
                    'updated_at' => now(),
                ]);

                // Update payment status to completed
                \DB::table('payments')
                    ->where('user_id', $enrollment->user_id)
                    ->where('course_id', $enrollment->course_id)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {}

        return back()->with('success', 'Enrollment approved and active.');
    }

    public function reject($id)
    {
        try {
            $enrollment = \DB::table('enrollments')->where('id', $id)->first();
            if ($enrollment) {
                // Reject enrollment
                \DB::table('enrollments')->where('id', $id)->update([
                    'status' => 'rejected',
                    'updated_at' => now(),
                ]);

                // Update payment status to rejected
                \DB::table('payments')
                    ->where('user_id', $enrollment->user_id)
                    ->where('course_id', $enrollment->course_id)
                    ->update([
                        'status' => 'failed',
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {}

        return back()->with('success', 'Enrollment application rejected.');
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
