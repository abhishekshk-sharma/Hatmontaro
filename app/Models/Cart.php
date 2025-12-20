<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'session_id', 'total'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    
    public function addItem(Product $product, $quantity = 1, $options = [])
    {
        $existingItem = $this->items()
            ->where('product_id', $product->id)
            ->first();
            
        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->save();
        } else {
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'options' => $options
            ]);
        }
        
        $this->updateTotal();
    }
    
    public function updateTotal()
    {
        $this->total = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $this->save();
    }
}