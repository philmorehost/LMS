<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        $settings = $this->getSettings();
        return view('auth.login', compact('settings'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if account is active/suspended
            if ($user->status === 'banned' || $user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }

            return $this->redirectToDashboard();
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectToDashboard()
    {
        $user = Auth::user();
        return match ($user->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            default      => redirect()->route('student.dashboard'),
        };
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
