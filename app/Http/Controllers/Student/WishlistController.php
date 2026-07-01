<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $wishlistItems = [];

        try {
            $wishlistItems = \DB::table('wishlist')
                ->join('courses', 'wishlist.course_id', '=', 'courses.id')
                ->where('wishlist.user_id', $user->id)
                ->select('wishlist.id as wishlist_item_id', 'courses.*')
                ->get();
        } catch (\Exception $e) {}

        return view('student.wishlist', compact('user', 'settings', 'wishlistItems'));
    }

    public function toggle(Request $request, $courseId)
    {
        $user = Auth::user();

        try {
            $existing = \DB::table('wishlist')
                ->where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->first();

            if ($existing) {
                \DB::table('wishlist')->where('id', $existing->id)->delete();
                $message = 'Course removed from wishlist.';
            } else {
                \DB::table('wishlist')->insert([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $message = 'Course added to wishlist.';
            }
        } catch (\Exception $e) {
            $message = 'Error updating wishlist.';
        }

        return back()->with('success', $message);
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
