<?php

namespace App\Transformers;

use App\Product;
use Saad\Fractal\Transformers\TransformerAbstract;
use function PHPUnit\Framework\isEmpty;

class ProductTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['id','name', 'description', 'image','price', 'offer','rate','size_per_unit','unit_of_measure', 'quantity', 'images','added_to_cart', 'is_wishlist','cart_item_quantity', 'price_after_discount'];

    protected $cartProductIds;

    protected $wishlistProductIds;

    public function __construct($cartProductIds = [], $wishlistProductIds = [])
    {
        $this->cartProductIds = $cartProductIds;
        $this->wishlistProductIds = $wishlistProductIds;
    }

    public function includeId(Product $product)
    {
        return $this->primitive($product->id);
    }

    public function includeName(Product $product)
    {
        return $this->primitive($product->getTranslatedAttribute('name', app()->getLocale()));
    }

    public function includeImage(Product $product)
    {
        return $this->primitive(getImageUrl($product->image));
    }

    public function includeDescription(Product $product)
    {
        return $this->primitive($product->getTranslatedAttribute('description', app()->getLocale()));
    }

    public function includePrice(Product $product)
    {
        return $this->primitive((float)$product->price);
    }

    public function includeOffer(Product $product)
    {
        if($product->pivot) {
            $offer = $product->pivot->discount;
        } elseif ($product->offers->count() > 0)  {
            $offer = $product->offers[0]->pivot->discount;
        }
        return $this->primitive($offer ?? 0);
    }

    public function includeRate(Product $product)
    {
        return $this->primitive($product->rate);
    }

    public function includeUnitOfMeasure(Product $product)
    {
        return $this->primitive($product->unit_of_measure);
    }

    public function includeSizePerUnit(Product $product)
    {
        return $this->primitive($product->size_per_unit);
    }

    public function includeQuantity(Product $product)
    {
        return $this->primitive($product->quantity);
    }

    public function includeImages(Product $product)
    {
        $images = [];
        if($product->multiple_images) {
            foreach (json_decode($product->multiple_images) as $image) {
                $images[] = getImageUrl($image);
            }
        }
        return $this->primitive($images);
    }

    public function includePriceAfterDiscount(Product $product) {
        if($product->pivot) {
            $offer = $product->pivot->discount;
        } elseif ($product->offers->count() > 0)  {
            $offer = $product->offers[0]->pivot->discount;
        }
        $offer = $offer ?? 0;
        $price_after_discount = $product->price - ($product->price * $offer / 100);
        return $this->primitive($price_after_discount);
    }

    public function includeAddedToCart(Product $product) {
        return $this->primitive(isset($this->cartProductIds[$product->id]));
    }

    public function includeCartItemQuantity(Product $product) {
        return $this->primitive($this->cartProductIds[$product->id] ?? 0);
    }

    public function includeIsWishlist(Product $product) {
        return $this->primitive(in_array($product->id, $this->wishlistProductIds));
    }
}
