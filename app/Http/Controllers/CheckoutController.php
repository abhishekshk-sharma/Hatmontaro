<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\FraudDetectionService;
use App\Services\PaymentAuditService;
use Illuminate\Support\Facades\RateLimiter;
use Exception;

class CheckoutController extends Controller
{
    private $paymentService;
    private $fraudDetection;

    public function __construct(PaymentService $paymentService, FraudDetectionService $fraudDetection)
    {
        $this->paymentService = $paymentService;
        $this->fraudDetection = $fraudDetection;
        $this->middleware('auth')->except(['webhook']);
        $this->middleware('throttle:10,1')->only(['index']); // 10 requests per minute
        $this->middleware('throttle:3,1')->only(['process']); // 3 payment attempts per minute
    }

    public function index()
    {
        try {
            // Debug: Log checkout access attempt
            Log::info('Checkout access attempt', [
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            
            // Rate limiting per user
            $key = 'checkout_access:' . auth()->id();
            if (RateLimiter::tooManyAttempts($key, 20)) { // 20 per hour
                Log::warning('Checkout access rate limit exceeded', [
                    'user_id' => auth()->id(),
                    'ip' => request()->ip()
                ]);
                return redirect()->route('user.cart')
                    ->with('error', 'Too many checkout attempts. Please try again later.');
            }
            RateLimiter::hit($key, 3600);
            
            $cartItems = UserCart::where('user_id', auth()->id())
                ->with(['product' => function($query) {
                    $query->where('stock_quantity', '>', 0);
                }])
                ->get();
            
            // Debug: Log cart items count
            Log::info('Cart items retrieved', [
                'user_id' => auth()->id(),
                'cart_items_count' => $cartItems->count(),
                'raw_items' => $cartItems->pluck('id')->toArray()
            ]);
            
            // Filter out items with inactive products
            $cartItems = $cartItems->filter(function($item) {
                $hasProduct = $item->product !== null;
                
                if (!$hasProduct) {
                    Log::warning('Cart item has no product', ['item_id' => $item->id]);
                }
                
                return $hasProduct;
            });
            
            // Debug: Log filtered cart items
            Log::info('Filtered cart items', [
                'user_id' => auth()->id(),
                'filtered_count' => $cartItems->count()
            ]);
            
            if ($cartItems->isEmpty()) {
                Log::info('Checkout attempted with empty cart', [
                    'user_id' => auth()->id()
                ]);
                return redirect()->route('user.cart')
                    ->with('error', 'Your cart is empty or contains unavailable items.');
            }
            
            // Validate stock availability
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock_quantity) {
                    return redirect()->route('user.cart')
                        ->with('error', "Insufficient stock for {$item->product->name}");
                }
            }
            
            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });
            
            // Enhanced amount validation
            if ($total < 1 || $total > 100000) {
                Log::warning('Invalid cart total detected', [
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'ip' => request()->ip()
                ]);
                return redirect()->route('user.cart')
                    ->with('error', 'Invalid cart total. Please review your items.');
            }
            
            // Fraud detection check
            $fraudCheck = $this->fraudDetection->detectSuspiciousActivity(
                auth()->id(), 
                $total, 
                request()->ip()
            );
            
            if ($fraudCheck['is_suspicious']) {
                Log::alert('Suspicious payment activity blocked', [
                    'user_id' => auth()->id(),
                    'total' => $total,
                    'risk_score' => $fraudCheck['risk_score'],
                    'reasons' => $fraudCheck['reasons']
                ]);
                return redirect()->route('user.cart')
                    ->with('error', 'Payment temporarily unavailable. Please contact support.');
            }
            
            // Create Razorpay order with enhanced security
            $razorpayOrder = $this->paymentService->createRazorpayOrder($total);
            
            if (!$razorpayOrder) {
                Log::error('Failed to create Razorpay order for checkout', [
                    'user_id' => auth()->id(),
                    'total' => $total
                ]);
                return redirect()->route('user.cart')
                    ->with('error', 'Unable to initialize payment. Please try again.');
            }
            
            // Log payment initiation
            PaymentAuditService::logPaymentAction(
                auth()->id(),
                'payment_initiated',
                $total,
                'pending',
                null,
                null,
                ['razorpay_order_id' => substr($razorpayOrder->id, 0, 8) . '***']
            );
            
            Log::info('Checkout page accessed successfully', [
                'user_id' => auth()->id(),
                'total' => $total,
                'items_count' => $cartItems->count(),
                'razorpay_order_id' => $razorpayOrder->id
            ]);
            
