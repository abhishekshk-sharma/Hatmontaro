<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        return view('cart.index', compact('cart'));
    }
    
    public function add(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string'
        ]);
        
        $cart = $this->getOrCreateCart();
        
        $options = [];
        if ($request->size) $options['size'] = $request->size;
        if ($request->color) $options['color'] = $request->color;
        
        $cart->addItem($product, $request->quantity ?? 1, $options);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cart->items->sum('quantity'),
                'message' => 'Product added to cart!'
            ]);
        }
        
        return back()->with('success', 'Product added to cart!');
    }
    
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $item->update(['quantity' => $request->quantity]);
        $item->cart->updateTotal();
        
        return response()->json([
            'success' => true,
            'subtotal' => $item->subtotal,
            'total' => $item->cart->total
        ]);
    }
    
    public function remove(CartItem $item)
    {
        $item->delete();
        $item->cart->updateTotal();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'total' => $item->cart->total
            ]);
        }
        
        return back()->with('success', 'Item removed from cart');
    }
    
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        $cart->updateTotal();
        
        return back()->with('success', 'Cart cleared');
    }
    
    private function getOrCreateCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        
        $sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }
}