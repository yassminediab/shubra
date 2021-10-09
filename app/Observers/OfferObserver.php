<?php

namespace App\Observers;

use App\AdminActivity;
use App\Offer as Model;
use Illuminate\Support\Str;

class OfferObserver
{
    public function creating(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'إضافة عرض جديد بواسطة '. $product->name
        ]);
    }

    public function updated(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بتعديل فى عرض  '. $product->title
        ]);
    }

    public function deleted(Model $product)
    {
        AdminActivity::create([
            'user_id' => auth()->user()->id,
            'action' => auth()->user()->name.'قام بمسح عرض  '. $product->title,
        ]);
    }
}
