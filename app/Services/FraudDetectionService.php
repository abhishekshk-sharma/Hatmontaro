<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class FraudDetectionService
{
    public function detectSuspiciousActivity($userId, $amount, $ip)
    {
        $riskScore = 0;
        $reasons = [];

        // Check multiple orders in short time
        $recentOrders = Order::where('user_id', $userId)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();
        
        if ($recentOrders >= 3) {
            $riskScore += 30;
            $reasons[] = 'Multiple orders in 30 minutes';
        }

        // Check high amount transactions
        if ($amount > 50000) {
            $riskScore += 25;
            $reasons[] = 'High amount transaction';
        }

        // Check IP-based activity
        $ipOrders = Cache::get("ip_orders:{$ip}", 0);
        if ($ipOrders >= 5) {
            $riskScore += 40;
            $reasons[] = 'Multiple orders from same IP';
        }

        // Check velocity patterns
        $userOrdersToday = Order::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('total_amount');
        
        if ($userOrdersToday > 100000) {
            $riskScore += 50;
            $reasons[] = 'High daily transaction volume';
        }

        // Log suspicious activity
        if ($riskScore >= 50) {
            Log::warning('Suspicious payment activity detected', [
                'user_id' => $userId,
                'amount' => $amount,
                'ip' => $ip,
                'risk_score' => $riskScore,
                'reasons' => $reasons
            ]);
        }

        return [
            'risk_score' => $riskScore,
            'is_suspicious' => $riskScore >= 50,
            'reasons' => $reasons
        ];
    }

    public function trackIPActivity($ip)
    {
        $key = "ip_orders:{$ip}";
        $count = Cache::get($key, 0);
        Cache::put($key, $count + 1, 3600); // 1 hour
    }
}