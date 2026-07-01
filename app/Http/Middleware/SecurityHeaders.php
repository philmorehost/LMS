<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', config('security.headers.x_frame_options', 'DENY'));

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', config('security.headers.x_content_type_options', 'nosniff'));

        // X-XSS-Protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', config('security.headers.x_xss_protection', '1; mode=block'));

        // Referrer-Policy
        $response->headers->set('Referrer-Policy', config('security.headers.referrer_policy', 'strict-origin-when-cross-origin'));

        // Permissions-Policy
        $response->headers->set('Permissions-Policy', config('security.headers.permissions_policy', 'camera=(), microphone=(), geolocation=()'));

        // HSTS (only on HTTPS)
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', config('security.headers.hsts', 'max-age=31536000; includeSubDomains; preload'));
        }

        // Content-Security-Policy
        if (config('security.headers.csp_enabled', false)) {
            $nonce = base64_encode(random_bytes(16));
            $request->attributes->set('csp_nonce', $nonce);

            $csp = "default-src 'self'; "
                . "script-src 'self' 'nonce-{$nonce}' https://www.google.com https://www.gstatic.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; "
                . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net; "
                . "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
                . "img-src 'self' data: https: blob:; "
                . "connect-src 'self' https://api.stripe.com https://www.google-analytics.com; "
                . "frame-src https://www.google.com https://js.stripe.com https://www.youtube.com https://player.vimeo.com; "
                . "object-src 'none'; "
                . "base-uri 'self'; "
                . "form-action 'self';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
