<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Category extends Model
{
    use Translatable;
    protected $translatable = ['name', 'description', 'meta_description', 'meta_title', 'meta_keyword'];
}
