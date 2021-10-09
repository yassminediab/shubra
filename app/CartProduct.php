<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected $fillable = ['cart_id', 'product_id','quantity','price','price_after_discount'];
    protected $table = 'cart_product';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
