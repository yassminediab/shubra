<?php

namespace App\Transformers;

use App\Cart;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','price','weight','discount','total_items','total_price','products', 'coupon' , 'coupon_value', 'voucher', 'voucher_amount','fees'];

    public function includeId(Cart $cart)
    {
        return $this->primitive($cart->id);
    }

    public function includePrice(Cart $cart)
    {
        return $this->primitive($cart->price);
    }

    public function includeWeight(Cart $cart)
    {
        return $this->primitive($cart->weight);
    }

    public function includeDiscount(Cart $cart)
    {
        return $this->primitive($cart->discount);
    }

    public function includeTotalItems(Cart $cart)
    {
        return $this->primitive($cart->total_items);
    }

    public function includeTotalPrice(Cart $cart)
    {
        return $this->primitive($cart->total_price);
    }

    public function includeCoupon(Cart $cart)
    {
        return $this->primitive($cart->coupon);
    }

    public function includeCouponValue(Cart $cart)
    {
        return $this->primitive($cart->coupon_value);
    }

    public function includeVoucher(Cart $cart)
    {
        return $this->primitive($cart->voucher);
    }

    public function includeVoucherAmount(Cart $cart)
    {
        return $this->primitive($cart->voucher_amount);
    }

    public function includeFees(Cart $cart)
    {
        return $this->primitive($cart->fees);
    }

    public function includeProducts(Cart $cart)
    {
        return $this->collection($cart->products,new CartProductTransformer());
    }

}
