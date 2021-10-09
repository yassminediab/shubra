<?php

namespace App\Transformers;


use App\Brand;
use App\Order;
use App\OrderStatus;
use Saad\Fractal\Transformers\TransformerAbstract;

class OrderStatusTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['code', 'name' , 'date'];

    public function includeName(OrderStatus $status)
    {
        return $this->primitive(Order::ORDER_STATUSES[$status->status][app()->getLocale()]);
    }

    public function includeCode(OrderStatus $status)
    {
        return $this->primitive($status->status);
    }

    public function includeDate(OrderStatus $status)
    {
        return $this->primitive($status->date);
    }
}
