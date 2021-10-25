<?php

namespace App\Http\Controllers\Voyager;

use App\OrderStatus;
use Carbon\Carbon;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Order;

class OrderController extends VoyagerBaseController
{
    public function changeStatus(){
        Order::where('id', \request("id"))->update(['current_status' => \request("status")]);
        OrderStatus::where(['order_id' => \request("id"), 'status' => \request("status")])->update([
            'date' => Carbon::now()
        ]);
       return redirect(route('voyager.orders.index'));
    }
}
