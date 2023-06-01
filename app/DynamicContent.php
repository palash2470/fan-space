<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicContent extends Model
{
    protected $table='dynamic_content';
    protected $guarded=[];
    protected $casts = [
        'content' => 'array'
    ];
}
