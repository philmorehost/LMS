<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use App\Models\Security\BlockedAccount;
use App\Models\Security\BlockedIp;
use App\Models\Security\CountryRule;
use App\Models\Security\LoginAttempt;
use App\Models\Security\SecurityAuditLog;
use App\Models\Security\WhitelistedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BruteForceController extends Controller
{
    /**
     * Main settings page.
     */
    public function index()
    {
        $settings       = $this->getSettings();
        $blockedIps     = BlockedIp::where('is_active', true)->orderByDesc('created_at')->take(10)->get();
        $blockedAccounts = BlockedAccount::where('is_active', true)->orderByDesc('blocked_at')->take(10)->get();
        $whitelistedIps = WhitelistedIp::orderByDesc('times_seen')->take(20)->get();
        $recentLogs     = SecurityAuditLog::orderByDesc('occurred_at')->take(20)->get();

        // Stats
        $stats = [
            'total_blocked_ips'      => BlockedIp::where('is_active', true)->count(),
            'total_blocked_accounts' => BlockedAccount::where('is_active', true)->count(),
            'total_whitelisted'      => WhitelistedIp::count(),
            'total_failed_today'     => LoginAttempt::where('success', false)
                ->where('attempted_at', '>=', now()->startOfDay())->count(),
        ];

        return view('admin.security.bruteforce', compact(
            'settings', 'blockedIps', 'blockedAccounts', 'whitelistedIps', 'recentLogs', 'stats'
        ));
    }

    /**
     * Save bruteforce settings.
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'username_protection_enabled'        => 'boolean',
            'username_period_minutes'            => 'required|integer|min:1|max:1440',
            'username_max_failures'              => 'required|integer|min:1|max:100',
            'username_protect_local_only'        => 'boolean',
            'username_protect_admin'             => 'boolean',
            'ip_protection_enabled'              => 'boolean',
            'ip_period_minutes'                  => 'required|integer|min:1|max:1440',
            'ip_max_failures'                    => 'required|integer|min:1|max:1000',
            'ip_block_at_firewall'               => 'boolean',
            'ip_block_duration'                  => 'required|in:one_day,one_week,one_month,one_year',
            'ip_max_before_one_day'              => 'required|integer|min:1|max:1000',
            'ip_one_day_block_firewall'          => 'boolean',
            'retain_failed_logins_minutes'       => 'required|in:5,10,15,60',
            'notify_admin_on_unknown_ip'         => 'boolean',
            'notify_admin_on_known_netblock'     => 'boolean',
            'notify_admin_on_bruteforce_detect'  => 'boolean',
        ]);

        $keys = [
            'username_protection_enabled', 'username_period_minutes', 'username_max_failures',
            'username_protect_local_only', 'username_protect_admin',
            'ip_protection_enabled', 'ip_period_minutes', 'ip_max_failures',
            'ip_block_at_firewall', 'ip_block_duration', 'ip_max_before_one_day',
            'ip_one_day_block_firewall', 'retain_failed_logins_minutes',
            'notify_admin_on_unknown_ip', 'notify_admin_on_known_netblock',
            'notify_admin_on_bruteforce_detect',
        ];

        foreach ($keys as $key) {
            DB::table('brute_force_settings')->upsert(
                [['key' => $key, 'value' => $request->input($key, false) ? '1' : '0']],
                ['key'],
                ['value']
            );
        }

        // Save string/integer fields properly
        $intKeys = ['username_period_minutes', 'username_max_failures', 'ip_period_minutes', 'ip_max_failures', 'ip_max_before_one_day', 'retain_failed_logins_minutes'];
        foreach ($intKeys as $key) {
            DB::table('brute_force_settings')->upsert(
                [['key' => $key, 'value' => (string) $request->input($key)]],
                ['key'],
                ['value']
            );
        }
        DB::table('brute_force_settings')->upsert(
            [['key' => 'ip_block_duration', 'value' => $request->input('ip_block_duration')]],
            ['key'],
            ['value']
        );

        // Clear settings cache
        Cache::forget('bf_settings');

        return response()->json(['success' => true, 'message' => 'Bruteforce settings saved successfully.']);
    }

    /**
     * Security logs page.
     */
    public function logs(Request $request)
    {
        return view('admin.security.logs');
    }

    /**
     * Security logs data (AJAX/polling).
     */
    public function logsData(Request $request)
    {
        $query = SecurityAuditLog::query();

        if ($request->type)     $query->where('event_type', $request->type);
        if ($request->severity) $query->where('severity', $request->severity);
        if ($request->ip)       $query->where('ip_address', 'like', '%' . $request->ip . '%');
        if ($request->from)     $query->where('occurred_at', '>=', $request->from);
        if ($request->to)       $query->where('occurred_at', '<=', $request->to);

        $logs = $query->orderByDesc('occurred_at')->paginate(50);

        return response()->json($logs);
    }

    /**
     * Get settings from DB with config fallbacks.
     */
    protected function getSettings(): array
    {
        $dbSettings = DB::table('brute_force_settings')->pluck('value', 'key')->toArray();
        return array_merge(config('security.bruteforce', []), $dbSettings);
    }
}
