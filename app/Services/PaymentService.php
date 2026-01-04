<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    private $razorpay;
    private $maxRetries = 3;
    private $timeoutSeconds = 30;

    public function __construct()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        
        if (empty($key) || empty($secret)) {
            throw new Exception('Razorpay credentials not configured');
        }
        
        // Validate key format for security (allow both test and live keys)
        if (!preg_match('/^rzp_(test|live)_[A-Za-z0-9]{14}$/', $key)) {
            // For development, allow the key to pass if it starts with rzp_
            if (!str_starts_with($key, 'rzp_')) {
                throw new Exception('Invalid Razorpay key format');
            }
        }
        
        $this->razorpay = new Api($key, $secret);
    }

    public function verifyPayment($paymentId, $orderId, $signature)
    {
        // Enhanced input validation
        if (empty($paymentId) || empty($orderId) || empty($signature)) {
            Log::warning('Payment verification failed: Missing parameters', [
                'payment_id' => $paymentId ? 'present' : 'missing',
                'order_id' => $orderId ? 'present' : 'missing',
                'signature' => $signature ? 'present' : 'missing',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return false;
        }
        
        // Validate ID formats
        if (!preg_match('/^pay_[A-Za-z0-9]{14}$/', $paymentId) || 
            !preg_match('/^order_[A-Za-z0-9]{14}$/', $orderId) ||
            !preg_match('/^[a-f0-9]{64}$/', $signature)) {
            Log::warning('Payment verification failed: Invalid format', [
                'payment_id' => $paymentId,
                'order_id' => $orderId
            ]);
            return false;
        }
        
        // Check for duplicate verification attempts with shorter cache time
        $cacheKey = "payment_verify:{$paymentId}:{$orderId}";
        if (Cache::has($cacheKey)) {
            $cacheData = Cache::get($cacheKey);
            // Allow re-verification if cache is older than 10 minutes
            if (is_array($cacheData) && isset($cacheData['verified_at'])) {
                if (now()->diffInMinutes($cacheData['verified_at']) > 10) {
                    Cache::forget($cacheKey);
                } else {
                    Log::warning('Duplicate payment verification attempt blocked', [
                        'payment_id' => $paymentId,
                        'ip' => request()->ip()
                    ]);
                    return false;
                }
            } else {
                // Old cache format, clear it
                Cache::forget($cacheKey);
            }
        }
        
        // Rate limiting per IP
        $rateLimitKey = 'payment_verify_ip:' . request()->ip();
        $attempts = Cache::get($rateLimitKey, 0);
        if ($attempts >= 10) {
            Log::warning('Payment verification rate limit exceeded', [
                'ip' => request()->ip(),
                'attempts' => $attempts
            ]);
            return false;
        }
        Cache::put($rateLimitKey, $attempts + 1, 300); // 5 minutes
        
        try {
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);
            
            // Cache successful verification for 24 hours
            Cache::put($cacheKey, [
                'verified_at' => now(),
                'ip' => request()->ip(),
                'user_id' => auth()->id()
            ], 86400);
            
            Log::info('Payment verification successful', [
                'payment_id' => substr($paymentId, 0, 8) . '***',
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            return true;
        } catch (Exception $e) {
            Log::error('Payment verification failed', [
                'payment_id' => substr($paymentId, 0, 8) . '***',
                'order_id' => substr($orderId, 0, 8) . '***',
                'error' => 'Verification failed',
                'ip' => request()->ip(),
                'user_id' => auth()->id()
            ]);
            return false;
        }
    }

    public function getPaymentDetails($paymentId)
    {
        if (empty($paymentId) || !preg_match('/^pay_[A-Za-z0-9]{14}$/', $paymentId)) {
            Log::warning('Invalid payment ID format', [
                'payment_id' => $paymentId,
                'ip' => request()->ip()
            ]);
            return null;
        }
        
        // Check cache first
        $cacheKey = "payment_details:{$paymentId}";
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }
        
        try {
            $payment = $this->razorpay->payment->fetch($paymentId);
            
            // Enhanced validation
            if (!in_array($payment->status, ['captured', 'authorized'])) {
                Log::warning('Payment not in valid status', [
                    'payment_id' => $paymentId,
                    'status' => $payment->status,
                    'user_id' => auth()->id()
                ]);
                return null;
            }
            
            // Validate payment amount is reasonable
            if ($payment->amount < 100 || $payment->amount > 10000000) { // 1 INR to 100,000 INR
                Log::warning('Payment amount out of range', [
                    'payment_id' => $paymentId,
                    'amount' => $payment->amount
                ]);
                return null;
            }
            
            // Cache for 1 hour
            Cache::put($cacheKey, $payment, 3600);
            
            return $payment;
        } catch (Exception $e) {
            Log::error('Failed to fetch payment details', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return null;
        }
    }

    public function createRazorpayOrder($amount, $currency = 'INR')
    {
        // Enhanced validation
        if (!is_numeric($amount) || $amount < 1 || $amount > 100000) {
            Log::error('Invalid payment amount', [
                'amount' => $amount,
                'user_id' => auth()->id()
            ]);
            return null;
        }
        
        if (!in_array($currency, ['INR'])) { // Only INR for security
            Log::error('Invalid currency', [
                'currency' => $currency,
                'user_id' => auth()->id()
            ]);
            return null;
        }
        
        // Check for duplicate order creation with shorter cache time
        $userId = auth()->id();
        $duplicateKey = "order_create:{$userId}:{$amount}";
        if (Cache::has($duplicateKey)) {
            Log::warning('Duplicate order creation attempt', [
                'user_id' => $userId,
                'amount' => $amount
            ]);
            // Clear the cache if it's older than 2 minutes to prevent permanent blocking
            $cacheData = Cache::get($duplicateKey);
            if (is_array($cacheData) && isset($cacheData['created_at'])) {
                if (now()->diffInMinutes($cacheData['created_at']) > 2) {
                    Cache::forget($duplicateKey);
                } else {
                    return null;
                }
            } else {
                // Old cache format, clear it
                Cache::forget($duplicateKey);
            }
        }
        
        try {
            $receiptId = 'ord_' . time() . '_' . $userId;
            
            $order = $this->razorpay->order->create([
                'amount' => round($amount * 100), // Amount in paise
                'currency' => $currency,
                'receipt' => $receiptId,
                'notes' => [
                    'user_id' => $userId,
                    'created_at' => now()->toISOString(),
                    'ip' => request()->ip(),
                    'user_agent' => substr(request()->userAgent(), 0, 100)
                ]
            ]);
            
            // Cache to prevent duplicates for 2 minutes instead of 5
            Cache::put($duplicateKey, [
                'created_at' => now(),
                'order_id' => $order->id
            ], 120);
            
            // Store order details securely
            DB::table('payment_orders')->insert([
                'razorpay_order_id' => $order->id,
                'user_id' => $userId,
                'amount' => $amount,
                'currency' => $currency,
                'receipt' => $receiptId,
                'status' => 'created',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info('Razorpay order created', [
                'order_id' => $order->id,
                'amount' => $amount,
                'user_id' => $userId,
                'ip' => request()->ip()
            ]);
            
            return $order;
        } catch (Exception $e) {
            Log::error('Failed to create Razorpay order', [
                'amount' => $amount,
                'currency' => $currency,
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'ip' => request()->ip()
            ]);
            return null;
        }
    }
    
    public function verifyWebhookSignature($payload, $signature, $secret)
    {
        if (empty($signature) || empty($secret) || empty($payload)) {
            Log::warning('Webhook signature verification failed: Missing data', [
                'ip' => request()->ip()
            ]);
            return false;
        }
        
        // Validate webhook source IP (Razorpay IPs - updated list)
        $allowedIPs = [
            '106.51.17.99',
            '106.51.17.100', 
            '3.108.63.118',
            '3.7.75.134',
            '52.66.191.22',
            '3.7.36.98',
            '127.0.0.1', // localhost for testing only
            '::1' // IPv6 localhost for testing only
        ];
        
        $clientIP = request()->ip();
        
        // In production, strictly validate IP (allow localhost only in local env)
        if (config('app.env') === 'production') {
            $productionIPs = array_filter($allowedIPs, fn($ip) => !in_array($ip, ['127.0.0.1', '::1']));
            if (!in_array($clientIP, $productionIPs)) {
                Log::warning('Webhook from unauthorized IP', [
                    'ip' => $clientIP,
                    'allowed_ips' => $productionIPs
                ]);
                return false;
            }
        } elseif (!in_array($clientIP, $allowedIPs)) {
            Log::warning('Webhook from unauthorized IP in dev', [
                'ip' => $clientIP
            ]);
            return false;
        }
        
        try {
            $expectedSignature = hash_hmac('sha256', $payload, $secret);
            $isValid = hash_equals($expectedSignature, $signature);
            
            if (!$isValid) {
                Log::warning('Webhook signature mismatch', [
                    'ip' => $clientIP,
                    'payload_length' => strlen($payload)
                ]);
            }
            
            return $isValid;
        } catch (Exception $e) {
            Log::error('Webhook signature verification error', [
                'error' => $e->getMessage(),
                'ip' => $clientIP
            ]);
            return false;
        }
    }
    
    public function clearPaymentCaches($userId = null, $amount = null)
    {
        $patterns = [
            'order_create:*',
            'payment_verify:*',
            'payment_details:*',
            'payment_verify_ip:*'
        ];
        
        if ($userId && $amount) {
            $specificKey = "order_create:{$userId}:{$amount}";
            Cache::forget($specificKey);
            Log::info('Cleared specific payment cache', ['key' => $specificKey]);
        }
        
        // Clear all payment-related caches
        foreach ($patterns as $pattern) {
            $keys = Cache::getRedis()->keys($pattern);
            if ($keys) {
                Cache::getRedis()->del($keys);
            }
        }
        
        Log::info('Payment caches cleared', ['patterns' => $patterns]);
    
    public function refundPayment($paymentId, $amount = null, $reason = 'requested_by_customer')
    {
        if (!preg_match('/^pay_[A-Za-z0-9]{14}$/', $paymentId)) {
            return false;
        }
        
        try {
            $refundData = [
                'payment_id' => $paymentId,
                'notes' => [
                    'reason' => $reason,
                    'refunded_by' => auth()->id(),
                    'refunded_at' => now()->toISOString()
                ]
            ];
            
            if ($amount) {
                $refundData['amount'] = round($amount * 100);
            }
            
            $refund = $this->razorpay->refund->create($refundData);
            
            Log::info('Refund initiated', [
                'payment_id' => $paymentId,
                'refund_id' => $refund->id,
                'amount' => $amount,
                'user_id' => auth()->id()
            ]);
            
            return $refund;
        } catch (Exception $e) {
            Log::error('Refund failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            return false;
        }
    }
}