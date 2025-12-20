<?php

namespace App\Services;

use Razorpay\Api\Api;
use Exception;

class PaymentService
{
    private $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    public function verifyPayment($paymentId, $orderId, $signature)
    {
        if (empty($paymentId) || empty($orderId) || empty($signature)) {
            \Log::warning('Payment verification failed: Missing required parameters');
            return false;
        }
        
        try {
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (Exception $e) {
            \Log::error('Payment verification failed: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'order_id' => $orderId
            ]);
            return false;
        }
    }

    public function getPaymentDetails($paymentId)
    {
        try {
            return $this->razorpay->payment->fetch($paymentId);
        } catch (Exception $e) {
            \Log::error('Failed to fetch payment details: ' . $e->getMessage(), [
                'payment_id' => $paymentId
            ]);
            return null;
        }
    }

    public function createRazorpayOrder($amount, $currency = 'INR')
    {
        try {
            $order = $this->razorpay->order->create([
                'amount' => $amount * 100, // Amount in paise
                'currency' => $currency,
                'receipt' => 'order_' . \Illuminate\Support\Str::uuid()
            ]);
            return $order;
        } catch (Exception $e) {
            \Log::error('Failed to create Razorpay order: ' . $e->getMessage(), [
                'amount' => $amount,
                'currency' => $currency
            ]);
            return null;
        }
    }
}