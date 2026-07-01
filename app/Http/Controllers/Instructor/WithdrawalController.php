<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        
        $balance = $this->calculateBalance($user->id);
        $withdrawals = [];
        $profile = null;

        try {
            $withdrawals = \DB::table('withdrawals')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            $profile = \DB::table('instructor_profiles')->where('user_id', $user->id)->first();
        } catch (\Exception $e) {}

        return view('instructor.withdrawals', compact('user', 'settings', 'balance', 'withdrawals', 'profile'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        $balance = $this->calculateBalance($user->id);

        if ($request->amount > $balance) {
            return back()->withErrors(['amount' => 'You cannot withdraw more than your available balance.']);
        }

        try {
            \DB::table('withdrawals')->insert([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'account_details' => json_encode([
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => 'Error processing withdrawal request.']);
        }

        return redirect()->route('instructor.withdrawals')->with('success', 'Your payout request has been submitted successfully.');
    }

    protected function calculateBalance($userId): float
    {
        $settings = $this->getSettings();
        $totalEarned = 0.00;
        $totalWithdrawn = 0.00;

        try {
            $courseIds = \DB::table('courses')->where('instructor_id', $userId)->pluck('id')->toArray();
            
            if (count($courseIds) > 0) {
                $payments = \DB::table('payments')
                    ->whereIn('course_id', $courseIds)
                    ->where('status', 'completed')
                    ->get();

                $commissionRate = floatval($settings['commission_rate'] ?? 20) / 100;
                foreach ($payments as $p) {
                    $totalEarned += $p->amount * (1 - $commissionRate);
                }
            }

            // Sum up approved/pending withdrawals
            $totalWithdrawn = \DB::table('withdrawals')
                ->where('user_id', $userId)
                ->whereIn('status', ['pending', 'approved'])
                ->sum('amount');

        } catch (\Exception $e) {}

        return max(0.00, $totalEarned - $totalWithdrawn);
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
