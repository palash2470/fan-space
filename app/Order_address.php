<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_address extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'order_addresses';

    protected $fillable = ['order_id', 'type', 'first_name', 'last_name', 'company_name', 'address_line_1', 'country_id', 'zip_code'];
}
