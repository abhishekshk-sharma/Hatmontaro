<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class EnhancedPaymentSecurity
{
    public function handle(Request $request, Closure $next)
    {
        // Enhanced HTTPS enforcement for production
        if (!$request->secure() && config('app.env') === 'production') {
            Log::warning('Insecure payment request blocked', [
                'ip' => $request->ip(),
                'url' => parse_url($request->fullUrl(), PHP_URL_PATH)
            ]);
            return redirect()->secure($request->getRequestUri());
        }
        
        // Enhanced rate limiting
        $key = 'payment_security:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 50)) { // 50 per hour
            Log::warning('Payment security rate limit exceeded', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            abort(429, 'Too many requests');
        }
        RateLimiter::hit($key, 3600);
        
        // Block suspicious user agents
        $userAgent = $request->userAgent();
        $suspiciousAgents = ['bot', 'crawler', 'spider', 'scraper'];
        
        foreach ($suspiciousAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                Log::warning('Suspicious user agent blocked from payment', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent
                ]);
                abort(403, 'Access denied');
            }
        }
        
        // Validate content type for POST requests
        if ($request->isMethod('post') && !$request->expectsJson()) {
            $contentType = $request->header('Content-Type');
            if (!str_contains($contentType, 'application/x-www-form-urlencoded') && 
                !str_contains($contentType, 'multipart/form-data')) {
                Log::warning('Invalid content type for payment request', [
                    'ip' => $request->ip(),
                    'content_type' => $contentType
                ]);
                abort(400, 'Invalid request format');
            }
        }
        
        // Add security headers
        $response = $next($request);
        
        if (method_exists($response, 'header')) {
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'DENY');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' checkout.razorpay.com; style-src 'self' 'unsafe-inline';");
            
            if (config('app.env') === 'production') {
                $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
            }
        }
        
        return $response;
    }
}