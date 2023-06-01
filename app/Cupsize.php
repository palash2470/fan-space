<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Cupsize extends Model
{

    public $timestamps = false;
    protected $table = 'cupsizes';
    protected $fillable = ['id', 'title'];

    

}
