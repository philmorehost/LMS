<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function home()
    {
        $settings = $this->getSettings();
        $featuredCourses = [];
        $categories = [];
        $testimonials = [];

        try {
            $featuredCourses = \DB::table('courses')
                ->where('is_published', true)
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        } catch (\Exception $e) {}

        try {
            $categories = \DB::table('course_categories')
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('position')
                ->limit(12)
                ->get();
        } catch (\Exception $e) {}

        try {
            $testimonials = \DB::table('testimonials')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {}

        return view('frontend.home', compact('settings', 'featuredCourses', 'categories', 'testimonials'));
    }

    public function about()
    {
        $settings = $this->getSettings();
        return view('frontend.about', compact('settings'));
    }

    public function contact()
    {
        $settings = $this->getSettings();
        return view('frontend.contact', compact('settings'));
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string|max:5000',
        ]);

        try {
            \DB::table('contact_messages')->insert([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'subject'    => $request->subject,
                'message'    => $request->message,
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {}

        return back()->with('success', 'Your message has been sent. We will get back to you soon!');
    }

    public function faq()
    {
        $settings = $this->getSettings();
        $faqs = [];
        try {
            $faqs = \DB::table('faqs')->where('is_active', true)->orderBy('position')->get();
        } catch (\Exception $e) {}
        return view('frontend.faq', compact('settings', 'faqs'));
    }

    public function show($slug)
    {
        $settings = $this->getSettings();
        $page = null;
        try {
            $page = \DB::table('cms_pages')->where('slug', $slug)->where('is_published', true)->first();
        } catch (\Exception $e) {}
        if (!$page) abort(404);
        return view('frontend.page', compact('settings', 'page'));
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

    public function __call($method, $args)
    {
        return response()->json(['status' => 'stub', 'method' => $method, 'class' => __CLASS__]);
    }
}
