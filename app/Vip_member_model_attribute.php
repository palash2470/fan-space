<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Vip_member_model_attribute extends Model
{

    public $timestamps = false;
    protected $table = 'vip_member_model_attributes';
    protected $fillable = ['id', 'user_id', 'model_attribute_id'];

    

}
