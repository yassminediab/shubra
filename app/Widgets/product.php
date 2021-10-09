<?php

namespace App\Widgets;

use App\Product as productModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Widgets\BaseDimmer;

class product extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count =  productModel::count();
        $string = 'products';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-news',
            'title'  => "{$count} {$string}",
            'text'   => __('dimmer.news_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => 'products',
                'link' => route('voyager.products.index'),
            ],
            'image' => asset('images/paige_spiranac_golf.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', app(productModel::class));
    }
}
