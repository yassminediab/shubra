<?php

namespace App\Transformers;

use App\Cart;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','price','weight','discount','total_items','total_price', 'coupon' , 'coupon_value', 'voucher', 'voucher_amount','fees','total_prices', 'total_products_price', 'vat', 'products'];

    public function includeId(Cart $cart)
    {
        return $this->primitive($cart->id);
    }

    public function includePrice(Cart $cart)
    {
        return $this->primitive((float)$cart->price);
    }

    public function includeWeight(Cart $cart)
    {
        return $this->primitive($cart->weight);
    }

    public function includeDiscount(Cart $cart)
    {
        return $this->primitive((float)$cart->discount);
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

    public function includeTotalProductsPrice(Cart $cart)
    {
        return $this->primitive($cart->total_products_price);
    }

    public function includeVat(Cart $cart)
    {
        return $this->primitive($cart->vat);
    }

    public function includeProducts(Cart $cart)
    {
        return $this->collection($cart->products,new CartProductTransformer());
    }

    public function includeTotalPrices(Cart $cart)
    {
        $prices = [
            $this->getPriceObject(Cart::PRICES['price'][app()->getLocale()] , $cart->total_products_price, false),
            $this->getPriceObject(Cart::PRICES['delivery'][app()->getLocale()] , $cart->fees, false),
            $this->getPriceObject(Cart::PRICES['coupon'][app()->getLocale()] , $cart->coupon_value, true),
            $this->getPriceObject(Cart::PRICES['voucher'][app()->getLocale()] , $cart->voucher_value, true),
            $this->getPriceObject(Cart::PRICES['vat'][app()->getLocale()] , $cart->vat, false),
            $this->getPriceObject(Cart::PRICES['total'][app()->getLocale()] , $cart->total_price, false),
        ];
        return $this->primitive($prices);
    }

    public function getPriceObject($title,$value, $minus) {
        return [
            'title' => $title,
            'value' => ($minus) ? -$value : $value,
        ];
    }

}
