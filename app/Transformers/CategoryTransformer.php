<?php

namespace App\Transformers;


use App\Category;
use Saad\Fractal\Transformers\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id', 'name', 'image','children_count'];
    protected $availableIncludes = ['children'];

    public function transformWithDefault(Category $category)
    {
        return [

        ];
    }

    public function includeId(Category $category)
    {
        return $this->primitive($category->id);
    }

    public function includeName(Category $category)
    {
        return $this->primitive($category->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(Category $category)
    {
        return $this->primitive($category->image);
    }

    public function includeChildrenCount(Category $category)
    {
        return $this->primitive($category->children_count);
    }

    public function includeChildren(Category $category)
    {
        return $this->collection($category->children, new CategoryTransformer());
    }
}
