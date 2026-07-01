<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Course Categories ────────────────────────────────────────────────
        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();
        });

        // ─── Course Levels ────────────────────────────────────────────────────
        Schema::create('course_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Course Languages ─────────────────────────────────────────────────
        Schema::create('course_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Courses ──────────────────────────────────────────────────────────
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('course_categories')->restrictOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('course_levels')->nullOnDelete();
            $table->foreignId('language_id')->nullable()->constrained('course_languages')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('requirements')->nullable();  // JSON array
            $table->json('outcomes')->nullable();       // JSON array
            $table->json('for_whom')->nullable();       // JSON array

            $table->string('thumbnail')->nullable();
            $table->string('promo_video')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);

            $table->enum('approval_status', ['pending', 'approved', 'rejected', 'draft'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Stats (denormalized for performance)
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('total_lessons')->default(0);
            $table->unsignedInteger('total_duration_minutes')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('total_reviews')->default(0);

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['approval_status', 'is_published']);
            $table->index(['category_id', 'approval_status']);
            $table->index(['instructor_id', 'approval_status']);
            $table->fullText(['title', 'short_description']);
        });

        // ─── Course Modules (Sections) ────────────────────────────────────────
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        // ─── Course Lessons ───────────────────────────────────────────────────
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('type', ['video', 'text', 'file', 'quiz'])->default('video');
            $table->longText('content')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_source')->nullable(); // youtube|vimeo|self_hosted|external
            $table->string('file_path')->nullable();
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->boolean('is_published')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        // ─── Course Enrollments ───────────────────────────────────────────────
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('gateway')->nullable();
            $table->foreignId('coupon_id')->nullable();
            $table->decimal('progress_percent', 5, 2)->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('enrolled_at');
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        // ─── Lesson Progress ──────────────────────────────────────────────────
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('course_lessons')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->integer('watch_percentage')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });

        // ─── Course Reviews ───────────────────────────────────────────────────
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned();  // 1-5
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        // ─── Wishlist ─────────────────────────────────────────────────────────
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        // ─── Quizzes ──────────────────────────────────────────────────────────
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('course_lessons')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->integer('time_limit_minutes')->nullable();
            $table->integer('pass_percentage')->default(60);
            $table->boolean('shuffle_questions')->default(false);
            $table->timestamps();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->enum('type', ['multiple_choice', 'true_false', 'short_answer']);
            $table->json('options')->nullable();      // JSON array of options
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();
            $table->integer('time_taken_seconds')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('course_reviews');
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_languages');
        Schema::dropIfExists('course_levels');
        Schema::dropIfExists('course_categories');
    }
};
