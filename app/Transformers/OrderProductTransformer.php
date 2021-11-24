<?php

namespace App\Transformers;

use App\CartProduct;
use App\OrderProduct;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class OrderProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name','price', 'image','size_per_unit','unit_of_measure','quantity','price_after_discount', 'is_prepared','issue'];

    public function includeId(OrderProduct $product)
    {
        return $this->primitive($product->product->id);
    }

    public function includeName(OrderProduct $product)
    {
        return $this->primitive($product->product->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(OrderProduct $product)
    {
        return $this->primitive($product->product->image);
    }

    public function includePrice(OrderProduct $product)
    {
        return $this->primitive($product->price);
    }

    public function includePriceAfterDiscount(OrderProduct $product)
    {
        return $this->primitive($product->price_after_discount);
    }

    public function includeIsPrepared(OrderProduct $product)
    {
        return $this->primitive($product->is_prepared);
    }

    public function includeIssue(OrderProduct $product)
    {
        return $this->primitive($product->issue);
    }

    public function includeQuantity(OrderProduct $product)
    {
        return $this->primitive($product->quantity);
    }

    public function includeUnitOfMeasure(OrderProduct $product)
    {
        return $this->primitive($product->unit_of_measure);
    }

    public function includeSizePerUnit(OrderProduct $product)
    {
        return $this->primitive($product->size_per_unit);
    }
}
