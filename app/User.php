<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Input;
use Hash;
use Carbon\Carbon;

use App\User_meta;
use App\User_interest_cat;
use App\User_own_cat;
use App\Cupsize;
use App\Sexual_orientation;
use App\Zodiac;
use App\Model_attribute;
use App\Vip_member_model_attribute;
use App\User_fav_user;
use App\User_follow_user;
use App\Subscriber;
use App\User_earning;
use App\User_payout;
use App\Live_session;

use App\Http\Helpers;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'phone', 'display_name', 'dob', 'role', 'social_login', 'password', 'affiliate_user_id', 'remember_token', 'password_reset_token', 'status', 'last_activity','is_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //public $timestamps = false;

    protected $table = 'users';

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function update_activity($param=array()) {
        self::where('id', Auth::id())->update(['last_activity' => Carbon::now()->format('Y-m-d H:i:s')]);
        return true;
    }


    public static function get_list($param=array()) {
        $role = $param['role'] ?? [3];
        $search = $param['search'] ?? '';
        $status = $param['status'] ?? '';
        $order_by = $param['order_by'] ?? [["CONCAT(users.first_name, ' ', users.last_name)", 'asc', '1']];
        $cur_page = $param['cur_page'] ?? '1';
        $per_page = $param['per_page'] ?? 20;
        $return_all = $param['return_all'] ?? false;
        $limit_start = ($cur_page - 1) * $per_page;
        if(count($role) == 1 && $role[0] == 2) {
            $users = self::select('users.*', 'um1_c.iso_code_2 as country_iso_code_2', 'um1_c.name as country_name', 'um2_c.iso_code_2 as bank_country_iso_code_2', 'um2_c.name as bank_country_name');
            $users->leftJoin('user_metas as um1', 'um1.user_id', 'users.id')->leftJoin('user_metas as um2', 'um2.user_id', 'users.id')->leftJoin('countries as um1_c', 'um1_c.country_id', 'um1.value')->leftJoin('countries as um2_c', 'um2_c.country_id', 'um2.value')
            ->leftJoin('user_own_cats as oct', 'oct.user_id', 'users.id')->leftJoin('user_categories as uct1', 'uct1.id', 'oct.user_cat_id')
            ->leftJoin('user_interest_cats as ict', 'ict.user_id', 'users.id')->leftJoin('user_categories as uct2', 'uct2.id', 'ict.user_cat_id')
            ->where('um1.key', 'country_id')
            ->where('um2.key', 'bank_country_id');
        } else {
           $users = self::select('users.*');
        }
        if($search != '') {
            $users->where(function ($query) use ($search) {
                $query->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $search . '%')->orWhere('users.email', 'like', '%' . $search . '%')->orWhere('users.username', 'like', '%' . $search . '%');
            });
        }
        if(count($role) > 0) {
            $users->whereIn('users.role', $role);
        }
        if($status != '') $users->where('users.status', $status);
        //$users = $users->orderBy(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'asc');
        foreach ($order_by as $key => $value) {
            if(isset($value[2]) && $value[2] == '1') {
                $users = $users->orderBy(DB::raw($value[0]), $value[1]);
            } else {
                $users = $users->orderBy($value[0], $value[1]);
            }
        }
        $users = $users->groupBy('users.id');
        $users = DB::table( DB::raw("({$users->toSql()}) as sub") )->select(DB::raw('sub.*'))
        ->mergeBindings($users->getQuery());
        /*$bindings = $users->getBindings();
        $sql = str_replace('?', '"%s"', $users->toSql());
        $sql = sprintf($sql, ...$bindings);
        die($sql);*/
        $total_users = $users->count();
        if($return_all != true)
            $users = $users->offset($limit_start)->limit($per_page);
        $users = $users->get();
        foreach ($users as $key => $value) {
            $usermeta = new User_meta;
            $users[$key]->usermeta_data = $usermeta->get_usermeta($value->id);
            $usermeta_data = [];
            foreach ($users[$key]->usermeta_data as $k => $v) {
                $usermeta_data[$v->key] = $v->value;
            }
            $users[$key]->usermeta_data = $usermeta_data;
            $interest_cat_data = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->interest_cat_data = $interest_cat_data;
            $own_cat_data = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->own_cat_data = $own_cat_data;
        }
        $return = ['total_data' => $total_users, 'data' => $users];
        return $return;
    }

    public static function vip_member_search($param=array()) {
        $role = 2;
        $result_type = $param['result_type'] ?? 'vip_member_search';
        $follower_id = $param['follower_id'] ?? '';
        //$subscriber_id = $param['subscriber_id'] ?? '';
        $search = $param['search'] ?? '';
        $online_status = $param['online_status'] ?? [];
        $live_status = $param['live_status'] ?? [];
        $user_cat = $param['user_cat'] ?? [];
        $uo = $param['uo'] ?? [];
        // dd($uo);
        $age = $param['age'] ?? [];
        $status = $param['status'] ?? 1;
        //$order_by = $param['order_by'] ?? [["CONCAT(users.first_name, ' ', users.last_name)", 'asc', '1']];
        $order_by = $param['order_by'] ?? [["users.display_name", 'asc']];
        $cur_page = $param['cur_page'] ?? '1';
        $per_page = $param['per_page'] ?? 20;
        $return_all = $param['return_all'] ?? false;
        $limit_start = ($cur_page - 1) * $per_page;
        $helper_settings = Helpers::get_settings();
        //$last_activity_time_ago = 2 * 60; // 2 min
        $last_activity_time_ago = $helper_settings['user_online_time_flag'];
        $last_activity_time = date('Y-m-d H:i:s', (time() - $last_activity_time_ago));
        $users = self::select('users.*', DB::raw('(CASE
            WHEN users.last_activity <= "' . $last_activity_time . '" THEN 0
            WHEN users.last_activity > "' . $last_activity_time . '" THEN 1
            ELSE 0
        END) AS online'), 'um1_c.iso_code_2 as country_iso_code_2', 'um1_c.name as country_name', DB::raw('(select count(p.id) from posts as p where p.user_id = users.id and p.post_type = "photo") as photo_post_count'), DB::raw('(select count(p.id) from posts as p where p.user_id = users.id and p.post_type = "video") as video_post_count'), DB::raw('(select value from user_metas where user_metas.user_id = users.id and user_metas.key = "sexual_orientation_id") as uo'),DB::raw('(select sb.validity_date from subscribers as sb where sb.user_id = users.id and sb.subscriber_id = "' . $follower_id . '") as subscription_validity_date'), 'ls.id as live_session_id', 'ls.session_id as live_session_session_id');
        $users->leftJoin('user_metas as um1', 'um1.user_id', 'users.id')->leftJoin('countries as um1_c', 'um1_c.country_id', 'um1.value')
        ->leftJoin('user_own_cats as oct', 'oct.user_id', 'users.id')->leftJoin('user_categories as uct1', 'uct1.id', 'oct.user_cat_id')
        ->leftJoin('user_interest_cats as ict', 'ict.user_id', 'users.id')->leftJoin('user_categories as uct2', 'uct2.id', 'ict.user_cat_id')->leftJoin('live_sessions as ls', 'ls.user_id', 'users.id')
        ->where('um1.key', 'country_id');
        if($result_type == 'following_list') {
            $follow_user_ids = User_follow_user::where('user_id', $follower_id)->pluck('follow_user_id')->toArray();
            if(count($follow_user_ids) == 0) $follow_user_ids = [0];
            $users->whereIn('users.id', $follow_user_ids);
        }
        if($result_type == 'hotlist') {
            $fav_user_ids = User_fav_user::where('user_id', $follower_id)->pluck('fav_user_id')->toArray();
            if(count($fav_user_ids) == 0) $fav_user_ids = [0];
            $users->whereIn('users.id', $fav_user_ids);
        }
        if($result_type == 'subscription') {
            $subscription_user_ids = Subscriber::where('subscriber_id', $follower_id)->pluck('user_id')->toArray();
            if(count($subscription_user_ids) == 0) $subscription_user_ids = [0];
            $users->whereIn('users.id', $subscription_user_ids);
        }
        if($search != '') {
            $users->where(function ($query) use ($search) {
                $query->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $search . '%')->orWhere('users.email', 'like', '%' . $search . '%')->orWhere('users.username', 'like', '%' . $search . '%')->orWhere('users.display_name', 'like', '%' . $search . '%');
            });
        }
        if($result_type == 'live_user') {
            $live_user_ids = Live_session::pluck('user_id')->toArray();
            if(count($live_user_ids) == 0) $live_user_ids = [0];
            $users->whereIn('users.id', $live_user_ids);
        }
        if(count($online_status) > 0) {
            $users->where(function ($q) use ($online_status, $last_activity_time){
                if(in_array('0', $online_status))
                    $q->orWhere(function ($q2) use ($last_activity_time){ $q2->orWhere('users.last_activity', '<=', $last_activity_time)->orWhereNull('users.last_activity'); });
                if(in_array('1', $online_status))
                    $q->orWhere('users.last_activity', '>', $last_activity_time);
            });
        }
        if(count($live_status) > 0) {
            $users->where(function ($q) use ($live_status){
                if(in_array('0', $live_status))
                    $q->orWhereNull('ls.id');
                if(in_array('1', $live_status))
                    $q->orWhereNotNull('ls.id');
            });
        }
        if(count($user_cat) > 0)
            $users->whereIn('oct.user_cat_id', $user_cat);

        if(count($age) > 0) {
            $users->where(function($q) use ($age){
                foreach ($age as $key => $value) {
                    $q->orWhere(function($q2) use ($value){
                        $ageval = explode('_', $value);
                        $age_st = date('Y-m-d', strtotime('-' . ($ageval[1] + 1) . ' year'));
                        $age_end = date('Y-m-d', strtotime('-' . $ageval[0] . ' year'));
                        $q2->where('users.dob', '>', $age_st)->where('users.dob', '<=', $age_end);
                    });
                }
            });
        }
        $users->whereIn('users.role', [2]);
        if($status != '') $users->where('users.status', $status);
        //$users = $users->orderBy(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'asc');
        foreach ($order_by as $key => $value) {
            if(isset($value[2]) && $value[2] == '1') {
                $users = $users->orderBy(DB::raw($value[0]), $value[1]);
            } else {
                $users = $users->orderBy($value[0], $value[1]);
            }
        }
        $users = $users->groupBy('users.id');
        $users = DB::table( DB::raw("({$users->toSql()}) as sub") )->select(DB::raw('sub.*'))
        ->mergeBindings($users->getQuery());
        if(count($uo) > 0){
            $users->whereIn('sub.uo', $uo);
        }
        /*$bindings = $users->getBindings();
        $sql = str_replace('?', '"%s"', $users->toSql());
        $sql = sprintf($sql, ...$bindings);
        die($sql);*/
        $total_users = $users->count();
        if($return_all != true)
            $users = $users->offset($limit_start)->limit($per_page);
        $users = $users->get();
        foreach ($users as $key => $value) {
            $usermeta = new User_meta;
            $users[$key]->usermeta_data = $usermeta->get_usermeta($value->id);
            $usermeta_data = [];
            foreach ($users[$key]->usermeta_data as $k => $v) {
                $usermeta_data[$v->key] = $v->value;
            }
            $users[$key]->usermeta_data = $usermeta_data;
            $interest_cat_data = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->interest_cat_data = $interest_cat_data;
            $own_cat_data = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->own_cat_data = $own_cat_data;
        }
        // dd($users,$uo);
        $return = ['total_data' => $total_users, 'data' => $users];
        return $return;
    }

    public static function vip_member_details($param=array()) {
        $search = $param['search'] ?? '';
        $search_by = $param['search_by'] ?? 'id';
        $data = ['user' => [], 'usermeta' => [], 'own_cat' => [], 'interest_cat' => [], 'model_attributes' => []];
        $data['user'] = User::where($search_by, $search)->first();
        if(isset($data['user']->id)) {
            $usermeta = new User_meta;
            $data['usermeta'] = $usermeta->get_usermeta($data['user']->id);
            $usermeta_data = [];
            foreach ($data['usermeta'] as $k => $v) {
                $usermeta_data[$v->key] = $v->value;
            }
            $data['usermeta'] = $usermeta_data;
            $interest_cat_data = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $data['user']->id)->orderBy('uc.title')->get();
            $data['own_cat'] = $interest_cat_data;
            $own_cat_data = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $data['user']->id)->orderBy('uc.title')->get();
            $data['interest_cat'] = $own_cat_data;
            $model_attributes = Vip_member_model_attribute::select('vip_member_model_attributes.*', 'ma.title')->leftJoin('model_attributes as ma', 'ma.id', 'vip_member_model_attributes.model_attribute_id')->where('vip_member_model_attributes.user_id', $data['user']->id)->orderBy('ma.title')->get();
            $data['model_attributes'] = $model_attributes;
            $data['live_session'] = Live_session::where('user_id', $data['user']->id)->first();
        }
        $return = ['data' => $data];
        return $return;
    }

    /*public static function following_list($param=array()) {
        $role = 2;
        $user_id = $param['user_id'] ?? '';
        $search = $param['search'] ?? '';
        $user_cat = $param['user_cat'] ?? [];
        $order_by = $param['order_by'] ?? [["users.display_name", 'asc']];
        $cur_page = $param['cur_page'] ?? '1';
        $per_page = $param['per_page'] ?? 20;
        $return_all = $param['return_all'] ?? false;
        $limit_start = ($cur_page - 1) * $per_page;
        $helper_settings = Helpers::get_settings();
        $last_activity_time_ago = $helper_settings['user_online_time_flag'];
        $last_activity_time = date('Y-m-d H:i:s', (time() - $last_activity_time_ago));
        $users = self::select('users.*', DB::raw('(CASE
            WHEN users.last_activity <= "' . $last_activity_time . '" THEN 0
            WHEN users.last_activity > "' . $last_activity_time . '" THEN 1
            ELSE 0
        END) AS online'), 'um1_c.iso_code_2 as country_iso_code_2', 'um1_c.name as country_name', DB::raw('(select count(p.id) from posts as p where p.user_id = users.id and p.post_type = "photo") as photo_post_count'), DB::raw('(select count(p.id) from posts as p where p.user_id = users.id and p.post_type = "video") as video_post_count'));
        $users->leftJoin('user_metas as um1', 'um1.user_id', 'users.id')->leftJoin('countries as um1_c', 'um1_c.country_id', 'um1.value')
        ->leftJoin('user_own_cats as oct', 'oct.user_id', 'users.id')->leftJoin('user_categories as uct1', 'uct1.id', 'oct.user_cat_id')
        ->leftJoin('user_interest_cats as ict', 'ict.user_id', 'users.id')->leftJoin('user_categories as uct2', 'uct2.id', 'ict.user_cat_id')
        ->where('um1.key', 'country_id');
        if($search != '') {
            $users->where(function ($query) use ($search) {
                $query->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', '%' . $search . '%')->orWhere('users.email', 'like', '%' . $search . '%')->orWhere('users.username', 'like', '%' . $search . '%')->orWhere('users.display_name', 'like', '%' . $search . '%');
            });
        }
        if(count($user_cat) > 0)
            $users->whereIn('oct.user_cat_id', $user_cat);
        $users->whereIn('users.role', [2]);
        $users->where('users.status', 1);
        foreach ($order_by as $key => $value) {
            if(isset($value[2]) && $value[2] == '1') {
                $users = $users->orderBy(DB::raw($value[0]), $value[1]);
            } else {
                $users = $users->orderBy($value[0], $value[1]);
            }
        }
        $users = $users->groupBy('users.id');
        $users = DB::table( DB::raw("({$users->toSql()}) as sub") )->select(DB::raw('sub.*'))
        ->mergeBindings($users->getQuery());

        $total_users = $users->count();
        if($return_all != true)
            $users = $users->offset($limit_start)->limit($per_page);
        $users = $users->get();
        foreach ($users as $key => $value) {
            $usermeta = new User_meta;
            $users[$key]->usermeta_data = $usermeta->get_usermeta($value->id);
            $usermeta_data = [];
            foreach ($users[$key]->usermeta_data as $k => $v) {
                $usermeta_data[$v->key] = $v->value;
            }
            $users[$key]->usermeta_data = $usermeta_data;
            $interest_cat_data = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->interest_cat_data = $interest_cat_data;
            $own_cat_data = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $value->id)->orderBy('uc.title')->get();
            $users[$key]->own_cat_data = $own_cat_data;
        }
        $return = ['total_data' => $total_users, 'data' => $users];
        return $return;
    }*/

    public static function subscriber_list($param) {
        $vip_member_id = $param['vip_member_id'] ?? '';
        $order_by = $param['order_by'] ?? [["concat(users.first_name, ' ', users.last_name)", 'asc', 1]];
        $cur_page = $param['cur_page'] ?? '1';
        $per_page = $param['per_page'] ?? 20;
        $return_all = $param['return_all'] ?? false;
        $limit_start = ($cur_page - 1) * $per_page;
        $users = User::select('users.*', 'sb.validity_date as subscription_validity_date')->leftJoin('subscribers as sb', 'sb.subscriber_id', 'users.id')->where('sb.user_id', $vip_member_id);
        foreach ($order_by as $key => $value) {
            if(isset($value[2]) && $value[2] == '1') {
                $users = $users->orderBy(DB::raw($value[0]), $value[1]);
            } else {
                $users = $users->orderBy($value[0], $value[1]);
            }
        }
        $subscribers = DB::table( DB::raw("({$users->toSql()}) as sub") )->select(DB::raw('sub.*'))
        ->mergeBindings($users->getQuery());
        $total_subscribers = $subscribers->count();
        if($return_all != true)
            $subscribers = $subscribers->offset($limit_start)->limit($per_page);
        $subscribers = $subscribers->get();
        foreach ($subscribers as $key => $value) {
            $usermeta = new User_meta;
            $subscribers[$key]->usermeta_data = $usermeta->get_usermeta($value->id);
            $usermeta_data = [];
            foreach ($subscribers[$key]->usermeta_data as $k => $v) {
                $usermeta_data[$v->key] = $v->value;
            }
            $subscribers[$key]->usermeta_data = $usermeta_data;
        }
        $return = ['total_data' => $total_subscribers, 'data' => $subscribers];
        return $return;
    }

    public static function wallet($param) {
        $user_id = $param['user_id'];
        $balance = 0;
        $user = User::find($user_id);
        if($user->role == 2) {
            $balance += User_earning::where('user_id', $user_id)->sum('token_coins');
            $balance += User_earning::where('referral_user_id', $user_id)->sum('referral_token_coins');
            $balance -= User_payout::where('user_id', $user_id)->sum('token_coins');
        }
        if($user->role == 3) {
            $wallet_coins = User_meta::where(['user_id' => $user_id, 'key' => 'wallet_coins'])->pluck('value')->first();
            $balance = ($wallet_coins == '' ? 0 : $wallet_coins);
        }
        $return = ['balance' => $balance];
        return $return;
    }
    public function user_meta(){
        return $this->hasMany('App\User_meta','user_id','id');
    }
    public function user_meta_array(){
        // dd($this->user_meta()->get());
        $usermeta_data = [];
        foreach ($this->user_meta()->get() as $k => $v) {
            $usermeta_data[$v->key] = $v->value;
        }
        return $usermeta_data;
    }
    public function suscribe_to(){
        return $this->hasMany('App\Subscriber','subscriber_id','id');
    }
    public function check_subscribe_to_model($model_id){
        $model_id = (int)$model_id;
        $subscribe_model_id= $this->suscribe_to()->pluck('user_id')->toArray();
        // dd($subscribe_model_id,$model_id);
        // return $model_id;
        if(in_array($model_id,$subscribe_model_id)){
            return true;
        }
        else{
            return false;
        }

    }



}
