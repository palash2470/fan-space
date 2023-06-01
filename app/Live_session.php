<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class Live_session extends Model
{

    //public $timestamps = false;
    protected $table = 'live_sessions';
    protected $fillable = ['user_id', 'session_id', 'token', 'ts', 'created_at', 'updated_at'];

    public static function delete_inactive() {
    	$time = time();
    	self::where('ts', '<=', ($time - 30))->delete();
    }

    

}
