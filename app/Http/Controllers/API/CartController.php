<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\CartProduct;
use App\Product;
use App\Transformers\CartTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Saad\Fractal\Fractal;

class CartController extends ApiController
{
    public function addToCart(Request $request,$id = null)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $user = $request->user();

        $cart = Cart::find($id);

        if(!$cart) {
            $cart = Cart::create([
                'user_id' => $user ? $user->id : null
            ]);
        }
        $product = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        }])->find($request->product_id);

        $price = $request->quantity*$product->price;
        $discount = $product->offers->count() > 0 ? $product->offers[0]->pivot->discount : 0;
        $price_after_discount = $price - $price*$discount/100;
        CartProduct::updateOrCreate([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ],[
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $price,
            'price_after_discount' => $price_after_discount,
        ]);

        Cart::where('id',$cart->id)->update([
            'price' => $cart->price + $price,
            'total_items' => $cart->total_items + $request->quantity,
            'weight' => $cart->weight + $request->quantity,
            'discount' => $cart->discount + $price*$discount/100,
            'total_price' => $cart->total_price + $price_after_discount,
        ]);

        return $this->respondSuccess('Cart created successfully',$cart);
    }

    public function getCart($id) {
        $cart = Cart::with('products.product')->find($id);
        $transformedCart = Fractal::create($cart, new CartTransformer());
        return $this->respondSuccess('',$transformedCart);
    }

    public function deleteCart($id,$productId) {
        $cart = Cart::find($id);
        $cartProduct = CartProduct::where('cart_id',$id)->where('product_id',$productId)->first();
        if(!$cartProduct) {
            return $this->respondNotFound('cart not found');
        }

        $cartProduct->delete();

        Cart::where('id',$id)->update([
            'price' => $cart->price - $cartProduct->price,
            'total_items' => $cart->total_items - $cartProduct->quantity,
            'weight' => $cart->weight - $cartProduct->quantity,
            'discount' => ($cart->price - $cartProduct->price) - ($cart->total_price - $cartProduct->price_after_discount),
            'total_price' => $cart->total_price - $cartProduct->price_after_discount,
        ]);

        return $this->respondSuccess('Product deleted successfully');
    }

}
