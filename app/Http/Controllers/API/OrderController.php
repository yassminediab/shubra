<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\CartProduct;
use App\Order;
use App\OrderFeedback;
use App\OrderProduct;
use App\OrderStatus;
use App\Product;
use App\Setting;
use App\Transformers\CartTransformer;
use App\Transformers\OrderTransformer;
use App\Transformers\ProductTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Saad\Fractal\Fractal;

class OrderController extends ApiController
{
    public function createOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_id' => 'required|exists:carts,id',
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required',
            'delivery_time' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }
        $user = $request->user();
        $cart = Cart::find($request->cart_id);

        if(!$cart) {
            return $this->respondNotFound('Cart not found');
        }
        $delivery_fee = Setting::first()->delivery_fee;
        $order = Order::create([
            'transaction_id' =>  Str::random(8),
            'customer_id' => $user ?  $user->id : null,
            'discount' => $cart->discount,
            'weight' => $cart->weight,
            'price' => $cart->price,
            'total_items' => $cart->total_items,
            'total_item_price' => $cart->total_price,
            'delivery_time' => $request->delivery_time,
            'address_id' => $request->address_id,
            'payment_method' => $request->payment_method,
            'current_status' => 'confirmed',
            'delivery_fee' => $delivery_fee,
            'total_price' => $cart->total_price+$delivery_fee,
        ]);
        $cart_products = CartProduct::where('cart_id',$cart->id)->get();
        foreach ($cart_products as $cart_product) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $cart_product->product_id,
                'quantity' => $cart_product->quantity,
                'price' => $cart_product->price,
                'price_after_discount' => $cart_product->price_after_discount,
            ]);
        }
        foreach (Order::ORDER_STATUSES as $key => $status) {
            OrderStatus::create([
                'status' => $key,
                'order_id' => $order->id,
                'date' => $key == 'confirmed' ? Carbon::now() : null
            ]);
        }

        $order = Fractal::create($order, new OrderTransformer());


        return $this->respondSuccess('Order created successfully',$order);
    }

    public function getOrder($id)
    {
        $order = Order::with('products.product','statuses')->find($id);
        if(!$order) {
            return $this->respondNotFound('Order not found');
        }
        $transformedOrder = Fractal::create($order, new OrderTransformer());
        return $this->respondSuccess('',$transformedOrder);
    }

    public function listOrders(Request $request)
    {
        $onGoingOrders = Order::with('products.product','statuses')->where('customer_id',$request->user()->id)->where('current_status' , '<>' ,'delivered')->get();
        $historyOrders = Order::with('products.product','statuses')->where('customer_id',$request->user()->id)->whereIn('current_status'  ,['delivered','canceled'])->get();
        $transformedGoingOrders = Fractal::create($onGoingOrders, new OrderTransformer());
        $transformedHistoryOrder = Fractal::create($historyOrders, new OrderTransformer());
        return $this->respondSuccess('',[
            'ongoing' => $transformedGoingOrders,
            'history' => $transformedHistoryOrder,
        ]);
    }

    public function rateOrder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $order = Order::find($id);

        if(!$order) {
            return $this->respondNotFound('Order not found');
        }

        OrderFeedback::updateOrCreate([
            'order_id' => $id
        ],
        [
            'order_id' => $id,
            'order_feedback' => $request->rate,
            'order_comment' => $request->comment
        ]);

        return $this->respondSuccess('Order rated successfully');
    }


    public function rateDelivery(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        $order = Order::find($id);

        if(!$order) {
            return $this->respondNotFound('Order not found');
        }

        OrderFeedback::updateOrCreate([
            'order_id' => $id
        ],
            [
                'order_id' => $id,
                'delivery_feedback' => $request->rate,
                'delivery_comment' => $request->comment
            ]);

        return $this->respondSuccess('Delivery rated successfully');
    }
}
