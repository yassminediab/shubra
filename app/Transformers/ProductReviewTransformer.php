<?php

namespace App\Transformers;


use App\Brand;
use App\ProductReview;
use Saad\Fractal\Transformers\TransformerAbstract;

class ProductReviewTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','rate', 'comment', 'user', 'date'];

    public function includeId(ProductReview $review)
    {
        return $this->primitive($review->id);
    }

    public function includeComment(ProductReview $review)
    {
        return $this->primitive($review->comment);
    }

    public function includeRate(ProductReview $review)
    {
        return $this->primitive($review->rate);
    }

    public function includeDate(ProductReview $review)
    {
        return $this->primitive($review->created_at);
    }

    public function includeUser(ProductReview $review)
    {
        return $this->item($review->user, new UserTransformer());
    }
}
