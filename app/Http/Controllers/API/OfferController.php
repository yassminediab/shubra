<?php

namespace App\Http\Controllers\API;

use App\CartProduct;
use App\Offer;
use App\OfferType;
use App\Transformers\OfferTransformer;
use App\Transformers\OfferTypeTransformer;
use Illuminate\Http\Request;
use Saad\Fractal\Fractal;

class OfferController extends ApiController
{
    public function getOffersWithTypes() {
        $offers = OfferType::with('offers')->get();
        $offersTransformed = Fractal::create($offers, new OfferTypeTransformer());
        return $this->respondAccepted('',$offersTransformed);
    }

    public function index()
    {
        $offers = Offer::all();

        $offers = Fractal::create($offers, new OfferTransformer());

        return $this->respondSuccess('Offers returned successfully', $offers);
    }

    public function show($id, Request $request)
    {

        $cartProductIds = [];
        if($request->input('cart_id')) {
            $cartProductIds = CartProduct::where('cart_id', $request->input('cart_id'))->get()->pluck('quantity','product_id')->toArray();
        }

        $user = $request->user();
        $wishlistProductIds = [];
        if($user) {
            $wishlistProductIds = $user->wishlist->pluck('id')->toArray();
        }

        $offers= Offer::where('id', $id)->with('products')->first();

        $offers = Fractal::create($offers, new OfferTransformer($cartProductIds,$wishlistProductIds), null, 'products');

        return $this->respondSuccess('Offers returned successfully', $offers);
    }
}
