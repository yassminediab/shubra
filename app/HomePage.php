<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'homepage_category')->where('status', 1);
    }

    public function offerProducts()
    {
        return $this->belongsToMany(Product::class, 'homepage_offer_product')->where('status', 1);
    }

    public function mostlyView()
    {
        return $this->belongsToMany(Product::class, 'homepage_mostly_view_product')->where('status', 1);
    }

    public function topProducts()
    {
        return $this->belongsToMany(Product::class, 'homepage_top_product')->where('status', 1);
    }

    public function discountedProducts()
    {
        return $this->belongsToMany(Offer::class, 'homepage_discount')->where('status', 1);
    }

}
