<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['transaction_id','customer_id', 'weight','price','total_items','discount','total_price', 'delivery_date','delivery_slot','delivery_fee','current_status','address_id','payment_method','total_item_price', 'coupon','voucher' ,'coupon_value','voucher_value', 'total_products_price', 'vat'];

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function statuses()
    {
        return $this->hasMany(OrderStatus::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    const ORDER_STATUSES = [
        'confirmed' => [
            'en' => 'Order Confirmed',
            'ar' => 'تم التأكيد'
        ],
        'preparing' => [
            'en' => 'Order Preparing',
            'ar' => 'اعداد الطلب'
        ],
        'shipped' => [
            'en' => 'Order Shipped',
            'ar' => 'تم الشحن'
        ],
        'delivered' => [
            'en' => 'Delivery',
            'ar' => 'التوصيل'
        ],

        'canceled' => [
            'en' => 'Canceled',
            'ar' => 'الغا'
        ],
    ];

    const NEXT_STATUS = [
        'confirmed' => 'preparing',
        'preparing' => 'shipped',
        'shipped' => 'delivered',
        'delivered' => '',
    ];

    const PAYMENT_STATUSES = [
        'cash_on_delivery' => [
            'en' => 'Cash on delivery',
            'ar' => 'الدفع عند الاستلام'
        ]
    ];
}
