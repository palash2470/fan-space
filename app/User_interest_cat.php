<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_interest_cat extends Model
{
    //
    public $timestamps = false;

    protected $table = 'user_interest_cats';

    protected $fillable = ['user_id', 'user_cat_id'];
}
