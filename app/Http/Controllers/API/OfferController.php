<?php

namespace App\Http\Controllers\API;

use App\Offer;
use App\Transformers\OfferTransformer;
use Saad\Fractal\Fractal;

class OfferController extends ApiController
{
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
