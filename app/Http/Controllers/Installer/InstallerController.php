<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class InstallerController extends Controller
{
    /**
     * Required PHP version.
     */
    protected $requiredPhpVersion = '8.2.0';

    /**
     * Required PHP extensions.
     */
    protected $requiredExtensions = [
        'pdo', 'pdo_mysql', 'mbstring', 'openssl', 'curl', 'gd',
        'zip', 'xml', 'bcmath', 'fileinfo', 'exif', 'tokenizer', 'json',
    ];

    /**
     * Required writable paths.
     */
    protected $requiredWritablePaths = [
        'storage/app',
        'storage/framework',
        'storage/logs',
        'bootstrap/cache',
    ];

    // ─── Step 1: Welcome & Requirements ──────────────────────────────────────

    public function welcome()
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        $phpVersion    = PHP_VERSION;
        $phpOk         = version_compare($phpVersion, $this->requiredPhpVersion, '>=');
        $extensions    = $this->checkExtensions();
        $permissions   = $this->checkPermissions();
        $allExtOk      = !in_array(false, array_column($extensions, 'ok'));
        $allPermsOk    = !in_array(false, array_column($permissions, 'writable'));
        $canProceed    = $phpOk && $allExtOk && $allPermsOk;

        return view('installer.step1-welcome', compact(
            'phpVersion', 'phpOk', 'extensions', 'permissions', 'allExtOk', 'allPermsOk', 'canProceed'
        ));
    }

    public function verifyLicense(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|min:10',
        ]);

        $licenseKey = $request->input('license_key');
        $apiUrl     = config('lms.license_api');

        try {
            $response = Http::timeout(10)->post($apiUrl, [
                'action'      => 'verify',
                'license_key' => $licenseKey,
                'domain'      => $request->getHost(),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'valid' || $data['valid'] === true) {
                    session(['installer_license_key' => $licenseKey, 'installer_license_verified' => true]);
                    return response()->json(['success' => true, 'message' => 'License verified successfully!']);
                }
            }

            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Invalid license key. Please check and try again.',
            ], 422);
        } catch (\Exception $e) {
            // If API is unreachable, store key and allow proceeding (offline mode)
            session(['installer_license_key' => $licenseKey, 'installer_license_verified' => true]);
            return response()->json([
                'success' => true,
                'message' => 'License key saved. (Verification server unreachable — will re-verify on first admin login.)',
            ]);
        }
    }

    // ─── Step 2: Database ────────────────────────────────────────────────────

    public function database()
    {
        if ($this->isInstalled()) return redirect('/');
        if (!session('installer_license_verified')) return redirect()->route('installer.welcome');

        return view('installer.step2-database');
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_database};charset=utf8mb4",
                $request->db_username,
                $request->db_password ?? '',
                [\PDO::ATTR_TIMEOUT => 5]
            );
            return response()->json(['success' => true, 'message' => 'Database connection successful!']);
        } catch (\PDOException $e) {
            return response()->json(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()], 422);
        }
    }

    public function installDatabase(Request $request)
    {
        $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        // Write DB config to .env
        $this->updateEnv([
            'DB_HOST'     => $request->db_host,
            'DB_PORT'     => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password ?? '',
        ]);

        // Explicitly set the configuration in runtime to bypass config cache issues
        config([
            'database.connections.mysql.host'     => $request->db_host,
            'database.connections.mysql.port'     => $request->db_port,
            'database.connections.mysql.database' => $request->db_database,
            'database.connections.mysql.username' => $request->db_username,
            'database.connections.mysql.password' => $request->db_password ?? '',
        ]);

        // Reconnect DB with new runtime configuration
        DB::purge('mysql');
        DB::reconnect('mysql');

        // Clear config cache
        Artisan::call('config:clear');

        try {
            // Disable foreign key checks to prevent drop table conflicts
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            
            // Fetch all tables in database
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $keyName = "Tables_in_" . $dbName;
            
            // Drop each table individually
            foreach ($tables as $table) {
                if (isset($table->$keyName)) {
                    $tName = $table->$keyName;
                    DB::statement("DROP TABLE IF EXISTS `$tName` CASCADE");
                }
            }
            
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            // Run migrations clean
            Artisan::call('migrate', ['--force' => true]);
            $output = Artisan::output();

            // Run seeders
            Artisan::call('db:seed', ['--force' => true]);

            session(['installer_db_configured' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Database tables created successfully!',
                'output'  => $output,
            ]);
        } catch (\Throwable $e) {
            // Fallback: try to reset FK check
            try { DB::statement('SET FOREIGN_KEY_CHECKS = 1'); } catch(\Exception $ex) {}
            
            return response()->json([
                'success' => false,
                'message' => 'Migration/Seeding failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(),
            ], 500);
        }
    }

    // ─── Step 3: Admin Account ────────────────────────────────────────────────

    public function adminSetup()
    {
        if ($this->isInstalled()) return redirect('/');
        if (!session('installer_db_configured')) return redirect()->route('installer.database');

        return view('installer.step3-admin');
    }

    public function saveAdminSetup(Request $request)
    {
        $request->validate([
            'admin_name'     => 'required|string|max:255',
            'admin_email'    => 'required|email|max:255',
            'admin_password' => ['required', 'confirmed', Password::min(8)],
            'site_title'     => 'required|string|max:255',
            'site_url'       => 'required|url',
            'currency'       => 'required|string|max:10',
            'language'       => 'required|string|max:10',
        ]);

        try {
            // Create admin user
            $admin = \App\Models\User::create([
                'name'              => $request->admin_name,
                'email'             => $request->admin_email,
                'password'          => Hash::make($request->admin_password),
                'role'              => 'admin',
                'status'            => 'active',
                'email_verified_at' => now(),
            ]);

            // Generate favicon letter
            $faviconLetter = strtoupper(mb_substr($request->site_title, 0, 1));
            $this->generateFaviconSvg($faviconLetter);

            // Update .env
            $this->updateEnv([
                'APP_NAME'            => '"' . $request->site_title . '"',
                'APP_URL'             => $request->site_url,
                'LMS_INSTALLED'       => 'true',
                'LMS_LICENSE_KEY'     => session('installer_license_key', ''),
                'LMS_NAME'            => '"' . $request->site_title . '"',
                'LMS_FAVICON_LETTER'  => $faviconLetter,
            ]);

            // Save core settings to DB
            $this->saveSettings([
                'site_title'       => $request->site_title,
                'site_url'         => $request->site_url,
                'default_currency' => $request->currency,
                'default_language' => $request->language,
                'favicon_letter'   => $faviconLetter,
            ]);

            // Generate install lock
            File::put(storage_path('installed'), 'installed_at=' . now()->toISOString());

            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            session(['installer_completed' => true]);

            return response()->json(['success' => true, 'message' => 'Admin account created successfully!']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
            ], 500);
        }
    }

    // ─── Step 4: Complete ─────────────────────────────────────────────────────

    public function complete()
    {
        if (!session('installer_completed') && !$this->isInstalled()) {
            return redirect()->route('installer.welcome');
        }

        // Clear session
        session()->forget([
            'installer_license_key', 'installer_license_verified',
            'installer_db_configured', 'installer_completed'
        ]);

        return view('installer.step4-complete', [
            'admin_url' => url('/admin/dashboard'),
            'login_url' => url('/admin/login'),
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    protected function isInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }

    protected function checkExtensions(): array
    {
        return array_map(function ($ext) {
            return ['name' => $ext, 'ok' => extension_loaded($ext)];
        }, $this->requiredExtensions);
    }

    protected function checkPermissions(): array
    {
        return array_map(function ($path) {
            $fullPath = base_path($path);
            return [
                'path'     => $path,
                'writable' => is_writable($fullPath),
            ];
        }, $this->requiredWritablePaths);
    }

    protected function updateEnv(array $data): void
    {
        $envPath = base_path('.env');
        $content = File::get($envPath);

        foreach ($data as $key => $value) {
            // Replace existing key or append
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }

        File::put($envPath, $content);
    }

    protected function generateFaviconSvg(string $letter): void
    {
        $bg   = config('lms.favicon_bg_color', '#6366f1');
        $text = config('lms.favicon_text_color', '#ffffff');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
  <rect width="100" height="100" rx="18" fill="{$bg}"/>
  <text x="50" y="72" font-family="Arial, sans-serif" font-size="58" font-weight="bold"
        fill="{$text}" text-anchor="middle">{$letter}</text>
</svg>
SVG;
        File::put(public_path('favicon.svg'), $svg);
        File::put(public_path('favicon.ico'), $svg); // Modern browsers support SVG favicons
    }

    protected function saveSettings(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            DB::table('settings')->upsert(
                [['key' => $key, 'value' => $value, 'group' => $group]],
                ['key'],
                ['value']
            );
        }
    }
}
