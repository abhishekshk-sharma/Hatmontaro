<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserCart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $cartItems = UserCart::where('user_id', auth()->id())->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        // Create Razorpay order
        $razorpayOrder = $this->paymentService->createRazorpayOrder($total);
        
        return view('checkout.index', compact('cartItems', 'total', 'razorpayOrder'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'phone_no' => 'required|string|max:15',
            'payment_method' => 'required|in:razorpay,cod',
            'razorpay_payment_id' => 'required_if:payment_method,razorpay',
            'razorpay_order_id' => 'required_if:payment_method,razorpay',
            'razorpay_signature' => 'required_if:payment_method,razorpay'
        ]);

        try {
            DB::beginTransaction();

            $cartItems = UserCart::where('user_id', auth()->id())->with('product')->get();
            
            if ($cartItems->isEmpty()) {
                throw new Exception('Cart is empty');
            }

            $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

            // Verify payment for Razorpay
            $paymentStatus = 'pending';
            $paymentId = null;
            $paymentSignature = null;
            
            if ($request->payment_method === 'razorpay') {
                $isPaymentValid = $this->paymentService->verifyPayment(
                    $request->razorpay_payment_id,
                    $request->razorpay_order_id,
                    $request->razorpay_signature
                );

                if (!$isPaymentValid) {
                    throw new Exception('Payment verification failed');
                }

                $paymentDetails = $this->paymentService->getPaymentDetails($request->razorpay_payment_id);
                
                if (!$paymentDetails || $paymentDetails->status !== 'captured') {
                    throw new Exception('Payment not captured');
                }

                $paymentStatus = 'success';
                $paymentId = $request->razorpay_payment_id;
                $paymentSignature = $request->razorpay_signature;
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . \Illuminate\Support\Str::uuid(),
                'total_amount' => $total,
                'status' => $paymentStatus === 'success' ? 'processing' : 'pending',
                'shipping_address' => $request->shipping_address,
                'phone_no' => $request->phone_no,
                'payment_method' => $request->payment_method,
                'payment_status' => $paymentStatus
            ]);
            
            // Set protected payment fields separately
            if ($paymentId) {
                $order->payment_id = $paymentId;
                $order->payment_signature = $paymentSignature;
                $order->transaction_id = $paymentId;
                $order->save();
            }

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart only after successful order creation
            UserCart::where('user_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => 'Order placed successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function success($orderId)
    {
        $order = Order::where('id', $orderId)
                     ->where('user_id', auth()->id())
                     ->firstOrFail();
                     
        return view('checkout.success', compact('order'));
    }

    public function webhook(Request $request)
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();

        // Verify webhook signature
        if (empty($webhookSignature) || empty($webhookSecret)) {
            return response()->json(['error' => 'Missing signature or secret'], 400);
        }
        
        $expectedSignature = hash_hmac('sha256', $webhookBody, $webhookSecret);
        
        if (!hash_equals($expectedSignature, $webhookSignature)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->all();
        
        if ($event['event'] === 'payment.captured') {
            if (!isset($event['payload']['payment']['entity']['id'])) {
                return response()->json(['error' => 'Invalid payload structure'], 400);
            }
            $paymentId = $event['payload']['payment']['entity']['id'];
            
            // Update order payment status
            Order::where('payment_id', $paymentId)
                 ->update([
                     'payment_status' => 'success',
                     'status' => 'processing'
                 ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
