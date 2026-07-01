<?php

return [
    'name' => env('LMS_NAME', 'Educve LMS'),
    'version' => '1.0.0',
    'installed' => env('LMS_INSTALLED', false),
    'license_key' => env('LMS_LICENSE_KEY', ''),
    'license_api' => env('LMS_LICENSE_API', 'https://manager.pmhserver.name.ng/api-docs.php'),
    'favicon_letter' => env('LMS_FAVICON_LETTER', 'L'),
    'favicon_bg_color' => env('LMS_FAVICON_BG', '#6366f1'),
    'favicon_text_color' => env('LMS_FAVICON_TEXT', '#ffffff'),
    'roles' => ['admin' => 'admin', 'instructor' => 'instructor', 'student' => 'student'],
    'course' => ['approval_required' => true, 'per_page' => 12, 'featured_limit' => 8],
    'commission' => ['default_rate' => env('LMS_COMMISSION_RATE', 20)],
    'certificate' => ['enabled' => true, 'font' => 'DejaVu Sans', 'verification_url' => env('APP_URL') . '/certificate/verify/'],
    'seo' => ['title_separator' => ' | ', 'generate_sitemap' => true, 'sitemap_ping_google' => true, 'sitemap_ping_bing' => true, 'generate_llmstxt' => true, 'ai_friendly_robots' => true],
    'performance' => ['redis_page_cache' => env('LMS_PAGE_CACHE', false), 'page_cache_ttl' => 3600, 'webp_conversion' => true, 'lazy_loading_images' => true],
    'social' => ['google' => env('GOOGLE_LOGIN_ENABLED', false), 'facebook' => env('FACEBOOK_LOGIN_ENABLED', false)],
    'integrations' => ['google_analytics' => env('GOOGLE_ANALYTICS_ID', ''), 'google_recaptcha' => env('GOOGLE_RECAPTCHA_ENABLED', false), 'recaptcha_site_key' => env('RECAPTCHA_SITE_KEY', ''), 'recaptcha_secret' => env('RECAPTCHA_SECRET_KEY', ''), 'facebook_pixel' => env('FACEBOOK_PIXEL_ID', ''), 'tawk_to' => env('TAWK_TO_PROPERTY_ID', ''), 'zoom_api_key' => env('ZOOM_API_KEY', ''), 'zoom_api_secret' => env('ZOOM_API_SECRET', '')],
];
