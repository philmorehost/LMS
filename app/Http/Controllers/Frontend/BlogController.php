<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $settings = $this->getSettings();
        $posts = [];

        try {
            $posts = \DB::table('blog_posts')
                ->where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {}

        return view('frontend.blog.index', compact('settings', 'posts'));
    }

    public function show($slug)
    {
        $settings = $this->getSettings();
        $post = null;

        try {
            $post = \DB::table('blog_posts')->where('slug', $slug)->where('is_published', true)->first();
        } catch (\Exception $e) {}

        if (!$post) abort(404);

        return view('frontend.blog.show', compact('settings', 'post'));
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
