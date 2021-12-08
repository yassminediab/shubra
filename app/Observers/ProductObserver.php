<?php

namespace App\Observers;

use App\AdminActivity;
use App\Product as Model;
use Illuminate\Support\Str;

class ProductObserver
{
    public function creating(Model $product)
    {
        if(auth()->user()) {
            AdminActivity::create([
                'user_id' => auth()->user()->id,
                'action' => auth()->user()->name . 'إضافة منتج جديد بواسطة ' . $product->name
            ]);
        }
    }

    public function updated(Model $product)
    {
        if(!auth()->guest()) {
            AdminActivity::create([
                'user_id' => auth()->user()->id,
                'action' => auth()->user()->name . 'قام بتعديل فى منتج  ' . $product->name
            ]);
        }
    }

    public function deleted(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بمسح منتج  '. $product->name
        ]);
    }
}
