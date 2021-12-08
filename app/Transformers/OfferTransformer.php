<?php

namespace App\Transformers;

use App\Offer;
use Saad\Fractal\Transformers\TransformerAbstract;

class OfferTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','title', 'image', 'description', 'type'];
    protected $availableIncludes = ['products'];

    protected $cartProductIds;

    protected $wishlistProductIds;

    public function __construct($cartProductIds = [], $wishlistProductIds = [])
    {
        $this->cartProductIds = $cartProductIds;
        $this->wishlistProductIds = $wishlistProductIds;
    }

    public function includeId(Offer $offer)
    {
        return $this->primitive($offer->id);
    }

    public function includeTitle(Offer $offer)
    {
        return $this->primitive($offer->getTranslatedAttribute('title', app()->getLocale()));
    }

    public function includeDescription(Offer $offer)
    {
        return $this->primitive($offer->getTranslatedAttribute('description', app()->getLocale()));
    }

    public function includeImage(Offer $offer)
    {
        return $this->primitive(getImageUrl($offer->image));
    }

    public function includeType(Offer $offer)
    {
        return $this->primitive($offer->offerTypeId->type);
    }

    public function includeProducts(Offer $offer)
    {
        return $this->collection($offer->products, new ProductTransformer($this->cartProductIds, $this->wishlistProductIds));
    }
}
