<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Transformers\CategoryTransformer;
use App\Transformers\OfferTransformer;
use Saad\Fractal\Fractal;

class CategoryController extends ApiController
{
    public function index($id = null)
    {
        if(!$id) {
            $categories = Category::with(['children' => function ($query)  {
                $query->withCount('children');
            }])->withCount('children')->whereNull('parent')->orWhere('parent', 0)->get();
        } else {
            $categories = Category::where('parent', $id)->withCount('children')->get();
        }
        $categories = Fractal::create($categories, new CategoryTransformer(), null, 'children');

        return $this->respondSuccess('Categories returned successfully', $categories);
    }
}
