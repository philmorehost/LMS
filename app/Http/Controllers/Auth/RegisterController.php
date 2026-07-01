<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        $settings = $this->getSettings();
        return view('auth.register', compact('settings'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'role'       => 'required|in:student,instructor',
            'password'   => ['required', 'confirmed', Password::min(8)],
        ]);

        $name = trim($request->first_name . ' ' . $request->last_name);

        $user = \DB::table('users')->insert([
            'name'       => $name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'status'     => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Auto login
        Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $request->session()->regenerate();

        return redirect()->route('student.dashboard')
            ->with('success', 'Welcome aboard! Your account has been created.');
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
