<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\CartProduct;
use App\Category;
use App\HomePage;
use App\Offer;
use App\Product;
use App\Slider;
use App\Transformers\CategoryTransformer;
use App\Transformers\SliderTransformer;
use App\Transformers\ProductTransformer;
use App\Transformers\BrandTransformer;
use App\Transformers\OfferTransformer;
use Illuminate\Http\Request;
use Saad\Fractal\Fractal;

class HomePageController extends ApiController
{
    public function index(Request $request)
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
        $categories = HomePage::with(['categories' => function($query) {
            $query->limit(10);
        }])->first();
        $offerProducts = HomePage::with(['offerProducts'=> function($query) {
            $query->limit(10);
        }])->first();
        $mostlyView = HomePage::with(['mostlyView'=> function($query) {
            $query->limit(10);
        }])->first();
        $slider = Slider::where('status', 1)->get();
        $brands = Brand::limit(10)->get();
        $productsDiscounted = HomePage::with(['discountedProducts'=> function($query) {
            $query->limit(10);
        }])->first();
        $topProducts = HomePage::with(['topProducts' => function($query) {
            $query->limit(10);
        }])->first();

        $categories = Fractal::create($categories->categories, new CategoryTransformer(), null, null);
        $brands = Fractal::create($brands, new BrandTransformer());
        $sliders = Fractal::create($slider, new SliderTransformer());$offerProducts = Fractal::create($offerProducts->offerProducts, new ProductTransformer($cartProductIds, $wishlistProductIds));
        $mostlyView = Fractal::create($mostlyView->mostlyView, new ProductTransformer($cartProductIds, $wishlistProductIds));
        $topProducts = Fractal::create($topProducts->topProducts, new ProductTransformer($cartProductIds, $wishlistProductIds));
        $productsDiscounted = Fractal::create($productsDiscounted->discountedProducts, new OfferTransformer());

        return $this->respondSuccess('Home Page returned successfully', [
            $this->getHomePageObject('topSlider','sliderList',$sliders,1),
            $this->getHomePageObject('specialOffer','specialOfferList',$offerProducts,2),
            $this->getHomePageObject('category','categoryList',$categories,3),
            $this->getHomePageObject('mostlyViewed','mostlyViewedList',$mostlyView,4),
            $this->getHomePageObject('brand','brandList',$brands,5),
            $this->getHomePageObject('discount','discountList',$productsDiscounted,6),
            $this->getHomePageObject('topProduct','topProductList',$topProducts,7),
        ]);
    }

    public function getHomePageObject($type, $arrayName, $array, $sort) {
        return [
            'type' => $type,
            'sort' => $sort,
            'key' => $array
        ];
    }

    public function getHomePageByType(Request $request, $type) {
        $cartProductIds = [];
        if($request->input('cart_id')) {
            $cartProductIds = CartProduct::where('cart_id', $request->input('cart_id'))->get()->pluck('quantity','product_id')->toArray();
        }

        $user = $request->user();
        $wishlistProductIds = [];
        if($user) {
            $wishlistProductIds = $user->wishlist->pluck('id')->toArray();
        }

        if($type == 'category') {
            $categories = Category::has('homePage')->paginate(10);
            $transformedCategories = Fractal::create($categories, new CategoryTransformer(), null, null);
            return $this->respondPaginated('',['data' => $transformedCategories],$categories);
        }

        if($type == 'mostlyViewed') {
            $mostlyView = Product::has('homePage')->paginate(10);
            $transformedMostlyView = Fractal::create($mostlyView, new ProductTransformer($cartProductIds, $wishlistProductIds));
            return $this->respondPaginated('',['data' => $transformedMostlyView],$mostlyView);
        }

        if($type == 'topProduct') {
            $product = Product::has('topProducts')->paginate(10);
            $transformedProducts = Fractal::create($product, new ProductTransformer($cartProductIds, $wishlistProductIds));
            return $this->respondPaginated('',['data' => $transformedProducts],$product);
        }

        if($type == 'specialOffer') {
            $product = Product::has('offerProducts')->paginate(10);
            $transformedProducts = Fractal::create($product, new ProductTransformer($cartProductIds, $wishlistProductIds));
            return $this->respondPaginated('',['data' => $transformedProducts],$product);
        }

        if($type == 'discount') {
            $product = Offer::has('discountedProducts')->paginate(10);
            $transformedProducts = Fractal::create($product, new OfferTransformer());
            return $this->respondPaginated('',['data' => $transformedProducts],$product);
        }

        if($type == 'brand') {
            $product = Brand::paginate(10);
            $transformedProducts = Fractal::create($product, new BrandTransformer());
            return $this->respondPaginated('',['data' => $transformedProducts],$product);
        }
    }
}
