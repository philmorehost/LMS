<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        return view('student.profile', compact('user', 'settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:25',
        ]);

        try {
            \DB::table('users')->where('id', $user->id)->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        try {
            \DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Password updated successfully.');
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
