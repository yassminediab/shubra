<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['full_name', 'address','landmark','address_type','city_id','pincode','state_id','mobile','lat','lng','user_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
