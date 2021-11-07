<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\CartProduct;
use App\Coupon;
use App\Product;
use App\Transformers\CartTransformer;
use App\Transformers\ProductTransformer;
use App\Voucher;
use Carbon\Carbon;
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

    public function addCouponToCart(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $cart = Cart::find($id);

        $coupon = Coupon::where('user_id', $request->user()->id)->where('coupon',$request->coupon)->where('start_date','<=', Carbon::today())->where('end_date','>=',Carbon::today())->first();
        if(!$coupon) {
            return $this->respondNotAcceptable('Invalid or Expired Coupon');
        }

        $cartProduct = CartProduct::where('cart_id',$id)->where('product_id',$coupon->product_id)->first();
        if(!$cartProduct) {
            return $this->respondNotAcceptable('Product of this coupon not added to cart');
        }

        $discountedValue = $cartProduct->price_after_discount * $coupon->discount /100;
        $cartProduct->price_after_discount = $cartProduct->price_after_discount - $discountedValue;
        $cartProduct->save();

        $cart->discount = $cart->discount + $discountedValue;
        $cart->total_price = $cart->total_price - $discountedValue;
        $cart->coupon = $request->coupon;
        $cart->save();

        return $this->respondSuccess('Coupon added successfully',$cart);
    }

    public function addVoucherToCart(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'voucher' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $cart = Cart::find($id);

        $coupon = Voucher::where('user_id', $request->user()->id)->where('voucher_code',$request->voucher)->where('start_date','<=', Carbon::today())->where('end_date','>=',Carbon::today())->where('is_used',false)->first();
        if(!$coupon) {
            return $this->respondNotAcceptable('Invalid or Expired voucher or used before');
        }

        $cart->discount = $cart->discount + $coupon->voucher_amount;
        $cart->total_price = $cart->total_price - $coupon->voucher_amount;
        if($cart->total_price < 0) {
            $cart->total_price = 0;
        }
        $cart->voucher = $request->voucher;
        $cart->save();

        $coupon->is_used = 1;
        $coupon->save();

        return $this->respondSuccess('Voucher added successfully',$cart);
    }

}
