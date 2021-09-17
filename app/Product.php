<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Product extends Model
{
    use Translatable;
    protected $translatable = ['name', 'description', 'meta_description', 'meta_title', 'meta_keyword','tag'];
    protected $fillable = ['id','name','description', 'meta_description', 'meta_title', 'meta_keyword','status','image','model','sku'
        ,'date_available','quantity','price','tag'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offer');
    }

    public function scopeHasOffer($query)
    {
        return $query->has('offers');
    }
}
