<?php

namespace App\Models;

use App\Http\Resources\cartItemsResource;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'cart_items', 'total', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function increaseProductInCard(Product $product, $qty = 1)
    {$cartItems = $this->cartItems;
        if (is_null($cartItems)) {
            $cartItems = [];
        } else {
            if (!is_array($cartItems)) {
                $cartItems = json_decode($cartItems);
            }}
        /*
         * @var $cartItems cartItems
         */
        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->id === $product->id) {
                $cartItem->qty += $qty;
            }
        }
        $this->cart_items = json_encode($cartItems);
        $tempTotal = 0;
        foreach ($cartItems as $cartItem) {$tempTotal += ($cartItem->qty * $cartItem->product->price);}
        $this->total = $tempTotal;
    }
    public function addProductToCard(Product $product, $qty = 1){
        $cartItems = $this->cartItems;
        if (is_null($cartItems)) {
            $cartItems = [];
        } else {
            if (!is_array($cartItems)) {
                $cartItems = json_decode($cartItems);
            }
        }
        /*
         * @var $$cartItem CartItem
         */
        $cartItem = new CartItem($product, $qty);
        array_push($cartItems, $cartItem);
        $this->cart_items = json_encode($cartItems);
        $tempTotal = 0;
        foreach ($cartItems as $cartItem) {
            $tempTotal += ($cartItem->qty * $cartItem->product->price);
        }
        $this->total = $tempTotal;
    }





    public
    function inItems($product_id)
    {
        $cartItems = $this->cartItems;
        if (is_null($cartItems)) {
            $cartItems = [];
        } else {
            if (!is_array($cartItems)) {
                $cartItems = json_decode($cartItems);
            }
        }

        $returnresulr = false;


        foreach ($cartItems as $cartItem) {
            if ($product_id == $cartItem->product->id) {
                $returnresulr = true;
            }
        }
        return $returnresulr;
    }


}
