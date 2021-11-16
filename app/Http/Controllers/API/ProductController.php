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
            ->where('product_id',$product->id)
            ->groupBy('rate')
            ->get()->keyBy('rate');

        $count = DB::table('product_reviews')
            ->where('product_id',$product->id)
            ->count();

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
            ['reviews' => $transformedReviews, 'rates' => $rates,'product_rate' => $product->rate, 'review_count' => $count],
            $reviews);
    }

    public function getProduct($id)
    {
        $product = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        }, 'images'])->find($id);
        $transformedProduct = Fractal::create($product, new ProductTransformer());

        $product->number_of_views = $product->number_of_views +1;
        $product->save();

        $similarProducts = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        }])->whereHas('categories',function ($query) use ($product) {
            $query->whereIn('categories.id',$product->categories->pluck('id'));
        })->limit(6)->get();
        $transformedSimilarProduct = Fractal::create($similarProducts, new ProductTransformer());

        return $this->respondSuccess('product returned successfully',['product' => $transformedProduct,'similar_products'=> $transformedSimilarProduct]);
    }

    public function wishlistProduct($id, Request $request)
    {
        $user = $request->user();

        $user->wishlist()->attach([$id]);

        return $this->respondSuccess('Wishlist created successfully');
    }

    public function getCategoryProducts($id, Request $request) {
        $query = $request->query;
        $sort_field = $query->sort_by ?? 'created_at';
        $sort = $query->sort ?? 'desc';
        $products = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        },'images'])->whereHas('categories',function ($query) use ($id) {
            $query->where('categories.id',$id);
        })->orderBy($sort_field)->orderBy($sort)->paginate(6);

        $transformedSimilarProduct = Fractal::create($products, new ProductTransformer())->toArray();
        return $this->respondPaginated('product returned successfully',['products' => $transformedSimilarProduct],$products);

    }

    public function searchProducts(Request $request) {
        $products = Product::with(['offers'=> function($query) {
            $query->latest()->first();
        },'images'])->where('name','like','%'.$request->input('query').'%')->paginate(6);

        $transformedSimilarProduct = Fractal::create($products, new ProductTransformer())->toArray();
        return $this->respondPaginated('product returned successfully',['products' => $transformedSimilarProduct],$products);

    }
}
