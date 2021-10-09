<?php

namespace App\Transformers;

use App\Cart;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','price','weight','discount','total_items','total_price','products'];

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

    public function includeProducts(Cart $cart)
    {
        return $this->collection($cart->products,new CartProductTransformer());
    }

}
