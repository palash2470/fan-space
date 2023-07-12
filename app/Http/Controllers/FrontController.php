<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB AS DB;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;
use Illuminate\Support\Str;
use App\Http\Helpers;
use Carbon\Carbon;

use App\User;
use App\User_meta;
use App\User_category;
use App\Country;
use App\Cupsize;
use App\Sexual_orientation;
use App\Zodiac;
use App\Model_attribute;
use App\Vip_member_model_attribute;
use App\User_fav_user;
use App\User_follow_user;
use App\Post;
use App\Post_comment;
use App\Subscriber;
use App\Product;
use App\Cart;
use App\ContactUS;
use App\DynamicContent;
use App\Faq;
use App\User_earning;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
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

                $usermeta = new User_meta;
                $this->user_data['meta_data'] = $usermeta->get_usermeta(Auth::id());
                $usermeta_data = [];
                foreach ($this->user_data['meta_data'] as $k => $v) {
                    $usermeta_data[$v->key] = $v->value;
                }
                $this->user_data['meta_data'] = $usermeta_data;
            }
            return $next($request);
        });

        $this->meta_data['settings'] = array();
        $settings = DB::table('settings')->get();
        foreach ($settings as $key => $value) {
            $this->meta_data['settings'][$value->key] = $value->value;
        }
    }

    public function epoch_return(Request $request) {
        $get = $_GET;
        //$token_products = Token_product::orderBy('price')->get();
        $ans = $get['ans'] ?? '';
        $amount = $get['amount'] ?? '';
        $pi_code = $get['pi_code'] ?? '';
        $transaction_id = $get['transaction_id'] ?? '';
        $x_user_id = $get['x_user_id'] ?? '';
        $ans_1 = substr($ans, 0, 1);
        /*if($ans_1 == 'Y') {
            $request->session()->flash('success', 'Payment successfully done');
        } else {
            $request->session()->flash('error', 'Payment failed');
        }
        return redirect('user/dashboard/wallet');*/
        if($ans_1 == 'Y') {
            echo 'epoch payment successfull';
        } else {
            echo 'no epoch payment found';
        }
    }

    public function epoch_postback(Request $request) {
        $post = $_POST;
        $ans = $post['ans'] ?? '';
        $amount = $post['amount'] ?? '';
        $pi_code = $post['pi_code'] ?? '';
        $transaction_id = $post['transaction_id'] ?? '';
        $x_user_id = $post['x_user_id'] ?? '';
        $ans_1 = substr($ans, 0, 1);
        if($ans_1 == 'Y') {
            echo 'epoch payment successfull';
        } else {
            echo 'no epoch payment found';
        }
    }

    public function stripe_postback(Request $request) {
        $post = $_POST;
        $json = file_get_contents('php://input');
        $time = Carbon::now()->toDateTimeString();
        DB::table('stripe_test')->insert(['json_data' => $json, 'created_at' => $time, 'updated_at' => $time]);
        $json_data = json_decode($json, true);
        $type = $json_data['type'];
        if($type == 'invoice.payment_failed') {
            $subscription_id = $json_data['data']['object']['subscription'];
            if($subscription_id != '')
                Subscriber::where('stripe_subscription_id', $subscription_id)->delete();
        }
        if($type == 'invoice.paid') {
            $subscription_id = $json_data['data']['object']['subscription'];
            if($subscription_id != '') {
                $subscriber = Subscriber::where('stripe_subscription_id', $subscription_id)->first();
                if(isset($subscriber->id) && date('Y-m-d', strtotime($subscriber->created_at)) != date('Y-m-d') && $subscriber->next_renewal_date == date('Y-m-d')) {
                    $validity_date = date('Y-m-d 23:59:59', strtotime("+" . ($subscriber->duration_days - 1) . " day"));
                    $next_renewal_date = date('Y-m-d', strtotime("+" . $subscriber->duration_days . " day"));
                    Subscriber::where('id', $subscriber->id)->update(['validity_date' => $validity_date, 'next_renewal_date' => $next_renewal_date, 'stripe_subscription_id' => $subscription_id]);
                }
            }
        }
    }


    public function affiliate($username) {
        $user = User::where('username', $username)->where('role', '2')->first();
        if(!isset($user->id))
            return redirect('/');
        if(isset($_COOKIE['affiliate_user_id']))
            setcookie('affiliate_user_id', '', (time() - 3600), '/', '', false, true);
        setcookie('affiliate_user_id', $user->id, now()->addMonths(3)->timestamp, '/', '', false, true);
        return redirect('/');
    }

    public function cron_once_at_day_start_75810753477002() {
      //Subscriber::delete_expired_subscription();
    }

    public function index() {
        //User::create(['email' => 'tinnipapi005@gmail.com', 'phone' => '1212', 'role' => '2', 'password' => Hash::make('123456')]);
        /*$table_data = [
            ['key' => 'key 1', 'value' => 'value kgikd dnfidkf dnf'],
            ['key' => 'key 2', 'value' => 'value kgikd dnfidkf dnf 2'],
            ['key' => 'key 3', 'value' => 'value kgikd dnfidkf dnf 3<br><br>fdgfgfdg']
        ];
        echo view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $this->meta_data['settings']['settings_website_logo']), 'title' => 'User Registration', 'heading' => 'You are successfully registered as follower.', 'message' => 'lorem ipsum sit dolorm amet', 'table_data' => $table_data));
        die;*/
        //echo Session::get('vipregister_verification_code');
        /*if(isset($_GET['stripe_payment'])) {
            $card_number = '4242424242424242';
            $card_exp_month = '05';
            $card_exp_year = 2025;
            $card_cvc = 121;
            $payment_ammount = 5.00;
            $customer_data = ['name' => 'aa aa', 'description' => 'lorem ipsum', 'email' => 'aa@aa.aa', "address" => ["city" => 'kolkata', "country" => 'IN', "line1" => '000webhost', "line2" => '', "postal_code" => '700131']];
            $customer_data = [];
            $stripe_payment = Helpers::stripe_payment(['card_number' => $card_number, 'exp_month' => $card_exp_month, 'exp_year' => $card_exp_year, 'card_cvv' => $card_cvc, 'order_total' => $payment_ammount, 'currency' => 'usd', 'description' => 'fan-space pay', 'customer_data' => $customer_data]);
            dd($stripe_payment);
            $paymentdata = array('transaction_id'=>$stripe_payment['txn_id'], 'charge_id'=>$stripe_payment['charge_id'], 'stripe_customer_id'=>$stripe_payment['customer'], 'payment_date'=>date('Y-m-d'));
        }*/
        // dd(env('MAIL_HOST'));
        if(isset($_GET['dev'])) {
            $mail_body = view('email_template/thank_you', array('msg' => 'hvghvhbv'));
            $mail_data = array(
                'to' => array(
                    array('hkbaf@dfdf.dfdf', "")
                ),
                'subject' => 'Thank you email',
                'body' => $mail_body,
            );
            Helpers::pmail($mail_data);

            dd('fdfd',);
            //$interval1111 = 'day' 'week' 'month' 'year';
            /*$customer_data = [];
            $stripe_payment = Helpers::stripe_subscription_payment(['card_number' => '4242424242424242', 'exp_month' => '05', 'exp_year' => '2025', 'card_cvv' => '555', 'order_total' => '20', 'currency' => 'usd', 'description' => 'fan-space subscription buy', 'customer_data' => $customer_data, 'plan_data' => ['name' => 'sudipta_001', 'interval' => 'day', 'interval_count' => 1]]);
            die;*/
            //$stripe_subscription = Helpers::stripe_subscription_cancel(['subscription_id' => 'sub_1Jzw3VARPFOQu1Xv5oSZRZGa']);
        }
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data = [];
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['page_content'] = DynamicContent::where('page_name','HOME')->first();
        $meta_data['latest_model'] = User::where('role',2)->orderBy('users.id','desc')->limit(8)->get()->toArray();
        foreach ($meta_data['latest_model'] as $key => $value) {
            $image_post = Post::where('user_id',$value['id'])->where('post_type','photo')->get()->toArray();
            $video_post = Post::where('user_id',$value['id'])->where('post_type','video')->get()->toArray();
            $profile_photo = User_meta::where('user_id',$value['id'])->where('key','profile_photo')->select('value')->pluck('value')->first();
            // dd($image_post);
            $meta_data['latest_model'][$key]['image_post'] = count($image_post);
            $meta_data['latest_model'][$key]['video_post'] = count($video_post);
            $meta_data['latest_model'][$key]['profile_photo'] = $profile_photo;
        }
        $meta_data['top_performer'] = User_earning::join('users','users.id','=','user_earnings.user_id')->select('*',DB::raw('sum(token_coins) as total_coin'))->groupBy('user_id')->orderBy('total_coin','desc')->limit(8)->get()->toArray();
        foreach ($meta_data['top_performer'] as $key => $value) {
            $image_post = Post::where('user_id',$value['id'])->where('post_type','photo')->get()->toArray();
            $video_post = Post::where('user_id',$value['id'])->where('post_type','video')->get()->toArray();
            $profile_photo = User_meta::where('user_id',$value['id'])->where('key','profile_photo')->select('value')->pluck('value')->first();
            // dd($image_post);
            $meta_data['top_performer'][$key]['image_post'] = count($image_post);
            $meta_data['top_performer'][$key]['video_post'] = count($video_post);
            $meta_data['top_performer'][$key]['profile_photo'] = $profile_photo;
        }
        // dd($meta_data['top_performer']);
        return view('front.index', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => '']);
    }

    public function download(Request $request) {
      $type = trim($request->input('t'));
      $product_id = trim($request->input('pid'));
      $order_id = trim($request->input('oid'));
      $order_item_id = trim($request->input('oiid'));
      if(in_array($type, ['product', 'order']))
        product::download(['type' => $type, 'product_id' => $product_id, 'order_id' => $order_id, 'order_item_id' => $order_item_id]);
      abort(404);
    }
    public function show_attachment($file=null,$type=null){
        // $path = base64_decode($file);
        // dd($path);

        // $path = 'file.mp4';
        $path = base_path('/public/uploads/product/attachments/'.$file);
        if($type==null){

            if (file_exists($path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: inline; filename='.basename($path));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path));
                ob_clean();
                flush();
                readfile($path);
                exit;
            }
        }else{
            if (file_exists($path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename='.basename($path));
                header('Expires: 0');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($path));
                header('Accept-Ranges: bytes');
                // header('Content-Length: ' . filesize($path));
                // ob_clean();
                // flush();
                readfile($path);
                exit;
            }
        }
        // dd($path);

        // $size=filesize($path);

        // $fm=@fopen($path,'rb');
        // if(!$fm) {
        //   // You can also redirect here
        //   header ("HTTP/1.0 404 Not Found");
        //   die();
        // }

        // $begin=0;
        // $end=$size;

        // if(isset($_SERVER['HTTP_RANGE'])) {
        //   if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
        //     $begin=intval($matches[0]);
        //     if(!empty($matches[1])) {
        //       $end=intval($matches[1]);
        //     }
        //   }
        // }

        // if($begin>0||$end<$size)
        //   header('HTTP/1.0 206 Partial Content');
        // else
        //   header('HTTP/1.0 200 OK');

        // header("Content-Type: video/mp4");
        // header('Accept-Ranges: bytes');
        // header('Content-Length:'.($end-$begin));
        // header("Content-Disposition: inline;");
        // header("Content-Range: bytes $begin-$end/$size");
        // header("Content-Transfer-Encoding: binary\n");
        // header('Connection: close');

        // $cur=$begin;
        // fseek($fm,$begin,0);

        // while(!feof($fm)&&$cur<$end&&(connection_status()==0))
        // { print fread($fm,min(1024*16,$end-$cur));
        //   $cur+=1024*16;
        //   usleep(1000);
        // }
        // die();




        // $file = decrypt($file);
        // dd($file);
        // if (file_exists($file)) {
        //     header('Content-Description: File Transfer');
        //     header('Content-Type: application/octet-stream');
        //     header('Content-Disposition: attachment; filename='.basename($file));
        //     header('Expires: 0');
        //     header('Cache-Control: must-revalidate');
        //     header('Pragma: public');
        //     header('Content-Length: ' . filesize($file));
        //     ob_clean();
        //     flush();
        //     readfile($file);
        //     exit;
        // }
        // // dd($request->all());
        // $html ='';
        // // $url =url('public/uploads/product/attachments/'.$request->attachment);
        // // $ch = curl_init();
        // // curl_setopt($ch, CURLOPT_HEADER, 0);
        // // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        // // curl_setopt($ch, CURLOPT_URL, $url);
        // // $data = curl_exec($ch);
        // // curl_close($ch);
        // // return $data;
        // $source=readfile(base_path('/public/uploads/product/attachments/'.$request->attachment));
        // // $source = file_get_contents(url('public/uploads/product/attachments/'.$request->attachment));
        // if($request->type==3||$request->type=='3'){
        //     $html .='<video width="400" controls>
        //             <source src="'.$source.'" type="video/mp4">
        //             <source src="mov_bbb.ogg" type="video/ogg">
        //             Your browser does not support HTML video.
        //             </video>';
        // return ['html'=>$source];
        // }

    }

    public function search(Request $request) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $s = trim($request->input('s'));
        $st = trim($request->input('st'));
        $lst = trim($request->input('lst'));
        $uc = trim($request->input('uc'));
        $uo = trim($request->input('uo'));
        $age = trim($request->input('age'));
        $cur_page = trim($request->input('pg'));
        $cur_page = ($cur_page == '' || $cur_page < 1 ? 1 : $cur_page);
        $per_page = 16;
        $st = ($st == '' ? [] : explode(',', $st));
        $lst = ($lst == '' ? [] : explode(',', $lst));
        $uc = ($uc == '' ? [] : explode(',', $uc));
        $uo = ($uo == '' ? [] : explode(',', $uo));
        $age = ($age == '' ? [] : explode(',', $age));
        $vip_member_search = User::vip_member_search(['search' => $s, 'online_status' => $st, 'live_status' => $lst, 'user_cat' => $uc, 'age' => $age, 'cur_page' => $cur_page, 'per_page' => $per_page,'uo'=>$uo]);
        if(count($vip_member_search['data']) == 1) {
            return redirect('u/' . $vip_member_search['data'][0]->username);
        }
        $meta_data['vip_members'] = $vip_member_search['data'];
        $meta_data['search_params'] = ['s' => $s, 'st' => $st, 'lst' => $lst, 'uc' => $uc, 'age' => $age,'uo'=>$uo];
        $additional_params = '&s=' . $s . '&st=' . implode(',', $st) . '&lst=' . implode(',', $lst) . '&uc=' . implode(',', $uc) .'&uo=' . implode(',', $uo). '&age=' . implode(',', $age);
        $meta_data['pagination'] = ['per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $vip_member_search['total_data'], 'additional_params' => $additional_params, 'page_url' => url('/search')];
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        return view('front.search', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Search']);
    }

    public function vip_member_profile(Request $request, $username, $section = '') {
        $user_data = $this->user_data;
        // if(!isset($user_data['id'])) return redirect('/');
        $helper_settings = Helpers::get_settings();
        $vip_member = User::vip_member_details(['search' => $username, 'search_by' => 'username']);
        $vip_member = $vip_member['data'];
        if(!isset($vip_member['user']->id)) abort(404);
        $cupsizes = Cupsize::orderBy('title')->get();
        $sexual_orientations = Sexual_orientation::orderBy('title')->get();
        $zodiacs = Zodiac::orderBy('title')->get();
        $model_attributes = Model_attribute::orderBy('title')->get();
        if(in_array($section, ['posts', 'photos', 'videos'])) {
          $age = trim($request->age);
          $ordby = trim($request->ordby);
          $ord = trim($request->ord);
          $cur_page = 1;
          $per_page = 36;
          $post_type = [];
          if($section == 'photos') $post_type[] = 'photo';
          if($section == 'videos') $post_type[] = 'video';
          $posts = Post::get_profile_posts(['user_id' => $vip_member['user']->id, 'logged_user_id' => ($user_data['id']??0), 'post_type' => $post_type, 'age' => $age, 'ordby' => $ordby, 'ord' => $ord, 'per_page' => $per_page, 'cur_page' => $cur_page]);
        //   dd($posts);
          $meta_data['per_page'] = $per_page;
          $meta_data['cur_page'] = $cur_page;
          $meta_data['posts'] = $posts['data'];
          $meta_data['total_posts'] = $posts['total_data'];
        }
        if(in_array($section, ['store'])) {
          $cur_page = 1;
          $per_page = 36;
          $products = Product::get_list(['user_id' => $vip_member['user']->id, 'logged_user_id' => ($user_data['id']??0), 'in_stock' => '', 'per_page' => $per_page, 'cur_page' => $cur_page]);
          $meta_data['per_page'] = $per_page;
          $meta_data['cur_page'] = $cur_page;
          $meta_data['products'] = $products['data'];
          $meta_data['total_products'] = $products['total_data'];
        }
        if(in_array($section, ['live'])) {
        }
        $subscriber = Subscriber::where('user_id', $vip_member['user']->id)->where('subscriber_id', ($user_data['id']??0))->first();
        $meta_data['vip_member'] = $vip_member;
        $meta_data['cupsizes'] = $cupsizes;
        $meta_data['sexual_orientations'] = $sexual_orientations;
        $meta_data['zodiacs'] = $zodiacs;
        $meta_data['model_attributes'] = $model_attributes;
        $meta_data['subscriber'] = $subscriber;
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        //dd($meta_data);
        return view('front.vip_member_profile', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => $vip_member['user']->display_name]);
    }

    public function cart(Request $request) {
      $user_data = $this->user_data;
      if(!isset($user_data['id'])) return redirect('/');
      $helper_settings = Helpers::get_settings();
      $meta_data['global_settings'] = $this->meta_data['settings'];
      $meta_data['helper_settings'] = $helper_settings;
      return view('front.cart', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Cart']);
    }

    public function checkout(Request $request) {
      $user_data = $this->user_data;
      if(!isset($user_data['id'])) return redirect('/');
      $cart = $_COOKIE['cart'] ?? '{}';
      $cart = json_decode($cart, true);
      if(count($cart) == 0) return redirect('/cart');
      $helper_settings = Helpers::get_settings();
      $meta_data['global_settings'] = $this->meta_data['settings'];
      $meta_data['helper_settings'] = $helper_settings;
      return view('front.checkout', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Checkout']);
    }

    public function order_placed(Request $request) {
      $user_data = $this->user_data;
      $helper_settings = Helpers::get_settings();
      $meta_data['global_settings'] = $this->meta_data['settings'];
      $meta_data['helper_settings'] = $helper_settings;
      return view('front.order_placed', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Order Placed']);
    }

    public function reset_password(Request $request, $token, $email) {
        if ($request->has('new_password') && $request->isMethod('post')) {
            $email = trim($request->input('email'));
            $new_password = trim($request->input('new_password'));
            $token = trim($request->input('token'));
            $user = User::where('email', $email)->where('password_reset_token', $token)->first();
            if($new_password == '') {
                $request->session()->flash('error', 'Password can not be blank');
                return redirect('reset-password/' . $token . '/' . $email);
            } elseif(isset($user->id)) {
                User::where('id', $user->id)->update(['password_reset_token' => '', 'password' => Hash::make($new_password)]);
                $request->session()->flash('success', 'Password successfully updated');
                return redirect('reset-password-success');
            } else {
                $request->session()->flash('error', 'Email or token mismatch');
                return redirect('reset-password/' . $token . '/' . $email);
            }
        }
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data = ['data' => []];
        $user = User::where('email', $email)->where('password_reset_token', $token)->first();
        if(!isset($user->id)) abort(404);
        $meta_data['data']['user'] = $user;
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        return view('front.reset_password', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Reset Password']);
    }


    public function reset_password_success(Request $request) {
        if(!Session::has('success')) return redirect('/');
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        return view('front.reset_password_success', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Reset Password']);
    }


    public function admin_login(Request $request) {
        $user_data = $this->user_data;
        if(isset($user_data['id'])) return redirect('/');
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        return view('front.admin_login', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Admin Login']);
    }
    public function contact_us(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string',
                'ph_no' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ]);
            if($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            ContactUS::create($request->all());
            return redirect()->back()->with('success','Your message submitted !');
        }else{
            $user_data = $this->user_data;
            $helper_settings = Helpers::get_settings();
            $meta_data['global_settings'] = $this->meta_data['settings'];
            $meta_data['helper_settings'] = $helper_settings;
            $meta_data['page_content'] = DynamicContent::where('page_name','CONTACT_US')->first();

            return view('front.contact_us', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Contact Us']);
        }
    }
    public function privacy_policy(Request $request) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['page_content'] = DynamicContent::where('page_name','PRIVACY_POLICY')->first();

        return view('front.privacy_policy', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Privacy Policy']);
    }
    public function faq(Request $request) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['faq'] = Faq::paginate(6);

        return view('front.faq', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Privacy Policy']);
    }
    public function about_us(Request $request) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['page_content'] = DynamicContent::where('page_name','ABOUTUS')->first();

        return view('front.about_us', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Privacy Policy']);
    }

    public function terms_conditions(Request $request) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['page_content'] = DynamicContent::where('page_name','TERMS_CONDITION')->first();
        return view('front.terms_conditions', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => 'Terms & Conditions']);
    }

    /*public function login()
    {
        if(Auth::id())
            return redirect('/');
        return view('front.login', []);
    }*/

    public function logout()
    {
        User::where('id', Auth::id())->update(['is_login' => 0]);
        Auth::logout();
        return redirect('/');
    }

    public function userLiveVideo(Request $request,$username) {
        $user_data = $this->user_data;
        $helper_settings = Helpers::get_settings();
        $vip_member = User::vip_member_details(['search' => $username, 'search_by' => 'username']);
        $vip_member = $vip_member['data'];
        if(!isset($vip_member['user']->id)) abort(404);
        $cupsizes = Cupsize::orderBy('title')->get();
        $sexual_orientations = Sexual_orientation::orderBy('title')->get();
        $zodiacs = Zodiac::orderBy('title')->get();
        $model_attributes = Model_attribute::orderBy('title')->get();
        
        $subscriber = Subscriber::where('user_id', $vip_member['user']->id)->where('subscriber_id', ($user_data['id']??0))->first();
        $meta_data['vip_member'] = $vip_member;
        $meta_data['cupsizes'] = $cupsizes;
        $meta_data['sexual_orientations'] = $sexual_orientations;
        $meta_data['zodiacs'] = $zodiacs;
        $meta_data['model_attributes'] = $model_attributes;
        $meta_data['subscriber'] = $subscriber;
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        //dd($meta_data);
        return view('front.user_live_video', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => $vip_member['user']->display_name]);
    }
}
