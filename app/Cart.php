<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'weight','price','total_items','discount','total_price','is_active'];

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }

}