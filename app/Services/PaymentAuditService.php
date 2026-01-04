<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\PaymentEncryptionService;

class PaymentAuditService
{
    public static function logPaymentAction($userId, $action, $amount, $status, $paymentId = null, $orderId = null, $metadata = [])
    {
        try {
            DB::table('payment_audit_logs')->insert([
                'user_id' => $userId,
                'order_id' => $orderId,
                'action' => $action,
                'payment_id_masked' => $paymentId ? PaymentEncryptionService::maskPaymentId($paymentId) : null,
                'amount' => $amount,
                'status' => $status,
                'ip_address' => request()->ip(),
                'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
                'metadata' => json_encode($metadata),
                'created_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment audit logging failed', [
                'user_id' => $userId,
                'action' => $action
            ]);
        }
    }
}