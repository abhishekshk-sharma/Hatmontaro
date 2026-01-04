<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PaymentSecurityMiddleware
{
    // Razorpay webhook IP ranges (update with actual IPs from Razorpay documentation)
    private $allowedWebhookIPs = [
        '127.0.0.1', // localhost for testing
        '::1', // IPv6 localhost
        // Add Razorpay's actual webhook IPs here
    ];

    public function handle(Request $request, Closure $next)
    {
        // Rate limiting for payment endpoints
        $key = 'payment:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json(['error' => 'Too many payment attempts'], 429);
        }
        
        RateLimiter::hit($key, 300); // 5 minutes
        
        // Validate HTTPS in production
        if (app()->environment('production') && !$request->secure()) {
            return response()->json(['error' => 'HTTPS required'], 400);
        }
        
        // Validate webhook source IP for webhook endpoints
        if ($request->is('payment/webhook')) {
            $clientIP = $request->ip();
            if (!in_array($clientIP, $this->allowedWebhookIPs) && app()->environment('production')) {
                \Log::warning('Webhook access from unauthorized IP', ['ip' => $clientIP]);
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }
        
        return $next($request);
    }
}