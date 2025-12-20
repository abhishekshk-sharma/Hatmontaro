<?php

namespace App\Http\Controllers;

use App\Models\UserCart;
use App\Models\UserWishlist;
use Illuminate\Http\Request;

class UserCartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->carts()->with('product')->get();
        return view('user.cart', compact('cartItems'));
    }

    public function add(Request $request, $productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to cart');
        }

        UserCart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $productId],
            ['quantity' => \DB::raw('quantity + 1')]
        );

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
