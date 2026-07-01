<?php

namespace App\Http\Controllers\Admin;

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
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.instructors.index', compact('settings', 'instructors'));
    }

    public function approve($id)
    {
        try {
            \DB::table('users')->where('id', $id)->update([
                'status' => 'active',
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Instructor approved successfully.');
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
