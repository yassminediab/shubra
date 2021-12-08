<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Coupon extends Model
{
    use Translatable;
    protected $translatable = [ 'title' , 'description'];
    protected $fillable = ['start_date','end_date', 'title' , 'description' ,'coupon' ,'discount', 'user_id','product_id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
