<?php

namespace App\Transformers;

use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name', 'description', 'image','price', 'offer'];

    public function includeId(Product $product)
    {
        return $this->primitive($product->id);
    }

    public function includeName(Product $product)
    {
        return $this->primitive($product->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(Product $product)
    {
        return $this->primitive($product->image);
    }

    public function includeDescription(Product $product)
    {
        return $this->primitive($product->getTranslatedAttribute('description', app()->getLocale()));
    }

    public function includePrice(Product $product)
    {
        return $this->primitive($product->price);
    }

    public function includeOffer(Product $product)
    {
        return $this->primitive($product->pivot->offer);
    }
}
