<?php

namespace App\Transformers;

use App\Cart;
use App\Order;
use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','transaction_id','price','weight','discount','total_items','total_price','products','delivery_time','delivery_fee','current_status','address','payment_method','total_item_price','statuses', 'packages'];

    public function includeId(Order $order)
    {
        return $this->primitive($order->id);
    }

    public function includeTransactionId(Order $order)
    {
        return $this->primitive($order->transaction_id);
    }


    public function includePrice(Order $order)
    {
        return $this->primitive($order->price);
    }

    public function includeWeight(Order $order)
    {
        return $this->primitive($order->weight);
    }

    public function includeDiscount(Order $order)
    {
        return $this->primitive($order->discount);
    }

    public function includeTotalItems(Order $order)
    {
        return $this->primitive($order->total_items);
    }

    public function includeTotalPrice(Order $order)
    {
        return $this->primitive($order->total_price);
    }

    public function includeTotalItemPrice(Order $order)
    {
        return $this->primitive($order->total_item_price);
    }

    public function includeProducts(Order $order)
    {
        return $this->collection($order->products,new OrderProductTransformer());
    }

    public function includePaymentMethod(Order $order)
    {
        return $this->primitive($order->payment_method);
    }
    public function includeDeliveryTime(Order $order)
    {
        return $this->primitive($order->delivery_time);
    }
    public function includeDeliveryFee(Order $order)
    {
        return $this->primitive($order->delivery_fee);
    }
    public function includeCurrentStatus(Order $order)
    {
        return $this->primitive([
            'name' => Order::ORDER_STATUSES[$order->current_status][app()->getLocale()],
            'code' => $order->current_status
        ]);
    }
    public function includeAddress(Order $order)
    {
        return $this->item($order->address, new AddressTransformer());
    }
    public function includeStatuses(Order $order)
    {
        return $this->collection($order->statuses, new OrderStatusTransformer());
    }

    public function includePackages(Order $order)
    {
        return $this->primitive($order->packages);
    }
}
