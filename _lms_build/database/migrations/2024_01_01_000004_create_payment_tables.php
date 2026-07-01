<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Payments ─────────────────────────────────────────────────────────
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gateway'); // stripe|paypal|razorpay|flutterwave|mollie|paystack|instamojo|bank
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->decimal('gateway_fee', 10, 2)->default(0);
            $table->decimal('admin_commission', 10, 2)->default(0);
            $table->decimal('instructor_earning', 10, 2)->default(0);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->json('metadata')->nullable();
            $table->string('bank_receipt')->nullable(); // for bank transfers
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        // ─── Cart ─────────────────────────────────────────────────────────────
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        // ─── Coupons ──────────────────────────────────────────────────────────
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();  // null = unlimited
            $table->integer('used_count')->default(0);
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete(); // null = global
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->timestamps();
        });

        // ─── Instructor Withdrawals ───────────────────────────────────────────
        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('fields')->nullable(); // required fields JSON
            $table->boolean('is_active')->default(true);
            $table->decimal('min_amount', 10, 2)->default(10);
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('withdraw_method_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('fee', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2);
            $table->json('account_details')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing', 'completed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // ─── Certificates ─────────────────────────────────────────────────────
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('design_data')->nullable(); // canvas design JSON
            $table->string('background_image')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->string('certificate_uid')->unique(); // UUID for verification
            $table->string('file_path')->nullable();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        // ─── Instructor Earnings ──────────────────────────────────────────────
        Schema::create('instructor_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->decimal('gross_amount', 10, 2);
            $table->decimal('commission_percent', 5, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->boolean('is_settled')->default(false);
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
        });

        // ─── Currencies ───────────────────────────────────────────────────────
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('symbol', 10);
            $table->decimal('rate', 15, 6)->default(1); // relative to base
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_earnings');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_templates');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('withdraw_methods');
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('currencies');
    }
};
