<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $payments = [];

        try {
            $payments = \DB::table('payments')
                ->join('users', 'payments.user_id', '=', 'users.id')
                ->select('payments.*', 'users.name as user_name', 'users.email as user_email')
                ->orderBy('payments.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.payments.index', compact('settings', 'payments'));
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
