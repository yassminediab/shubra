<?php

namespace App\Transformers;

use App\CartProduct;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class CartProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name','price', 'image','size_per_unit','unit_of_measure','quantity','price_after_discount'];

    public function includeId(CartProduct $product)
    {
        return $this->primitive($product->product->id);
    }

    public function includeName(CartProduct $product)
    {
        return $this->primitive($product->product->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(CartProduct $product)
    {
        return $this->primitive($product->product->image);
    }

    public function includePrice(CartProduct $product)
    {
        return $this->primitive($product->price);
    }

    public function includePriceAfterDiscount(CartProduct $product)
    {
        return $this->primitive($product->price_after_discount);
    }

    public function includeQuantity(CartProduct $product)
    {
        return $this->primitive($product->quantity);
    }

    public function includeUnitOfMeasure(CartProduct $product)
    {
        return $this->primitive($product->unit_of_measure);
    }

    public function includeSizePerUnit(CartProduct $product)
    {
        return $this->primitive($product->size_per_unit);
    }
}
