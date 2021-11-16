<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'weight','price','total_items','discount','total_price','is_active','coupon','voucher' ,'coupon_value','voucher_value','fees'];

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }

}
