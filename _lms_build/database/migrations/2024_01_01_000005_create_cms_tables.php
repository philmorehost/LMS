<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Blog Categories ──────────────────────────────────────────────────
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Blog Posts ───────────────────────────────────────────────────────
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('blog_categories')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            // Schema
            $table->string('article_schema_type')->default('Article'); // Article|NewsArticle|BlogPosting
            $table->softDeletes();
            $table->timestamps();
            $table->index(['status', 'published_at']);
            $table->fullText(['title', 'excerpt', 'content']);
        });

        // ─── Blog Comments ────────────────────────────────────────────────────
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('blog_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('parent_id')->nullable()->constrained('blog_comments')->nullOnDelete();
            $table->timestamps();
        });

        // ─── CMS Pages ────────────────────────────────────────────────────────
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('type')->default('custom'); // custom|about|contact|terms|privacy|faq
            $table->boolean('is_published')->default(true);
            $table->boolean('show_in_menu')->default(false);
            $table->integer('menu_order')->default(0);
            $table->string('layout')->default('default'); // default|full-width|sidebar
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->timestamps();
        });

        // ─── FAQs ─────────────────────────────────────────────────────────────
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->nullable();
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Testimonials ─────────────────────────────────────────────────────
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('company')->nullable();
            $table->string('avatar')->nullable();
            $table->text('content');
            $table->tinyInteger('rating')->default(5);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        // ─── Contact Messages ─────────────────────────────────────────────────
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_replied')->default(false);
            $table->text('reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // ─── Newsletter ───────────────────────────────────────────────────────
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('token')->nullable(); // for email verification
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // ─── Languages ────────────────────────────────────────────────────────
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('flag_emoji', 10)->nullable();
            $table->boolean('is_rtl')->default(false);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Email Templates ──────────────────────────────────────────────────
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // registration_welcome, enrollment_success, etc.
            $table->string('name');
            $table->string('subject');
            $table->longText('body');
            $table->json('variables')->nullable(); // available placeholders
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── Notifications ────────────────────────────────────────────────────
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // ─── Activity Log ─────────────────────────────────────────────────────
        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('occurred_at');
            $table->index(['user_id', 'occurred_at']);
        });

        // ─── Instructor Profiles ──────────────────────────────────────────────
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('title')->nullable(); // e.g. "Senior Developer"
            $table->text('expertise')->nullable();
            $table->string('education')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('join_request_approved')->default(false);
            $table->timestamp('join_request_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->decimal('available_balance', 12, 2)->default(0);
            $table->timestamps();
        });

        // ─── Homepage Sections ────────────────────────────────────────────────
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0);
            $table->json('data')->nullable(); // section-specific JSON config
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('instructor_profiles');
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
    }
};
