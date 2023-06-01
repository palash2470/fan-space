<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Model_attribute extends Model
{

    public $timestamps = false;
    protected $table = 'model_attributes';
    protected $fillable = ['id', 'title'];

    

}
