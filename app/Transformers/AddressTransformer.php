<?php

namespace App\Transformers;


use App\Address;
use Saad\Fractal\Transformers\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','full_name', 'address','landmark','address_type','city','pincode','state','mobile','lat','lng','is_verified'];

    public function includeId(Address $address)
    {
        return $this->primitive($address->id);
    }

    public function includeFullName(Address $address)
    {
        return $this->primitive($address->full_name);
    }

    public function includeAddress(Address $address)
    {
        return $this->primitive($address->address);
    }

    public function includeLandmark(Address $address)
    {
        return $this->primitive($address->landmark);
    }

    public function includeAddressType(Address $address)
    {
        return $this->primitive($address->address_type);
    }

    public function includeMobile(Address $address)
    {
        return $this->primitive($address->mobile);
    }

    public function includePincode(Address $address)
    {
        return $this->primitive($address->pincode);
    }

    public function includeCity(Address $address)
    {
        return $this->item($address->city,new CityTransformer());
    }

    public function includeState(Address $address)
    {
        return $this->item($address->state,new StateTransformer());
    }

    public function includeLat(Address $address)
    {
        return $this->primitive($address->lat);
    }

    public function includeLng(Address $address)
    {
        return $this->primitive($address->lng);
    }

    public function includeIsVerified(Address $address)
    {
        return $this->primitive($address->isVerified);
    }

}
