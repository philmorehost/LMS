<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Installer\InstallerController;

use Illuminate\Support\Facades\Artisan;

Route::get('/seed-demo', function () {
    try {
        // Run database migrations to create any missing tables
        Artisan::call('migrate', ['--force' => true]);
        
        // Run seeders to populate values
        Artisan::call('db:seed', ['--force' => true]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database migrations and demo seeders completed successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

Route::get('/stop-impersonating', [\App\Http\Controllers\Admin\UserController::class, 'stopImpersonation'])->name('users.stop-impersonating');

Route::get('/clear-cache', function () {
    $results = [];
    $error = null;
    
    try {
        Artisan::call('config:clear');
        $results['Configuration Cache'] = 'Cleared';
        
        Artisan::call('cache:clear');
        $results['Application Cache'] = 'Cleared';
        
        Artisan::call('route:clear');
        $results['Route Cache'] = 'Cleared';
        
        Artisan::call('view:clear');
        $results['Compiled Views'] = 'Cleared';
    } catch (\Exception $e) {
        $error = $e->getMessage();
    }

    return response()->html = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Cache System — Clean Operations</title>
        <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
        <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' rel='stylesheet'>
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                font-family: 'Inter', sans-serif;
                background: #09090b;
                color: #f4f4f5;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }
            .card {
                width: 100%;
                max-width: 480px;
                background: #18181b;
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 20px;
                padding: 40px 32px;
                text-align: center;
                box-shadow: 0 10px 40px rgba(0,0,0,0.5);
                position: relative;
                overflow: hidden;
            }
            .card::before {
                content: '';
                position: absolute; top: 0; left: 0; right: 0; height: 4px;
                background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
            }
            .icon-wrapper {
                width: 72px;
                height: 72px;
                background: rgba(99,102,241,0.12);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                color: #a5b4fc;
                margin: 0 auto 24px;
                animation: spinPulse 3s ease-in-out infinite;
            }
            @keyframes spinPulse {
                0%, 100% { transform: scale(1) rotate(0deg); }
                50% { transform: scale(1.05) rotate(180deg); }
            }
            h1 { font-size: 22px; font-weight: 700; margin-bottom: 8px; }
            .subtitle { color: #a1a1aa; font-size: 13px; margin-bottom: 32px; }
            .status-list { text-align: left; list-style: none; margin-bottom: 32px; }
            .status-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 16px;
                background: rgba(255,255,255,0.02);
                border: 1px solid rgba(255,255,255,0.04);
                border-radius: 12px;
                margin-bottom: 8px;
                font-size: 14px;
            }
            .status-label { font-weight: 500; }
            .badge-success { background: rgba(16,185,129,0.15); color: #34d399; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; }
            .badge-error { background: rgba(239,68,68,0.15); color: #fca5a5; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; }
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: 100%;
                padding: 13px;
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                border: none;
                border-radius: 10px;
                color: #fff;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.2s;
            }
            .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        </style>
    </head>
    <body>
        <div class='card'>
            <div class='icon-wrapper'>
                <i class='fa-solid fa-broom'></i>
            </div>
            <h1>System Cache Cleaned</h1>
            <p class='subtitle'>Laravel optimization parameters updated successfully.</p>
            
            <ul class='status-list'>
                " . implode('', array_map(function($key, $val) {
                    return "
                    <li class='status-item'>
                        <span class='status-label'>$key</span>
                        <span class='badge-success'><i class='fa-solid fa-circle-check'></i> $val</span>
                    </li>";
                }, array_keys($results), $results)) . "
                " . ($error ? "
                <li class='status-item' style='border-color: rgba(239,68,68,0.2)'>
                    <span class='status-label' style='color:#fca5a5'>Errors</span>
                    <span class='badge-error'>$error</span>
                </li>" : "") . "
            </ul>
            
            <a href='" . url('/') . "' class='btn'><i class='fa-solid fa-arrow-left'></i> Back to Homepage</a>
        </div>
    </body>
    </html>
    ";
});

/*
|--------------------------------------------------------------------------
| Installer Routes (only accessible when not installed)
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('installer.')->group(function () {
    Route::get('/', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('/step/1', [InstallerController::class, 'welcome'])->name('welcome');
    Route::post('/verify-license', [InstallerController::class, 'verifyLicense'])->name('verify-license');
    Route::get('/step/2', [InstallerController::class, 'database'])->name('database');
    Route::post('/test-connection', [InstallerController::class, 'testConnection'])->name('test-connection');
    Route::post('/install-database', [InstallerController::class, 'installDatabase'])->name('install-database');
    Route::get('/step/3', [InstallerController::class, 'adminSetup'])->name('admin-setup');
    Route::post('/save-admin-setup', [InstallerController::class, 'saveAdminSetup'])->name('save-admin-setup');
    Route::get('/step/4', [InstallerController::class, 'complete'])->name('complete');
});

/*
|--------------------------------------------------------------------------
| Root redirect: if not installed → installer; if installed → homepage
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!File::exists(storage_path('installed'))) {
        return redirect()->route('installer.welcome');
    }
    return app(\App\Http\Controllers\Frontend\PageController::class)->home();
})->name('home');

/*
|--------------------------------------------------------------------------
| Frontend Routes (Public)
|--------------------------------------------------------------------------
*/
Route::group([], function () {

    // Course browsing
    Route::get('/courses', [\App\Http\Controllers\Frontend\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{slug}', [\App\Http\Controllers\Frontend\CourseController::class, 'show'])->name('courses.show');

    // Instructors
    Route::get('/instructors', [\App\Http\Controllers\Frontend\InstructorController::class, 'index'])->name('instructors.index');
    Route::get('/instructors/{slug}', [\App\Http\Controllers\Frontend\InstructorController::class, 'show'])->name('instructors.show');

    // Blog
    Route::get('/blog', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('blog.show');

    // CMS pages
    Route::get('/page/{slug}', [\App\Http\Controllers\Frontend\PageController::class, 'show'])->name('pages.show');
    Route::get('/about', [\App\Http\Controllers\Frontend\PageController::class, 'about'])->name('about');
    Route::get('/contact', [\App\Http\Controllers\Frontend\PageController::class, 'contact'])->name('contact');
    Route::post('/contact', [\App\Http\Controllers\Frontend\PageController::class, 'sendContact'])->name('contact.send');
    Route::get('/faq', [\App\Http\Controllers\Frontend\PageController::class, 'faq'])->name('faq');

    // Certificate verification
    Route::get('/certificate/verify/{uid}', [\App\Http\Controllers\Frontend\CertificateController::class, 'verify'])->name('certificate.verify');

    // SEO
    Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SeoController::class, 'sitemap'])->name('sitemap');
    Route::get('/robots.txt', [\App\Http\Controllers\Frontend\SeoController::class, 'robots'])->name('robots');

    // Newsletter
    Route::post('/newsletter/subscribe', [\App\Http\Controllers\Frontend\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\Frontend\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showForm'])->name('login')->middleware('guest');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register')->middleware('guest');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('guest');
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordController::class, 'showForgotForm'])->name('password.request')->middleware('guest');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\PasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordController::class, 'reset'])->name('password.update');
    Route::get('/verify-email/{token}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])->name('email.verify');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['web', 'auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/courses', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses');
    Route::get('/courses/{id}/learn', [\App\Http\Controllers\Student\CourseController::class, 'learn'])->name('courses.learn');
    Route::post('/lessons/{id}/progress', [\App\Http\Controllers\Student\CourseController::class, 'updateProgress'])->name('lessons.progress');
    Route::get('/wishlist', [\App\Http\Controllers\Student\WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle/{courseId}', [\App\Http\Controllers\Student\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/cart', [\App\Http\Controllers\Student\CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{courseId}', [\App\Http\Controllers\Student\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [\App\Http\Controllers\Student\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [\App\Http\Controllers\Student\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\Student\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/paystack/callback', [\App\Http\Controllers\Student\CheckoutController::class, 'paystackCallback'])->name('checkout.paystack.callback');
    Route::post('/checkout/apply-coupon', [\App\Http\Controllers\Student\CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
    Route::get('/enrollments', [\App\Http\Controllers\Student\EnrollmentController::class, 'index'])->name('enrollments');
    Route::get('/certificates', [\App\Http\Controllers\Student\CertificateController::class, 'index'])->name('certificates');
    Route::get('/certificates/{id}/download', [\App\Http\Controllers\Student\CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [\App\Http\Controllers\Student\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/password', [\App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/delete', [\App\Http\Controllers\Student\ProfileController::class, 'delete'])->name('profile.delete');
    Route::post('/become-instructor', [\App\Http\Controllers\Student\ProfileController::class, 'becomeInstructor'])->name('become-instructor');
});

/*
|--------------------------------------------------------------------------
| Payment Callback Routes (public, signed URLs)
|--------------------------------------------------------------------------
*/
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/success', [\App\Http\Controllers\Payment\PaymentController::class, 'success'])->name('success');
    Route::get('/cancel', [\App\Http\Controllers\Payment\PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/stripe/webhook', [\App\Http\Controllers\Payment\StripeController::class, 'webhook'])->name('stripe.webhook');
    Route::post('/paypal/webhook', [\App\Http\Controllers\Payment\PaypalController::class, 'webhook'])->name('paypal.webhook');
    Route::get('/paypal/callback', [\App\Http\Controllers\Payment\PaypalController::class, 'callback'])->name('paypal.callback');
});

/*
|--------------------------------------------------------------------------
| Instructor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('instructor')->name('instructor.')->middleware(['web', 'auth', 'role:instructor,admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/courses', \App\Http\Controllers\Instructor\CourseController::class);
    Route::post('/subscriptions', [\App\Http\Controllers\Instructor\SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::resource('/courses.modules', \App\Http\Controllers\Instructor\ModuleController::class)->shallow();
    Route::resource('/courses.modules.lessons', \App\Http\Controllers\Instructor\LessonController::class)->shallow();
    Route::get('/earnings', [\App\Http\Controllers\Instructor\EarningsController::class, 'index'])->name('earnings');
    Route::get('/withdrawals', [\App\Http\Controllers\Instructor\WithdrawalController::class, 'index'])->name('withdrawals');
    Route::post('/withdrawals', [\App\Http\Controllers\Instructor\WithdrawalController::class, 'request'])->name('withdrawals.request');
    Route::get('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/enrollments', [\App\Http\Controllers\Instructor\EnrollmentController::class, 'index'])->name('enrollments');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Courses
    Route::resource('/courses', \App\Http\Controllers\Admin\CourseController::class);
    Route::post('/courses/{id}/approve', [\App\Http\Controllers\Admin\CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{id}/reject', [\App\Http\Controllers\Admin\CourseController::class, 'reject'])->name('courses.reject');
    Route::post('/courses/{id}/approve-subscription', [\App\Http\Controllers\Admin\CourseController::class, 'approveSubscription'])->name('courses.approve-subscription');
    Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('/course-levels', \App\Http\Controllers\Admin\CourseLevelController::class);
    Route::resource('/course-languages', \App\Http\Controllers\Admin\CourseLanguageController::class);

    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}/update', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/toggle-block', [\App\Http\Controllers\Admin\UserController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/impersonate', [\App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');

    // Finance
    Route::resource('/coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/enrollments', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments/{id}/approve', [\App\Http\Controllers\Admin\EnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{id}/reject', [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::get('/withdrawals', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals/{id}/approve', [\App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [\App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Blog
    Route::resource('/blog/categories', \App\Http\Controllers\Admin\BlogCategoryController::class, ['as' => 'blog']);
    Route::resource('/blog/posts', \App\Http\Controllers\Admin\BlogPostController::class, ['as' => 'blog']);
    Route::get('/blog/comments', [\App\Http\Controllers\Admin\BlogCommentController::class, 'index'])->name('blog.comments');
    Route::post('/blog/comments/{id}/approve', [\App\Http\Controllers\Admin\BlogCommentController::class, 'approve'])->name('blog.comments.approve');

    // CMS
    Route::resource('/pages', \App\Http\Controllers\Admin\CmsPageController::class);
    Route::resource('/faqs', \App\Http\Controllers\Admin\FaqController::class);
    Route::resource('/testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
    Route::resource('/contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);

    // Newsletter
    Route::get('/newsletter', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletter.index');
    Route::post('/newsletter/send', [\App\Http\Controllers\Admin\NewsletterController::class, 'send'])->name('newsletter.send');

    // Certificates
    Route::resource('/certificate-templates', \App\Http\Controllers\Admin\CertificateTemplateController::class);

    // Reports
    Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/enrollments', [\App\Http\Controllers\Admin\ReportController::class, 'enrollments'])->name('reports.enrollments');
    Route::get('/reports/instructors', [\App\Http\Controllers\Admin\ReportController::class, 'instructors'])->name('reports.instructors');

    // Security (Anti-Bruteforce)
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/bruteforce', [\App\Http\Controllers\Admin\Security\BruteForceController::class, 'index'])->name('bruteforce');
        Route::post('/bruteforce/settings', [\App\Http\Controllers\Admin\Security\BruteForceController::class, 'saveSettings'])->name('bruteforce.settings');
        Route::get('/logs', [\App\Http\Controllers\Admin\Security\BruteForceController::class, 'logs'])->name('logs');
        Route::get('/logs/data', [\App\Http\Controllers\Admin\Security\BruteForceController::class, 'logsData'])->name('logs.data');
        Route::resource('/blocked-ips', \App\Http\Controllers\Admin\Security\BlockedIpController::class);
        Route::resource('/blocked-accounts', \App\Http\Controllers\Admin\Security\BlockedAccountController::class);
        Route::resource('/whitelisted-ips', \App\Http\Controllers\Admin\Security\WhitelistedIpController::class);
        Route::get('/countries', [\App\Http\Controllers\Admin\Security\CountryRuleController::class, 'index'])->name('countries');
        Route::post('/countries/{code}', [\App\Http\Controllers\Admin\Security\CountryRuleController::class, 'update'])->name('countries.update');
        Route::post('/countries/bulk', [\App\Http\Controllers\Admin\Security\CountryRuleController::class, 'bulkUpdate'])->name('countries.bulk');
    });

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/logo', [\App\Http\Controllers\Admin\SettingController::class, 'updateLogo'])->name('settings.logo');
    Route::get('/settings/email', [\App\Http\Controllers\Admin\SettingController::class, 'email'])->name('settings.email');
    Route::post('/settings/email', [\App\Http\Controllers\Admin\SettingController::class, 'updateEmail'])->name('settings.email.update');
    Route::get('/settings/email-templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])->name('settings.email-templates');
    Route::put('/settings/email-templates/{id}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])->name('settings.email-templates.update');
    Route::get('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'seo'])->name('settings.seo');
    Route::post('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'updateSeo'])->name('settings.seo.update');
    Route::get('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'payment'])->name('settings.payment');
    Route::post('/settings/payment', [\App\Http\Controllers\Admin\SettingController::class, 'updatePayment'])->name('settings.payment.update');
    Route::get('/settings/social', [\App\Http\Controllers\Admin\SettingController::class, 'social'])->name('settings.social');
    Route::post('/settings/social', [\App\Http\Controllers\Admin\SettingController::class, 'updateSocial'])->name('settings.social.update');
    Route::resource('/languages', \App\Http\Controllers\Admin\LanguageController::class);
    Route::resource('/currencies', \App\Http\Controllers\Admin\CurrencyController::class);
    Route::post('/settings/theme', [\App\Http\Controllers\Admin\SettingController::class, 'updateTheme'])->name('settings.theme');
    Route::post('/settings/maintenance', [\App\Http\Controllers\Admin\SettingController::class, 'toggleMaintenance'])->name('settings.maintenance');
    Route::post('/settings/cache-clear', [\App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.cache-clear');
    Route::post('/settings/db-clear', [\App\Http\Controllers\Admin\SettingController::class, 'dbClear'])->name('settings.db-clear');
});
