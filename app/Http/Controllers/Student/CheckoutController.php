<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = $this->getSettings();

        // Get cart items
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

        if (count($cartItems) === 0) {
            return redirect()->route('student.cart')->with('error', 'Your cart is empty.');
        }

        return view('student.checkout', compact('user', 'settings', 'cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:paystack,bank_transfer',
        ]);

        $user = Auth::user();
        $settings = $this->getSettings();
        
        // Calculate total
        $cartItems = \DB::table('cart')
            ->join('courses', 'cart.course_id', '=', 'courses.id')
            ->where('cart.user_id', $user->id)
            ->select('courses.id', 'courses.price')
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->price;
        }

        if ($total == 0) {
            // Free enrollment fallback
            foreach ($cartItems as $item) {
                $this->enrollUser($user->id, $item->id);
            }
            \DB::table('cart')->where('user_id', $user->id)->delete();
            return redirect()->route('student.courses')->with('success', 'Enrolled in course successfully!');
        }

        if ($request->payment_method === 'bank_transfer') {
            $request->validate([
                'bank_reference' => 'required|string|max:100',
            ]);

            // Save payments log as pending
            foreach ($cartItems as $item) {
                $paymentId = \DB::table('payments')->insertGetId([
                    'user_id' => $user->id,
                    'course_id' => $item->id,
                    'amount' => $item->price,
                    'gateway' => 'bank_transfer',
                    'status' => 'pending',
                    'transaction_id' => $request->bank_reference,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Create pending enrollment
                \DB::table('enrollments')->insert([
                    'user_id' => $user->id,
                    'course_id' => $item->id,
                    'progress' => 0,
                    'status' => 'pending_approval',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Clear Cart
            \DB::table('cart')->where('user_id', $user->id)->delete();

            return redirect()->route('student.dashboard')->with('success', 'Your bank transfer reference has been submitted. The admin will verify it shortly.');
        }

        // Paystack redirection link generation
        if ($request->payment_method === 'paystack') {
            $email = $user->email;
            $amountInKobo = $total * 100;
            $callbackUrl = route('student.checkout.paystack.callback');
            
            $paystackSecret = $settings['paystack_secret_key'] ?? '';
            if (empty($paystackSecret)) {
                return back()->withErrors(['payment_method' => 'Paystack gateway is currently disabled (missing API keys).']);
            }

            // Call Paystack API to initialize transaction
            $url = "https://api.paystack.co/transaction/initialize";
            $fields = [
                'email' => $email,
                'amount' => $amountInKobo,
                'callback_url' => $callbackUrl,
                'metadata' => [
                    'user_id' => $user->id,
                    'cart_items' => $cartItems->pluck('id')->toArray()
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . $paystackSecret,
                "Content-Type: application/json"
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($result && $result['status']) {
                return redirect($result['data']['authorization_url']);
            } else {
                return back()->withErrors(['payment_method' => 'Paystack Initialization Error: ' . ($result['message'] ?? 'Unable to connect.')]);
            }
        }

        return back();
    }

    public function paystackCallback(Request $request)
    {
        $reference = $request->query('reference');
        if (!$reference) {
            return redirect()->route('student.checkout')->with('error', 'No reference returned from Paystack.');
        }

        $settings = $this->getSettings();
        $paystackSecret = $settings['paystack_secret_key'] ?? '';

        // Verify transaction
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $paystackSecret,
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result && $result['status'] && $result['data']['status'] === 'success') {
            $user_id = $result['data']['metadata']['user_id'];
            $course_ids = $result['data']['metadata']['cart_items'];
            $amount = $result['data']['amount'] / 100;

            // Fetch all courses to calculate their individual prices for correct splitting
            $cartCourses = \DB::table('courses')->whereIn('id', $course_ids)->get()->keyBy('id');

            $totalOriginalPrice = $cartCourses->sum('price');

            foreach ($course_ids as $course_id) {
                $course = $cartCourses->get($course_id);
                if (!$course) continue;

                // Determine proportion of total amount
                $coursePrice = $course->price;
                $paidCourseAmount = $totalOriginalPrice > 0 ? ($coursePrice / $totalOriginalPrice) * $amount : $amount / count($course_ids);

                // Log payment
                \DB::table('payments')->insert([
                    'user_id' => $user_id,
                    'course_id' => $course_id,
                    'amount' => $paidCourseAmount,
                    'gateway' => 'paystack',
                    'status' => 'completed',
                    'transaction_id' => $reference,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Distribute Earnings to Instructor Wallet
                try {
                    $commissionRate = floatval($settings['commission_rate'] ?? 20) / 100;
                    $instructorEarning = $paidCourseAmount * (1 - $commissionRate);

                    $wallet = \DB::table('wallet_balances')->where('user_id', $course->instructor_id)->first();

                    if ($wallet) {
                        \DB::table('wallet_balances')
                            ->where('user_id', $course->instructor_id)
                            ->increment('balance', $instructorEarning);
                    } else {
                        \DB::table('wallet_balances')->insert([
                            'user_id' => $course->instructor_id,
                            'balance' => $instructorEarning,
                            'locked_balance' => 0.00,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Failed to distribute earnings to instructor {$course->instructor_id} for course {$course_id}: " . $e->getMessage());
                }

                // Enroll user
                $this->enrollUser($user_id, $course_id);
            }

            return redirect()->route('student.courses')->with('success', 'Payment successful! You are now enrolled.');
        }

        return redirect()->route('student.checkout')->with('error', 'Paystack payment verification failed.');
    }

    protected function enrollUser($userId, $courseId)
    {
        try {
            \DB::table('enrollments')->updateOrInsert(
                ['user_id' => $userId, 'course_id' => $courseId],
                ['progress' => 0, 'status' => 'active', 'updated_at' => now(), 'created_at' => now()]
            );
        } catch (\Exception $e) {}
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
