<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_react extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'post_reacts';

    protected $fillable = ['post_id', 'user_id'];
}
