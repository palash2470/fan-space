<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\DB AS DB;
use App\User as User;
use App\User_meta as User_meta;
use App\Setting as Setting;
use App\Country;
use App\Product;

class Cart extends Model
{
    //
    public $timestamps = false;
    protected $table = '';


    public static function get_cart() {
    	$cart = $_COOKIE['cart'] ?? '{}';
			$cart = json_decode($cart, true);
			$coupon = $_COOKIE['coupon'] ?? '{}';
			$coupon = json_decode($coupon, true);
			$data = ['items' => [], 'total_qty' => 0, 'total_amount' => 0];
			$cart2 = [];
			$product_item_id = [];
			$total_qty = 0;
			foreach ($cart as $key => $value) {
				$product_item_id[] = $key;
			}
			$total_amount = 0;
			if(count($product_item_id) > 0) {
				$product_items = Product::get_list(['product_ids' => $product_item_id, 'in_stock' => 1, 'return_all' => true]);
				foreach ($product_items['data'] as $key => $value) {
					if(in_array($value->type, [4]) && $value->stock < $cart[$value->id]['qty'])
						continue;
					$total_qty += $cart[$value->id]['qty'];
					$cart2[$value->id] = ['product_id' => $value->id, 'qty' => $cart[$value->id]['qty']];
					$thumbnail = url('public/front/images/product-thumbnail.png');
		      if($value->thumbnail != '')
		        $thumbnail = url('public/uploads/product/' . $value->thumbnail);
					$item_total = $value->price * $cart[$value->id]['qty'];
					$temp = $value;
					$temp->thumbnail = $thumbnail;
					$temp->qty = $cart[$value->id]['qty'];
					$temp->item_total = $item_total;
					$data['items'][] = $temp;
					//$data['items'][] = ['id' => $value->id, 'title' => $value->title, 'thumbnail' => $thumbnail, 'price' => $value->price, 'qty' => $cart[$value->id]['qty'], 'item_total' => $item_total];
					$total_amount += $item_total;
				}
			}
			if(isset($_COOKIE['cart']))
        setcookie('cart', '', (time() - 3600), "/");
      setcookie('cart', json_encode($cart2), time() + (86400), "/");
			$data['total_qty'] = $total_qty;
			$data['total_amount'] = $total_amount;
			return $data;
    }
    

}
