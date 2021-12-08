<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'total_products_price','weight','price','total_items','discount','total_price','is_active','coupon','voucher' ,'coupon_value','voucher_value','fees'];

    public function products()
    {
        return $this->hasMany(CartProduct::class);
    }

    const PRICES = [
        'price' => [
            'en' => 'Price',
            'ar' => 'السعر'
        ],
        'delivery' => [
            'en' => 'Delivery fees',
            'ar' => 'الشحن'
        ],
        'coupon' => [
            'en' => 'Coupon',
            'ar' => 'كوبون'
        ],
        'voucher' => [
            'en' => 'Voucher',
            'ar' => 'خصم'
        ],
        'vat' => [
            'en' => 'Vat',
            'ar' => 'الضريبه المضافه'
        ],
        'total' => [
            'en' => 'Total',
            'ar' => 'المجموع'
        ],
    ];

}
