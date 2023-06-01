<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_own_cat extends Model
{
    //
    public $timestamps = false;

    protected $table = 'user_own_cats';

    protected $fillable = ['user_id', 'user_cat_id'];
}
