<?php

namespace App\Transformers;


use App\Address;
use App\Brand;
use App\City;
use Saad\Fractal\Transformers\TransformerAbstract;

class CityTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name'];

    public function includeId(City $city)
    {
        return $this->primitive($city->id);
    }

    public function includeName(City $city)
    {
        return $this->primitive($city->name);
    }

}
