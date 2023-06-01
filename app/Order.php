<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB as DB;

use App\Order_address;
use App\Order_item;

class Order extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'orders';

    protected $fillable = ['user_id', 'vip_member_id', 'email', 'phone', 'order_notes', 'total_amount', 'status'];
    public function order_by()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function order_address()
    {
        return $this->hasOne('App\Order_address', 'order_id');
    }
    public static function get_list($param = array())
    {
        $user_id = $param['user_id'] ?? [];
        $vip_member_id = $param['vip_member_id'] ?? [];
        $search = $param['search'] ?? '';
        $where_raw = $param['where_raw'] ?? [];
        $cur_page = $param['cur_page'] ?? '1';
        $per_page = $param['per_page'] ?? 20;
        $return_all = $param['return_all'] ?? false;
        $limit_start = ($cur_page - 1) * $per_page;
        $list = self::select(
            'orders.*',
            'c.name as country_title',
            DB::raw('concat(u.first_name, " ", u.last_name) as user_full_name'),
            DB::raw('concat(u_vip.first_name, " ", u_vip.last_name) as vip_member_full_name'),
            'u_vip.username as vip_member_username',
            'u_vip.display_name as vip_member_display_name',
            DB::raw('concat(oa.first_name, " ", oa.last_name) as address_full_name'),
            'oa.company_name as address_company_name',
            'oa.address_line_1 as address_address_line_1',
            'oa.zip_code as address_zip_code'
        )->leftJoin('order_addresses as oa', 'oa.order_id', 'orders.id')->leftJoin('countries as c', 'c.country_id', 'oa.country_id')->leftJoin('users as u', 'u.id', 'orders.user_id')->leftJoin('users as u_vip', 'u_vip.id', 'orders.vip_member_id');
        if (count($user_id) > 0)
            $list->whereIn('orders.user_id', $user_id);
        if (count($vip_member_id) > 0)
            $list->whereIn('orders.vip_member_id', $vip_member_id);
        //$list->groupBy('orders.id');
        //$total_list = $list->get()->count();
        $list = DB::table(DB::raw("({$list->toSql()}) as sub"))
            ->mergeBindings($list->getQuery());
        /*if($search != '') {
            $list->where(function($list) use ($search){
                $list->orWhere('shipping_method_title', 'like', '%' . $search . '%')
                ->orWhere('payment_method_title', 'like', '%' . $search . '%');
            });
        }*/
        if ($search != '') {
            $list->where(function ($q) use ($search) {
                $q->orWhere('user_full_name', 'like', '%' . $search . '%')
                    ->orWhere('vip_member_full_name', 'like', '%' . $search . '%')
                    ->orWhere('vip_member_username', 'like', '%' . $search . '%')
                    ->orWhere('vip_member_display_name', 'like', '%' . $search . '%')
                    ->orWhere('address_full_name', 'like', '%' . $search . '%')
                    ->orWhere('address_company_name', 'like', '%' . $search . '%')
                    ->orWhere('address_address_line_1', 'like', '%' . $search . '%')
                    ->orWhere('country_title', 'like', '%' . $search . '%')
                    ->orWhere('address_zip_code', 'like', '%' . $search . '%');
            });
        }
        if (count($where_raw) > 0) {
            foreach ($where_raw as $key => $value) {
                $list->whereRaw($value);
            }
        }
        $list->groupBy('id');
        $total_list = $list->get()->count();
        $list = $list->orderBy('created_at', 'desc');
        if ($return_all != true)
            $list = $list->offset($limit_start)->limit($per_page);
        $list = $list->get();
        foreach ($list as $key => $value) {
            $user_data = User::find($value->user_id);
            $vip_member_data = User::find($value->vip_member_id);
            $address_data = Order_address::select('order_addresses.*', 'c.name as country_title')->leftJoin('countries as c', 'c.country_id', 'order_addresses.country_id')->where('order_addresses.order_id', $value->id)->get();
            $item_data = Order_item::where('order_id', $value->id)->get();
            $list[$key]->user_data = $user_data;
            $list[$key]->vip_member_data = $vip_member_data;
            $list[$key]->address_data = $address_data;
            $list[$key]->item_data = $item_data;
        }
        //print_r($total_list); die;
        $return = ['total_data' => $total_list, 'data' => $list];
        return $return;
    }
}
