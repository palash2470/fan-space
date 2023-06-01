<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User_earning extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'user_earnings';

    protected $fillable = ['user_id', 'token_coins', 'referral_user_id', 'referral_token_coins', 'post_paid_users_id', 'post_tips_id', 'live_session_tips_id', 'order_id','private_chat_id'];

    public static function earnings($param) {
    	$user_id = $param['user_id'] ?? '';
    	$order_by = $param['order_by'] ?? [["sub.created_at", 'desc', 0]];
      $cur_page = $param['cur_page'] ?? '1';
      $per_page = $param['per_page'] ?? 20;
      $return_all = $param['return_all'] ?? false;
      $limit_start = ($cur_page - 1) * $per_page;
      $user_earnings = self::select('user_earnings.*')->where(function($q) use ($user_id){
      	$q->orWhere('user_earnings.user_id', $user_id)->orWhere('user_earnings.referral_user_id', $user_id);
      });
      /*foreach ($order_by as $key => $value) {
          if(isset($value[2]) && $value[2] == '1') {
              $user_earnings = $user_earnings->orderBy(DB::raw($value[0]), $value[1]);
          } else {
              $user_earnings = $user_earnings->orderBy($value[0], $value[1]);
          }
      }*/
      $earnings = DB::table( DB::raw("({$user_earnings->toSql()}) as sub") )->select(DB::raw('sub.*'))
      ->mergeBindings($user_earnings->getQuery());
      foreach ($order_by as $key => $value) {
          if(isset($value[2]) && $value[2] == '1') {
              $earnings = $earnings->orderBy(DB::raw($value[0]), $value[1]);
          } else {
              $earnings = $earnings->orderBy($value[0], $value[1]);
          }
      }
      $total_earnings = $earnings->count();
      if($return_all != true)
          $earnings = $earnings->offset($limit_start)->limit($per_page);
      $earnings = $earnings->get();
      $return = ['total_data' => $total_earnings, 'data' => $earnings];
      return $return;
    }

    public static function getPaidBy($table_name,$id,$relation_column_id){
        $user_details=[];
        //$post_paid_users = DB::table($table_name)->select($table_name.'.*','users.first_name','users.last_name','user_metas.value as profile_photo')->where($table_name.'.id', '=', $id)->leftJoin('users', 'users.id', $table_name.'.user_id')->leftJoin('user_metas', 'users.id', 'user_metas.user_id')->where('user_metas.key','profile_photo')->get();
        
        $user_details = DB::table($table_name)->select($table_name.'.*','users.first_name','users.last_name','user_metas.value as profile_photo')->where($table_name.'.id', '=', $id)->leftJoin('users', 'users.id', $table_name.'.'.$relation_column_id)->leftJoin('user_metas', 'users.id', 'user_metas.user_id')->where('user_metas.key','profile_photo')->get();
        //dd($user_details);
        return $user_details;
    }

}
