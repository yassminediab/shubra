<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class OfferType extends Model
{
    protected $fillable = ['type'];
    use Translatable;


    public function offers() {
        return $this->hasMany(Offer::class);
    }
}
