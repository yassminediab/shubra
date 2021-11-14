<?php

namespace App\Transformers;

use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name', 'description', 'image','price', 'offer','rate','size_per_unit','unit_of_measure', 'quantity', 'images'];

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
        if($product->pivot) {
            $offer = $product->pivot->discount;
        } elseif ($product->offers->count() > 0)  {
            $offer = $product->offers[0]->pivot->discount;
        }
        return $this->primitive($offer ?? 0);
    }

    public function includeRate(Product $product)
    {
        return $this->primitive($product->rate);
    }

    public function includeUnitOfMeasure(Product $product)
    {
        return $this->primitive($product->unit_of_measure);
    }

    public function includeSizePerUnit(Product $product)
    {
        return $this->primitive($product->size_per_unit);
    }

    public function includeQuantity(Product $product)
    {
        return $this->primitive($product->quantity);
    }

    public function includeImages(Product $product)
    {
        return $this->primitive($product->images->pluck('image'));
    }
}
