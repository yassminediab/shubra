<?php

namespace App\Transformers;


use App\Address;
use App\Brand;
use App\City;
use App\State;
use Saad\Fractal\Transformers\TransformerAbstract;

class StateTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name'];

    public function includeId(State $state)
    {
        return $this->primitive($state->id);
    }

    public function includeName(State $state)
    {
        return $this->primitive($state->name);
    }

}
