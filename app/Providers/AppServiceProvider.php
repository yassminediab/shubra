<?php

namespace App\Providers;

use App\Category;
use App\Coupon;
use App\Observers\CategoryObserver;
use App\Observers\CouponObserver;
use App\Observers\OfferObserver;
use App\Observers\ProductObserver;
use App\Offer;
use App\Product;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Offer::observe(OfferObserver::class);
        Coupon::observe(CouponObserver::class);
        Voyager::addAction(\App\Actions\OrderStatusAction::class);

    }
}
