<?php

namespace App;

use Helpers;
use App\Setting as Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    //public $timestamps = false;
    protected $table = 'settings';


    public static function get_list(){
		$data = array();
        $settings = DB::table('settings')->get();
        foreach ($settings as $key => $value) {
            $data[$value->key] = $value->value;
        }
        return $data;
	}


	public static function get_item($keys = []){
		$data = array();
		$settings = DB::table('settings');
		if(is_array($keys) && count($keys) > 0)
        	$settings->whereIn('key', $keys);
        if(!is_array($keys) && $keys != '')
        	$settings->where('key', $keys);
        $settings = $settings->get();
        foreach ($settings as $key => $value) {
            $data[$value->key] = $value->value;
        }
        if(!is_array($keys) && $keys != '')
        	return ($data[$keys] ?? '');
        else
        	return $data;
	}

}
