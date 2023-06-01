<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class User_meta extends Model
{
    
    protected $table = 'user_metas';

    protected $fillable = ['user_id', 'key', 'value', 'created_at', 'updated_at'];

    public function get_usermeta($user_id, $key=array()) {
    	$data = self::where(array('user_id'=>$user_id));
    	if(!empty($key)) {

    		$data->where(function ($query) use ($key) {
			    foreach ($key as $k => $v) {
	    			$query->orWhere('key',$v);
	    		}
			});
    	}
    	return $data->get();
    }


    public function update_usermeta($user_id, $keyval=array()) {
        foreach ($keyval as $k => $v) {
            $usermeta = self::updateOrCreate(
                ['user_id' => $user_id, 'key' => $k],
                ['value' => $v]
            );
        }
        
    }

}
