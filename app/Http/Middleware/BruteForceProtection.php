<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Security\LoginAttempt;
use App\Models\Security\BlockedAccount;
use App\Models\Security\BlockedIp;
use App\Models\Security\WhitelistedIp;
use App\Models\Security\CountryRule;
use App\Models\Security\SecurityAuditLog;
use App\Models\Security\IpSessionTracker;
use App\Mail\Security\BruteForceDetectedMail;
use App\Mail\Security\AdminLoginUnknownIpMail;

class BruteForceProtection
{
    /**
     * Settings cache TTL in seconds.
     */
    protected int $settingsCacheTtl = 300;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $ip       = $this->getRealIp($request);
        $username = $request->input('email') ?? $request->input('username', '');

        // 1. Check if IP is whitelisted (skip all checks)
        if ($this->isIpWhitelisted($ip)) {
            return $next($request);
        }

        // 2. Check country blacklist
        $countryBlock = $this->checkCountryBlock($ip);
        if ($countryBlock) {
            return $this->blockResponse('Your country is blocked from accessing this service.', 403);
        }

        // 3. Check if IP is currently blocked
        if ($this->isIpBlocked($ip)) {
            $this->logAudit('ip_blocked_access', $ip, $username, 'critical',
                'Blocked IP attempted access');
            return $this->blockResponse('Your IP address has been blocked due to suspicious activity.', 403);
        }

        // 4. Check if account is locked (username-based)
        $settings = $this->getSettings();
        if ($settings['username_protection_enabled'] && $username) {
            if ($this->isAccountLocked($username)) {
                $this->logAudit('account_locked_access', $ip, $username, 'warning',
                    "Locked account '{$username}' attempted access");
                return $this->blockResponse(
                    'Your account has been temporarily locked due to multiple failed login attempts. Please try again later or reset your password.',
                    423
                );
            }
        }

        // 5. Check IP attempt count
        if ($settings['ip_protection_enabled']) {
            $ipFailures = $this->countRecentFailures('ip', $ip, $settings['ip_period_minutes']);
            if ($ipFailures >= $settings['ip_max_failures']) {
                $this->blockIp($ip, $settings['ip_block_duration'], 'system',
                    "Exceeded max login failures ({$ipFailures})");
                $this->logAudit('ip_auto_blocked', $ip, $username, 'critical',
                    "IP blocked after {$ipFailures} failures");
                $this->notifyAdminBruteforce($ip, $username, $ipFailures, 'ip');
                return $this->blockResponse(
                    'Too many failed login attempts from your IP address. Access has been temporarily blocked.',
                    429
                );
            }
        }

        // 6. Check username attempt count
        if ($settings['username_protection_enabled'] && $username) {
            // Skip locking admin/administrator accounts if not configured
            $isAdminUser = in_array(strtolower($username), ['admin', 'administrator']);
            if ($isAdminUser && !$settings['username_protect_admin']) {
                // Skip username locking for admin/administrator accounts
            } else {
                $usernameFailures = $this->countRecentFailures('username', $username, $settings['username_period_minutes']);
                if ($usernameFailures >= $settings['username_max_failures']) {
                    $this->lockAccount($username, $settings['username_period_minutes']);
                    $this->logAudit('account_auto_locked', $ip, $username, 'critical',
                        "Account '{$username}' locked after {$usernameFailures} failures");
                    $this->notifyAdminBruteforce($ip, $username, $usernameFailures, 'username');
                    return $this->blockResponse(
                        'Your account has been temporarily locked due to multiple failed login attempts.',
                        423
                    );
                }
            }
        }

        // Process the login request
        $response = $next($request);

        // 7. Post-login: record attempt
        $success = $this->wasLoginSuccessful($response);
        $this->recordAttempt($ip, $username, $success, $request);

        // 8. On success: handle whitelist tracking and admin notifications
        if ($success) {
            $this->handleSuccessfulLogin($ip, $username, $request);
        }

