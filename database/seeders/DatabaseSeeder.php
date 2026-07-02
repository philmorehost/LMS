<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Categories
        $categories = [
            ['name' => 'Web Development', 'slug' => 'web-development', 'icon' => '💻', 'position' => 1],
            ['name' => 'UI/UX Design', 'slug' => 'ui-ux-design', 'icon' => '🎨', 'position' => 2],
            ['name' => 'Data Science', 'slug' => 'data-science', 'icon' => '📊', 'position' => 3],
            ['name' => 'Mobile Development', 'slug' => 'mobile-development', 'icon' => '📱', 'position' => 4],
        ];

        foreach ($categories as $cat) {
            DB::table('course_categories')->updateOrInsert(['slug' => $cat['slug']], [
                'name' => $cat['name'],
                'icon' => $cat['icon'],
                'position' => $cat['position'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Fetch an Instructor to link courses
        $instructor = DB::table('users')->where('role', 'instructor')->first();
        if (!$instructor) {
            $instructorId = DB::table('users')->insertGetId([
                'name' => 'Instructor User',
                'email' => 'instructor@example.com',
                'password' => bcrypt('password'),
                'role' => 'instructor',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $instructorId = $instructor->id;
        }

        // 3. Seed Course 1: Web Development
        $catWeb = DB::table('course_categories')->where('slug', 'web-development')->first();
        $course1Id = DB::table('courses')->insertGetId([
            'title' => 'Full Stack Web Development BootCamp',
            'slug' => 'full-stack-web-development-bootcamp',
            'description' => 'A comprehensive guide to modern web development. Learn HTML, CSS, JavaScript, React, Node.js, and database deployments from scratch.',
            'price' => 49.99,
            'is_free' => false,
            'is_featured' => true,
            'is_published' => true,
            'instructor_id' => $instructorId,
            'category_id' => $catWeb->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lessons for Course 1
        $lessons1 = [
            ['title' => 'Introduction to Web Technologies', 'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY'],
            ['title' => 'HTML5 Structures & Semantic Layouts', 'video_url' => 'https://www.youtube.com/embed/dGcsHMXbSOA'],
            ['title' => 'Styling Web Pages with Modern CSS Grid & Flexbox', 'video_url' => 'https://www.youtube.com/embed/yfoY53QXEnI'],
            ['title' => 'JavaScript Basics: Variables, Loops, and Functions', 'video_url' => 'https://www.youtube.com/embed/hdI2bqOjy3c'],
            ['title' => 'DOM Manipulation & Interactivity', 'video_url' => 'https://www.youtube.com/embed/y17RuWkWdn8'],
        ];

        foreach ($lessons1 as $index => $les) {
            DB::table('lessons')->insert([
                'course_id' => $course1Id,
                'title' => $les['title'],
                'content' => 'Learn core fundamentals in this detailed training lecture.',
                'video_source' => 'youtube',
                'video_url' => $les['video_url'],
                'duration_minutes' => 10,
                'position' => $index + 1,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Seed Course 2: UI/UX Design
        $catUI = DB::table('course_categories')->where('slug', 'ui-ux-design')->first();
        $course2Id = DB::table('courses')->insertGetId([
            'title' => 'Mastering Figma UI/UX Design',
            'slug' => 'mastering-figma-ui-ux-design',
            'description' => 'Learn design thinking, prototyping, user flows, wireframes, and high-fidelity interface design using Figma.',
            'price' => 29.99,
            'is_free' => false,
            'is_featured' => true,
            'is_published' => true,
            'instructor_id' => $instructorId,
            'category_id' => $catUI->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lessons2 = [
            ['title' => 'Introduction to Figma & Workspace setup', 'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY'],
            ['title' => 'Creating UI Components, Auto-layouts & Variants', 'video_url' => 'https://www.youtube.com/embed/dGcsHMXbSOA'],
            ['title' => 'Prototyping Transitions & User Micro-interactions', 'video_url' => 'https://www.youtube.com/embed/yfoY53QXEnI'],
        ];

        foreach ($lessons2 as $index => $les) {
            DB::table('lessons')->insert([
                'course_id' => $course2Id,
                'title' => $les['title'],
                'content' => 'Professional UI/UX guidelines and workspace tips.',
                'video_source' => 'youtube',
                'video_url' => $les['video_url'],
                'duration_minutes' => 7,
                'position' => $index + 1,
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