            return view('checkout.index', compact('cartItems', 'total', 'razorpayOrder'));
            
        } catch (Exception $e) {
            Log::error('Checkout page error', [
                'user_id' => auth()->id(),
                'error' => 'Checkout initialization failed',
                'ip' => request()->ip()
            ]);
            return redirect()->route('user.cart')
                ->with('error', 'Checkout temporarily unavailable. Please try again.');
        }
    }

    public function process(Request $request)
    {
        // Enhanced rate limiting
        $userKey = 'payment_process:' . auth()->id();
        $ipKey = 'payment_process_ip:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($userKey, 5) || RateLimiter::tooManyAttempts($ipKey, 10)) {
            Log::warning('Payment processing rate limit exceeded', [
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Too many payment attempts. Please wait before trying again.'
            ], 429);
        }
        
        RateLimiter::hit($userKey, 300); // 5 minutes
        RateLimiter::hit($ipKey, 300);
        
        // Enhanced validation
        $validator = Validator::make($request->all(), [
            'shipping_address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'regex:/^[a-zA-Z0-9\s,.\/\-#()]+$/'
            ],
            'phone_no' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/',
                'size:10'
            ],
            'payment_method' => 'required|in:razorpay',
            'razorpay_payment_id' => [
                'required_if:payment_method,razorpay',
                'regex:/^pay_[A-Za-z0-9]{14}$/',
                'size:18'
            ],
            'razorpay_order_id' => [
                'required_if:payment_method,razorpay',
                'regex:/^order_[A-Za-z0-9]{14}$/',
                'size:20'
            ],
            'razorpay_signature' => [
                'required_if:payment_method,razorpay',
                'string',
                'regex:/^[a-f0-9]{64}$/',
                'size:64'
            ]
        ], [
            'shipping_address.regex' => 'Shipping address contains invalid characters.',
            'phone_no.regex' => 'Please enter a valid 10-digit Indian mobile number.',
            'razorpay_payment_id.regex' => 'Invalid payment ID format.',
            'razorpay_order_id.regex' => 'Invalid order ID format.',
            'razorpay_signature.regex' => 'Invalid signature format.'
        ]);

        if ($validator->fails()) {
            Log::warning('Checkout validation failed', [
                'user_id' => auth()->id(),
                'errors' => $validator->errors()->toArray(),
                'ip' => request()->ip()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Re-validate cart items for security
            $cartItems = UserCart::where('user_id', auth()->id())
                ->with('product')
                ->lockForUpdate() // Prevent concurrent modifications
                ->get();
            
            if ($cartItems->isEmpty()) {
                throw new Exception('Cart is empty or items are no longer available');
            }

            // Validate stock and calculate total with locks
            $total = 0;
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    throw new Exception('Some items are no longer available');
                }
                
                if ($item->quantity > $item->product->stock_quantity) {
                    throw new Exception("Insufficient stock for {$item->product->name}");
                }
                
                $total += $item->product->price * $item->quantity;
            }
            
            // Enhanced amount validation
            if ($total < 1 || $total > 100000) {
                throw new Exception('Invalid order total amount');
            }

            // Verify payment with enhanced security
            $paymentStatus = 'pending';
            $paymentId = null;
            $paymentSignature = null;
            
            if ($request->payment_method === 'razorpay') {
                // Check for duplicate payment processing
                $existingOrder = Order::where('payment_id', $request->razorpay_payment_id)
                    ->where('payment_status', 'success')
                    ->first();
                    
                if ($existingOrder) {
                    throw new Exception('Payment already processed');
                }
                
                $isPaymentValid = $this->paymentService->verifyPayment(
                    $request->razorpay_payment_id,
                    $request->razorpay_order_id,
                    $request->razorpay_signature
                );

                if (!$isPaymentValid) {
                    throw new Exception('Payment verification failed');
                }

                $paymentDetails = $this->paymentService->getPaymentDetails($request->razorpay_payment_id);
                
                if (!$paymentDetails || !in_array($paymentDetails->status, ['captured', 'authorized'])) {
                    throw new Exception('Payment not captured or authorized');
                }
                
                // Verify payment amount matches order total (with tolerance for rounding)
                $paidAmount = $paymentDetails->amount / 100;
                if (abs($paidAmount - $total) > 0.01) {
                    Log::error('Payment amount mismatch detected', [
                        'expected' => $total,
                        'paid' => $paidAmount,
                        'payment_id' => $request->razorpay_payment_id,
                        'user_id' => auth()->id(),
                        'ip' => request()->ip()
                    ]);
                    throw new Exception('Payment amount verification failed');
                }

                $paymentStatus = 'success';
                $paymentId = $request->razorpay_payment_id;
                $paymentSignature = $request->razorpay_signature;
                
                // Log successful payment verification
                PaymentAuditService::logPaymentAction(
                    auth()->id(),
                    'payment_verified',
                    $total,
                    'success',
                    $paymentId
                );
            }

            // Generate secure order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            
            // Ensure unique order number
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
            }

            // Create order with enhanced security
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'total_amount' => $total,
                'status' => $paymentStatus === 'success' ? 'processing' : 'pending',
                'shipping_address' => strip_tags(trim($request->shipping_address)),
                'phone_no' => $request->phone_no,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus,
                'order_date' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => substr(request()->userAgent(), 0, 255)
            ]);
            
            // Set protected payment fields
            if ($paymentId) {
                $order->update([
                    'payment_id' => $paymentId,
                    'payment_signature' => $paymentSignature,
                    'transaction_id' => $paymentId,
                    'razorpay_order_id' => $request->razorpay_order_id
                ]);
            }

            // Create order items and update stock
            foreach ($cartItems as $item) {
                if ($item->quantity <= 0 || $item->quantity > 100) {
                    throw new Exception('Invalid item quantity');
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'product_name' => $item->product->name
                ]);
                
                // Update stock quantity
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart only after successful order creation
            UserCart::where('user_id', auth()->id())->delete();

            DB::commit();
            
            // Clear rate limiting on successful payment
            RateLimiter::clear($userKey);
            RateLimiter::clear($ipKey);
            
            // Track IP activity for fraud detection
            $this->fraudDetection->trackIPActivity(request()->ip());
            
            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => auth()->id(),
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_id' => $paymentId,
                'ip' => request()->ip()
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => 'Order placed successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Order creation failed', [
                'user_id' => auth()->id(),
                'error' => 'Payment processing failed',
                'ip' => request()->ip(),
                'payment_data' => [
                    'payment_id' => $request->razorpay_payment_id ? substr($request->razorpay_payment_id, 0, 8) . '***' : null,
                    'order_id' => $request->razorpay_order_id ? substr($request->razorpay_order_id, 0, 8) . '***' : null
                ]
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing temporarily unavailable. Please contact support.'
            ], 400);
        }
    }

    public function success($orderId)
    {
        // Enhanced validation
        if (!is_numeric($orderId) || $orderId <= 0) {
            Log::warning('Invalid order ID in success page', [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            abort(404);
        }
        
        $order = Order::where('id', $orderId)
                     ->where('user_id', auth()->id())
                     ->with(['orderItems.product'])
                     ->first();
                     
        if (!$order) {
            Log::warning('Unauthorized access to order success page', [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            abort(404);
        }
        
        // Mark order as viewed
        if (!$order->viewed_at) {
            $order->update(['viewed_at' => now()]);
        }
                     
        return view('checkout.success', compact('order'));
    }

    public function webhook(Request $request)
    {
        // Enhanced webhook security
        $webhookSecret = config('services.razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();
        
        // Rate limiting for webhooks
        $ipKey = 'webhook_ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 100)) { // 100 per minute
            Log::warning('Webhook rate limit exceeded', [
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }
        RateLimiter::hit($ipKey, 60);

        // Verify webhook signature
        if (!$this->paymentService->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret)) {
            Log::warning('Invalid webhook signature', [
                'signature' => $webhookSignature,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        try {
            $event = json_decode($webhookBody, true);
            
            if (!$event || !isset($event['event']) || !isset($event['payload'])) {
                throw new Exception('Invalid webhook payload structure');
            }
            
            Log::info('Webhook received', [
                'event' => $event['event'],
                'payment_id' => $event['payload']['payment']['entity']['id'] ?? null,
                'ip' => $request->ip()
            ]);
            
            if ($event['event'] === 'payment.captured') {
                $this->handlePaymentCaptured($event['payload']);
            } elseif ($event['event'] === 'payment.failed') {
                $this->handlePaymentFailed($event['payload']);
            }

            return response()->json(['status' => 'ok']);
            
        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $webhookBody,
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Webhook processing failed'], 400);
        }
    }
    
    private function handlePaymentCaptured($payload)
    {
        if (!isset($payload['payment']['entity']['id'])) {
            throw new Exception('Invalid payment captured payload');
        }
        
        $paymentId = $payload['payment']['entity']['id'];
        
        if (!preg_match('/^pay_[A-Za-z0-9]{14}$/', $paymentId)) {
            throw new Exception('Invalid payment ID format in webhook');
        }
        
        $updated = Order::where('payment_id', $paymentId)
             ->where('payment_status', '!=', 'success')
             ->update([
                 'payment_status' => 'success',
                 'status' => 'processing',
                 'payment_captured_at' => now()
             ]);
             
        if ($updated > 0) {
            Log::info('Order payment status updated via webhook', [
                'payment_id' => $paymentId,
                'updated_orders' => $updated
            ]);
        }
    }
    
    private function handlePaymentFailed($payload)
    {
        if (!isset($payload['payment']['entity']['id'])) {
            return;
        }
        
        $paymentId = $payload['payment']['entity']['id'];
        
        Order::where('payment_id', $paymentId)
             ->where('payment_status', 'pending')
             ->update([
                 'payment_status' => 'failed',
                 'status' => 'cancelled'
             ]);
             
        Log::info('Order marked as failed via webhook', [
            'payment_id' => $paymentId
        ]);
    }
}
