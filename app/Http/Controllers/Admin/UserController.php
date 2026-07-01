<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $settings = $this->getSettings();
        $role = $request->query('role');
        
        $query = \DB::table('users');
        if ($role) {
            $query->where('role', $role);
        }
        
        $users = $query->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('settings', 'users', 'role'));
    }

    public function edit($id)
    {
        $settings = $this->getSettings();
        $user = \DB::table('users')->where('id', $id)->first();
        if (!$user) abort(404);
        
        return view('admin.users.edit', compact('settings', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:admin,instructor,student',
            'status' => 'required|in:active,suspended,banned',
        ]);

        try {
            \DB::table('users')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return redirect()->route('admin.users.index')->with('success', 'User details updated successfully.');
    }

    public function toggleBlock($id)
    {
        try {
            $user = \DB::table('users')->where('id', $id)->first();
            if ($user) {
                $newStatus = $user->status === 'active' ? 'suspended' : 'active';
                \DB::table('users')->where('id', $id)->update([
                    'status' => $newStatus,
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {}

        return back()->with('success', 'User status changed successfully.');
    }

    public function destroy($id)
    {
        try {
            \DB::table('users')->where('id', $id)->delete();
        } catch (\Exception $e) {}

        return back()->with('success', 'User deleted successfully.');
    }

    public function impersonate($id)
    {
        $admin = Auth::user();
        if ($admin->role !== 'admin') abort(403);

        $user = User::find($id);
        if (!$user) abort(404);

        // Store original admin ID in session to allow switching back
        session(['original_admin_id' => $admin->id]);

        Auth::login($user);

        return redirect('/')->with('success', 'Logged in as ' . $user->name);
    }

    public function stopImpersonation()
    {
        $originalAdminId = session('original_admin_id');
        if (!$originalAdminId) return redirect('/');

        $admin = User::find($originalAdminId);
        if ($admin) {
            Auth::login($admin);
            session()->forget('original_admin_id');
            return redirect()->route('admin.dashboard')->with('success', 'Switched back to Admin Panel.');
        }

        return redirect('/');
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
