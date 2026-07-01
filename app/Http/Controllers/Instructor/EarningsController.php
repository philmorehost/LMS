<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $payments = [];
        $totalEarned = 0.00;

        try {
            $courseIds = \DB::table('courses')->where('instructor_id', $user->id)->pluck('id')->toArray();
            
            if (count($courseIds) > 0) {
                $payments = \DB::table('payments')
                    ->join('users', 'payments.user_id', '=', 'users.id')
                    ->join('courses', 'payments.course_id', '=', 'courses.id')
                    ->whereIn('payments.course_id', $courseIds)
                    ->where('payments.status', 'completed')
                    ->select('payments.*', 'users.name as student_name', 'courses.title as course_title')
                    ->orderBy('payments.created_at', 'desc')
                    ->get();

                $commissionRate = floatval($settings['commission_rate'] ?? 20) / 100;
                foreach ($payments as $payment) {
                    $totalEarned += $payment->amount * (1 - $commissionRate);
                }
            }
        } catch (\Exception $e) {}

        return view('instructor.earnings', compact('user', 'settings', 'payments', 'totalEarned'));
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
