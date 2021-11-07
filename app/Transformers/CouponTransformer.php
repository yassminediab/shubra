<?php

namespace App\Transformers;

use App\Coupon;
use App\Offer;
use App\OfferType;
use Saad\Fractal\Transformers\TransformerAbstract;

class CouponTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id', 'title','description','discount','product', 'coupon'];

    public function includeId(Coupon $coupon)
    {
        return $this->primitive($coupon->id);
    }

    public function includeTitle(Coupon $coupon)
    {
        return $this->primitive($coupon->getTranslatedAttribute('title', app()->getLocale()));
    }

    public function includeDescription(Coupon $coupon)
    {
        return $this->primitive($coupon->getTranslatedAttribute('description', app()->getLocale()));
    }

    public function includeDiscount(Coupon $coupon)
    {
        return $this->primitive($coupon->discount);
    }

    public function includeCoupon(Coupon $coupon)
    {
        return $this->primitive($coupon->coupon);
    }

    public function includeProduct(Coupon $coupon)
    {
        return $this->item($coupon->product, new ProductTransformer());
    }
}
