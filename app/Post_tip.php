<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post_tip extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'post_tips';

    protected $fillable = ['post_id', 'tipper_id'];
}
