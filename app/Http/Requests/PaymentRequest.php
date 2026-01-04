<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'shipping_address' => [
                'required',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s,.-]+$/'
            ],
            'phone_no' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/'
            ],
            'payment_method' => 'required|in:razorpay,cod',
            'razorpay_payment_id' => [
                'required_if:payment_method,razorpay',
                'regex:/^pay_[A-Za-z0-9]{14}$/'
            ],
            'razorpay_order_id' => [
                'required_if:payment_method,razorpay',
                'regex:/^order_[A-Za-z0-9]{14}$/'
            ],
            'razorpay_signature' => [
                'required_if:payment_method,razorpay',
                'string',
                'size:64'
            ]
        ];
    }

    public function messages()
    {
        return [
            'phone_no.regex' => 'Please enter a valid Indian mobile number.',
            'shipping_address.regex' => 'Shipping address contains invalid characters.',
            'razorpay_payment_id.regex' => 'Invalid payment ID format.',
            'razorpay_order_id.regex' => 'Invalid order ID format.',
            'razorpay_signature.size' => 'Invalid signature format.'
        ];
    }
}