<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

class User_payout extends Model
{

    //public $timestamps = false;
    protected $table = 'user_payouts';
    protected $fillable = ['user_id', 'token_coins', 'price_amount', 'transaction_id', 'status', 'created_at', 'updated_at'];

    
    public static function payouts($param) {
    	$user_id = $param['user_id'] ?? '';
    	$order_by = $param['order_by'] ?? [["user_payouts.created_at", 'desc', 0]];
      $cur_page = $param['cur_page'] ?? '1';
      $per_page = $param['per_page'] ?? 20;
      $return_all = $param['return_all'] ?? false;
      $limit_start = ($cur_page - 1) * $per_page;
      $user_payouts = self::select('user_payouts.*')->where('user_payouts.user_id', $user_id);
      foreach ($order_by as $key => $value) {
          if(isset($value[2]) && $value[2] == '1') {
              $user_payouts = $user_payouts->orderBy(DB::raw($value[0]), $value[1]);
          } else {
              $user_payouts = $user_payouts->orderBy($value[0], $value[1]);
          }
      }
      $payouts = DB::table( DB::raw("({$user_payouts->toSql()}) as sub") )->select(DB::raw('sub.*'))
      ->mergeBindings($user_payouts->getQuery());
      $total_payouts = $payouts->count();
      if($return_all != true)
          $payouts = $payouts->offset($limit_start)->limit($per_page);
      $payouts = $payouts->get();
      $return = ['total_data' => $total_payouts, 'data' => $payouts];
      return $return;
    }

}
