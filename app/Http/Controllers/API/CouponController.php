<?php

namespace App\Http\Controllers\API;

use App\Coupon;
use App\Offer;
use App\OfferType;
use App\Transformers\CouponTransformer;
use App\Transformers\OfferTransformer;
use App\Transformers\OfferTypeTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Saad\Fractal\Fractal;

class CouponController extends ApiController
{

    public function index(Request $request)
    {
        $coupons = Coupon::with(['product'])->where('user_id', $request->user()->id)->where('start_date','<=', Carbon::today())->where('end_date','>=',Carbon::today())->get();

        $coupons = Fractal::create($coupons, new CouponTransformer());

        return $this->respondSuccess('Coupons returned successfully', $coupons);
    }

    public function get($id)
    {
        $coupons = Coupon::with('product')->find($id);

        $coupons = Fractal::create($coupons, new CouponTransformer());

        return $this->respondSuccess('Coupons returned successfully', $coupons);
    }
}
