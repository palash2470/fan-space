<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_paid_user extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'post_paid_users';

    protected $fillable = ['post_id', 'user_id'];
}