        return $response;
    }

    /**
     * Get the real IP address considering proxies.
     */
    protected function getRealIp(Request $request): string
    {
        $trustedProxies = config('trustedproxy.proxies', []);
        if (!empty($trustedProxies)) {
            foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CF_CONNECTING_IP'] as $header) {
                if ($ip = $request->server($header)) {
                    return trim(explode(',', $ip)[0]);
                }
            }
        }
        return $request->ip();
    }

    /**
     * Get bruteforce settings (cached).
     */
    protected function getSettings(): array
    {
        return Cache::remember('bf_settings', $this->settingsCacheTtl, function () {
            $defaults = config('security.bruteforce');
            $dbSettings = DB::table('brute_force_settings')->pluck('value', 'key')->toArray();
            return array_merge($defaults, $dbSettings);
        });
    }

    /**
     * Check if IP is whitelisted.
     */
    protected function isIpWhitelisted(string $ip): bool
    {
        return Cache::remember("bf_whitelist_{$ip}", 300, function () use ($ip) {
            return WhitelistedIp::where('ip_address', $ip)->exists()
                || $this->isIpInCidrWhitelist($ip);
        });
    }

    /**
     * Check if IP falls in a CIDR-whitelisted range.
     */
    protected function isIpInCidrWhitelist(string $ip): bool
    {
        $cidrs = Cache::remember('bf_cidr_whitelist', 600, function () {
            return WhitelistedIp::whereNotNull('cidr_range')->pluck('cidr_range')->toArray();
        });

        foreach ($cidrs as $cidr) {
            if ($this->ipInCidr($ip, $cidr)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if country is blocked.
     */
    protected function checkCountryBlock(string $ip): bool
    {
        $countryCode = $this->getCountryCode($ip);
        if (!$countryCode) return false;

        return Cache::remember("bf_country_{$countryCode}", 3600, function () use ($countryCode) {
            $rule = CountryRule::where('country_code', $countryCode)->first();
            return $rule && $rule->action === 'blacklisted';
        });
    }

    /**
     * Check if IP is currently blocked.
     */
    protected function isIpBlocked(string $ip): bool
    {
        return BlockedIp::where('ip_address', $ip)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '>', now());
            })
            ->exists();
    }

    /**
     * Check if account is locked.
     */
    protected function isAccountLocked(string $identifier): bool
    {
        return BlockedAccount::where(function ($q) use ($identifier) {
                $q->where('username', $identifier)
                  ->orWhere('email', $identifier);
            })
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('blocked_until')
                    ->orWhere('blocked_until', '>', now());
            })
            ->exists();
    }

    /**
     * Count recent failed login attempts for an identifier.
     */
    protected function countRecentFailures(string $type, string $identifier, int $periodMinutes): int
    {
        $since = now()->subMinutes($periodMinutes);

        return Cache::remember("bf_failures_{$type}_{$identifier}", 60, function () use ($type, $identifier, $since) {
            return LoginAttempt::where('identifier_type', $type)
                ->where('identifier', $identifier)
                ->where('success', false)
                ->where('attempted_at', '>=', $since)
                ->count();
        });
    }

    /**
     * Record a login attempt.
     */
    protected function recordAttempt(string $ip, string $username, bool $success, Request $request): void
    {
        $retain = (int) ($this->getSettings()['retain_failed_logins_minutes'] ?? 10);

        $attempt = LoginAttempt::create([
            'identifier_type' => 'both',
            'identifier'      => $ip,
            'ip_address'      => $ip,
            'username'        => $username,
            'user_agent'      => substr($request->userAgent() ?? '', 0, 500),
            'country_code'    => $this->getCountryCode($ip),
            'country_name'    => $this->getCountryName($ip),
            'success'         => $success,
            'attempted_at'    => now(),
        ]);

        // Clear relevant caches
        Cache::forget("bf_failures_ip_{$ip}");
        Cache::forget("bf_failures_username_{$username}");

        // Prune old failed attempts beyond retention period
        if (!$success) {
            LoginAttempt::where('success', false)
                ->where('attempted_at', '<', now()->subMinutes($retain))
                ->limit(500)
                ->delete();
        }
    }

    /**
     * Handle a successful login.
     */
    protected function handleSuccessfulLogin(string $ip, string $username, Request $request): void
    {
        $settings = $this->getSettings();

        // Track IP session for auto-whitelisting
        IpSessionTracker::create([
            'ip_address' => $ip,
            'user_id'    => auth()->id(),
            'session_id' => session()->getId(),
            'success'    => true,
            'logged_at'  => now(),
        ]);

        // Count distinct successful sessions from this IP
        $threshold = (int) ($settings['auto_whitelist_threshold'] ?? 5);
        $successCount = IpSessionTracker::where('ip_address', $ip)
            ->where('success', true)
            ->distinct('session_id')
            ->count('session_id');

        if ($successCount >= $threshold) {
            WhitelistedIp::updateOrCreate(
                ['ip_address' => $ip],
                [
                    'recognized_auto'     => true,
                    'king_status'         => true,
                    'times_seen'          => $successCount,
                    'successful_sessions' => $successCount,
                    'added_at'            => now(),
                ]
            );

            Cache::forget("bf_whitelist_{$ip}");

            $this->logAudit('ip_auto_whitelisted', $ip, $username, 'info',
                "IP auto-whitelisted after {$successCount} successful sessions");
        }

        // Notify admin if this is an admin login from unknown IP
        if ($settings['notify_admin_on_unknown_ip'] && $this->isAdminUser()) {
            $isWhitelisted = $this->isIpWhitelisted($ip);
            if (!$isWhitelisted) {
                $this->notifyAdminUnknownIp($ip, $username, $request);
            }
        }
    }

    /**
     * Block an IP address.
     */
    protected function blockIp(string $ip, string $duration, string $blockedBy, string $reason): void
    {
        $blockedUntil = match ($duration) {
            'one_day'   => now()->addDay(),
            'one_week'  => now()->addWeek(),
            'one_month' => now()->addMonth(),
            'one_year'  => now()->addYear(),
            default     => now()->addDay(),
        };

        BlockedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'country_code' => $this->getCountryCode($ip),
                'country_name' => $this->getCountryName($ip),
                'blocked_by'   => $blockedBy,
                'block_type'   => $duration,
                'reason'       => $reason,
                'blocked_at'   => now(),
                'blocked_until' => $blockedUntil,
                'is_active'    => true,
            ]
        );

        Cache::forget("bf_whitelist_{$ip}");
    }

    /**
     * Lock a user account.
     */
    protected function lockAccount(string $identifier, int $periodMinutes): void
    {
        BlockedAccount::updateOrCreate(
            ['username' => $identifier],
            [
                'blocked_by'    => 'system',
                'reason'        => 'Exceeded maximum login failures',
                'blocked_at'    => now(),
                'blocked_until' => now()->addMinutes($periodMinutes),
                'is_active'     => true,
            ]
        );
    }

    /**
     * Determine if the last login response was successful.
     */
    protected function wasLoginSuccessful($response): bool
    {
        $status = $response->getStatusCode();
        // Successful login redirects (302/301) or JSON 200
        return in_array($status, [200, 302, 301]) && !session()->has('errors');
    }

    /**
     * Check if the currently authenticated user is an admin.
     */
    protected function isAdminUser(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Return a blocked access response.
     */
    protected function blockResponse(string $message, int $status = 403)
    {
        if (request()->expectsJson()) {
            return response()->json(['message' => $message, 'blocked' => true], $status);
        }

        return response()->view('errors.blocked', [
            'message' => $message,
            'status'  => $status,
        ], $status);
    }

    /**
     * Log a security audit event.
     */
    protected function logAudit(
        string $eventType,
        string $ip,
        string $username,
        string $severity,
        string $details
    ): void {
        SecurityAuditLog::create([
            'event_type'   => $eventType,
            'ip_address'   => $ip,
            'username'     => $username,
            'country_code' => $this->getCountryCode($ip),
            'country_name' => $this->getCountryName($ip),
            'action_taken' => $eventType,
            'details'      => $details,
            'severity'     => $severity,
            'occurred_at'  => now(),
        ]);
    }

    /**
     * Notify admin about brute force detection.
     */
    protected function notifyAdminBruteforce(string $ip, string $username, int $failCount, string $type): void
    {
        $settings = $this->getSettings();
        if (!($settings['notify_admin_on_bruteforce_detect'] ?? false)) return;

        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->queue(new BruteForceDetectedMail($ip, $username, $failCount, $type));
        } catch (\Exception $e) {
            Log::error('BruteForce notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Notify admin about admin login from unknown IP.
     */
    protected function notifyAdminUnknownIp(string $ip, string $username, Request $request): void
    {
        try {
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->queue(new AdminLoginUnknownIpMail($ip, $username, $request));
        } catch (\Exception $e) {
            Log::error('AdminLogin notification failed: ' . $e->getMessage());
        }
    }

    /**
     * Get country code from IP using a free GeoIP service.
     */
    protected function getCountryCode(string $ip): ?string
    {
        if ($ip === '127.0.0.1' || $ip === '::1') return 'LO'; // localhost
        return Cache::remember("geoip_country_{$ip}", 86400, function () use ($ip) {
            try {
                $response = file_get_contents("https://ip-api.com/json/{$ip}?fields=countryCode", false,
                    stream_context_create(['http' => ['timeout' => 3]]));
                $data = json_decode($response, true);
                return $data['countryCode'] ?? null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * Get country name from IP.
     */
    protected function getCountryName(string $ip): ?string
    {
        if ($ip === '127.0.0.1' || $ip === '::1') return 'Localhost';
        return Cache::remember("geoip_name_{$ip}", 86400, function () use ($ip) {
            try {
                $response = file_get_contents("https://ip-api.com/json/{$ip}?fields=country", false,
                    stream_context_create(['http' => ['timeout' => 3]]));
                $data = json_decode($response, true);
                return $data['country'] ?? null;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    /**
     * Check if an IP is in a CIDR range.
     */
    protected function ipInCidr(string $ip, string $cidr): bool
    {
        [$subnet, $bits] = explode('/', $cidr) + [null, 32];
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip      = ip2long($ip);
            $subnet  = ip2long($subnet);
            $mask    = -1 << (32 - (int) $bits);
            return ($ip & $mask) === ($subnet & $mask);
        }
        return false;
    }
}
