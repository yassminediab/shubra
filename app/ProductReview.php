<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['product_id','user_id', 'comment','rate'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
