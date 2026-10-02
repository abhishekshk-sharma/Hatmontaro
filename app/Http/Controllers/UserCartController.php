<?php

namespace App\Http\Controllers;

use App\Models\UserCart;
use App\Models\UserWishlist;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        
        // Sync items from session Cart if UserCart is currently empty
        if (auth()->user()->carts()->count() === 0) {
            $cart = \App\Models\Cart::where('user_id', $userId)
                ->orWhere('session_id', session()->getId())
                ->first();
                
            if ($cart && $cart->items->count() > 0) {
                foreach ($cart->items as $item) {
                    UserCart::firstOrCreate([
                        'user_id' => $userId,
                        'product_id' => $item->product_id
                    ], [
                        'quantity' => $item->quantity
                    ]);
                }
            }
        }

        $cartItems = auth()->user()->carts()->with('product')->get();
        return view('user.cart', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to cart');
        }

        $existingCart = UserCart::where('user_id', auth()->id())
                                ->where('product_id', $productId)
                                ->first();

        if ($existingCart) {
            // If product already exists in cart, increment quantity by 1
            $existingCart->increment('quantity');
        } else {
            // If product doesn't exist in cart, create new entry with quantity 1
            UserCart::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    public function update(Request $request, $id)
    {
        $cart = UserCart::where('user_id', auth()->id())->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Cart updated!');
    }

    public function remove($id)
    {
        UserCart::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Removed from cart!');
    }

    public function saveForLater($id)
    {
        $cart = UserCart::where('user_id', auth()->id())->findOrFail($id);
        UserWishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $cart->product_id
        ]);
        $cart->delete();
        return back()->with('success', 'Saved for later!');
    }
}
