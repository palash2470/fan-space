<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB AS DB;
use Illuminate\Support\Facades\Auth;
use Hash;
use Carbon\Carbon;
use Session;
use App\Http\Helpers;
use App\User;
use App\User_meta;
use App\User_interest_cat;
use App\User_own_cat;
use App\Cupsize;
use App\Sexual_orientation;
use App\Zodiac;
use App\Model_attribute;
use App\Vip_member_model_attribute;
use App\Post;
use App\User_fav_user;
use App\User_follow_user;
use App\Post_react;
use App\Post_comment;
use App\Post_comment_react;
use App\Coin_price_plan;
use App\Product;
use App\Notification;
use App\Subscriber;
use App\User_earning;
use App\User_payout;
use App\Order;
use App\Order_address;
use App\Order_item;
use App\Ticket;
class UserController extends Controller
{
    private $user_data = array();
    private $meta_data = array();
    function __construct() {
        $this->middleware(function ($request, $next) {
            if(Auth::user()) {
                $getData = User::find(Auth::id())->toArray();
                $dashboard_url = '';
                if($getData['role'] == 1)
                    $dashboard_url = url('/admin');
                if(in_array($getData['role'], [2, 3]))
                    $dashboard_url = url('/dashboard');
                $logout_url = url('logout');
                $this->user_data = $getData;
                $this->user_data['name'] = $getData['first_name'] . ' ' . $getData['last_name'];
                $this->user_data['dashboard_url'] = $dashboard_url;
                $this->user_data['logout_url'] = $logout_url;
                User::update_activity();
            }
            return $next($request);
        });
        $this->meta_data['settings'] = array();
        $settings = DB::table('settings')->get();
        foreach ($settings as $key => $value) {
            $this->meta_data['settings'][$value->key] = $value->value;
        }
    }
    public function index(Request $request, $slug) {
        // dd($request['post_type']);
        $user_data = $this->user_data;
        $usermeta = new User_meta;
        $user_data['meta_data'] = $usermeta->get_usermeta($user_data['id']);
        $usermeta_data = [];
        foreach ($user_data['meta_data'] as $k => $v) {
            $usermeta_data[$v->key] = $v->value;
        }
        $user_data['meta_data'] = $usermeta_data;
        $meta_data = [];
        $helper_settings = Helpers::get_settings();
        $slug = $request->segment(2);
        if($slug == '')
            return redirect('dashboard');
        $page_title = 'Dashboard';
        $function_data = $this->{$slug}($request);
        if(!is_array($function_data))
            return $function_data;
        $page_title = $function_data['page_title'];
        $meta_data = $function_data;
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['slug'] = $slug;
		
		//dd($user_data);
		
		
        return view('user.dashboard', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => $page_title]);
    }

    public function home($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $cur_page = 1;
        $per_page = 10;
        $posts = Post::get_own_posts(['user_id' => $user_id, 'per_page' => $per_page, 'cur_page' => $cur_page]);
        $return['per_page'] = $per_page;
        $return['cur_page'] = $cur_page;
        $return['posts'] = $posts['data'];
        $return['total_posts'] = $posts['total_data'];
      }
      if($this->user_data['role'] == 3) {
        $cur_page = 1;
        $per_page = 10;
        $posts = Post::get_posts(['logged_user_id' => $user_id, 'per_page' => $per_page, 'cur_page' => $cur_page]);
        $return['per_page'] = $per_page;
        $return['cur_page'] = $cur_page;
        $return['posts'] = $posts['data'];
        $return['total_posts'] = $posts['total_data'];
        //$return['right_sidebar'] = [];
      }
      $return['page_title'] = 'Dashboard';

      $shareComponent = \Share::page(
        'https://www.positronx.io/create-autocomplete-search-in-laravel-with-typeahead-js/',
        'Your share text comes here',
        )
        ->facebook()
        ->twitter()
        ->linkedin()
        ->telegram()
        ->whatsapp()        
        ->reddit();
        $return['shareComponent'] = $shareComponent;
      return $return;
    }

    public function post_details($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $post_id = $request->id;
      // if($this->user_data['role'] == 2) {
      //   $cur_page = 1;
      //   $per_page = 10;
      //   $posts = Post::get_own_posts(['user_id' => $user_id, 'per_page' => $per_page, 'cur_page' => $cur_page]);
      //   $return['per_page'] = $per_page;
      //   $return['cur_page'] = $cur_page;
      //   $return['posts'] = $posts['data'];
      //   $return['total_posts'] = $posts['total_data'];
      // }
      // if($this->user_data['role'] == 3) {
        $cur_page = 1;
        $per_page = 10;
        $posts = Post::get_posts(['logged_user_id' => $user_id, 'per_page' => $per_page, 'cur_page' => $cur_page,'post_ids'=>[$post_id]]);
        $return['per_page'] = $per_page;
        $return['cur_page'] = $cur_page;
        $return['posts'] = $posts['data'];
        $return['total_posts'] = $posts['total_data'];

        

        //$return['right_sidebar'] = [];
      // }
      $return['page_title'] = 'Post Details';
      return $return;
    }

    public function profile___($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $interest_cats = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $user_id)->orderBy('uc.title')->get();
      $own_cats = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $user_id)->orderBy('uc.title')->get();
      $return['own_cats'] = $own_cats;
      $return['interest_cats'] = $interest_cats;
      $return['page_title'] = 'Profile';
      return $return;
    }
    public function profile($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $interest_cats = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $user_id)->orderBy('uc.title')->get();
      $own_cats = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $user_id)->orderBy('uc.title')->get();
      if($this->user_data['role'] == 2) {
        $vip_member = User::vip_member_details(['search' => $this->user_data['username'], 'search_by' => 'username']);
        $vip_member = $vip_member['data'];
        if(!isset($vip_member['user']->id)) abort(404);
        $cupsizes = Cupsize::orderBy('title')->get();
        $sexual_orientations = Sexual_orientation::orderBy('title')->get();
        $zodiacs = Zodiac::orderBy('title')->get();
        $model_attributes = Model_attribute::orderBy('title')->get();
        $vip_member_model_attributes = Vip_member_model_attribute::where('user_id', $user_id)->get();
        $section = trim($request->section);
        if(in_array($section, ['posts', 'photos', 'videos'])) {
          $age = trim($request->age);
          $ordby = trim($request->ordby);
          $ord = trim($request->ord);
          $cur_page = 1;
          $per_page = 10;
          $post_type = [];
          if($section == 'photos') $post_type[] = 'photo';
          if($section == 'videos') $post_type[] = 'video';
          $posts = Post::get_profile_posts(['user_id' => $vip_member['user']->id, 'logged_user_id' => $user_id, 'post_type' => $post_type, 'age' => $age, 'ordby' => $ordby, 'ord' => $ord, 'per_page' => $per_page, 'cur_page' => $cur_page]);
          $return['per_page'] = $per_page;
          $return['cur_page'] = $cur_page;
          $return['posts'] = $posts['data'];
          $return['total_posts'] = $posts['total_data'];
        }
        $return['vip_member'] = $vip_member;
        $return['cupsizes'] = $cupsizes;
        $return['sexual_orientations'] = $sexual_orientations;
        $return['zodiacs'] = $zodiacs;
        $return['model_attributes'] = $model_attributes;
        $return['vip_member_model_attributes'] = $vip_member_model_attributes;
      }
      $return['own_cats'] = $own_cats;
      $return['interest_cats'] = $interest_cats;
      $return['page_title'] = 'Profile';
      return $return;
    }
    public function about($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $cupsizes = Cupsize::orderBy('title')->get();
        $sexual_orientations = Sexual_orientation::orderBy('title')->get();
        $zodiacs = Zodiac::orderBy('title')->get();
        $model_attributes = Model_attribute::orderBy('title')->get();
        $vip_member_model_attributes = Vip_member_model_attribute::where('user_id', $user_id)->get();
        $return['cupsizes'] = $cupsizes;
        $return['sexual_orientations'] = $sexual_orientations;
        $return['zodiacs'] = $zodiacs;
        $return['model_attributes'] = $model_attributes;
        $return['vip_member_model_attributes'] = $vip_member_model_attributes;
      }
      $return['page_title'] = 'About';
      return $return;
    }
    public function bank_details($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $return['page_title'] = 'Bank Details';
      return $return;
    }
    public function settings($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $interest_cats = User_interest_cat::select('user_interest_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_interest_cats.user_cat_id')->where('user_interest_cats.user_id', $user_id)->orderBy('uc.title')->get();
      $own_cats = User_own_cat::select('user_own_cats.*', 'uc.title')->leftJoin('user_categories as uc', 'uc.id', 'user_own_cats.user_cat_id')->where('user_own_cats.user_id', $user_id)->orderBy('uc.title')->get();
      $return['own_cats'] = $own_cats;
      $return['interest_cats'] = $interest_cats;
      $return['page_title'] = 'Settings';
      return $return;
    }
    public function subscribers($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $subscriber_list = User::subscriber_list(['vip_member_id' => $user_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $subscriber_list['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/subscribers')];
        $return['subscribers'] = $subscriber_list['data'];
      }
      $return['page_title'] = 'Subscribers';
      return $return;
    }
    public function wallet($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $tab = $_GET['tab'] ?? '';
        if(!in_array($tab, ['withdrawls', 'earnings'])) $tab = '';
        if($tab == 'earnings') {
          $cur_page = trim($request->input('pg'));
          $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
          $per_page = 50;
          $user_earnings = User_earning::earnings(['user_id' => $user_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
          $additional_params = '&tab=' . $tab;
          $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $user_earnings['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/wallet')];
          $return['user_earnings'] = $user_earnings['data'];
        }
        if($tab == 'withdrawls') {
          $cur_page = trim($request->input('pg'));
          $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
          $per_page = 50;
          $payouts = User_payout::payouts(['user_id' => $user_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
          $additional_params = '&tab=' . $tab;
          $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $payouts['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/wallet')];
          $return['payouts'] = $payouts['data'];
        }
        $return['tab'] = $tab;
      }
      $return['page_title'] = 'Wallet';
      return $return;
    }
    public function subscription($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $subscriber_id = $user_id;
        $subscription_list = User::vip_member_search(['result_type' => 'subscription', 'follower_id' => $subscriber_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $subscription_list['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/subscription')];
        $return['subscription'] = $subscription_list['data'];
      }
      $return['page_title'] = 'Subscriptions';
      return $return;
    }
    public function following($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $following_list = User::vip_member_search(['result_type' => 'following_list', 'follower_id' => $user_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $following_list['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/following')];
        $return['following'] = $following_list['data'];
      }
      $return['page_title'] = 'Following';
      return $return;
    }
    public function hotlist($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $hotlist_list = User::vip_member_search(['result_type' => 'hotlist', 'follower_id' => $user_id, 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $hotlist_list['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/hotlist')];
        $return['hotlist'] = $hotlist_list['data'];
      }
      $return['page_title'] = 'Hotlist';
      return $return;
    }
    public function favorites($request) {
        // dd($request['post_type']);
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $cur_page = 1;
        $per_page = 10;
        $params = ['logged_user_id' => $user_id, 'favorite' => 1, 'per_page' => $per_page, 'cur_page' => $cur_page];
        if(!empty($request['post_type'])){
            $params['post_type']=[$request['post_type']];
        }
        $posts = Post::get_posts($params);
        $return['per_page'] = $per_page;
        $return['cur_page'] = $cur_page;
        $return['posts'] = $posts['data'];
        $return['total_posts'] = $posts['total_data'];
      }
      $return['page_title'] = 'Favorites';
      return $return;
    }
    public function notification($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $notifications = [];
      $per_page = 10;
      $notifications = Notification::get_list(['user_id' => $user_id, 'per_page' => $per_page]);
      $return['per_page'] = $per_page;
      if($this->user_data['role'] == 2) {
      }
      if($this->user_data['role'] == 3) {
      }
      $return['notifications'] = $notifications;
      $return['page_title'] = 'Notifications';
      return $return;
    }
	public function message($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $notifications = [];
      $per_page = 10;
      //$notifications = Notification::get_list(['user_id' => $user_id, 'per_page' => $per_page]);
	  
	  $friend_list = Notification::get_friend_list(['user_id' => $user_id]);
	  
	  $user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $user_id)->pluck('value')->first();
	  $user_img=url('/public/front/images/user-placeholder.jpg');
	  if($user_profile_photo != ''){
		  $user_img=url('public/uploads/profile_photo/' . $user_profile_photo);
	  }
	  
	 //echo '<pre>';print_r($friend_list);exit;
	  
	  
	  
	  $return['own_user_img'] = $user_img;
	  $return['friend_list'] = $friend_list;
      $return['per_page'] = $per_page;
      if($this->user_data['role'] == 2) {
      }
      if($this->user_data['role'] == 3) {
      }
      $return['notifications'] = $notifications;
      $return['page_title'] = 'Notifications';
	 
      return $return;
    }
    public function post($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $post_id = trim($request->input('post_id'));
      $posts = Post::get_posts(['logged_user_id' => $user_id, 'post_ids' => [$post_id]]);
      if(!isset($posts['data'][0])) abort(404);
      if($this->user_data['role'] == 2) {
      }
      if($this->user_data['role'] == 3) {
      }
      $return['post'] = $posts['data'][0];
      $return['page_title'] = 'Post';
      return $return;
    }
    public function buy_coins($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $coin_price_plans = Coin_price_plan::orderBy('price')->get();
        $return['coin_price_plans'] = $coin_price_plans;
      }
      $return['page_title'] = 'Buy Coins';
      return $return;
    }
    public function product($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $title = '';
      if($this->user_data['role'] == 2) {
        $product_id = $request->id;
        $title = 'Add Product';
        $product = new \stdClass;
        if($product_id != '') {
          $product = Product::find($product_id);
          $return['product'] = $product;
          $title = 'Edit Product';
        }
      }
      $return['page_title'] = $title;
      return $return;
    }
    public function my_store($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $offset = $per_page * ($cur_page - 1);
        $products = Product::where('user_id', $user_id)->orderBy('id','desc');
        $total_products = $products->count();
        $products = $products->limit($per_page)->offset($offset)->get();
        $return['products'] = $products;
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $total_products, 'additional_params' => $additional_params, 'page_url' => url('dashboard/my_store')];
      }
      $return['page_title'] = 'My Store';
      return $return;
    }
    public function orders($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 2) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 20;
        $offset = $per_page * ($cur_page - 1);
        $orders = Order::get_list(['vip_member_id' => [$user_id], 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $return['orders'] = $orders['data'];
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $orders['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/orders')];
      }
      $return['page_title'] = 'Orders';
      return $return;
    }
    public function my_orders($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] == 3) {
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 20;
        $offset = $per_page * ($cur_page - 1);
        $orders = Order::get_list(['user_id' => [$user_id], 'cur_page' => $cur_page, 'per_page' => $per_page]);
        $return['orders'] = $orders['data'];
        $additional_params = '';
        $return['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $orders['total_data'], 'additional_params' => $additional_params, 'page_url' => url('dashboard/my_orders')];
      }
      $return['page_title'] = 'My Orders';
      return $return;
    }
    public function change_password($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      $return['page_title'] = 'Change Password';
      return $return;
    }
    public function go_live($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] != 2) abort(404);
      $return['page_title'] = 'Live';
      return $return;
    }
	
	public function go_live_demo($request) {
      $return = array();
      $return['meta_data'] = array();
      $user_id = $this->user_data['id'];
      if($this->user_data['role'] != 2) abort(404);
      $return['page_title'] = 'Live';
      return $return;
    }

  public function ticket($request){
    // dd('hi');
    $return = array();
    $return['meta_data'] = array();
    $user_id = $this->user_data['id'];
    // if($this->user_data['role'] != 2) abort(404);
    $return['page_title'] = 'Ticket';
    $return['tickets'] = Ticket::with('created_by_dtl')->where('created_by',\Auth::user()->id)->get();
    // dd($return['tickets']);
    return $return;
  }
  public function ticket_chat($request){
    // dd($request->all());
    $id = decrypt($request->id);
    $return = array();
    $return['meta_data'] = array();
    $user_id = $this->user_data['id'];
    $return['page_title'] = 'Ticket Chat';
    $return['ticket_dtl'] = Ticket::with('created_by_dtl', 'ticket_chat', 'ticket_chat.created_by_dtl')->find($id);
    return $return;
  }
  public function add_ticket($request){
    $request->validate([
      'title' => 'required',
      'description' => 'required',
      'how_to_help' => 'required',
      'contacts' => 'required',
    ]);
    $data = [
      'auto_gen_id' => rand(111,999).strtotime(date('Y-m-d H:i:s')),
      'title' => $request->title,
      'description' => $request->description,
      'contacts' => $request->contacts,
      'how_to_help' => $request->how_to_help,
      'created_by' => \Auth::user()->id,
    ];
    // dd($data);
    Ticket::create($data);
    return redirect()->back()->with('success','Ticket created successfully !');
  }

  public function go_live_chat($request){
    $return = array();
    $return['meta_data'] = array();
    $user_id = $this->user_data['id'];
    if($this->user_data['role'] != 2) abort(404);
    $return['page_title'] = 'Live';
    
    return view('user.includes.vip_member.model_go_live',['return' =>$return,'user_data' => $this->user_data,'page_title' => $return['page_title']]);
  }
	
	
}
