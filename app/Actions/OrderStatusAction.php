<?php

namespace App\Actions;

use App\Order;
use TCG\Voyager\Actions\AbstractAction;

class OrderStatusAction extends AbstractAction
{
    public function getTitle()
    {
        // Action title which display in button based on current status
        if($this->data->{'current_status'} == 'confirmed') {
            return 'إعداد الطلب';
        }elseif($this->data->{'current_status'} == 'preparing') {
            return  'تم الشحن';
        }elseif($this->data->{'current_status'} == 'shipped') {
            return 'تم التوصيل';
        }

      //  return $this->data->{'status'}=="confirmed"?'preparing':'confirmed';
    }

    public function getIcon()
    {
        // Action icon which display in left of button based on current status
        return $this->data->{'status'}=="PUBLISHED"?'voyager-x':'voyager-external';
    }

    public function getAttributes()
    {
        // Action button class
        return [
            'class' => 'btn btn-sm btn-primary pull-left',
        ];
    }

    public function shouldActionDisplayOnRow($row)
    {
        return $row->current_status != 'delivered';
    }

    public function shouldActionDisplayOnDataType()
    {
        // show or hide the action button, in this case will show for posts model
        return $this->dataType->slug == 'orders';
    }

    public function getDefaultRoute()
    {
        // URL for action button when click
        return route('orders.status', array("id"=>$this->data->{$this->data->getKeyName()},'status' => Order::NEXT_STATUS[$this->data->{'current_status'}]));
    }
}
