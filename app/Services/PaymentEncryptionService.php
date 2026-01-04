<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentEncryptionService
{
    public static function encryptPaymentData($data)
    {
        try {
            return Crypt::encryptString($data);
        } catch (\Exception $e) {
            Log::error('Payment data encryption failed');
            throw new \Exception('Data encryption failed');
        }
    }

    public static function decryptPaymentData($encryptedData)
    {
        try {
            return Crypt::decryptString($encryptedData);
        } catch (\Exception $e) {
            Log::error('Payment data decryption failed');
            throw new \Exception('Data decryption failed');
        }
    }

    public static function maskPaymentId($paymentId)
    {
        if (strlen($paymentId) <= 8) {
            return str_repeat('*', strlen($paymentId));
        }
        return substr($paymentId, 0, 4) . str_repeat('*', strlen($paymentId) - 8) . substr($paymentId, -4);
    }
}