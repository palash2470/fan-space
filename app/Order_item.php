<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order_item extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'order_items';

    protected $fillable = ['order_id', 'product_id', 'type', 'title', 'price', 'attachment', 'quantity'];

    public function order_details(){
        return $this->belongsTo('App\Order','order_id');
    }
    public function product_details(){
        return $this->belongsTo('App\Product','product_id');
    }
}
