<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_follow_user extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'user_follow_users';

    protected $fillable = ['user_id', 'follow_user_id'];
}
