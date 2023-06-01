<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_fav_user extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'user_fav_users';

    protected $fillable = ['user_id', 'fav_user_id'];
}
