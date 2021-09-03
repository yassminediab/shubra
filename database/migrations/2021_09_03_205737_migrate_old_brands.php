<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOldBrands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('brands')->delete();
        $categories = DB::connection('mysql2')->table('oc_category')->get();
        foreach ($categories as $category) {
            $category_en = DB::connection('mysql2')->table('oc_category_description')->where('category_id',$category->category_id)->where('language_id',1)->first();
            $category_ar = DB::connection('mysql2')->table('oc_category_description')->where('category_id',$category->category_id)->where('language_id',2)->first();
            $new_category = \App\Category::create([
                'id' => $category->category_id,
                'name' => $category_en->name,
                'description' => $category_en->description,
                'meta_title' => $category_en->meta_title,
                'meta_description' => $category_en->meta_description,
                'meta_keyword' => $category_en->meta_keyword,
                'image' => $category->app_image,
                'status' => $category->status,
                'parent' => $category->parent_id,
            ]);
            $new_category = $new_category->translate('ar');
            $new_category->name = $category_ar->name;
            $new_category->description = $category_ar->description;
            $new_category->meta_title = $category_ar->meta_title;
            $new_category->meta_description = $category_ar->meta_description;
            $new_category->meta_keyword = $category_ar->meta_keyword;
            $new_category->save();
        }
        die;
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
