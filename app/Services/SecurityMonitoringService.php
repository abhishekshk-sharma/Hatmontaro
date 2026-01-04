<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SecurityMonitoringService
{
    public function logSecurityEvent($type, $data = [])
    {
        $securityLog = [
            'type' => $type,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'data' => $data
        ];

        Log::channel('security')->warning("Security Event: {$type}", $securityLog);

        // Alert on critical events
        if (in_array($type, ['payment_fraud', 'multiple_failed_payments', 'suspicious_webhook'])) {
            $this->sendSecurityAlert($type, $securityLog);
        }
    }

    public function checkFailedPaymentAttempts($userId)
    {
        $key = "failed_payments:{$userId}";
        $attempts = Cache::get($key, 0);
        
        if ($attempts >= 5) {
            $this->logSecurityEvent('multiple_failed_payments', [
                'user_id' => $userId,
                'attempts' => $attempts
            ]);
            return true;
        }
        
        return false;
    }

    public function recordFailedPayment($userId)
    {
        $key = "failed_payments:{$userId}";
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, 3600); // 1 hour
        
        return $attempts;
    }

    private function sendSecurityAlert($type, $data)
    {
        // In production, send email to admin
        if (config('app.env') === 'production') {
            try {
                Mail::raw("Security Alert: {$type}\n\n" . json_encode($data, JSON_PRETTY_PRINT), function ($message) {
                    $message->to(config('mail.admin_email', 'admin@yourdomain.com'))
                           ->subject('Security Alert - Payment System');
                });
            } catch (\Exception $e) {
                Log::error('Failed to send security alert email', ['error' => $e->getMessage()]);
            }
        }
    }
}