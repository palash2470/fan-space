<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Order;

class Product extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'products';

    protected $fillable = ['user_id', 'type', 'title', 'thumbnail', 'stock', 'description', 'price', 'attachment', 'status'];

    public function user_details(){
        return $this->belongsTo('App\User','user_id');
    }

    public static function get_list($param = []) {
    	$user_id = $param['user_id'] ?? '';
    	$logged_user_id = $param['logged_user_id'] ?? '';
    	$product_ids = $param['product_ids'] ?? [];
    	$in_stock = $param['in_stock'] ?? '';
    	$cur_page = $param['cur_page'] ?? '1';
      $per_page = $param['per_page'] ?? 10;
      $return_all = $param['return_all'] ?? false;
      $order_by = $param['order_by'] ?? [["sub.title", 'asc']];
      $limit_start = ($cur_page - 1) * $per_page;
      $addl_q = 'CASE
          WHEN products.type in ("1", "2", "3") THEN "1"
          WHEN products.type in ("4") and stock > 0 THEN "1"
          ELSE "0"
      END as in_stock';
      $products = self::select('products.*', 'u.first_name as seller_first_name', 'u.last_name as seller_last_name', 'u.username as seller_username', 'u.display_name as seller_display_name', DB::raw($addl_q))->leftJoin('users as u', 'u.id', 'products.user_id')->where('products.status', '1');
      if($user_id != '')
      	$products = $products->where('products.user_id', $user_id);
      if(count($product_ids) > 0)
      	$products = $products->whereIn('products.id', $product_ids);
      $products2 = DB::table( DB::raw("({$products->toSql()}) as sub") )->select(DB::raw('sub.*, sub.in_stock'))
      ->mergeBindings($products->getQuery());
      if($in_stock != '') $products2->where('sub.in_stock', $in_stock);
      $products2 = $products2->orderBy('sub.in_stock', 'desc');
      foreach ($order_by as $key => $value) {
        if(isset($value[2]) && $value[2] == '1') {
          $products2 = $products2->orderBy(DB::raw($value[0]), $value[1]);
        } else {
          $products2 = $products2->orderBy($value[0], $value[1]);
        }
      }
      $total_products = $products2->count();
      if($return_all != true)
        $products2 = $products2->offset($limit_start)->limit($per_page);
      $products2 = $products2->get();
      $return = ['total_data' => $total_products, 'data' => $products2];
      return $return;
    }

    public static function get_item($param = []) {
    	$product_id = $param['product_id'] ?? '';
    	$logged_user_id = $param['logged_user_id'] ?? '';
      $addl_q = 'CASE
          WHEN products.type in ("1", "2", "3") THEN "1"
          WHEN products.type in ("4") and stock > 0 THEN "1"
          ELSE "0"
      END as in_stock';
      $products = self::select('products.*', 'u.first_name as seller_first_name', 'u.last_name as seller_last_name', 'u.username as seller_username', 'u.display_name as seller_display_name', DB::raw($addl_q))->leftJoin('users as u', 'u.id', 'products.user_id')->where('products.status', '1')->where('products.id', $product_id);
      $products2 = DB::table( DB::raw("({$products->toSql()}) as sub") )->select(DB::raw('sub.*'))
      ->mergeBindings($products->getQuery());
      $products2 = $products2->first();
      $data = ['product' => $products2];
      $return = ['data' => $data];
      return $return;
    }

    public static function product_html($param = []) {
      $product = $param['product'];
      $user_data = $param['user_data'] ?? [];
      $html = '';
      $thumbnail = url('public/front/images/product-thumbnail.png');
      if($product->thumbnail != '')
        $thumbnail = url('public/uploads/product/' . $product->thumbnail);
      $html = '<div class="col-lg-4 col-md-4 col-sm-6 col-12 product_item product_item_' . $product->id . '" product_id="' . $product->id . '">
	        <div class="performerBox w-100 store">
	            <div class="performerImg w-100">
	                <div class="storeImg"><img src="' . $thumbnail . '" alt=""></div>
	            </div>
	            <div class="performerdecc w-100 text-center">
	                <h4><a href="javascript:;">' . $product->title . '</a><!-- <span><i class="ti-heart"></i></span> --></h4>
	                <p>Cost : <span>' . $product->price . ' Coin(s)</span></p>
	                <!-- <h6>Total Buy : <span>2345</span> Qty</h6> -->
	            </div>
	            <div class="performerBtn text-center">
	                <a href="javascript:;" data-toggle111="modal" data-target111=".bd-example-modal-lg" class="show_product_details" product_id="' . $product->id . '"><!-- <i class="fas fa-shopping-cart"></i> -->View Details</a>
	            </div>
	        </div>
	    </div>';
      return ['html' => $html];
    }

    public static function download($params) {
    	$user_id = Auth::id();
    	$type = $params['type'] ?? '';
    	$product_id = $params['product_id'] ?? '';
    	$order_id = $params['order_id'] ?? '';
    	$order_item_id = $params['order_item_id'] ?? '';
    	$download_path = '';
    	if($type == 'product') {
    		$product = Product::where('user_id', $user_id)->where('id', $product_id)->first();
    		if(isset($product->attachment) && $product->attachment != '' && file_exists(base_path('/public/uploads/product/attachments/' . $product->attachment)))
    			$download_path = base_path('/public/uploads/product/attachments/' . $product->attachment);
    	}
			if($type == 'order') {
				$order = Order::select('oi.attachment')->leftJoin('order_items as oi', 'oi.order_id', 'orders.id')->where('orders.id', $order_id)->where('oi.id', $order_item_id)->where(function($q) use ($user_id){
					$q->orWhere('orders.user_id', $user_id)->orWhere('orders.vip_member_id', $user_id);
				})->first();
				if(isset($order->attachment) && $order->attachment != '' && file_exists(base_path('/public/uploads/product/attachments/' . $order->attachment)))
    			$download_path = base_path('/public/uploads/product/attachments/' . $order->attachment);
			}
    	if($download_path == '') abort(404);
    	$file_url = $download_path;
      header('Content-Type: application/octet-stream');
      header("Content-Transfer-Encoding: Binary");
      header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
      readfile($file_url);
      die;
    }

}
