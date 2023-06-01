<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin_price_plan extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'coin_price_plans';

    protected $fillable = ['title', 'info', 'coins', 'price', 'featured', 'active'];
}
