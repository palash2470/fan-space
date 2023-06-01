<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_comment_react extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'post_comment_reacts';

    protected $fillable = ['post_comment_id', 'user_id'];
}
