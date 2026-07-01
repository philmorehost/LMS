<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $certificates = [];

        try {
            $certificates = \DB::table('certificates')
                ->join('courses', 'certificates.course_id', '=', 'courses.id')
                ->where('certificates.user_id', $user->id)
                ->select('certificates.*', 'courses.title as course_title')
                ->orderBy('certificates.created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('student.certificates', compact('user', 'settings', 'certificates'));
    }

    public function download($id)
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        
        $certificate = null;
        try {
            $certificate = \DB::table('certificates')
                ->join('courses', 'certificates.course_id', '=', 'courses.id')
                ->where('certificates.id', $id)
                ->where('certificates.user_id', $user->id)
                ->select('certificates.*', 'courses.title as course_title')
                ->first();
        } catch (\Exception $e) {}

        if (!$certificate) abort(404);

        // Render printable certificate layout
        return view('student.certificate_print', compact('user', 'settings', 'certificate'));
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
