<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $withdrawals = [];

        try {
            $withdrawals = \DB::table('withdrawals')
                ->join('users', 'withdrawals.user_id', '=', 'users.id')
                ->select('withdrawals.*', 'users.name as instructor_name', 'users.email as instructor_email')
                ->orderBy('withdrawals.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.withdrawals.index', compact('settings', 'withdrawals'));
    }

    public function approve($id)
    {
        try {
            \DB::table('withdrawals')->where('id', $id)->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Withdrawal payout marked as APPROVED successfully.');
    }

    public function reject($id)
    {
        try {
            \DB::table('withdrawals')->where('id', $id)->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Withdrawal payout marked as REJECTED successfully.');
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
