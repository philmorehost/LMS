<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $students = [];

        try {
            $students = \DB::table('users')
                ->where('role', 'student')
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('admin.students.index', compact('settings', 'students'));
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
