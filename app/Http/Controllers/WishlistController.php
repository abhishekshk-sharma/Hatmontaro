<?php

namespace App\Http\Controllers;

use App\Models\UserWishlist;
use App\Models\UserCart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = UserWishlist::where('user_id', auth()->id())
            ->with('product')
            ->latest()
            ->get();
            
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add($productId)
    {
        UserWishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $productId
        ]);

        return back()->with('success', 'Added to wishlist!');
    }

    public function remove($id)
    {
        UserWishlist::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Removed from wishlist!');
    }

    public function moveToCart($id)
    {
        $wishlist = UserWishlist::where('user_id', auth()->id())->findOrFail($id);
        
        UserCart::updateOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $wishlist->product_id],
            ['quantity' => \DB::raw('quantity + 1')]
        );
        
        $wishlist->delete();
        return back()->with('success', 'Moved to cart!');
    }
}