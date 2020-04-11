<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = $user->Cart;
        $cartItems = json_decode($cart->cart_items);
        $finalcartitems = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find(intval($cartItem->product->id));
            $finalcartitem = new \stdClass();
            $finalcartitem->product = new ProductResource($product);
            $finalcartitem->qty = $cartItem->qty;
            array_push($finalcartitems, $finalcartitem);
        }

        return [
            'cart_items' => $finalcartitems,
            'id' => $cart->id,
            'total' => $cart->total,
        ];


    }

    public function addProductToCard(Request $request)
    {


        $request->validate([
            'product_id' => 'required',
            'qty' => 'required'

        ]);

        $user = Auth::user();
        $product_id = $request->input('product_id');
        $qty = $request->input('qty');

        $product = Product::findOrfail($product_id);
        /*
        *@var Cart
        */
        $cart = $user->cart;
        if (is_null($cart)) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->cart_items = [];
            $cart->total = 0;

        }

        if ($cart->inItems($product_id)) {
            $cart->increaseProductInCard($product, $qty);

        } else {

            $cart->addProductToCard($product, $qty);


        }
        $cart->save();
        $user->cart_id = $cart->id;
        $user->save();
        return $cart;
    }


}
