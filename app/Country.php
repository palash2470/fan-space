<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    public $timestamps = false;

    protected $table = 'countries';

    protected $primaryKey = 'country_id';
}
