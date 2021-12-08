<?php

namespace App\Transformers;


use App\Brand;
use Saad\Fractal\Transformers\TransformerAbstract;

class BrandTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name', 'image'];

    public function includeId(Brand $brand)
    {
        return $this->primitive($brand->id);
    }

    public function includeName(Brand $brand)
    {
        return $this->primitive($brand->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(Brand $brand)
    {
        return $this->primitive(getImageUrl($brand->image));
    }
}
