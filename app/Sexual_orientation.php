<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Sexual_orientation extends Model
{

    public $timestamps = false;
    protected $table = 'sexual_orientations';
    protected $fillable = ['id', 'title'];

    

}
