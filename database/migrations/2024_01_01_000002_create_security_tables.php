<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // â”€â”€â”€ Brute Force Settings â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('brute_force_settings')) {
        Schema::create('brute_force_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        }
        // â”€â”€â”€ Login Attempts â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('login_attempts')) {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->enum('identifier_type', ['username', 'ip', 'both']);
            $table->string('identifier');          // username or IP
            $table->string('ip_address', 45);
            $table->string('username')->nullable(); // for IP-type, still store attempted username
            $table->text('user_agent')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name')->nullable();
            $table->boolean('success')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamp('attempted_at');
            $table->index(['identifier', 'identifier_type', 'attempted_at']);
            $table->index(['ip_address', 'attempted_at']);
        });
        }
        // â”€â”€â”€ Blocked Accounts â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('blocked_accounts')) {
        Schema::create('blocked_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username')->index();
            $table->string('email')->nullable();
            $table->string('blocked_by')->default('system'); // system|admin
            $table->string('reason')->nullable();
            $table->timestamp('blocked_at');
            $table->timestamp('blocked_until')->nullable(); // null = permanent
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }
        // â”€â”€â”€ Blocked IPs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('blocked_ips')) {
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('cidr_range')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name')->nullable();
            $table->enum('blocked_by', ['system', 'admin'])->default('system');
            $table->enum('block_type', ['one_day', 'one_week', 'one_month', 'one_year', 'permanent'])->default('one_day');
            $table->string('reason')->nullable();
            $table->timestamp('blocked_at');
            $table->timestamp('blocked_until')->nullable(); // null = permanent
            $table->boolean('at_firewall_level')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }
        // â”€â”€â”€ Whitelisted IPs â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('whitelisted_ips')) {
        Schema::create('whitelisted_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->unique();
            $table->string('cidr_range')->nullable();
            $table->string('label')->nullable();
            $table->boolean('recognized_auto')->default(false); // auto-recognized after 5 sessions
            $table->boolean('king_status')->default(false);     // King icon = trusted
            $table->integer('times_seen')->default(0);
            $table->integer('successful_sessions')->default(0);
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('added_at');
            $table->timestamps();
        });
        }
        // â”€â”€â”€ Country Rules â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('country_rules')) {
        Schema::create('country_rules', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 5)->unique();
            $table->string('country_name');
            $table->string('flag_emoji', 10)->nullable();
            $table->enum('action', ['whitelisted', 'not_specified', 'blacklisted'])->default('not_specified');
            $table->timestamps();
        });
        }
        // â”€â”€â”€ IP Session Tracker â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('ip_session_tracker')) {
        Schema::create('ip_session_tracker', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id')->nullable();
            $table->boolean('success')->default(true);
            $table->timestamp('logged_at');
            $table->index(['ip_address', 'user_id']);
        });
        }
        // â”€â”€â”€ Security Audit Log â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
        if (!Schema::hasTable('security_audit_log')) {
        Schema::create('security_audit_log', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // bruteforce_detected|ip_blocked|account_locked|whitelist_added|country_blocked|admin_login_unknown_ip
            $table->string('ip_address', 45)->nullable();
            $table->string('username')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('country_name')->nullable();
            $table->string('action_taken')->nullable();
            $table->text('details')->nullable();
            $table->enum('severity', ['info', 'warning', 'critical'])->default('info');
            $table->timestamp('occurred_at');
            $table->index(['event_type', 'occurred_at']);
            $table->index(['ip_address', 'occurred_at']);
        });
        }    }

    public function down(): void
    {
        Schema::dropIfExists('security_audit_log');
        Schema::dropIfExists('ip_session_tracker');
        Schema::dropIfExists('country_rules');
        Schema::dropIfExists('whitelisted_ips');
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('blocked_accounts');
        Schema::dropIfExists('login_attempts');
    }

};
