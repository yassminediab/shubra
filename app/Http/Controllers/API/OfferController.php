<?php

namespace App\Http\Controllers\API;

use App\Offer;
use App\OfferType;
use App\Transformers\OfferTransformer;
use App\Transformers\OfferTypeTransformer;
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

    public function show($id)
    {
        $offers= Offer::where('id', $id)->with('products')->first();

        $offers = Fractal::create($offers, new OfferTransformer(), null, 'products');

        return $this->respondSuccess('Offers returned successfully', $offers);
    }
}
