<?php

namespace App\Observers;

use App\AdminActivity;
use App\Category as Model;
use Illuminate\Support\Str;

class CategoryObserver
{
    public function creating(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'إضافة قسم جديد بواسطة '. $product->name
        ]);
    }

    public function updated(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بتعديل فى قسم  '. $product->name
        ]);
    }

    public function deleted(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بمسح قسم  '. $product->name,
            'warning' => 'هذا قد يؤدى الى مسح جميع المنتجات الملتحقه بهذا القسم'
        ]);
    }
}
