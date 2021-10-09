<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderFeedback extends Model
{
    protected $fillable = ['order_id' ,'order_feedback', 'order_comment' ,'delivery_feedback' ,'delivery_comment'];
}
