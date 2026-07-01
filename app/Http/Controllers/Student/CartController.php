<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();
        $cartItems = [];
        $total = 0;

        try {
            $cartItems = \DB::table('cart')
                ->join('courses', 'cart.course_id', '=', 'courses.id')
                ->where('cart.user_id', $user->id)
                ->select('cart.id as cart_item_id', 'courses.*')
                ->get();

            foreach ($cartItems as $item) {
                $total += $item->price;
            }
        } catch (\Exception $e) {}

        return view('student.cart', compact('user', 'settings', 'cartItems', 'total'));
    }

    public function add(Request $request, $courseId)
    {
        $user = Auth::user();

        try {
            // Check if already enrolled
            $enrolled = \DB::table('enrollments')
                ->where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->exists();

            if ($enrolled) {
                return redirect()->route('student.courses')->with('success', 'You are already enrolled in this course.');
            }

            // Check if already in cart
            $inCart = \DB::table('cart')
                ->where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->exists();

            if (!$inCart) {
                \DB::table('cart')->insert([
                    'user_id' => $user->id,
                    'course_id' => $courseId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {}

        return redirect()->route('student.cart')->with('success', 'Course added to cart.');
    }

    public function remove($id)
    {
        try {
            \DB::table('cart')->where('id', $id)->delete();
        } catch (\Exception $e) {}

        return back()->with('success', 'Course removed from cart.');
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
