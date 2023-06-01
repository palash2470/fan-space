<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reported_item extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'reported_items';

    protected $fillable = ['type', 'item_id', 'user_id', 'comment', 'status'];
}
