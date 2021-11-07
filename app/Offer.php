<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Offer extends Model
{
    use Translatable;
    protected $translatable = ['title', 'description'];
    protected $fillable = ['offer_type_id','title', 'description','start_date','end_date','status','image'];

    public function offerTypeId()
    {
        return $this->belongsTo(OfferType::class, 'offer_type_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_offers')->withPivot('discount');
    }

}
