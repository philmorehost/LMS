<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $profile = \DB::table('instructor_profiles')->where('user_id', $user->id)->first();

        return view('instructor.profile', compact('user', 'settings', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'expertise' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:100',
        ]);

        try {
            \DB::table('users')->where('id', $user->id)->update([
                'name' => $request->name,
                'updated_at' => now(),
            ]);

            \DB::table('instructor_profiles')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'title' => $request->title,
                    'expertise' => $request->expertise,
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'account_name' => $request->account_name,
                    'updated_at' => now(),
                ]
            );
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the profile.']);
        }

        return back()->with('success', 'Profile and bank details updated successfully.');
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
