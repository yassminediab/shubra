<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Product extends Model
{
    use Translatable;
    protected $translatable = ['name', 'description', 'meta_description', 'meta_title', 'meta_keyword','tag'];
    protected $fillable = ['id','name','description', 'meta_description', 'meta_title', 'meta_keyword','status','image','model','sku'
        ,'date_available','quantity','price','tag','multiple_images'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offers')->withPivot('discount');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function scopeHasOffer($query)
    {
        return $query->has('offers');
    }

    public function mostlyViewed()
    {
        return $this->belongsToMany(HomePage::class, 'homepage_mostly_view_product');
    }

    public function topProducts()
    {
        return $this->belongsToMany(HomePage::class, 'homepage_top_product');
    }

    public function offerProducts()
    {
        return $this->belongsToMany(HomePage::class, 'homepage_offer_product');
    }

}
