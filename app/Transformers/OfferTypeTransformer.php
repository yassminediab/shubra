<?php

namespace App\Transformers;

use App\Offer;
use App\OfferType;
use Saad\Fractal\Transformers\TransformerAbstract;

class OfferTypeTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id', 'type','offers'];

    public function includeId(OfferType $offer)
    {
        return $this->primitive($offer->id);
    }

    public function includeType(OfferType $offer)
    {
        return $this->primitive($offer->getTranslatedAttribute('type', app()->getLocale()));
    }

    public function includeOffers(OfferType $offer)
    {
        return $this->collection($offer->offers, new OfferTransformer());
    }
}
