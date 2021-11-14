<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\HomePage;
use App\Slider;
use App\Transformers\CategoryTransformer;
use App\Transformers\SliderTransformer;
use App\Transformers\ProductTransformer;
use App\Transformers\BrandTransformer;
use App\Transformers\OfferTransformer;
use Saad\Fractal\Fractal;

class HomePageController extends ApiController
{
    public function index()
    {
        $categories = HomePage::with('categories')->first();
        $offerProducts = HomePage::with('offerProducts')->first();
        $mostlyView = HomePage::with('mostlyView')->first();
        $slider = Slider::where('status', 1)->get();
        $brands = Brand::all();
        $productsDiscounted = HomePage::with('discountedProducts')->first();
        $topProducts = HomePage::with('topProducts')->first();

        $categories = Fractal::create($categories->categories, new CategoryTransformer(), null, null);
        $brands = Fractal::create($brands, new BrandTransformer());
        $sliders = Fractal::create($slider, new SliderTransformer());
        $offerProducts = Fractal::create($offerProducts->offerProducts, new ProductTransformer());
        $mostlyView = Fractal::create($mostlyView->mostlyView, new ProductTransformer());
        $topProducts = Fractal::create($topProducts->topProducts, new ProductTransformer());
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
}
