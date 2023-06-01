<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Zodiac extends Model
{

    public $timestamps = false;
    protected $table = 'zodiacs';
    protected $fillable = ['id', 'title'];

    

}
