<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateOldProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('products')->delete();
        $products = DB::connection('mysql2')->table('oc_product')->get();
        foreach ($products as $product) {
            $product_en = DB::connection('mysql2')->table('oc_product_description')->where('product_id',$product->product_id)->where('language_id',1)->first();
            $product_ar = DB::connection('mysql2')->table('oc_product_description')->where('product_id',$product->product_id)->where('language_id',2)->first();
            $new_product = \App\Product::create([
                'id' => $product->product_id,
                'name' => $product_en->name,
                'description' => $product_en->description,
                'meta_title' => $product_en->meta_title,
                'meta_description' => $product_en->meta_description,
                'meta_keyword' => $product_en->meta_keyword,
                'tag' => $product_en->tag,
                'image' => $product->image,
                'status' => $product->status,
                'sku' => $product->sku,
                'quantity' => $product->quantity,
                'model' => $product->model,
                'date_available' => $product->date_available,
                'price' => $product->price,
            ]);
            $new_product = $new_product->translate('ar');
            $new_product->name = $product_ar->name;
            $new_product->description = $product_ar->description;
            $new_product->meta_title = $product_ar->meta_title;
            $new_product->meta_description = $product_ar->meta_description;
            $new_product->meta_keyword = $product_ar->meta_keyword;
            $new_product->tag = $product_ar->tag;
            $new_product->save();
            $product_images =  DB::connection('mysql2')->table('oc_product_image')->where('product_id',$product->product_id)->get();
            foreach ($product_images as $image) {
                DB::table('product_images')->insert(['product_id'=>$product->product_id, 'image' => $image->image]);
            }

            $product_categories =  DB::connection('mysql2')->table('oc_product_to_category')->where('product_id',$product->product_id)->get();
            foreach ($product_categories as $category) {
                DB::table('product_categories')->insert(['product_id'=>$product->product_id, 'category_id' => $category->category_id]);
            }

            $related_products =  DB::connection('mysql2')->table('oc_product_related')->where('product_id',$product->product_id)->get();
            foreach ($related_products as $related) {
                DB::table('related_products')->insert(['product_id'=>$product->product_id, 'related_id' => $related->related_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
