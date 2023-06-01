<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Live_session_history extends Model
{

    //public $timestamps = false;
    protected $table = 'live_session_histories';
    protected $fillable = ['user_id', 'session_id', 'token', 'created_at', 'updated_at'];

    

}
