<?php

namespace App\Http\Controllers\API;

use App\Offer;
use App\Product;
use App\ProductReview;
use App\Transformers\OfferTransformer;
use App\Transformers\ProductReviewTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Saad\Fractal\Fractal;

class ProductController extends ApiController
{
    public function reviewProduct($id , Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|integer|min:0|max:5',
        ]);

        if ($validator->fails()) {
            return $this->respondBadRequest("", ['errors' => $validator->errors()]);
        }

        ProductReview::updateOrCreate([
            'product_id' => $id,
            'user_id' => $request->user()->id
        ],[
            'product_id' => $id,
            'rate' => $request->rate,
            'comment' => $request->comment,
            'user_id' => $request->user()->id
        ]);

        $reviews = ProductReview::where('product_id',$id)->get();
        $rate = $reviews->sum('rate')/$reviews->count();

        Product::where('id', $id)->update(['rate' => $rate]);
        return $this->respondSuccess('Review created successfully');
    }

    public function getProductReviews($id)
    {
        $reviews = ProductReview::with('user')->where('product_id',$id)->paginate(10);
        $product = Product::find($id);

        $transformedReviews = Fractal::create($reviews, new ProductReviewTransformer());

        $groupedRates = DB::table('product_reviews')
            ->select('rate', DB::raw('count(*) as count'))
            ->groupBy('rate')
            ->get()->keyBy('rate');

        $rates = [
            [
                'rate' => 5,
                'count' => $groupedRates[5]->count ?? 0
            ],
            [
                'rate' => 4,
                'count' => $groupedRates[4]->count ?? 0
            ],
            [
                'rate' => 3,
                'count' => $groupedRates[3]->count ?? 0
            ],
            [
                'rate' => 2,
                'count' => $groupedRates[2]->count ?? 0
            ],
            [
                'rate' => 1,
                'count' => $groupedRates[1]->count ?? 0
            ]
        ];
        return $this->respondPaginated('Review returned successfully',
            ['reviews' => $transformedReviews, 'rates' => $rates,'product_rate' => $product->rate],
            $reviews, null);
    }

    public function getProduct($id)
    {
        $product = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        }])->find($id);
        $transformedProduct = Fractal::create($product, new ProductTransformer());

        $similarProducts = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        }])->whereHas('categories',function ($query) use ($product) {
            $query->whereIn('categories.id',$product->categories->pluck('id'));
        })->limit(6)->get();
        $transformedSimilarProduct = Fractal::create($similarProducts, new ProductTransformer());

        return $this->respondSuccess('product returned successfully',['product' => $transformedProduct,'similar_products'=> $transformedSimilarProduct]);
    }
}
