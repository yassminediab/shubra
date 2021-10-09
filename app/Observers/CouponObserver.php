<?php

namespace App\Observers;

use App\AdminActivity;
use App\Coupon as Model;
use Illuminate\Support\Str;

class CouponObserver
{
    public function creating(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'إضافة كود خصم جديد بواسطة '. $product->coupon
        ]);
    }

    public function updated(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بتعديل فى كود خصم  '. $product->coupon
        ]);
    }

    public function deleted(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بمسح كود خصم  '. $product->coupon
        ]);
    }
}
