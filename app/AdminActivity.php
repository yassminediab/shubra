<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    protected $fillable = ['user_id', 'action', 'warning'];
}
