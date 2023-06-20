<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session as Laravel_session;
use Illuminate\Support\Str;
use App\Http\Helpers;

use App\Country;
use App\User;
use App\User_meta;
use App\User_interest_cat;
use App\User_own_cat;
use App\Cupsize;
use App\Sexual_orientation;
use App\Zodiac;
use App\Model_attribute;
use App\Vip_member_model_attribute;
use App\Subscriber;
use App\Post;
use App\User_fav_user;
use App\User_follow_user;
use App\Post_react;
use App\Post_comment;
use App\Post_comment_react;
use App\Product;
use App\Reported_item;
use App\Notification;
use App\Coin_price_plan;
use App\Payment;
use App\Post_paid_user;
use App\User_earning;
use App\Post_tip;
use App\User_payout;
use App\Cart;
use App\Order;
use App\Order_item;
use App\Order_address;
use App\Live_session;
use App\Live_session_history;
use App\Live_session_chat_message;
use App\Live_session_tip;
use App\PrivateChat;
use App\AdminNotification;
use App\GroupChat;
use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;
use Twilio\Rest\Client;
use Carbon\Carbon;

class AjaxController extends Controller
{

  private $user_data = array();

  private $meta_data = array();

  function __construct()
  {
    $this->middleware(function ($request, $next) {

      if (Auth::user()) {
        $getData = User::find(Auth::id())->toArray();
        $dashboard_url = '';
        if ($getData['role'] == 1)
          $dashboard_url = url('/admin');
        if (in_array($getData['role'], [2, 3]))
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

  public function ajaxpost(Request $request)
  {
    $action = $request->input('action');
    $this->{'ajaxpost_' . $action}($request);
  }

  public function ajaxget(Request $request)
  {
    $action = $request->input('action');
    $this->{'ajaxget_' . $action}($request);
  }

  public function ajaxpost_getFriendChat($request)
  {
    $friend_user_id = $request->friend_id ?? '';
    $user_id = Auth::user()->id;

    $own_profile_photo = url('/public/front/images/user-placeholder.jpg');
    $own_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $user_id)->pluck('value')->first();
    if ($own_profile_photo != '') {
      $own_profile_photo = url('public/uploads/profile_photo/' . $own_profile_photo);
    }



    $user = User::find($friend_user_id);
    $user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $friend_user_id)->pluck('value')->first();
    $user_img = url('/public/front/images/user-placeholder.jpg');
    if ($user_profile_photo != '') {
      $user_img = url('public/uploads/profile_photo/' . $user_profile_photo);
    }
    $user_title = $user->first_name . ' ' . $user->last_name;

    $friend_data = [];
    $friend_data['user_title'] = $user_title;
    $friend_data['user_img'] = $user_img;
    $friend_data['username'] = $user->username;



    $messages = Notification::where('action', 'sendMessageToSubscriber')->where(function ($query) use ($user_id, $friend_user_id) {
      $query->where('user_id', '=', $user_id)->where('object_id', '=', $friend_user_id);
    })->orWhere(function ($query) use ($user_id, $friend_user_id) {
      $query->where('user_id', '=', $friend_user_id)->where('object_id', '=', $user_id);
    })->orderBy('id', 'asc')->get();



    $data = [];
    if (count($messages) > 0) {
      foreach ($messages as $row) {
        if ($row->message != '') {
          $is_me = 'N';
          $profile_photo = $user_img;
          if ($row->object_id == $user_id) {
            $is_me = 'Y';
            $profile_photo = $own_profile_photo;
          }

          $data[] = array(
            'id'    => $row->id,
            'from_id'  => $row->user_id,
            'to_id'    => $row->object_id,
            'is_me'    => $is_me,
            'msg'    => $row->message,
            'deleted_at'    => $row->deleted_at,
            'seen'    => $row->seen,
            'profile_photo'  => $profile_photo,
            'time'    => Carbon::parse($row->created_at)->format('Y-m-d H:i a'),
          );
        }
      }
    }




    $result = array(
      'statusCode'  => '200',
      'result'    => $data,
      'friend_data'  => $friend_data,
      'message'    => 'Success'
    );

    echo json_encode($result);
  }

  public function ajaxpost_deleteMessage($request)
  {
    // dd($request->all());
    foreach ($request->delete_id as $key => $value) {
      Notification::find($value)->update([
        'deleted_at' => date('Y-m-d H:i:s'),
      ]);
    }
    $return_data['success'] = true;
    $return_data['status'] = 200;
    echo json_encode($return_data);
  }

  public function ajaxpost_sendMessge($request)
  {
    $user_id = Auth::user()->id;
    $friend_id   = $request->friend_id ?? '';
    $message   = $request->message ?? '';
    Notification::create([
      'user_id' => $friend_id, 'object_type' => 'user', 'object_id' => $user_id, 'action' => 'sendMessageToSubscriber', 'message' => $message, 'json_data' => json_encode(['user_id' => $friend_id]), 'created_at' => date('Y-m-d H:i:s')
    ]);
    $return_data['action'] = 1;
    echo json_encode($return_data);
  }

  public function ajaxpost_send_email_otp($request)
  {
    $email = $request->email ?? '';
    $role = $request->role ?? '';

    $return_data['success'] = 0;

    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    if ($email == '' || preg_match($email_pattern, $email) == 0) {
      $return_data['success'] = 0;
      $return_data['error_msg'] = 'Email not valid';
    } else if ($email != '') {
      $user_found = User::where('email', $email)->where('role', $role)->where('status', '1')->count();
      if ($user_found == 1) {
        $return_data['success'] = 0;
        $return_data['error_msg'] = 'Email already exists';
      } else {
        $otp = Helpers::generateOTP();
        $otpArr = array(
          'email' => $email,
          'verification_code' => $otp
        );
        Laravel_session::put('reg_otp', $otpArr);



        $email_template = '<table style="width: 900px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 14px;"> <tr class="header"> <td><table class="table" style="width: 100%; border-bottom: 1px solid #00aff0;"> <tr> <td style="width: 150px;"><img src="https://onlyfans.biplab.aqualeafitsol.com/public/uploads/settings/settings_website_logo/f32ed34d-5e31-494f-b20a-65de6c6c981f.png" class="logo" width="100%"/></td><td align="right"><h4 style="font-size: 24px;">Email Verification</h4></td></tr></table></td></tr><tr class="body"> <td><div class="" style="margin-bottom: 30px; margin-top: 40px;"> <p style="margin-bottom: 8px; font-size: 18px;">Your email verification code is </p></div><div class="m-t-30" style="margin-bottom: 20px;"> <table class="table plainTable" border="1" style="width: 100%; margin: 30px 0; border-collapse: collapse; border-color: #00aff0;"> <tr> <th style="text-align: left; background: #cdf0fd; padding: 10px 10px;">OTP</th> <td style="text-align: left; padding: 10px 10px;">' . $otp . '</td></tr></table> </div></div></td></tr><tr class="footer"> <td><p style="font-size: 10px; color: #888;">Disclaimer: The information provided on OnlyFans is for general informational purposes only. All information on the Site is provided in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability or completeness of any information on the Site.</p></td></tr></table>';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: biplabaqualeafit@server.aqualeafitsol.com' . "\r\n" .
          'Reply-To: biplabaqualeafit@server.aqualeafitsol.com' . "\r\n" .
          'X-Mailer: PHP/' . phpversion();
        mail($email, 'Registration signup process', $email_template, $headers);

        /*$mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/f32ed34d-5e31-494f-b20a-65de6c6c981f.png'), 'title' => 'Email Verification', 'heading' => 'Your email verification code is ' . $otp, 'message' => ''));

				print_r($mail_body);exit;
				$mail_data = array(
					'to' => array(
						array($email, "")
					),
					'subject' => 'Email Verification',
					'body' => $mail_body,
				);

				print_r($mail_data);exit;

				Helpers::pmail($mail_data);*/
        $return_data['success'] = 1;
        $return_data['otp'] = $otp;
      }
    }
    echo json_encode($return_data);
  }


  public function ajaxpost_emailOtpValidation($request)
  {
    $otp = $request->otp_password ?? '';
    $getotpArr = Laravel_session::get('reg_otp');

    $verification_code = $getotpArr['verification_code'];

    if (!empty($otp)) {
      if ($verification_code == $otp) {
        $return_data['action'] = 1;
      } else {
        $return_data['action'] = 0;
      }
    } else {
      $return_data['action'] = 0;
    }


    echo json_encode($return_data);
  }


  public function ajaxpost_send_phone_otp($request)
  {
    //$phone = $request->phone ?? '';
    $receiverNumber = $request->phone ?? '';

    //print_r($receiverNumber);exit;
    $otp = Helpers::generateOTP();
    $otpArr = array(
      'phone' => $receiverNumber,
      'verification_code' => $otp
    );
    $message = 'Your verification code is: ' . $otp . '. Do not share this code with anyone.';
    Laravel_session::put('reg_otp', $otpArr);

    //print_r($message);exit;

    $error = '';

    try {
      $account_sid = env("TWILIO_SID");
      // dd($account_sid);
      $auth_token = env("TWILIO_TOKEN");
      $twilio_number = env("TWILIO_FROM");

      $account_sid = "AC50725b570c8e5e907cf1c11f0c2ab1ce"; //"AC4b73ed56efbdbaf3f191c9007b910c24";
      // dd($account_sid);
      $auth_token = "ee383c3afe80844abe2b41137aa50f2f"; //"a97a0273fc671863c34493fb6c79ab82";
      $twilio_number = "+19205888450"; //"+12164877483";


      /*$account_sid ="ACe2a43c211c2cde4e71ae3b42413de4f7";
            $auth_token = "b945974143b47e326c7d274fedc9882c";
            $twilio_number = "+17069325494";*/

      $client = new Client($account_sid, $auth_token);
      $client->messages->create($receiverNumber, [
        'from' => $twilio_number,
        'body' => $message
      ]);
      // dd('SMS Sent Successfully.');
      $error = '';
    } catch (Exception $e) {
      $error = $e->getMessage();
    }





    $return_data['success'] = 1;
    $return_data['otp'] = $otp;
    //print_r($return_data);exit;
    //return response()->json([$return_data]);

    echo json_encode(array('success' => 1, 'otp' => $otp, 'error' => $error));
  }


  public function ajaxpost_phoneOtpValidation($request)
  {
    $otp = $request->otp_password ?? '';
    $getotpArr = Laravel_session::get('reg_otp');

    $verification_code = $getotpArr['verification_code'];

    if (!empty($otp)) {
      if ($verification_code == $otp) {
        $return_data['action'] = 1;
      } else {
        $return_data['action'] = 0;
      }
    } else {
      $return_data['action'] = 0;
    }


    echo json_encode($return_data);
  }




  public function ajaxpost_login_check($request)
  {
    $role = $request->role ?? '';
    $email = $request->email ?? '';
    $password = $request->password ?? '';
    $remember = $request->remember ?? '0';
    $data = [];
    $message = '';
    $success = 0;
    $credentials = $request->only('email', 'password');
    $credentials['status'] = 1;
    $remember = false;
    if ($request->remember = '1') {
      $remember = true;
    }
    $user = User::where('email', $email)->where('role', $role)->where('status', '1')->get();
    if (count($user) == 1) {
      if (Hash::check($password, $user[0]->password)) {
        $success = 1;
      }
    }
    $message = __('Invalid email address or password, try again');
    echo json_encode(array('success' => $success, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_forgot_password($request)
  {
    $email = $request->email ?? '';
    $data = [];
    $message = __('Invalid email address, try again');
    $success = 0;
    $user = User::where('email', $email)->first();
    if (isset($user->id)) {
      $token = Str::random(60);
      User::where('id', $user->id)->update(['password_reset_token' => $token]);
      $link = url('/reset-password/' . $token . '/' . $email);
      $msg = 'Click following link to reset password of your account';
      $mail_body = view('email_template/forgot_password', array('link' => $link, 'msg' => $msg));
      $mail_data = array(
        'to' => array(
          array($email, "")
        ),
        'subject' => 'Reset Password Link',
        'body' => $mail_body,
      );
      Helpers::pmail($mail_data);
      $success = 1;
      $message = 'A reset password link has been sent to your email.';
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_social_login($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $request->role ?? '';
    $type = $request->type ?? '';
    $data = [];
    if ($user_id != '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    $data['redirect_url'] = '';
    if ($type == 'facebook') $data['redirect_url'] = url('social-login/facebook');
    if ($type == 'google') $data['redirect_url'] = url('social-login/google');
    if (isset($_COOKIE['social_login']))
      setcookie('social_login', '', (time() - 3600));
    setcookie('social_login', json_encode(['role' => $role]), time() + (86400), "/");
    echo json_encode(array('success' => 1, 'data' => $data, 'message' => ''));
  }


  public function ajaxpost_validate_vipmember_register($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $step = $request->step ?? '';
    $form_data = json_decode(($request->data ?? '{}'), true);
    $data = $errors = [];
    $message = '';
    $time = time();
    if ($user_id != '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($step == '1') {
      $email = trim($form_data['email']);
      $username = trim($form_data['username']);
      $secret = $this->meta_data['settings']['settings_google_recaptcha_secret_key'];
      $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $form_data['g_recaptcha_response']);
      $captcha_success = json_decode($verify);
      if ($captcha_success->success != '1') {
        $errors[] = ['field' => 'g_recaptcha_response', 'message' => 'Captcha not validated'];
      }
      $user_found = User::where('email', $email)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      $user_found = User::where('username', $username)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'username', 'message' => 'Username already exists'];
      }
      if (count($errors) == 0) {
        $vipregister_verified_email = Laravel_session::get('vipregister_verified_email');
        if ($vipregister_verified_email != $email) {
          /*$verification_code = rand(100000, 999999);
                    Laravel_session::put('vipregister_verification_code', $verification_code);
                    Laravel_session::put('vipregister_verified_email', '');
                    $mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $this->meta_data['settings']['settings_website_logo']), 'title' => 'Email Verification', 'heading' => 'Your email verification code is ' . $verification_code, 'message' => ''));
                    $mail_data = array(
                      'to' => array(
                          array($form_data['email'], "")
                      ),
                      'subject' => 'Email Verification',
                      'body' => $mail_body,
                    );
                    Helpers::pmail($mail_data);*/
          $data['need_email_verification'] = 1;
        }
      }
    }
    /*if($step == '1_3') {
            $email = trim($form_data['email']);
            $email_verification_code = trim($form_data['email_verification_code']);
            $vipregister_verification_code = Laravel_session::get('vipregister_verification_code');
            if($vipregister_verification_code != $email_verification_code) {
                $errors[] = ['field' => 'email_verification_code', 'message' => 'Email verification code mismatch'];
            } else {
                Laravel_session::put('vipregister_verified_email', $email);
            }
        }*/
    $success = 1;
    if (count($errors) > 0) $success = 0;
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => $message));
  }

  public function ajaxpost_register($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $request->role ?? '';
    $form_data = json_decode(($request->data ?? '{}'), true);
    $data = $errors = [];
    $message = '';
    $time = time();
    if ($user_id != '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($role == 2) {
      $email = trim($form_data['email']);
      $password = trim($form_data['password']);
      $username = trim($form_data['username']);
      $display_name = trim($form_data['display_name']);
      $first_name = trim($form_data['first_name']);
      $last_name = trim($form_data['last_name']);
      $mobile = trim($form_data['mobile']);
      $dob = trim($form_data['dob']);
      $user_cat_id = trim($form_data['user_cat_id']);
      $interest_cats = $form_data['interest_cats'];
      $allow_vip_friend_request = trim($form_data['allow_vip_friend_request']);
      $subscription_price_1m = trim($form_data['subscription_price_1m']);
      $subscription_price_3m = trim($form_data['subscription_price_3m']);
      $subscription_price_6m = trim($form_data['subscription_price_6m']);
      $subscription_price_12m = trim($form_data['subscription_price_12m']);
      $twitter_url = trim($form_data['twitter_url']);
      $wishlist_url = trim($form_data['wishlist_url']);
      $address_line_1 = trim($form_data['address_line_1']);
      $country_code = trim($form_data['country_code']);
      $zip_code = trim($form_data['zip_code']);
      $about_bio = trim($form_data['about_bio']);
      $free_follower = trim($form_data['free_follower']);
      $profile_keywords = trim($form_data['profile_keywords']);
      $bank_country_id = trim($form_data['bank_country_id']);
      $bank_account_name = trim($form_data['bank_account_name']);
      $bank_account_sort_code = trim($form_data['bank_account_sort_code']);
      $bank_account_number = trim($form_data['bank_account_number']);
      $vipregister_verified_email = Laravel_session::get('vipregister_verified_email');
      $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
      $username_pattern = "/^[a-z0-9_]{3,50}$/i";
      $bank_account_sort_code_pattern = "/^(\d){2}-(\d){2}-(\d){2}$/";
      $bank_account_number_pattern = "/^(\d){8}$/";
      $subscription_min_price = $this->meta_data['settings']['settings_model_subscription_min_price'];
      $subscription_max_price = $this->meta_data['settings']['settings_model_subscription_max_price'];
      if ($email == '' || preg_match($email_pattern, $email) == 0) {
        $errors[] = ['field' => 'email', 'message' => 'Email not valid'];
      }
      if ($vipregister_verified_email != $email) {
        $errors[] = ['field' => 'email', 'message' => 'Email not verified'];
      }
      $user_found = User::where('email', $email)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      if ($password == '') {
        $errors[] = ['field' => 'password', 'message' => 'Password not valid'];
      }
      if ($username == '' || preg_match($username_pattern, $username) == 0) {
        $errors[] = ['field' => 'username', 'message' => 'Username not valid'];
      }
      $user_found = User::where('username', $username)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'username', 'message' => 'Username already exists'];
      }
      /*if($subscription_price_1m != '' && ($subscription_price_1m < $subscription_min_price || $subscription_price_1m > $subscription_max_price)) {
                $errors[] = ['field' => 'subscription_price_1m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
            }
            if($subscription_price_3m != '' && ($subscription_price_3m < $subscription_min_price || $subscription_price_3m > $subscription_max_price)) {
                $errors[] = ['field' => 'subscription_price_3m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
            }
            if($subscription_price_6m != '' && ($subscription_price_6m < $subscription_min_price || $subscription_price_6m > $subscription_max_price)) {
                $errors[] = ['field' => 'subscription_price_6m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
            }
            if($subscription_price_12m != '' && ($subscription_price_12m < $subscription_min_price || $subscription_price_12m > $subscription_max_price)) {
                $errors[] = ['field' => 'subscription_price_12m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
            }*/
      if ($subscription_price_1m != '' && $subscription_price_1m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_1m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_3m != '' && $subscription_price_3m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_3m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_6m != '' && $subscription_price_6m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_6m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_12m != '' && $subscription_price_12m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_12m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($bank_country_id == 222 && preg_match($bank_account_sort_code_pattern, $bank_account_sort_code) == 0) {
        $errors[] = ['field' => 'bank_account_sort_code', 'message' => 'Bank account sort code is not valid'];
      }
      if ($bank_country_id == 222 && preg_match($bank_account_number_pattern, $bank_account_number) == 0) {
        $errors[] = ['field' => 'bank_account_number', 'message' => 'Bank account number is not valid'];
      }
      if (!$request->hasFile('id_proof_doc')) {
        $errors[] = ['field' => 'id_proof_doc', 'message' => 'Photo of ID document required'];
      } else {
        $ext = $request->id_proof_doc->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof_doc', 'message' => 'Upload only jpg, png'];
        }
      }
      if (!$request->hasFile('id_proof')) {
        $errors[] = ['field' => 'id_proof', 'message' => 'Photo of ID required'];
      } else {
        $ext = $request->id_proof->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_video')) {
        $ext = $request->profile_video->extension();
        if (!in_array($ext, ['mp4'])) {
          $errors[] = ['field' => 'profile_video', 'message' => 'Upload only mp4'];
        }
      }
      if ($request->hasFile('profile_banner')) {
        $ext = $request->profile_banner->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_banner', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        $affiliate_user_id = '';
        if (isset($_COOKIE['affiliate_user_id']))
          $affiliate_user_id = $_COOKIE['affiliate_user_id'];
        $user = User::create(['email' => $email, 'role' => $role, 'password' => Hash::make($password), 'username' => $username, 'display_name' => $display_name, 'first_name' => $first_name, 'last_name' => $last_name, 'phone' => $mobile, 'dob' => date('Y-m-d', strtotime($dob)), 'status' => 0, 'affiliate_user_id' => $affiliate_user_id]);
        User_own_cat::create(['user_id' => $user->id, 'user_cat_id' => $user_cat_id]);
        $id_proof_doc_filename = $user->id . '_id_proof_doc.' . $request->id_proof_doc->extension();
        $request->id_proof_doc->move(public_path('uploads/id_proof_doc'), $id_proof_doc_filename);
        $id_proof_filename = $user->id . '_id_proof.' . $request->id_proof->extension();
        $request->id_proof->move(public_path('uploads/id_proof'), $id_proof_filename);
        $profile_photo_filename = '';
        if ($request->hasFile('profile_photo')) {
          $profile_photo_filename = $user->id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        $profile_video_filename = '';
        if ($request->hasFile('profile_video')) {
          $profile_video_filename = $user->id . '_profile_video.' . $request->profile_video->extension();
          $request->profile_video->move(public_path('uploads/profile_video'), $profile_video_filename);
        }
        $profile_banner_filename = '';
        if ($request->hasFile('profile_banner')) {
          $profile_banner_filename = $user->id . '_profile_banner.' . $request->profile_banner->extension();
          $request->profile_banner->move(public_path('uploads/profile_banner'), $profile_banner_filename);
        }
        $country_id = Country::where('iso_code_2', $country_code)->pluck('country_id')->first();
        User_meta::insert([
          ['user_id' => $user->id, 'key' => 'allow_vip_friend_request', 'value' => $allow_vip_friend_request],
          ['user_id' => $user->id, 'key' => 'subscription_price_1m', 'value' => $subscription_price_1m],
          ['user_id' => $user->id, 'key' => 'subscription_price_3m', 'value' => $subscription_price_3m],
          ['user_id' => $user->id, 'key' => 'subscription_price_6m', 'value' => $subscription_price_6m],
          ['user_id' => $user->id, 'key' => 'subscription_price_12m', 'value' => $subscription_price_12m],
          ['user_id' => $user->id, 'key' => 'twitter_url', 'value' => $twitter_url],
          ['user_id' => $user->id, 'key' => 'wishlist_url', 'value' => $wishlist_url],
          ['user_id' => $user->id, 'key' => 'address_line_1', 'value' => $address_line_1],
          ['user_id' => $user->id, 'key' => 'country_id', 'value' => $country_id],
          ['user_id' => $user->id, 'key' => 'zip_code', 'value' => $zip_code],
          ['user_id' => $user->id, 'key' => 'about_bio', 'value' => $about_bio],
          ['user_id' => $user->id, 'key' => 'free_follower', 'value' => $free_follower],
          ['user_id' => $user->id, 'key' => 'profile_keywords', 'value' => $profile_keywords],
          ['user_id' => $user->id, 'key' => 'bank_country_id', 'value' => $bank_country_id],
          ['user_id' => $user->id, 'key' => 'bank_account_name', 'value' => $bank_account_name],
          ['user_id' => $user->id, 'key' => 'bank_account_sort_code', 'value' => $bank_account_sort_code],
          ['user_id' => $user->id, 'key' => 'bank_account_number', 'value' => $bank_account_number],
          ['user_id' => $user->id, 'key' => 'id_proof_doc', 'value' => $id_proof_doc_filename],
          ['user_id' => $user->id, 'key' => 'id_proof', 'value' => $id_proof_filename],
          ['user_id' => $user->id, 'key' => 'profile_photo', 'value' => $profile_photo_filename],
          ['user_id' => $user->id, 'key' => 'profile_video', 'value' => $profile_video_filename],
          ['user_id' => $user->id, 'key' => 'profile_banner', 'value' => $profile_banner_filename]
        ]);
        foreach ($interest_cats as $key => $value) {
          User_interest_cat::insert(['user_id' => $user->id, 'user_cat_id' => $value]);
        }
        $mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $this->meta_data['settings']['settings_website_logo']), 'title' => 'User Registration', 'heading' => 'You are successfully registered as VIP member.', 'message' => ''));
        $mail_data = array(
          'to' => array(
            array($form_data['email'], "")
          ),
          'subject' => 'User Registration',
          'body' => $mail_body,
        );
        Helpers::pmail($mail_data);
        $message = 'You are successfully registered as VIP member';
      }
    }
    if ($role == 3) {
      $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
      $username_pattern = "/^[a-z0-9_]{3,50}$/i";
      $secret = $this->meta_data['settings']['settings_google_recaptcha_secret_key'];
      $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $form_data['g_recaptcha_response']);
      $captcha_success = json_decode($verify);
      if ($captcha_success->success != '1') {
        $errors[] = ['field' => 'g_recaptcha_response', 'message' => 'Captcha not validated'];
      }
      if ($form_data['username'] == '' || preg_match($username_pattern, $form_data['username']) == 0) {
        $errors[] = ['field' => 'username', 'message' => 'Username not valid'];
      }
      $user_found = User::where('username', $form_data['username'])->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'username', 'message' => 'Username already exists'];
      }
      if ($form_data['email'] == '' || preg_match($email_pattern, $form_data['email']) == 0) {
        $errors[] = ['field' => 'email', 'message' => 'Email not valid'];
      }
      $user_found = User::where('email', $form_data['email'])->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        $user = User::create(['first_name' => $form_data['first_name'], 'last_name' => $form_data['last_name'], 'username' => $form_data['username'], 'email' => $form_data['email'], 'role' => $role, 'password' => Hash::make($form_data['password']), 'status' => 1]);
        //User::where('id', $user->id)->update(['username' => 'user-' . $user->id . $time]);
        $profile_photo_filename = '';
        if ($request->hasFile('profile_photo')) {
          $profile_photo_filename = $user->id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        $country_id = Country::where('iso_code_2', $form_data['country_code'])->pluck('country_id')->first();
        User_meta::insert([
          ['user_id' => $user->id, 'key' => 'profile_photo', 'value' => $profile_photo_filename],
          ['user_id' => $user->id, 'key' => 'address_line_1', 'value' => $form_data['address_line_1']],
          ['user_id' => $user->id, 'key' => 'country_id', 'value' => $country_id],
          ['user_id' => $user->id, 'key' => 'zip_code', 'value' => $form_data['zip_code']]
        ]);
        foreach ($form_data['interest_cats'] as $key => $value) {
          User_interest_cat::insert(['user_id' => $user->id, 'user_cat_id' => $value]);
        }
        $mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $this->meta_data['settings']['settings_website_logo']), 'title' => 'User Registration', 'heading' => 'You are successfully registered as follower.', 'message' => ''));
        $mail_data = array(
          'to' => array(
            array($form_data['email'], "")
          ),
          'subject' => 'User Registration',
          'body' => $mail_body,
        );
        Helpers::pmail($mail_data);
        $message = 'You are successfully registered as follower';
      }
    }
    $success = 1;
    if (count($errors) > 0) $success = 0;
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => $message));
  }

  public function ajaxpost_user_last_activity($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    User::update_activity();
    $live_session = Live_session::where('user_id', $user_id)->first();
    if (isset($live_session->id)) {
      $live_session_history = Live_session_history::where(['session_id' => $live_session->session_id, 'token' => $live_session->token])->first();
      Live_session_history::find($live_session_history->id)->touch();
    }
    echo json_encode(array('success' => 1, 'data' => [], 'message' => ''));
  }


  public function ajaxpost_change_password($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $current_password = trim(($request->current_password ?? ''));
    $new_password = trim(($request->new_password ?? ''));
    $data = $errors = [];
    $success = 0;
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    $usr = User::where('id', $user_id)->first();
    if (!Hash::check($current_password, $usr->password)) {
      $errors[] = ['field' => 'current_password', 'message' => 'Current password does not match'];
    }
    if ($new_password == '') {
      $errors[] = ['field' => 'new_password', 'message' => 'New password can not blank'];
    }
    if (count($errors) == 0) {
      User::where('id', $user_id)->update(['password' => Hash::make($new_password)]);
      $success = 1;
      Laravel_session::flash('success', 'Password successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_profile($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }

    //print_r($_POST);exit;
    if ($this->user_data['role'] == 2) {
      $email = trim($request->email);
      $display_name = trim($request->display_name);
      $first_name = trim($request->first_name);
      $last_name = trim($request->last_name);
      $mobile = trim($request->mobile);
      $dob = trim($request->dob);
      $user_cat_id = trim($request->user_cat_id);
      $interest_cats = json_decode(($request->interest_cats ?? '{}'), true);
      $allow_vip_friend_request = trim($request->allow_vip_friend_request);
      $subscription_price_1m = trim($request->subscription_price_1m);
      $subscription_price_1m_discounted = trim($request->subscription_price_1m_discounted);
      $subscription_price_1m_discounted_todate = trim($request->subscription_price_1m_discounted_todate);
      $subscription_price_3m = trim($request->subscription_price_3m);
      $subscription_price_3m_discounted = trim($request->subscription_price_3m_discounted);
      $subscription_price_3m_discounted_todate = trim($request->subscription_price_3m_discounted_todate);
      $subscription_price_6m = trim($request->subscription_price_6m);
      $subscription_price_6m_discounted = trim($request->subscription_price_6m_discounted);
      $subscription_price_6m_discounted_todate = trim($request->subscription_price_6m_discounted_todate);
      $subscription_price_12m = trim($request->subscription_price_12m);
      $subscription_price_12m_discounted = trim($request->subscription_price_12m_discounted);
      $subscription_price_12m_discounted_todate = trim($request->subscription_price_12m_discounted_todate);
      $private_session_price = trim($request->private_session_price);
      $address_line_1 = trim($request->address_line_1);
      $country_code = trim($request->country_code);
      $zip_code = trim($request->zip_code);
      $twitter_url = trim($request->twitter_url);
      $wishlist_url = trim($request->wishlist_url);
      $short_bio = trim($request->short_bio);
      $about_bio = trim($request->about_bio);
      $free_follower = trim($request->free_follower);
      $profile_keywords = trim($request->profile_keywords);
      $profile_photo_removed = trim($request->profile_photo_removed);
      $profile_video_removed = trim($request->profile_video_removed);
      $profile_banner_removed = trim($request->profile_banner_removed);
      $subscription_min_price = $this->meta_data['settings']['settings_model_subscription_min_price'];
      $subscription_max_price = $this->meta_data['settings']['settings_model_subscription_max_price'];
      if ($email == '' || preg_match($email_pattern, $email) == 0) {
        $errors[] = ['field' => 'email', 'message' => 'Email not valid'];
      }
      $user_found = User::where('email', $email)->where('id', '!=', $user_id)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      /*if($subscription_price_1m != '' && ($subscription_price_1m < $subscription_min_price || $subscription_price_1m > $subscription_max_price)) {
              $errors[] = ['field' => 'subscription_price_1m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
          }
          if($subscription_price_3m != '' && ($subscription_price_3m < $subscription_min_price || $subscription_price_3m > $subscription_max_price)) {
              $errors[] = ['field' => 'subscription_price_3m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
          }
          if($subscription_price_6m != '' && ($subscription_price_6m < $subscription_min_price || $subscription_price_6m > $subscription_max_price)) {
              $errors[] = ['field' => 'subscription_price_6m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
          }
          if($subscription_price_12m != '' && ($subscription_price_12m < $subscription_min_price || $subscription_price_12m > $subscription_max_price)) {
              $errors[] = ['field' => 'subscription_price_12m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2) . ' or exceed ' . number_format($subscription_max_price, 2)];
          }*/
      if ($subscription_price_1m != '' && $subscription_price_1m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_1m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_3m != '' && $subscription_price_3m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_3m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_6m != '' && $subscription_price_6m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_6m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_12m != '' && $subscription_price_12m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_12m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_1m_discounted != '' && $subscription_price_1m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_1m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_3m_discounted != '' && $subscription_price_3m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_3m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_6m_discounted != '' && $subscription_price_6m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_6m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_12m_discounted != '' && $subscription_price_12m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_12m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($request->hasFile('id_proof_doc')) {
        $ext = $request->id_proof_doc->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof_doc', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('id_proof')) {
        $ext = $request->id_proof->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_video')) {
        $ext = $request->profile_video->extension();
        if (!in_array($ext, ['mp4'])) {
          $errors[] = ['field' => 'profile_video', 'message' => 'Upload only mp4'];
        }
      }
      if ($request->hasFile('profile_banner')) {
        $ext = $request->profile_banner->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_banner', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        User::where('id', $user_id)->update(['first_name' => $first_name, 'last_name' => $last_name, 'phone' => $mobile, 'dob' => date('Y-m-d', strtotime($dob)), 'email' => $email, 'display_name' => $display_name]);
        $user_meta = new User_meta;
        $user_meta = $user_meta->get_usermeta($user_id);
        $usermeta_data = [];
        foreach ($user_meta as $k => $v) {
          $usermeta_data[$v->key] = $v->value;
        }
        $country_id = Country::where('iso_code_2', $country_code)->pluck('country_id')->first();
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'allow_vip_friend_request'], ['value' => $allow_vip_friend_request]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m'], ['value' => $subscription_price_1m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m_discounted'], ['value' => $subscription_price_1m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m_discounted_todate'], ['value' => ($subscription_price_1m_discounted_todate == '' ? '' : strtotime($subscription_price_1m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m'], ['value' => $subscription_price_3m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m_discounted'], ['value' => $subscription_price_3m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m_discounted_todate'], ['value' => ($subscription_price_3m_discounted_todate == '' ? '' : strtotime($subscription_price_3m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m'], ['value' => $subscription_price_6m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m_discounted'], ['value' => $subscription_price_6m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m_discounted_todate'], ['value' => ($subscription_price_6m_discounted_todate == '' ? '' : strtotime($subscription_price_6m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m'], ['value' => $subscription_price_12m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m_discounted'], ['value' => $subscription_price_12m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m_discounted_todate'], ['value' => ($subscription_price_12m_discounted_todate == '' ? '' : strtotime($subscription_price_12m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'private_session_price'], ['value' => $private_session_price]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'address_line_1'], ['value' => $address_line_1]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'country_id'], ['value' => $country_id]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'zip_code'], ['value' => $zip_code]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'twitter_url'], ['value' => $twitter_url]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wishlist_url'], ['value' => $wishlist_url]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'short_bio'], ['value' => $short_bio]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'about_bio'], ['value' => $about_bio]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'free_follower'], ['value' => $free_follower]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_keywords'], ['value' => $profile_keywords]);
        if ($request->hasFile('id_proof_doc')) {
          @unlink(public_path('uploads/id_proof_doc/' . $usermeta_data['id_proof_doc']));
          $id_proof_doc_filename = $user_id . '_id_proof_doc.' . $request->id_proof_doc->extension();
          $request->id_proof_doc->move(public_path('uploads/id_proof_doc'), $id_proof_doc_filename);
          User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'id_proof_doc'], ['value' => $id_proof_doc_filename]);
        }
        if ($request->hasFile('id_proof')) {
          @unlink(public_path('uploads/id_proof/' . $usermeta_data['id_proof']));
          $id_proof_filename = $user_id . '_id_proof.' . $request->id_proof->extension();
          $request->id_proof->move(public_path('uploads/id_proof'), $id_proof_filename);
          User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'id_proof'], ['value' => $id_proof_filename]);
        }
        $profile_photo_filename = $usermeta_data['profile_photo'];
        if ($profile_photo_removed == 1) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = '';
        }
        if ($request->hasFile('profile_photo')) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = $user_id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_photo'], ['value' => $profile_photo_filename]);
        $profile_video_filename = $usermeta_data['profile_video'];
        if ($profile_video_removed == 1) {
          @unlink(public_path('uploads/profile_video/' . $usermeta_data['profile_video']));
          $profile_video_filename = '';
        }
        if ($request->hasFile('profile_video')) {
          @unlink(public_path('uploads/profile_video/' . $usermeta_data['profile_video']));
          $profile_video_filename = $user_id . '_profile_video.' . $request->profile_video->extension();
          $request->profile_video->move(public_path('uploads/profile_video'), $profile_video_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_video'], ['value' => $profile_video_filename]);
        $profile_banner_filename = $usermeta_data['profile_banner'];
        if ($profile_banner_removed == 1) {
          @unlink(public_path('uploads/profile_banner/' . $usermeta_data['profile_banner']));
          $profile_banner_filename = '';
        }
        if ($request->hasFile('profile_banner')) {
          @unlink(public_path('uploads/profile_banner/' . $usermeta_data['profile_banner']));
          $profile_banner_filename = $user_id . '_profile_banner.' . $request->profile_banner->extension();
          $request->profile_banner->move(public_path('uploads/profile_banner'), $profile_banner_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_banner'], ['value' => $profile_banner_filename]);
        User_own_cat::updateOrCreate(['user_id' => $user_id], ['user_cat_id' => $user_cat_id]);
        $interest_cats_old = User_interest_cat::where('user_id', $user_id)->pluck('user_cat_id')->toArray();
        foreach ($interest_cats as $key => $value) {
          if (!in_array($value, $interest_cats_old))
            User_interest_cat::insert(['user_id' => $user_id, 'user_cat_id' => $value]);
        }
        foreach ($interest_cats_old as $key => $value) {
          if (!in_array($value, $interest_cats))
            User_interest_cat::where('user_id', $user_id)->where('user_cat_id', $value)->delete();
        }
        $success = 1;
        Laravel_session::flash('success', 'Profile successfully updated');
      }
    }
    if ($this->user_data['role'] == 3) {
      $first_name = trim($request->first_name);
      $last_name = trim($request->last_name);
      $email = trim($request->email);
      $mobile = trim($request->mobile);
      $interest_cats = json_decode(($request->interest_cats ?? '{}'), true);
      $address_line_1 = trim($request->address_line_1);
      $country_code = trim($request->country_code);
      $zip_code = trim($request->zip_code);
      $profile_photo_removed = trim($request->profile_photo_removed);
      if ($email == '' || preg_match($email_pattern, $email) == 0) {
        $errors[] = ['field' => 'email', 'message' => 'Email not valid'];
      }
      $user_found = User::where('email', $email)->where('id', '!=', $user_id)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        User::where('id', $user_id)->update(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone' => $mobile]);
        $country_id = Country::where('iso_code_2', $country_code)->pluck('country_id')->first();
        $user_meta = new User_meta;
        $user_meta = $user_meta->get_usermeta($user_id);
        $usermeta_data = [];
        foreach ($user_meta as $k => $v) {
          $usermeta_data[$v->key] = $v->value;
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'address_line_1'], ['value' => $address_line_1]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'country_id'], ['value' => $country_id]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'zip_code'], ['value' => $zip_code]);
        $profile_photo_filename = $usermeta_data['profile_photo'] ?? '';
        if ($profile_photo_removed == 1) {
          @unlink(public_path('uploads/profile_photo/' . $profile_photo_filename));
          $profile_photo_filename = '';
        }
        if ($request->hasFile('profile_photo')) {
          @unlink(public_path('uploads/profile_photo/' . $profile_photo_filename));
          $profile_photo_filename = $user_id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_photo'], ['value' => $profile_photo_filename]);
        $interest_cats_old = User_interest_cat::where('user_id', $user_id)->pluck('user_cat_id')->toArray();
        foreach ($interest_cats as $key => $value) {
          if (!in_array($value, $interest_cats_old))
            User_interest_cat::insert(['user_id' => $user_id, 'user_cat_id' => $value]);
        }
        foreach ($interest_cats_old as $key => $value) {
          if (!in_array($value, $interest_cats))
            User_interest_cat::where('user_id', $user_id)->where('user_cat_id', $value)->delete();
        }
        $success = 1;
        Laravel_session::flash('success', 'Profile successfully updated');
      }
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_settings($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $email = trim($request->email);
      $display_name = trim($request->display_name);
      $first_name = trim($request->first_name);
      $last_name = trim($request->last_name);
      $mobile = trim($request->mobile);
      $dob = trim($request->dob);
      $user_cat_id = trim($request->user_cat_id);
      $interest_cats = json_decode(($request->interest_cats ?? '{}'), true);
      $allow_vip_friend_request = trim($request->allow_vip_friend_request);
      $subscription_price_1m = trim($request->subscription_price_1m);
      $subscription_price_1m_discounted = trim($request->subscription_price_1m_discounted);
      $subscription_price_1m_discounted_todate = trim($request->subscription_price_1m_discounted_todate);
      $subscription_price_3m = trim($request->subscription_price_3m);
      $subscription_price_3m_discounted = trim($request->subscription_price_3m_discounted);
      $subscription_price_3m_discounted_todate = trim($request->subscription_price_3m_discounted_todate);
      $subscription_price_6m = trim($request->subscription_price_6m);
      $subscription_price_6m_discounted = trim($request->subscription_price_6m_discounted);
      $subscription_price_6m_discounted_todate = trim($request->subscription_price_6m_discounted_todate);
      $subscription_price_12m = trim($request->subscription_price_12m);
      $subscription_price_12m_discounted = trim($request->subscription_price_12m_discounted);
      $subscription_price_12m_discounted_todate = trim($request->subscription_price_12m_discounted_todate);
      $private_session_price = trim($request->private_session_price);
      $address_line_1 = trim($request->address_line_1);
      $country_code = trim($request->country_code);
      $zip_code = trim($request->zip_code);
      $twitter_url = trim($request->twitter_url);
      $wishlist_url = trim($request->wishlist_url);
      $short_bio = trim($request->short_bio);
      $about_bio = trim($request->about_bio);
      $thank_you_msg = trim($request->thank_you_msg);
      $private_chat_charge = trim($request->private_chat_charge);
      $group_chat_charge = trim($request->group_chat_charge);
      $free_follower = trim($request->free_follower);
      $profile_keywords = trim($request->profile_keywords);
      $profile_photo_removed = trim($request->profile_photo_removed);
      $profile_video_removed = trim($request->profile_video_removed);
      $profile_banner_removed = trim($request->profile_banner_removed);
      $subscription_min_price = $this->meta_data['settings']['settings_model_subscription_min_price'];
      $subscription_max_price = $this->meta_data['settings']['settings_model_subscription_max_price'];
      if ($email == '' || preg_match($email_pattern, $email) == 0) {
        $errors[] = ['field' => 'email', 'message' => 'Email not valid'];
      }
      $user_found = User::where('email', $email)->where('id', '!=', $user_id)->count();
      if ($user_found == 1) {
        $errors[] = ['field' => 'email', 'message' => 'Email already exists'];
      }
      if ($subscription_price_1m != '' && $subscription_price_1m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_1m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_3m != '' && $subscription_price_3m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_3m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_6m != '' && $subscription_price_6m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_6m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_12m != '' && $subscription_price_12m < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_12m', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_1m_discounted != '' && $subscription_price_1m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_1m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_3m_discounted != '' && $subscription_price_3m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_3m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_6m_discounted != '' && $subscription_price_6m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_6m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($subscription_price_12m_discounted != '' && $subscription_price_12m_discounted < $subscription_min_price) {
        $errors[] = ['field' => 'subscription_price_12m_discounted', 'message' => 'Amount can not less than ' . number_format($subscription_min_price, 2)];
      }
      if ($request->hasFile('id_proof_doc')) {
        $ext = $request->id_proof_doc->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof_doc', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('id_proof')) {
        $ext = $request->id_proof->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'id_proof', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if ($request->hasFile('profile_video')) {
        $ext = $request->profile_video->extension();
        if (!in_array($ext, ['mp4'])) {
          $errors[] = ['field' => 'profile_video', 'message' => 'Upload only mp4'];
        }
      }
      if ($request->hasFile('profile_banner')) {
        $ext = $request->profile_banner->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_banner', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        User::where('id', $user_id)->update(['first_name' => $first_name, 'last_name' => $last_name, 'phone' => $mobile, 'dob' => date('Y-m-d', strtotime($dob)), 'email' => $email, 'display_name' => $display_name]);
        $user_meta = new User_meta;
        $user_meta = $user_meta->get_usermeta($user_id);
        $usermeta_data = [];
        foreach ($user_meta as $k => $v) {
          $usermeta_data[$v->key] = $v->value;
        }
        $country_id = Country::where('iso_code_2', $country_code)->pluck('country_id')->first();
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'allow_vip_friend_request'], ['value' => $allow_vip_friend_request]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m'], ['value' => $subscription_price_1m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m_discounted'], ['value' => $subscription_price_1m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_1m_discounted_todate'], ['value' => ($subscription_price_1m_discounted_todate == '' ? '' : strtotime($subscription_price_1m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m'], ['value' => $subscription_price_3m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m_discounted'], ['value' => $subscription_price_3m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_3m_discounted_todate'], ['value' => ($subscription_price_3m_discounted_todate == '' ? '' : strtotime($subscription_price_3m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m'], ['value' => $subscription_price_6m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m_discounted'], ['value' => $subscription_price_6m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_6m_discounted_todate'], ['value' => ($subscription_price_6m_discounted_todate == '' ? '' : strtotime($subscription_price_6m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m'], ['value' => $subscription_price_12m]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m_discounted'], ['value' => $subscription_price_12m_discounted]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'subscription_price_12m_discounted_todate'], ['value' => ($subscription_price_12m_discounted_todate == '' ? '' : strtotime($subscription_price_12m_discounted_todate))]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'private_session_price'], ['value' => $private_session_price]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'address_line_1'], ['value' => $address_line_1]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'country_id'], ['value' => $country_id]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'zip_code'], ['value' => $zip_code]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'twitter_url'], ['value' => $twitter_url]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wishlist_url'], ['value' => $wishlist_url]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'short_bio'], ['value' => $short_bio]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'about_bio'], ['value' => $about_bio]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'thank_you_msg'], ['value' => $thank_you_msg]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'private_chat_charge'], ['value' => $private_chat_charge]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'group_chat_charge'], ['value' => $group_chat_charge]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'free_follower'], ['value' => $free_follower]);
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_keywords'], ['value' => $profile_keywords]);
        if ($request->hasFile('id_proof_doc')) {
          @unlink(public_path('uploads/id_proof_doc/' . $usermeta_data['id_proof_doc']));
          $id_proof_doc_filename = $user_id . '_id_proof_doc.' . $request->id_proof_doc->extension();
          $request->id_proof_doc->move(public_path('uploads/id_proof_doc'), $id_proof_doc_filename);
          User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'id_proof_doc'], ['value' => $id_proof_doc_filename]);
        }
        if ($request->hasFile('id_proof')) {
          @unlink(public_path('uploads/id_proof/' . $usermeta_data['id_proof']));
          $id_proof_filename = $user_id . '_id_proof.' . $request->id_proof->extension();
          $request->id_proof->move(public_path('uploads/id_proof'), $id_proof_filename);
          User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'id_proof'], ['value' => $id_proof_filename]);
        }
        $profile_photo_filename = $usermeta_data['profile_photo'];
        if ($profile_photo_removed == 1) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = '';
        }
        if ($request->hasFile('profile_photo')) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = $user_id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_photo'], ['value' => $profile_photo_filename]);
        $profile_video_filename = $usermeta_data['profile_video'];
        if ($profile_video_removed == 1) {
          @unlink(public_path('uploads/profile_video/' . $usermeta_data['profile_video']));
          $profile_video_filename = '';
        }
        if ($request->hasFile('profile_video')) {
          @unlink(public_path('uploads/profile_video/' . $usermeta_data['profile_video']));
          $profile_video_filename = $user_id . '_profile_video.' . $request->profile_video->extension();
          $request->profile_video->move(public_path('uploads/profile_video'), $profile_video_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_video'], ['value' => $profile_video_filename]);
        $profile_banner_filename = $usermeta_data['profile_banner'];
        if ($profile_banner_removed == 1) {
          @unlink(public_path('uploads/profile_banner/' . $usermeta_data['profile_banner']));
          $profile_banner_filename = '';
        }
        if ($request->hasFile('profile_banner')) {
          @unlink(public_path('uploads/profile_banner/' . $usermeta_data['profile_banner']));
          $profile_banner_filename = $user_id . '_profile_banner.' . $request->profile_banner->extension();
          $request->profile_banner->move(public_path('uploads/profile_banner'), $profile_banner_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_banner'], ['value' => $profile_banner_filename]);
        User_own_cat::updateOrCreate(['user_id' => $user_id], ['user_cat_id' => $user_cat_id]);
        $interest_cats_old = User_interest_cat::where('user_id', $user_id)->pluck('user_cat_id')->toArray();
        foreach ($interest_cats as $key => $value) {
          if (!in_array($value, $interest_cats_old))
            User_interest_cat::insert(['user_id' => $user_id, 'user_cat_id' => $value]);
        }
        foreach ($interest_cats_old as $key => $value) {
          if (!in_array($value, $interest_cats))
            User_interest_cat::where('user_id', $user_id)->where('user_cat_id', $value)->delete();
        }
        $success = 1;
        Laravel_session::flash('success', 'Profile successfully updated');
      }
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_bank_details($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $bank_country_id = trim($request->bank_country_id);
      $bank_account_name = trim($request->bank_account_name);
      $bank_account_sort_code = trim($request->bank_account_sort_code);
      $bank_account_number = trim($request->bank_account_number);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'bank_country_id'], ['value' => $bank_country_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'bank_account_name'], ['value' => $bank_account_name]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'bank_account_sort_code'], ['value' => $bank_account_sort_code]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'bank_account_number'], ['value' => $bank_account_number]);
      $success = 1;
      Laravel_session::flash('success', 'Bank details successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_vip_member_about($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    $time = time();
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $composite_banners = json_decode(($request->composite_banners ?? '{}'), true);
      $gender = $request->gender ?? '';
      $sexual_orientation_id = $request->sexual_orientation_id ?? '';
      $language_spoken = $request->language_spoken ?? '';
      $zodiac_id = $request->zodiac_id ?? '';
      $height = $request->height ?? '';
      $weight = $request->weight ?? '';
      $eye_color = $request->eye_color ?? '';
      $hair_color = $request->hair_color ?? '';
      $build = $request->build ?? '';
      $ethnicity = $request->ethnicity ?? '';
      $cupsize_id = $request->cupsize_id ?? '';
      $public_hair = $request->public_hair ?? '';
      $measurements = $request->measurements ?? '';
      $model_attributes = $request->model_attributes ?? '';
      if ($model_attributes == '')
        $model_attributes = [];
      else
        $model_attributes = explode(',', $model_attributes);
      $user_meta = new User_meta;
      $user_meta = $user_meta->get_usermeta($user_id);
      $usermeta_data = [];
      foreach ($user_meta as $k => $v) {
        $usermeta_data[$v->key] = $v->value;
      }
      User::where('id', $user_id)->update(['gender' => $gender]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'sexual_orientation_id'], ['value' => $sexual_orientation_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'language_spoken'], ['value' => $language_spoken]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'zodiac_id'], ['value' => $zodiac_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'height'], ['value' => $height]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'weight'], ['value' => $weight]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'eye_color'], ['value' => $eye_color]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'hair_color'], ['value' => $hair_color]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'build'], ['value' => $build]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'ethnicity'], ['value' => $ethnicity]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'cupsize_id'], ['value' => $cupsize_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'public_hair'], ['value' => $public_hair]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'measurements'], ['value' => $measurements]);
      $composite_banners_old = json_decode(($usermeta_data['composite_banners'] ?? '{}'), true);
      $composite_banners2 = [];
      for ($i = 1; $i <= 8; $i++) {
        $kk = ('ban_' . $i);
        $composite_banners2[$kk] = $composite_banners_old[$kk] ?? '';
        if ($composite_banners[$kk] == '') {
          @unlink(public_path('uploads/profile_banner/' . $composite_banners2[$kk]));
          $composite_banners2[$kk] = '';
        }
        $image_parts = explode(";base64,", $composite_banners[$kk]);
        if (!isset($image_parts[1]) && $composite_banners[$kk] != '') {
          //old file retained
        } elseif (isset($image_parts[1])) {
          @unlink(public_path('uploads/profile_banner/' . $composite_banners2[$kk]));
          $composite_banners2[$kk] = '';
          //$image_type_aux = explode("image/", $image_parts[0]);
          //$image_type = $image_type_aux[1];
          $image_base64 = base64_decode($image_parts[1]);
          $composite_banners2[$kk] = $user_id . '_' . $time . '_' . $kk . '_composite_banner.png';
          $file = public_path('uploads/profile_banner/' . $composite_banners2[$kk]);
          file_put_contents($file, $image_base64);
        }
      }
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'composite_banners'], ['value' => json_encode($composite_banners2)]);
      $vip_member_model_attribute = Vip_member_model_attribute::where('user_id', $user_id)->get();
      $vip_member_model_attribute_ids = [];
      foreach ($vip_member_model_attribute as $key => $value) {
        if (!in_array($value->model_attribute_id, $model_attributes))
          Vip_member_model_attribute::destroy($value->id);
        else
          $vip_member_model_attribute_ids[] = $value->model_attribute_id;
      }
      foreach ($model_attributes as $key => $value) {
        if (!in_array($value, $vip_member_model_attribute_ids))
          Vip_member_model_attribute::create(['user_id' => $user_id, 'model_attribute_id' => $value]);
      }
      $success = 1;
      Laravel_session::flash('success', 'About details successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_vip_member_profile_modal_banner($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    $time = time();
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $composite_banners = json_decode(($request->composite_banners ?? '{}'), true);
      $user_meta = new User_meta;
      $user_meta = $user_meta->get_usermeta($user_id);
      $usermeta_data = [];
      foreach ($user_meta as $k => $v) {
        $usermeta_data[$v->key] = $v->value;
      }
      $composite_banners_old = json_decode(($usermeta_data['composite_banners'] ?? '{}'), true);
      $composite_banners2 = [];
      for ($i = 1; $i <= 8; $i++) {
        $kk = ('ban_' . $i);
        $composite_banners2[$kk] = $composite_banners_old[$kk] ?? '';
        if ($composite_banners[$kk] == '') {
          @unlink(public_path('uploads/profile_banner/' . $composite_banners2[$kk]));
          $composite_banners2[$kk] = '';
        }
        $image_parts = explode(";base64,", $composite_banners[$kk]);
        if (!isset($image_parts[1]) && $composite_banners[$kk] != '') {
          //old file retained
        } elseif (isset($image_parts[1])) {
          @unlink(public_path('uploads/profile_banner/' . $composite_banners2[$kk]));
          $composite_banners2[$kk] = '';
          $image_base64 = base64_decode($image_parts[1]);
          $composite_banners2[$kk] = $user_id . '_' . $time . '_' . $kk . '_composite_banner.png';
          $file = public_path('uploads/profile_banner/' . $composite_banners2[$kk]);
          file_put_contents($file, $image_base64);
        }
      }
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'composite_banners'], ['value' => json_encode($composite_banners2)]);
      $success = 1;
      Laravel_session::flash('success', 'Banner successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_vip_member_profile_modal_banner_cont($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    $time = time();
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $display_name = trim($request->display_name);
      $profile_photo_removed = trim($request->profile_photo_removed);
      $short_bio = trim($request->short_bio);
      if ($request->hasFile('profile_photo')) {
        $ext = $request->profile_photo->extension();
        if (!in_array($ext, ['jpg', 'png'])) {
          $errors[] = ['field' => 'profile_photo', 'message' => 'Upload only jpg, png'];
        }
      }
      if (count($errors) == 0) {
        User::where('id', $user_id)->update(['display_name' => $display_name]);
        $user_meta = new User_meta;
        $user_meta = $user_meta->get_usermeta($user_id);
        $usermeta_data = [];
        foreach ($user_meta as $k => $v) {
          $usermeta_data[$v->key] = $v->value;
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'short_bio'], ['value' => $short_bio]);
        $profile_photo_filename = $usermeta_data['profile_photo'];
        if ($profile_photo_removed == 1) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = '';
        }
        if ($request->hasFile('profile_photo')) {
          @unlink(public_path('uploads/profile_photo/' . $usermeta_data['profile_photo']));
          $profile_photo_filename = $user_id . '_profile_photo.' . $request->profile_photo->extension();
          $request->profile_photo->move(public_path('uploads/profile_photo'), $profile_photo_filename);
        }
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'profile_photo'], ['value' => $profile_photo_filename]);
      }
      $success = 1;
      Laravel_session::flash('success', 'Profile successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_vip_member_profile_modal_rightcont($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    $time = time();
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $about_bio = trim($request->about_bio);
      if (count($errors) == 0) {
        User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'about_bio'], ['value' => $about_bio]);
      }
      $success = 1;
      Laravel_session::flash('success', 'Profile successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_update_vip_member_profile_modal_leftcont($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $data = $errors = [];
    $success = 0;
    $time = time();
    if ($user_id == '') {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    if ($this->user_data['role'] == 2) {
      $gender = $request->gender ?? '';
      $sexual_orientation_id = $request->sexual_orientation_id ?? '';
      $language_spoken = $request->language_spoken ?? '';
      $zodiac_id = $request->zodiac_id ?? '';
      $height = $request->height ?? '';
      $weight = $request->weight ?? '';
      $eye_color = $request->eye_color ?? '';
      $hair_color = $request->hair_color ?? '';
      $build = $request->build ?? '';
      $ethnicity = $request->ethnicity ?? '';
      //$cupsize_id = $request->cupsize_id ?? '';
      $cupsize = $request->cupsize ?? '';
      $public_hair = $request->public_hair ?? '';
      $measurements = $request->measurements ?? '';
      $other_attribute = $request->other_attribute ?? '';
      $model_attributes = $request->model_attributes ?? '';
      $address_line_1 = $request->address_line_1 ?? '';
      $country_id = $request->country_id ?? '';
      if ($model_attributes == '')
        $model_attributes = [];
      else
        $model_attributes = explode(',', $model_attributes);
      $user_meta = new User_meta;
      $user_meta = $user_meta->get_usermeta($user_id);
      $usermeta_data = [];
      foreach ($user_meta as $k => $v) {
        $usermeta_data[$v->key] = $v->value;
      }
      User::where('id', $user_id)->update(['gender' => $gender]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'sexual_orientation_id'], ['value' => $sexual_orientation_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'language_spoken'], ['value' => $language_spoken]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'zodiac_id'], ['value' => $zodiac_id]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'height'], ['value' => $height]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'weight'], ['value' => $weight]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'eye_color'], ['value' => $eye_color]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'hair_color'], ['value' => $hair_color]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'build'], ['value' => $build]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'ethnicity'], ['value' => $ethnicity]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'other_attribute'], ['value' => $other_attribute]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'cupsize'], ['value' => $cupsize]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'public_hair'], ['value' => $public_hair]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'measurements'], ['value' => $measurements]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'address_line_1'], ['value' => $address_line_1]);
      User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'country_id'], ['value' => $country_id]);
      $vip_member_model_attribute = Vip_member_model_attribute::where('user_id', $user_id)->get();
      $vip_member_model_attribute_ids = [];
      foreach ($vip_member_model_attribute as $key => $value) {
        if (!in_array($value->model_attribute_id, $model_attributes))
          Vip_member_model_attribute::destroy($value->id);
        else
          $vip_member_model_attribute_ids[] = $value->model_attribute_id;
      }
      foreach ($model_attributes as $key => $value) {
        if (!in_array($value, $vip_member_model_attribute_ids))
          Vip_member_model_attribute::create(['user_id' => $user_id, 'model_attribute_id' => $value]);
      }
      $success = 1;
      Laravel_session::flash('success', 'Profile successfully updated');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_create_post($request)
  {
    //print_r($_POST);exit;

    $user_id = $this->user_data['id'] ?? '';
    $data = $errors = [];
    $success = 0;
    if (!isset($this->user_data['role']) || $this->user_data['role'] != 2) {
      return response()->json(['success' => 0, 'data' => $data, 'message' => ''])->send();
    }
    $post_type = trim($request->post_type);
    $post_head = trim($request->post_head);
    $post_content = trim($request->post_content);
    $visibility = trim($request->visibility);
    $visibility_subscriber_ids = trim($request->visibility_subscriber_ids);
    $priced = trim($request->priced);
    $price = trim($request->price);
    $time = time();
    if ($priced == 0) $price = 0;
    if (!in_array($visibility, ['public', 'subscriber', 'subscriber_except']))
      $visibility = 'public';
    if ($visibility != 'subscriber_except') $visibility_subscriber_ids = '';
    $post_type = 'text';
    if ($request->hasFile('media_video')) {
      $ext = $request->media_video->extension();
      if (!in_array($ext, ['mp4'])) {
        $errors[] = ['field' => 'media_video', 'message' => 'Upload only mp4'];
      }
      $post_type = 'video';
    }
    if ($request->hasFile('media_video_thumbnail')) {
      $ext = $request->media_video_thumbnail->extension();
      if (!in_array($ext, ['jpg', 'png'])) {
        $errors[] = ['field' => 'media_video_thumbnail', 'message' => 'Upload only jpg, png'];
      }
    }
    if ($request->hasFile('media_photo')) {
      $ext = $request->media_photo->extension();
      if (!in_array($ext, ['jpg', 'png'])) {
        $errors[] = ['field' => 'media_photo', 'message' => 'Upload only jpg, png'];
      }
      $post_type = 'photo';
    }
    if ($request->hasFile('media_doc')) {
      $ext = $request->media_doc->extension();
      if (!in_array($ext, ['pdf'])) {
        $errors[] = ['field' => 'media_doc', 'message' => 'Upload only pdf'];
      }
      $post_type = 'doc';
    }
    if (count($errors) == 0) {


      $post = Post::create(['user_id' => $user_id, 'post_head' => $post_head, 'post_content' => $post_content, 'post_type' => $post_type, 'visibility' => $visibility, 'visibility_except' => $visibility_subscriber_ids, 'price' => $price, 'status' => 1]);

      //print_r($post);exit;

      $media_file = $media_thumbnail_file = '';
      if ($request->hasFile('media_video')) {
        $media_file = $post->id . '_' . $user_id . '_' . $time . '_media_file.' . $request->media_video->extension();
        $request->media_video->move(public_path('uploads/post_media'), $media_file);
        if ($request->hasFile('media_video_thumbnail')) {
          $media_thumbnail_file = $post->id . '_' . $user_id . '_' . $time . '_media_thumbnail_file.' . $request->media_video_thumbnail->extension();
          $request->media_video_thumbnail->move(public_path('uploads/post_media'), $media_thumbnail_file);
        }
      }
      if ($request->hasFile('media_photo')) {
        $media_file = $post->id . '_' . $user_id . '_' . $time . '_media_file.' . $request->media_photo->extension();
        $request->media_photo->move(public_path('uploads/post_media'), $media_file);
      }
      if ($request->hasFile('media_doc')) {
        $media_file = $post->id . '_' . $user_id . '_' . $time . '_media_file.' . $request->media_doc->extension();
        $request->media_doc->move(public_path('uploads/post_media'), $media_file);
      }
      if ($media_file != '')
        Post::where('id', $post->id)->update(['media_file' => $media_file, 'media_thumbnail_file' => $media_thumbnail_file]);
      $notify_user_ids = [];
      if ($visibility == 'public') {
        $notify_user_ids = User_follow_user::where('follow_user_id', $user_id)->pluck('user_id')->toArray();
      }
      if ($visibility == 'subscriber') {
        $notify_user_ids = Subscriber::leftJoin('user_follow_users as uf', 'subscribers.subscriber_id', 'uf.user_id')->where('subscribers.user_id', $user_id)->where('uf.follow_user_id', $user_id)->pluck('subscribers.subscriber_id')->toArray();
      }
      if ($visibility == 'subscriber_except') {
        $notify_user_ids = Subscriber::leftJoin('user_follow_users as uf', 'subscribers.subscriber_id', 'uf.user_id')->where('subscribers.user_id', $user_id)->where('uf.follow_user_id', $user_id);
        if ($visibility_subscriber_ids != '')
          $notify_user_ids = $notify_user_ids->whereNotIn('subscribers.subscriber_id', explode(',', $visibility_subscriber_ids));
        $notify_user_ids = $notify_user_ids->pluck('subscribers.subscriber_id')->toArray();
      }
      $notif_arr = [];
      foreach ($notify_user_ids as $key => $value) {
        $json_data = [];
        $notif_arr[] = ['user_id' => $value, 'object_type' => 'post', 'object_id' => $post->id, 'action' => 'create', 'message' => '', 'json_data' => json_encode($json_data), 'created_at' => date('Y-m-d H:i:s')];
      }
      if (count($notif_arr) > 0) Notification::insert($notif_arr);
      $success = 1;
      Laravel_session::flash('success', 'Post successfully created');
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'errors' => $errors, 'message' => ''));
  }

  public function ajaxpost_get_own_posts($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $data = [];
    $html = '';
    $posts = Post::get_own_posts(['user_id' => $user_id, 'per_page' => $per_page, 'cur_page' => $cur_page]);
    foreach ($posts['data'] as $key => $value) {
      $post_html = Post::own_post_html(['post' => $value, 'user_data' => $this->user_data]);
      $html .= $post_html['html'];
    }
    $data['total_page'] = ceil($posts['total_data'] / $per_page);
    $data['html'] = $html;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_get_profile_posts($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $vip_member_id = trim($request->vip_member_id);
    $section = trim($request->section);
    $age = trim($request->age);
    $ordby = trim($request->ordby);
    $ord = trim($request->ord);
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $data = [];
    $html = '';
    $post_type = [];
    if ($section == 'photos') $post_type[] = 'photo';
    if ($section == 'videos') $post_type[] = 'video';
    $posts = Post::get_profile_posts(['user_id' => $vip_member_id, 'logged_user_id' => $user_id, 'post_type' => $post_type, 'age' => $age, 'ordby' => $ordby, 'ord' => $ord, 'per_page' => $per_page, 'cur_page' => $cur_page]);
    foreach ($posts['data'] as $key => $value) {
      $grid_view = 0;
      if (in_array($section, ['photos', 'videos'])) $grid_view = 1;
      $post_html = Post::post_html(['post' => $value, 'grid_view' => $grid_view, 'user_data' => $this->user_data]);
      $html .= $post_html['html'];
    }
    $data['total_page'] = ceil($posts['total_data'] / $per_page);
    $data['html'] = $html;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_get_posts($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $favorite = trim($request->favorite);
    $section = trim($request->section);
    $age = trim($request->age);
    $ordby = trim($request->ordby);
    $ord = trim($request->ord);
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $data = [];
    $html = '';
    $post_type = [];
    if ($section == 'photos') $post_type[] = 'photo';
    if ($section == 'videos') $post_type[] = 'video';
    $posts = Post::get_posts(['user_ids' => [], 'logged_user_id' => $user_id, 'post_type' => $post_type, 'favorite' => $favorite, 'age' => $age, 'ordby' => $ordby, 'ord' => $ord, 'per_page' => $per_page, 'cur_page' => $cur_page]);
    foreach ($posts['data'] as $key => $value) {
      $grid_view = 0;
      if (in_array($section, ['photos', 'videos'])) $grid_view = 1;
      $post_html = Post::post_html(['post' => $value, 'grid_view' => $grid_view, 'user_data' => $this->user_data]);
      $html .= $post_html['html'];
    }
    $data['total_page'] = ceil($posts['total_data'] / $per_page);
    $data['html'] = $html;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_get_post($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_id = trim($request->post_id);
    $view_type = trim($request->view_type);
    $data = [];
    $html = '';
    $post = Post::find($post_id);
    if (!empty(\Auth::user())) {
      if ($this->user_data['role'] == 2)
        $posts = Post::get_profile_posts(['user_id' => $post->user_id, 'logged_user_id' => $user_id, 'post_ids' => [$post_id]]);
      else
        $posts = Post::get_posts(['post_ids' => [$post_id], 'logged_user_id' => $user_id]);
      $post = $posts['data'][0];
    }
    $post_html = Post::post_html(['post' => $post, 'view_type' => $view_type, 'user_data' => $this->user_data]);
    $html .= $post_html['html'];
    $data['html'] = $html;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_delete_post($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_id = trim($request->post_id);
    $data = [];
    $success = 0;
    $post = Post::where('id', $post_id)->where('user_id', $user_id)->first();
    if (isset($post->id)) {
      Post::delete_post(['post_id' => $post_id]);
      $success = 1;
    }
    echo json_encode(['success' => $success, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_user_fav($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $fav_user_id = trim($request->fav_user_id);
    $fav = trim($request->fav);
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $user_fav_user = User_fav_user::where('user_id', $user_id)->where('fav_user_id', $fav_user_id)->first();
    if (isset($user_fav_user->id)) {
      if ($fav != '1') {
        User_fav_user::destroy($user_fav_user->id);
        $data['fav'] = 0;
      } else {
        $data['fav'] = 1;
      }
    } else {
      if ($fav != '0') {
        User_fav_user::create(['user_id' => $user_id, 'fav_user_id' => $fav_user_id]);
        $data['fav'] = 1;
      } else {
        $data['fav'] = 0;
      }
    }
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_user_follow($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $follow_user_id = trim($request->follow_user_id);
    $follow = trim($request->follow);
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $user_follow_user = User_follow_user::where('user_id', $user_id)->where('follow_user_id', $follow_user_id)->first();
    if (isset($user_follow_user->id)) {
      if ($follow != '1') {
        User_follow_user::destroy($user_follow_user->id);
        $data['follow'] = 0;
      } else {
        $data['follow'] = 1;
      }
    } else {
      if ($follow != '0') {
        User_follow_user::create(['user_id' => $user_id, 'follow_user_id' => $follow_user_id]);
        $data['follow'] = 1;
      } else {
        $data['follow'] = 0;
      }
    }
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_user_unsubscribe($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $subscription_user_id = trim($request->subscription_user_id);
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $subscriber = Subscriber::where('user_id', $subscription_user_id)->where('subscriber_id', $user_id)->first();
    if (isset($subscriber->id)) {
      $stripe_subscription = Helpers::stripe_subscription_cancel(['subscription_id' => $subscriber->stripe_subscription_id]);
      if ($stripe_subscription['success'] == '1') {
        Subscriber::destroy($subscriber->id);
      } else {
        echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
        die;
      }
    } else {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_post_react($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_id = trim($request->post_id);
    $data = ['react' => 0];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $post = Post::find($post_id);
    $post_react = Post_react::where('user_id', $user_id)->where('post_id', $post_id)->first();
    if (isset($post_react->id)) {
      Post_react::destroy($post_react->id);
      $data['react'] = 0;
    } else {
      Post_react::create(['user_id' => $user_id, 'post_id' => $post_id]);
      if ($post->user_id != $user_id)
        Notification::create(['user_id' => $post->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'react', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id]), 'created_at' => date('Y-m-d H:i:s')]);
      $data['react'] = 1;
    }
    $data['react_count'] = Post_react::where('post_id', $post_id)->count();
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_post_comment($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_id = trim($request->post_id);
    $comment = trim($request->comment);
    $top_parent_comment_id = trim($request->top_parent_comment_id);
    $parent_comment_id = trim($request->parent_comment_id);
    if ($top_parent_comment_id == '') $top_parent_comment_id = 0;
    if ($parent_comment_id == '') $parent_comment_id = 0;
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $post = Post::find($post_id);
    $post_comment = Post_comment::create(['user_id' => $user_id, 'post_id' => $post_id, 'top_parent_comment_id' => $top_parent_comment_id, 'parent_comment_id' => $parent_comment_id, 'comment' => $comment]);
    if ($post->user_id != $user_id)
      Notification::create(['user_id' => $post->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'comment', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'comment_id' => $post_comment->id]), 'created_at' => date('Y-m-d H:i:s')]);
    if ($parent_comment_id > 0) {
      $parent_comment = Post_comment::find($parent_comment_id);
      if ($parent_comment->user_id != $user_id)
        Notification::create(['user_id' => $parent_comment->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'commentReply', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'comment_id' => $post_comment->id, 'parent_comment_id' => $parent_comment_id]), 'created_at' => date('Y-m-d H:i:s')]);
      $top_parent_comment = Post_comment::find($top_parent_comment_id);
      if ($top_parent_comment->user_id != $user_id && $top_parent_comment->user_id != $parent_comment->user_id)
        Notification::create(['user_id' => $top_parent_comment->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'commentReply', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'comment_id' => $post_comment->id, 'top_parent_comment_id' => $top_parent_comment_id]), 'created_at' => date('Y-m-d H:i:s')]);
    }
    $post_comments = Post_comment::get_post_comments(['post_id' => $post_id, 'post_comment_id' => $post_comment->id, 'top_parent_comment_id' => $top_parent_comment_id, 'start_after_comment_id' => '', 'per_page' => 1, 'current_user_id' => $user_id]);
    $comment_html = Post_comment::get_html(['post_comments' => $post_comments, 'post_id' => $post_id, 'top_parent_comment_id' => $top_parent_comment_id, 'start_after_comment_id' => '', 'per_page' => 1, 'current_user_id' => $user_id]);
    $data['comment_html'] = $comment_html['html'];
    $data['comment_count'] = Post_comment::where('post_id', $post_id)->count();
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_get_post_comments($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_id = trim($request->post_id);
    $top_parent_comment_id = trim($request->top_parent_comment_id);
    $start_after_comment_id = trim($request->start_after_comment_id);
    $helper_settings = Helpers::get_settings();
    $per_page = $helper_settings['post_comments_per_page'];
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $post_comments = Post_comment::get_post_comments(['post_id' => $post_id, 'top_parent_comment_id' => $top_parent_comment_id, 'start_after_comment_id' => $start_after_comment_id, 'per_page' => $per_page, 'current_user_id' => $user_id]);
    $comment_html = Post_comment::get_html(['post_comments' => $post_comments, 'post_id' => $post_id, 'top_parent_comment_id' => $top_parent_comment_id, 'start_after_comment_id' => $start_after_comment_id, 'per_page' => $per_page, 'current_user_id' => $user_id]);
    $data['comment_html'] = $comment_html['html'];
    $data['comment_count'] = Post_comment::where('post_id', $post_id)->where('top_parent_comment_id', $top_parent_comment_id)->count();
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_post_comment_react($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $post_comment_id = trim($request->post_comment_id);
    $data = ['react' => 0];
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $post_comment = Post_comment::find($post_comment_id);
    $post_comment_react = Post_comment_react::where('user_id', $user_id)->where('post_comment_id', $post_comment_id)->first();
    if (isset($post_comment_react->id)) {
      Post_comment_react::destroy($post_comment_react->id);
      $data['react'] = 0;
    } else {
      Post_comment_react::create(['user_id' => $user_id, 'post_comment_id' => $post_comment_id]);
      if ($post_comment->user_id != $user_id)
        Notification::create(['user_id' => $post_comment->user_id, 'object_type' => 'post', 'object_id' => $post_comment->post_id, 'action' => 'commentReact', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'comment_id' => $post_comment_id]), 'created_at' => date('Y-m-d H:i:s')]);
      $data['react'] = 1;
    }
    $data['react_count'] = Post_comment_react::where('post_comment_id', $post_comment_id)->count();
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_delete_comment($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $comment_id = trim($request->comment_id);
    $data = [];
    $success = 0;
    $message = '';
    $post_comment = Post_comment::find($comment_id);
    $post = Post::find($post_comment->post_id);
    if ($user_id == $post_comment->user_id || $user_id == $post->user_id) {
      if ($post_comment->top_parent_comment_id > 0) {
        Post_comment::where('parent_comment_id', $comment_id)->update(['parent_comment_id' => $post_comment->parent_comment_id]);
      } else {
        $cmt = Post_comment::where('parent_comment_id', $comment_id)->where('top_parent_comment_id', $comment_id)->pluck('id')->toArray();
        Post_comment::where('parent_comment_id', $comment_id)->where('top_parent_comment_id', $comment_id)->update(['parent_comment_id' => '0', 'top_parent_comment_id' => '0']);
        foreach ($cmt as $value) {
          Post_comment::where('top_parent_comment_id', $comment_id)->where('parent_comment_id', $value)->update(['parent_comment_id' => $value, 'top_parent_comment_id' => $value]);
        }
        $cmt = Post_comment::where('top_parent_comment_id', $comment_id)->orderBy('id', 'asc')->pluck('id')->toArray();
        foreach ($cmt as $value) {
          $c1 = Post_comment::where('id', $value)->pluck('parent_comment_id')->first();
          $tp_nw = Post_comment::where('id', $c1)->pluck('top_parent_comment_id')->first();
          Post_comment::where('id', $value)->update(['top_parent_comment_id' => $tp_nw]);
        }
      }
      Post_comment_react::where('post_comment_id', $comment_id)->delete();
      Post_comment::destroy($comment_id);
      $success = 1;
    }
    $data['post_id'] = $post->id;
    $data['comment_count'] = Post_comment::where('post_id', $post->id)->count();
    echo json_encode(['success' => $success, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_add_report_item($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $type = trim($request->type);
    $item_id = trim($request->item_id);
    $message = '';
    if ($user_id == '') {
      echo json_encode(['success' => 0, 'data' => [], 'message' => '']);
      die;
    }
    $item_found = Reported_item::where('type', $type)->where('item_id', $item_id)->where('user_id', $user_id)->count();
    if ($item_found == 0) {
      Reported_item::create(['type' => $type, 'item_id' => $item_id, 'user_id' => $user_id]);
      $message = 'Your report successfully added';
    } else {
      $message = 'Already reported by you';
    }
    echo json_encode(['success' => 1, 'data' => [], 'message' => $message]);
  }


  public function ajaxpost_get_notifications($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $notification_type = trim($request->notification_type);
    $notifmark_id = trim($request->notifmark_id);
    $per_page = trim($request->per_page);
    if (!in_array($notification_type, ['comment', 'like', 'subscribe', 'tip', 'store']))
      $notification_type = '';
    $data = ['total_data' => 0, 'data' => [], 'html' => ''];
    $object_actions = [];
    if ($notification_type == 'comment') {
      $object_actions = [
        ['object_type' => 'post', 'action' => 'comment'],
        ['object_type' => 'post', 'action' => 'commentReply'],
        ['object_type' => 'user', 'action' => 'sendMessageToSubscriber']
      ];
    }
    if ($notification_type == 'like') {
      $object_actions = [
        ['object_type' => 'post', 'action' => 'react'],
        ['object_type' => 'post', 'action' => 'commentReact']
      ];
    }
    if ($notification_type == 'subscribe') {
      $object_actions = [
        ['object_type' => 'user', 'action' => 'subscriber']
      ];
    }
    if ($notification_type == 'tip') {
      $object_actions = [
        ['object_type' => 'post', 'action' => 'paid'],
        ['object_type' => 'post', 'action' => 'tip'],
        ['object_type' => 'live_session', 'action' => 'tip']
      ];
    }
    if ($notification_type == 'store') {
      $object_actions = [
        ['object_type' => 'order', 'action' => 'create']
      ];
    }
    $notifications = Notification::get_list(['user_id' => $user_id, 'object_actions' => $object_actions, 'notifmark_id' => $notifmark_id, 'per_page' => $per_page]);
    $data['total_data'] = $notifications['total_data'];
    $data['data'] = $notifications['data'];
    foreach ($notifications['data'] as $key => $value) {
      $html = Notification::get_notification_html(['notification' => $value]);
      $data['html'] .= $html['html'];
    }
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_set_notification_seen($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $notification_ids = trim($request->notification_ids);
    $notification_ids = explode(',', $notification_ids);
    Notification::whereIn('id', $notification_ids)->update(['seen' => '1']);
    echo json_encode(['success' => 1, 'data' => [], 'message' => '']);
  }

  public function ajaxpost_pay_buy_coins($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $coin_price_plan_id = trim($request->coin_price_plan_id);
    $card_number = trim($request->card_number);
    $exp_month = trim($request->exp_month);
    $exp_year = trim($request->exp_year);
    $card_cvv = trim($request->card_cvv);
    $success = 0;
    $message = '';
    $data = [];
    if ($user_id == '') {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $coin_price_plan = Coin_price_plan::find($coin_price_plan_id);
    if (!isset($coin_price_plan->id)) {
      $message = 'Price Plan does not exists';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    //$customer_data = ['name' => 'aa aa', 'description' => 'lorem ipsum', 'email' => 'aa@aa.aa', "address" => ["city" => 'kolkata', "country" => 'IN', "line1" => '000webhost', "line2" => '', "postal_code" => '700131']];
    $customer_data = [];
    $stripe_payment = Helpers::stripe_payment(['card_number' => $card_number, 'exp_month' => $exp_month, 'exp_year' => $exp_year, 'card_cvv' => $card_cvv, 'order_total' => $coin_price_plan->price, 'currency' => 'GBP', 'description' => 'fan-space coin buy', 'customer_data' => $customer_data]);
    if (!isset($stripe_payment['txn_id']) || $stripe_payment['success'] == 0) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $stripe_payment['message']]);
      die;
    }
    $wallet_coins = User_meta::where(['user_id' => $user_id, 'key' => 'wallet_coins'])->pluck('value')->first();
    $wallet_coins = ($wallet_coins == '' ? 0 : $wallet_coins);
    $wallet_coins += $coin_price_plan->coins;
    User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wallet_coins'], ['value' => $wallet_coins]);
    $payment_data = array('transaction_id' => $stripe_payment['txn_id'], 'charge_id' => $stripe_payment['charge_id'], 'stripe_customer_id' => $stripe_payment['customer']);
    Payment::create(['user_id' => $user_id, 'type' => '1', 'amount' => $coin_price_plan->price, 'gateway' => 'stripe', 'txn_id' => $stripe_payment['txn_id'], 'payment_data' => json_encode($payment_data)]);
    $message = 'Payment successfully done';
    $success = 1;
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_pay_buy_subscription($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $vip_member_id = trim($request->user_id);
    $duration = trim($request->duration);
    $card_number = trim($request->card_number);
    $exp_month = trim($request->exp_month);
    $exp_year = trim($request->exp_year);
    $card_cvv = trim($request->card_cvv);
    $success = 0;
    $message = '';
    $data = [];
    $time = time();
    if ($role != 3) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $subscription_prices = User_meta::where('user_id', $vip_member_id)->whereIn('key', [('subscription_price_' . $duration), ('subscription_price_' . $duration . '_discounted'), ('subscription_price_' . $duration . '_discounted_todate')])->get();
    if (count($subscription_prices) == 0) {
      $message = 'Subscription Plan does not exists';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $subscriber = Subscriber::where('user_id', $vip_member_id)->where('subscriber_id', $user_id)->first();
    if (isset($subscriber->id)) {
      $message = 'You are already subscribed';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $subscription_price = $subscription_price_discounted = $subscription_price_discounted_todate = '';
    foreach ($subscription_prices as $key => $value) {
      if ($value->key == 'subscription_price_' . $duration)
        $subscription_price = $value->value;
      if ($value->key == 'subscription_price_' . $duration . '_discounted')
        $subscription_price_discounted = $value->value;
      if ($value->key == 'subscription_price_' . $duration . '_discounted_todate')
        $subscription_price_discounted_todate = $value->value;
    }
    $price = $subscription_price;
    if ($subscription_price_discounted != '' && $subscription_price_discounted_todate != '' && $subscription_price_discounted_todate > time()) {
      $price = $subscription_price_discounted;
    }
    //$customer_data = ['name' => 'aa aa', 'description' => 'lorem ipsum', 'email' => 'aa@aa.aa', "address" => ["city" => 'kolkata', "country" => 'IN', "line1" => '000webhost', "line2" => '', "postal_code" => '700131']];
    $customer_data = [];
    //$stripe_payment = Helpers::stripe_payment(['card_number' => $card_number, 'exp_month' => $exp_month, 'exp_year' => $exp_year, 'card_cvv' => $card_cvv, 'order_total' => $price, 'currency' => 'usd', 'description' => 'fan-space subscription buy', 'customer_data' => $customer_data]);
    $interval_count = '';
    if ($duration == '1m') $interval_count = 1;
    if ($duration == '3m') $interval_count = 3;
    if ($duration == '6m') $interval_count = 6;
    if ($duration == '12m') $interval_count = 12;
    $plan_name = 'subscription_' . $vip_member_id . '_' . $user_id . '_' . $duration;
    $stripe_payment = Helpers::stripe_subscription_payment(['card_number' => $card_number, 'exp_month' => $exp_month, 'exp_year' => $exp_year, 'card_cvv' => $card_cvv, 'order_total' => $price, 'currency' => 'EUR', 'description' => 'fan-space subscription buy', 'customer_data' => $customer_data, 'plan_data' => ['name' => $plan_name, 'interval' => 'month', 'interval_count' => $interval_count]]);
    if ($stripe_payment['success'] == 0) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $stripe_payment['message']]);
      die;
    }
    $subscriber = Subscriber::where('user_id', $vip_member_id)->where('subscriber_id', $user_id)->first();
    $days = 30;
    if ($duration == '1m') $days = $days * 1;
    if ($duration == '3m') $days = $days * 3;
    if ($duration == '6m') $days = $days * 6;
    if ($duration == '12m') $days = $days * 12;
    $validity_date = $subscriber->validity_date ?? '';
    /*if(isset($subscriber->id)) {
          $validity_date = strtotime($validity_date);
          $validity_date = strtotime("+" . ($days - 1) . " day", $validity_date);
          $validity_date = date('Y-m-d 23:59:59', $validity_date);
        } else {
          $validity_date = date('Y-m-d 23:59:59', strtotime("+" . ($days - 1) . " day"));
        }*/
    $validity_date = date('Y-m-d 23:59:59', strtotime("+" . ($days - 1) . " day"));
    $next_renewal_date = date('Y-m-d', strtotime("+" . $days . " day"));

    Subscriber::updateOrCreate(['user_id' => $vip_member_id, 'subscriber_id' => $user_id], ['validity_date' => $validity_date, 'next_renewal_date' => $next_renewal_date, 'stripe_subscription_id' => $stripe_payment['subscription_id'], 'stripe_plan_id' => $stripe_payment['plan_id'], 'duration_days' => $days]);

    User_follow_user::updateOrCreate(['user_id' => $user_id, 'follow_user_id' => $vip_member_id]);
    $payment_data = array('plan_id' => $stripe_payment['plan_id'], 'subscription_id' => $stripe_payment['subscription_id'], 'stripe_customer_id' => $stripe_payment['customer']);

    Payment::create(['user_id' => $user_id, 'vip_member_id' => $vip_member_id, 'type' => '2', 'amount' => $price, 'gateway' => 'stripe', 'txn_id' => $stripe_payment['plan_id'], 'payment_data' => json_encode($payment_data)]);

    Notification::create(['user_id' => $vip_member_id, 'object_type' => 'user', 'object_id' => $user_id, 'action' => 'subscriber', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'duration' => $duration]), 'created_at' => date('Y-m-d H:i:s')]);

    // email send to follower

    $msg = 'Thank you !';
    $vip_member = User::find($vip_member_id);
    if (!empty($vip_member->user_meta_array()['thank_you_msg'])) {
      $msg = $vip_member->user_meta_array()['thank_you_msg'];
      $email = Auth::user()->email;

      //   $msg = 'Click following link to reset password of your account';
      $mail_body = view('email_template/thank_you', array('msg' => $msg));
      $mail_data = array(
        'to' => array(
          array($email, "")
        ),
        'subject' => 'Thank you email',
        'body' => $mail_body,
      );
      Helpers::pmail($mail_data);
    }

    //end email send
    $message = 'Your subscription successfully processed';
    $success = 1;
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_unlock_post($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $post_id = trim($request->post_id);
    $success = 0;
    $message = '';
    $data = [];
    if ($role != 3) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $post = Post::find($post_id);
    if ($post->price == 0) {
      $message = 'Post is not paid post';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $already_paid = Post_paid_user::where(['user_id' => $user_id, 'post_id' => $post_id])->count();
    if ($already_paid == 1) {
      $message = 'Already unlocked';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet_coins = User_meta::where(['user_id' => $user_id, 'key' => 'wallet_coins'])->pluck('value')->first();
    $wallet_coins = ($wallet_coins == '' ? 0 : $wallet_coins);
    if ($post->price > $wallet_coins) {
      $message = 'Insufficient coin balance';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet_coins -= $post->price;
    User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wallet_coins'], ['value' => $wallet_coins]);
    $post_paid_user = Post_paid_user::updateOrCreate(['user_id' => $user_id, 'post_id' => $post_id]);
    $vip_member = User::find($post->user_id);
    $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
    $affiliate_earning = round(($post->price * $affiliate_earning_percentage / 100));
    if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
    User_earning::create(['user_id' => $post->user_id, 'token_coins' => $post->price, 'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id), 'referral_token_coins' => $affiliate_earning, 'post_paid_users_id' => $post_paid_user->id]);
    Notification::create(['user_id' => $post->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'paid', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'price' => $post->price]), 'created_at' => date('Y-m-d H:i:s')]);
    $posts = Post::get_posts(['post_ids' => [$post_id], 'logged_user_id' => $user_id]);
    $post = $posts['data'][0];
    $post_html = Post::post_html(['post' => $post, 'grid_view' => 0, 'user_data' => $this->user_data]);
    $list_html = $post_html['unlock_html'];
    $post_html = Post::post_html(['post' => $post, 'grid_view' => 1, 'user_data' => $this->user_data]);
    $grid_html = $post_html['unlock_grid_html'];
    $wallet_coin = User::wallet(['user_id' => Auth::user()->id]);
    $data = ['list_html' => $list_html, 'grid_html' => $grid_html, 'wallet_coin' => $wallet_coin['balance']];

    $success = 1;
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_pay_send_tip($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $post_id = trim($request->post_id);
    $token_coin = trim($request->token_coin);
    $success = 0;
    $message = '';
    $data = [];
    if ($role == '') {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $post = Post::find($post_id);
    if (!isset($post->id)) {
      $message = 'Post does not exist';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet_coins = User_meta::where(['user_id' => $user_id, 'key' => 'wallet_coins'])->pluck('value')->first();
    $wallet_coins = ($wallet_coins == '' ? 0 : $wallet_coins);
    if ($token_coin > $wallet_coins) {
      $message = 'Insufficient coin balance';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet_coins -= $token_coin;
    User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wallet_coins'], ['value' => $wallet_coins]);
    $post_tip = Post_tip::create(['tipper_id' => $user_id, 'post_id' => $post_id]);
    $vip_member = User::find($post->user_id);
    $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
    $affiliate_earning = round(($token_coin * $affiliate_earning_percentage / 100));
    if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
    User_earning::create(['user_id' => $post->user_id, 'token_coins' => $token_coin, 'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id), 'referral_token_coins' => $affiliate_earning, 'post_tips_id' => $post_tip->id]);
    Notification::create(['user_id' => $post->user_id, 'object_type' => 'post', 'object_id' => $post_id, 'action' => 'tip', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'token_coin' => $token_coin]), 'created_at' => date('Y-m-d H:i:s')]);
    $success = 1;
    $message = 'Tips successfully paid';
    // email send to follower
    $msg = 'Thank you !';
    $msg = $vip_member->user_meta_array()['thank_you_msg'];
    $email = Auth::user()->email;

    //   $msg = 'Click following link to reset password of your account';
    $mail_body = view('email_template/thank_you', array('msg' => $msg));
    $mail_data = array(
      'to' => array(
        array($email, "")
      ),
      'subject' => 'Thank you email',
      'body' => $mail_body,
    );
    Helpers::pmail($mail_data);

    //end email send
    $wallet_coin = User::wallet(['user_id' => Auth::user()->id]);
    $data['wallet_coin'] = $wallet_coin['balance'];

    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_get_profile_products($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $vip_member_id = trim($request->vip_member_id);
    $in_stock = trim($request->in_stock);
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $data = [];
    $html = '';
    $products = Product::get_list(['user_id' => $vip_member_id, 'logged_user_id' => $user_id, 'in_stock' => $in_stock, 'per_page' => $per_page, 'cur_page' => $cur_page]);
    foreach ($products['data'] as $key => $value) {
      $product_html = Product::product_html(['product' => $value, 'user_data' => $this->user_data]);
      $html .= $product_html['html'];
    }
    $data['total_page'] = ceil($products['total_data'] / $per_page);
    $data['html'] = $html;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_get_product($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $data = [];
    $html = '';
    $products = Product::get_item(['product_id' => $product_id, 'logged_user_id' => $user_id]);
    $thumbnail = url('public/front/images/product-thumbnail.png');
    if ($products['data']['product']->thumbnail != '')
      $thumbnail = url('public/uploads/product/' . $products['data']['product']->thumbnail);
    $products['data']['product']->thumbnail_url = $thumbnail;
    $data = $products['data'];
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_add_to_cart($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $qty = trim($request->qty);
    $success = 0;
    $data = [];
    $message = '';
    $product = Product::where('status', '1')->where('id', $product_id)->first();
    if (!isset($product->id)) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Product does not exist']);
      die;
    }
    if (in_array($product->type, [4]) && $product->stock < $qty) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Insufficient products in stock']);
      die;
    }
    $cart = $_COOKIE['cart'] ?? '{}';
    $cart = json_decode($cart, true);
    if (isset($cart[$product_id]))
      $cart[$product_id]['qty'] += $qty;
    else
      $cart[$product_id] = ['product_id' => $product_id, 'qty' => $qty];
    if (isset($_COOKIE['cart']))
      setcookie('cart', '', (time() - 3600), "/");
    setcookie('cart', json_encode($cart), time() + (86400), "/");
    $total_cart_items = 0;
    foreach ($cart as $key => $value) {
      $total_cart_items += $value['qty'];
    }
    $data['total_cart_items'] = $total_cart_items;
    $success = 1;
    $message = 'Product successfully added to your cart';
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_remove_cart($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $success = 0;
    $data = [];
    $message = '';
    $cart = $_COOKIE['cart'] ?? '{}';
    $cart = json_decode($cart, true);
    if (isset($cart[$product_id]))
      unset($cart[$product_id]);
    if (isset($_COOKIE['cart']))
      setcookie('cart', '', (time() - 3600), "/");
    setcookie('cart', json_encode($cart), time() + (86400), "/");
    $success = 1;
    Laravel_session::flash('success', 'Cart successfully updated');
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_update_cart($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $qty = trim($request->qty);
    $success = 0;
    $data = [];
    $message = '';
    $cart = $_COOKIE['cart'] ?? '{}';
    $cart = json_decode($cart, true);
    if (!isset($cart[$product_id])) {
      Laravel_session::flash('error', 'Product does not exist in cart');
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Product does not exist in cart']);
      return false;
    }
    $product = Product::where('status', '1')->where('id', $product_id)->first();
    if (!isset($product->id)) {
      Laravel_session::flash('error', 'Product does not exist');
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Product does not exist']);
      return false;
    }
    if (in_array($product->type, [4]) && $product->stock < $qty) {
      Laravel_session::flash('error', 'Insufficient products in stock');
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Insufficient products in stock']);
      return false;
    }
    $cart[$product_id]['qty'] = $qty;
    if (isset($_COOKIE['cart']))
      setcookie('cart', '', (time() - 3600), "/");
    setcookie('cart', json_encode($cart), time() + (86400), "/");
    $success = 1;
    Laravel_session::flash('success', 'Cart successfully updated');
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_checkout_order($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $billing_first_name = trim($request->billing_first_name);
    $billing_last_name = trim($request->billing_last_name);
    $billing_company_name = trim($request->billing_company_name);
    $billing_location = trim($request->billing_location);
    $billing_address_line_1 = trim($request->billing_address_line_1);
    $billing_country_code = trim($request->billing_country_code);
    $billing_zip_code = trim($request->billing_zip_code);
    $billing_phone = trim($request->billing_phone);
    $billing_email = trim($request->billing_email);
    $ship_different = trim($request->ship_different);
    $shipping_first_name = trim($request->shipping_first_name);
    $shipping_last_name = trim($request->shipping_last_name);
    $shipping_company_name = trim($request->shipping_company_name);
    $shipping_location = trim($request->shipping_location);
    $shipping_address_line_1 = trim($request->shipping_address_line_1);
    $shipping_country_code = trim($request->shipping_country_code);
    $shipping_zip_code = trim($request->shipping_zip_code);
    $order_notes = trim($request->order_notes);
    $email_pattern = "/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i";
    $success = 0;
    $data = $errors = [];
    $message = '';
    $cart = Cart::get_cart();
    if ($user_id == '') {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'User not logged in']);
      die();
    }
    if ($cart['total_qty'] == 0) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Cart is empty']);
      die();
    }
    $wallet = User::wallet(['user_id' => $user_id]);
    $wallet_coins = $wallet['balance'];
    if ($cart['total_amount'] > $wallet_coins) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'Insufficient coin balance in your wallet']);
      die();
    }
    if ($billing_first_name == '')
      $errors[] = ['field' => 'billing_first_name', 'message' => 'Enter first name'];
    if ($billing_last_name == '')
      $errors[] = ['field' => 'billing_last_name', 'message' => 'Enter last name'];
    $billing_country = Country::where('iso_code_2', $billing_country_code)->first();
    if ($billing_address_line_1 == '' || $billing_country_code == '' || !isset($billing_country->country_id) || $billing_zip_code == '')
      $errors[] = ['field' => 'billing_location', 'message' => 'Enter valid address'];
    if ($billing_email == '' || preg_match($email_pattern, $billing_email) == 0)
      $errors[] = ['field' => 'billing_email', 'message' => 'Email not valid'];
    if ($billing_phone == '')
      $errors[] = ['field' => 'billing_phone', 'message' => 'Enter phone number'];
    if ($ship_different == 1) {
      if ($shipping_first_name == '')
        $errors[] = ['field' => 'shipping_first_name', 'message' => 'Enter first name'];
      if ($shipping_last_name == '')
        $errors[] = ['field' => 'shipping_last_name', 'message' => 'Enter last name'];
      $shipping_country = Country::where('iso_code_2', $shipping_country_code)->first();
      if ($shipping_address_line_1 == '' || $shipping_country_code == '' || !isset($shipping_country->country_id) || $shipping_zip_code == '')
        $errors[] = ['field' => 'shipping_location', 'message' => 'Enter valid address'];
    }
    $data['errors'] = $errors;
    if (count($errors) > 0) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => 'There has errors in order place form']);
      die();
    }
    $cart_items = [];
    foreach ($cart['items'] as $key => $value) {
      if (!isset($cart_items[$value->user_id]))
        $cart_items[$value->user_id] = [];
      $cart_items[$value->user_id][] = $value;
    }
    foreach ($cart_items as $key => $value) {
      $total_amount = 0;
      foreach ($value as $k => $v) {
        $total_amount += $v->item_total;
      }
      $order = Order::create(['user_id' => $user_id, 'vip_member_id' => $key, 'email' => $billing_email, 'phone' => $billing_phone, 'order_notes' => $order_notes, 'total_amount' => $total_amount, 'status' => 1]);
      foreach ($value as $k => $v) {
        $item_id = Order_item::create(['order_id' => $order->id, 'product_id' => $v->id, 'type' => $v->type, 'title' => $v->title, 'price' => $v->price, 'attachment' => $v->attachment, 'quantity' => $v->qty]);
        AdminNotification::create([
          'message' => $v->title . ' has been ordered by ' . \Auth::user()->first_name . ' ' . \Auth::user()->last_name,
          'link' => 'admin/store',
          'type' => 'order',
          'seen' => 0
        ]);
      }
      Order_address::create(['order_id' => $order->id, 'type' => 1, 'first_name' => $billing_first_name, 'last_name' => $billing_last_name, 'company_name' => $billing_company_name, 'address_line_1' => $billing_address_line_1, 'country_id' => $billing_country->country_id, 'zip_code' => $billing_zip_code]);
      if ($ship_different == 1)
        Order_address::create(['order_id' => $order->id, 'type' => 2, 'first_name' => $shipping_first_name, 'last_name' => $shipping_last_name, 'company_name' => $shipping_company_name, 'address_line_1' => $shipping_address_line_1, 'country_id' => $shipping_country->country_id, 'zip_code' => $shipping_zip_code]);
      else
        Order_address::create(['order_id' => $order->id, 'type' => 2, 'first_name' => $billing_first_name, 'last_name' => $billing_last_name, 'company_name' => $billing_company_name, 'address_line_1' => $billing_address_line_1, 'country_id' => $billing_country->country_id, 'zip_code' => $billing_zip_code]);
      $vip_member = User::find($key);
      $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
      $affiliate_earning = round(($total_amount * $affiliate_earning_percentage / 100));
      if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
      User_earning::create(['user_id' => $key, 'token_coins' => $total_amount, 'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id), 'referral_token_coins' => $affiliate_earning, 'order_id' => $order->id]);
      Notification::create(['user_id' => $key, 'object_type' => 'order', 'object_id' => $order->id, 'action' => 'create', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'total_amount' => $total_amount]), 'created_at' => date('Y-m-d H:i:s')]);
    }
    $wallet_coins -= $cart['total_amount'];
    User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wallet_coins'], ['value' => $wallet_coins]);
    setcookie('cart', '', (time() - 3600), "/");
    $success = 1;
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_payout_request($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $coin_amount = intval(trim($request->coin_amount));
    $success = 0;
    $message = '';
    $data = [];
    if ($role != 2) {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    if ($coin_amount <= 0) {
      $message = 'Invalid amount';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $min_payout_amount = $this->meta_data['settings']['settings_payment_min_payout_amount'];
    if ($coin_amount < $min_payout_amount) {
      $message = 'Requested amount does not reach to minimum amount';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet = User::wallet(['user_id' => $user_id]);
    $wallet_coins = $wallet['balance'];
    if ($coin_amount > $wallet_coins) {
      $message = 'Requested amount exceeds your wallet amount';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $token_to_currency = $this->meta_data['settings']['settings_payment_token_to_currency'];
    $price_amount = number_format(($coin_amount * $token_to_currency), 2, '.', '');
    User_payout::create(['user_id' => $user_id, 'token_coins' => $coin_amount, 'price_amount' => $price_amount, 'status' => '0']);
    $success = 1;
    AdminNotification::create([
      'message' => 'New payout requested from ' . \Auth::user()->first_name . ' ' . \Auth::user()->last_name,
      'link' => 'admin/payouts',
      'type' => 'payout',
      'seen' => 0
    ]);
    Laravel_session::flash('success', 'Payout request successfully send');
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_update_product($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $id = trim($request->id);
    $type = trim($request->type);
    $stock = trim($request->stock);
    $title = trim($request->title);
    $price = trim($request->price);
    $description = trim($request->description);
    $thumbnail_removed = trim($request->thumbnail_removed);
    $attachment_removed = trim($request->attachment_removed);
    if (!in_array($type, [1, 2, 3, 4])) $type = 4;
    $time = time();
    $data = [];
    if ($role != 2) {
      echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
      die;
    }
    $pd_old = new \stdClass;
    if ($id != '') {
      $pd_old = Product::where('user_id', $user_id)->where('id', $id)->first();
      if (!isset($pd_old->id)) {
        echo json_encode(['success' => 0, 'data' => $data, 'message' => '']);
        die;
      }
      Product::where('id', $id)->update(['type' => $type, 'description' => $description, 'title' => $title, 'stock' => $stock, 'price' => $price]);
      $pd = Product::find($id);
    }
    if ($id == '') {
      $pd = Product::create(['user_id' => $user_id, 'description' => $description, 'type' => $type, 'title' => $title, 'stock' => $stock, 'price' => $price, 'status' => 1]);
    }
    $thumbnail = $pd->thumbnail;
    if ($thumbnail_removed == 1) {
      @unlink(public_path('uploads/product/' . $pd->thumbnail));
      $thumbnail = '';
    }
    if ($request->hasFile('thumbnail')) {
      @unlink(public_path('uploads/product/' . $pd->thumbnail));
      $thumbnail = $pd->id . $time . '_thumbnail.' . $request->thumbnail->extension();
      $request->thumbnail->move(public_path('uploads/product'), $thumbnail);
    }
    Product::where('id', $pd->id)->update(['thumbnail' => $thumbnail]);
    $attachment = $pd->attachment;
    if ($attachment_removed == 1) {
      @unlink(public_path('uploads/product/attachments/' . $pd->attachment));
      $attachment = '';
    }
    if ($request->hasFile('attachment')) {
      @unlink(public_path('uploads/product/attachments/' . $pd->attachment));
      $attachment = $pd->id . $time . rand(10000, 99999) . '_attachment.' . $request->attachment->extension();
      $request->attachment->move(public_path('uploads/product/attachments'), $attachment);
    }
    Product::where('id', $pd->id)->update(['attachment' => $attachment]);
    Laravel_session::flash('success', 'Product successfully saved');
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_product_toggle_status($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $pd = Product::find($product_id);
    if ($pd->user_id == $user_id) {
      $new_status = '';
      if ($pd->status == '1') $new_status = 0;
      if ($pd->status == '0') $new_status = 1;
      Product::where('id', $product_id)->update(['status' => $new_status]);
    }
    Laravel_session::flash('success', 'Product status successfully changed');
    echo json_encode(['success' => 1, 'data' => [], 'message' => '']);
  }

  public function ajaxpost_delete_product($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $product_id = trim($request->product_id);
    $pd = Product::find($product_id);
    if ($pd->user_id == $user_id) {
      @unlink(public_path('uploads/product/' . $pd->thumbnail));
      @unlink(public_path('uploads/product/' . $pd->attachment));
      Product::destroy($product_id);
    }
    Laravel_session::flash('success', 'Product successfully deleted');
    echo json_encode(['success' => 1, 'data' => [], 'message' => '']);
  }

  public function ajaxpost_opentok_start_session($request)
  {
    $message = '';
    $data = [];
    $model_id = $request->user_id ?? '';
    $user_id = $this->user_data['id'] ?? '';
    $model = User::find($model_id);
    $user = new \stdClass;
    if ($user_id != '')
      $user = User::find($user_id);
    if ($user_id != '' && $model_id == '' && isset($user->role) && $user->role == 2) {
      $apiKey = $this->meta_data['settings']['settings_opentok_api_key'];
      $apiSecret = $this->meta_data['settings']['settings_opentok_api_secret_key'];
      $curl_url = url('opentok/OpenTokRequest.php?opentokHelper=fhg78y4h843hg43');
      $post_data = [
        'action' => 'create_session',
        'request' => ['apiKey' => $apiKey, 'apiSecret' => $apiSecret]
      ];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $curl_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      //curl_setopt($ch, CURLOPT_HEADER, FALSE);
      //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
      $server_output = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $server_output2 = json_decode($server_output, true);
      // dd($server_output);
      $sessionId = $server_output2['sessionId'];
      $token = $server_output2['token'];
      $opentok_data = ['apiKey' => $apiKey, 'apiSecret' => $apiSecret, 'sessionId' => $sessionId, 'token' => $token];
      DB::table('live_sessions')->where('user_id', $user_id)->delete();
      Live_session::create(['user_id' => $user_id, 'session_id' => $sessionId, 'token' => $token, 'ts' => time()]);
      Live_session_history::create(['user_id' => $user_id, 'session_id' => $sessionId, 'token' => $token]);
      /*DB::table('live_sessions')->insert(
        ['user_id' => $user_id, 'session_id' => $sessionId, 'token' => $token, 'ts' => time()]
      );
      DB::table('live_session_histories')->insert(
        ['user_id' => $user_id, 'session_id' => $sessionId, 'token' => $token]
      );*/
      $data['opentok_data'] = $opentok_data;
      $data['type'] = 'publisher';
    }
    if ($model_id != '') {
      $apiKey = $this->meta_data['settings']['settings_opentok_api_key'];
      $live_session = DB::table('live_sessions')->where('user_id', $model_id)->get();
      $opentok_data = ['apiKey' => '', 'apiSecret' => '', 'sessionId' => '', 'token' => ''];
      if (isset($live_session[0]->id)) {
        $opentok_data = ['apiKey' => $apiKey, 'sessionId' => $live_session[0]->session_id, 'token' => $live_session[0]->token];
      }
      $data['opentok_data'] = $opentok_data;
      $data['type'] = 'subscriber';
    }
    echo json_encode(array('success' => 1, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_opentok_end_session($request)
  {
    $message = '';
    $data = [];
    $user_id = $this->user_data['id'] ?? '';
    $live_session = Live_session::where('user_id', $user_id)->first();
    if (isset($live_session->id)) {
      $live_session_history = Live_session_history::where(['session_id' => $live_session->session_id, 'token' => $live_session->token])->first();
      Live_session_history::find($live_session_history->id)->touch();
      DB::table('live_sessions')->where('user_id', $user_id)->delete();
    }
    
    echo json_encode(array('success' => 1, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_check_user_session($request)
  {
    $message = '';
    $data = [];
    $user_id = $this->user_data['id'] ?? '';
    $vip_member_id = $request->user_id ?? '';
    $apiKey = $this->meta_data['settings']['settings_opentok_api_key'];
    $live_session = Live_session::where('user_id', $vip_member_id)->first();
    $opentok_data = ['apiKey' => '', 'apiSecret' => '', 'sessionId' => '', 'token' => ''];
    if (isset($live_session->id)) {
      $opentok_data = ['apiKey' => $apiKey, 'sessionId' => $live_session->session_id, 'token' => $live_session->token];
    }
    $data['opentok_data'] = $opentok_data;
    echo json_encode(array('success' => 1, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_set_live_session_chat_message($request)
  {
    $success = 0;
    $message = '';
    $data = [];
    $user_id = $this->user_data['id'] ?? '';
    $vip_member_id = $request->vip_member_id ?? '';
    $chat_message = $request->message ?? '';
    $live_session = Live_session::where('user_id', $vip_member_id)->first();
    if (isset($live_session->id)) {
      $lsh = Live_session_history::where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();
      Live_session_chat_message::create(['live_session_history_id' => $lsh->id, 'sender_id' => $user_id, 'message' => $chat_message]);
      $success = 1;
    }
    echo json_encode(array('success' => $success, 'data' => $data, 'message' => $message));
  }

  public function ajaxpost_pay_send_chat_tip($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $role = $this->user_data['role'] ?? '';
    $vip_member_id = trim($request->vip_member_id);
    $token_coin = trim($request->token_coin);
    $success = 0;
    $message = '';
    $data = [];
    if ($role == '') {
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $live_session = Live_session::where('user_id', $vip_member_id)->first();
    if (!isset($live_session->id)) {
      $message = 'No live session found';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $lsh = Live_session_history::where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();
    $wallet_coins = User_meta::where(['user_id' => $user_id, 'key' => 'wallet_coins'])->pluck('value')->first();
    $wallet_coins = ($wallet_coins == '' ? 0 : $wallet_coins);
    if ($token_coin > $wallet_coins) {
      $message = 'Insufficient coin balance';
      echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
      die;
    }
    $wallet_coins -= $token_coin;
    User_meta::updateOrCreate(['user_id' => $user_id, 'key' => 'wallet_coins'], ['value' => $wallet_coins]);
    $live_session_tip = Live_session_tip::create(['tipper_id' => $user_id, 'live_session_history_id' => $lsh->id]);
    $vip_member = User::find($vip_member_id);
    $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
    $affiliate_earning = round(($token_coin * $affiliate_earning_percentage / 100));
    if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
    User_earning::create(['user_id' => $vip_member_id, 'token_coins' => $token_coin, 'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id), 'referral_token_coins' => $affiliate_earning, 'live_session_tips_id' => $live_session_tip->id]);
    Notification::create(['user_id' => $vip_member_id, 'object_type' => 'live_session', 'object_id' => $lsh->id, 'action' => 'tip', 'message' => '', 'json_data' => json_encode(['user_id' => $user_id, 'token_coin' => $token_coin]), 'created_at' => date('Y-m-d H:i:s')]);
    $success = 1;
    $message = 'Tips successfully paid';
    $wallet_coin = User::wallet(['user_id' => Auth::user()->id]);
    $data['wallet_coin'] = $wallet_coin['balance'];

    // email send to follower
    $msg = 'Thank you !';
    $msg = $vip_member->user_meta_array()['thank_you_msg'];
    $email = Auth::user()->email;

    //   $msg = 'Click following link to reset password of your account';
    $mail_body = view('email_template/thank_you', array('msg' => $msg));
    $mail_data = array(
      'to' => array(
        array($email, "")
      ),
      'subject' => 'Thank you email',
      'body' => $mail_body,
    );
    Helpers::pmail($mail_data);

    //end email send
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
  }

  public function ajaxpost_get_order($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $order_id = trim($request->order_id);
    $data = [];
    $order = Order::find($order_id);
    $data['order'] = $order;
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  public function ajaxpost_private_chat_balance_update($request)
  {
    $low_balance_alert = 0;
    $insufficient_bal = 0;
    $model_id = $request->model_id;
    $follower_id = $request->follower_id;
    //dd($request->all());
    $vip_member = User::find($model_id);
    $model_charge = $vip_member->user_meta_array()['private_chat_charge'];
    $follower_bal = User::wallet(['user_id' => $follower_id])['balance'];

    $total_private_chat_minutes = (int)$follower_bal / $model_charge;
    if ($total_private_chat_minutes <= 3) {
      $low_balance_alert = 1;
    }
    if ($follower_bal >= $model_charge) {
      if ($request->created_by != $follower_id) {
        // follower coin update
        $value = $follower_bal - $model_charge;
        User_meta::where('user_id', $follower_id)->where('key', 'wallet_coins')->update([
          'value' => $value
        ]);

        //model coin update
        $model_earning_for_this_chat = User_earning::where('private_chat_id', $request->private_chat_id)->first();
        $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
        $affiliate_earning = (($model_charge * $affiliate_earning_percentage / 100));
        if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
        // dd($model_earning_for_this_chat);
        if (empty($model_earning_for_this_chat)) {
          $dd = User_earning::create([
            'user_id' => $model_id,
            'token_coins' => $model_charge,
            'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id),
            'referral_token_coins' => round($affiliate_earning),
            'private_chat_id' => $request->private_chat_id
          ]);
          // dd($dd->id);
        } else {
          User_earning::where('private_chat_id', $request->private_chat_id)->update([
            'token_coins' => $model_earning_for_this_chat->token_coins + $model_charge,
            'referral_token_coins' => round($model_earning_for_this_chat->referral_token_coins + $affiliate_earning),

          ]);
        }
      }
    } else {
      $insufficient_bal = 1;
    }
    $data = ['low_balance_alert' => $low_balance_alert, 'insufficient_bal' => $insufficient_bal, 'private_chat_id' => $request->private_chat_id];
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }
  public function ajaxpost_group_chat_balance_update($request)
  {
    $low_balance_alert = 0;
    $insufficient_bal = 0;
    $model_id = $request->model_id;
    $follower_id = $request->follower_id;

    $vip_member = User::find($model_id);
    //dd($request->all());
    $model_charge = $vip_member->user_meta_array()['group_chat_charge'];
    $follower_bal = User::wallet(['user_id' => $follower_id])['balance'];

    $total_group_chat_minutes = (int)$follower_bal / $model_charge;

    if ($total_group_chat_minutes <= 5) {
      $low_balance_alert = 1;
    }
    if ($follower_bal >= $model_charge) {
      if ($request->created_by == $follower_id) {
        // follower coin update
        //dd($request->sessionId);
        $value = $follower_bal - $model_charge;
        User_meta::where('user_id', $follower_id)->where('key', 'wallet_coins')->update([
          'value' => $value
        ]);
        
        $live_session = Live_session::where('user_id', $model_id)->first();
        $live_session_history = Live_session_history::where('user_id', $live_session->user_id)->where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();
        //model coin update
        $model_earning_for_this_chat = User_earning::where('live_session_history_id', $live_session_history->id)->first();
        $affiliate_earning_percentage = $this->meta_data['settings']['settings_payment_affiliate_earning_percentage'];
        $affiliate_earning = (($model_charge * $affiliate_earning_percentage / 100));
        if ($vip_member->affiliate_user_id == '') $affiliate_earning = 0;
        // dd($model_earning_for_this_chat);
        if (empty($model_earning_for_this_chat)) {
          //dd( $live_session_history->id);

          $dd = User_earning::create([
            'user_id' => $model_id,
            'token_coins' => $model_charge,
            'referral_user_id' => ($vip_member->affiliate_user_id == null ? 0 : $vip_member->affiliate_user_id),
            'referral_token_coins' => round($affiliate_earning),
            'live_session_history_id' => $live_session_history->id
          ]);
          // dd($dd->id);
        } else {
          User_earning::where('live_session_history_id', $live_session_history->id)->update([
            'token_coins' => $model_earning_for_this_chat->token_coins + $model_charge,
            'referral_token_coins' => round($model_earning_for_this_chat->referral_token_coins + $affiliate_earning),

          ]);
        }
        //Group chat table user spend coin this event
       $group_chat_update = GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$model_id)->where('follower_id',$follower_id)->where('exit_session',1);
       $group_chat_update->increment('coins',$model_charge);
       $group_chat_update->increment('video_chat_duration',1);
        
      }
    } else {
      $insufficient_bal = 1;
    }
    

    $data = ['low_balance_alert' => $low_balance_alert, 'insufficient_bal' => $insufficient_bal, 'live_session_history_id' => @$live_session_history->id,'follower_coin' => @$value == null ? 0 :@$value];
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }
  public function ajaxpost_group_chat_user_list_value_update($request){
    $model_id = $request->model_id;
    $follower_id = $request->follower_id;
    $live_session = Live_session::where('user_id', $model_id)->first();
    $live_session_history = Live_session_history::where('user_id', $live_session->user_id)->where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();

    $model_earning_for_this_session = User_earning::where('live_session_history_id', $live_session_history->id)->first();

    $group_chat_details = GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$model_id)->get();

    $data = ['model_earning_for_this_session' => $model_earning_for_this_session, 'live_session_history_id' => @$live_session_history->id,'group_chat_details' => $group_chat_details];
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);

  }
  public function ajaxpost_check_follower_balance_for_group_chat($request)
  {

    //dd($request->all());
    $follower_id = $request->follower_id;
    $model_id = $request->vip_id;

    $model_charge = User::find($model_id)->user_meta_array()['group_chat_charge'];
    $follower_bal = User::wallet(['user_id' => $follower_id])['balance'];
    $follower_detail = User::find($follower_id);
    $follower_sub_to_models = $follower_detail->check_subscribe_to_model($model_id);
    $data = [
      'follower_id' => $follower_id,
      'model_id' => $model_id,
      'model_charge' => $model_charge,
      'follower_bal' => $follower_bal,
      'insufficient_balance' => true,
      'follower_sub_to_models' => $follower_sub_to_models,
      'follower_detail' => $follower_detail,
      'model_online_status' => true,
    ];

    $apiKey = $this->meta_data['settings']['settings_opentok_api_key'];
    $live_session = Live_session::where('user_id', $model_id)->first();
    $opentok_data = ['apiKey' => '', 'apiSecret' => '', 'sessionId' => '', 'token' => ''];
    if (isset($live_session->id)) {
      
      //$live_session = Live_session::where('user_id', $model_id)->first();
      $live_session_history = Live_session_history::where('user_id', $live_session->user_id)->where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();

      $chek_group_chat =GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$model_id)->where('follower_id',$follower_id)->where('exit_session',1)->first();
      if($chek_group_chat){
        GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$model_id)->where('follower_id',$follower_id)->where('exit_session',1)->update(['exit_session'=>0]);
      }
      $group_chat = GroupChat::create([
        'live_session_history_id' => $live_session_history->id,
        'session_id' => $live_session->session_id,
        'model_id' => $model_id,
        'follower_id' => $follower_id,
        'video_chat_duration' => 0,
        'coins' => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        
      ]);
      //$data = ['private_chat_id' => $private_chat->id];
      $follower_spent_so_far =  GroupChat::where('model_id',$model_id)->where('follower_id',$follower_id)->sum('coins');
      $data['follower_spent_so_far'] = $follower_spent_so_far;
      $opentok_data = ['apiKey' => $apiKey, 'sessionId' => $live_session->session_id, 'token' => $live_session->token];
      
      $data['opentok_data'] = $opentok_data;
      if ($follower_bal != '' && $follower_bal != 0 && $follower_bal >= $model_charge) {
        $data['insufficient_balance'] = false;
      }
      //dd($data);
      if($data['insufficient_balance'] == true){
        GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$model_id)->where('follower_id',$follower_id)->where('exit_session',1)->update(['exit_session'=>0]);
      }
    }else{
      $data['model_online_status'] = false;
    }
    
    // dd($model_charge,$follower_bal);
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
    // return ['request'=>$request->all()];
  }

  public function ajaxpost_check_follower_balance_for_private_chat($request)
  {

    $follower_id = $request->follower_id;
    $model_id = $request->vip_id;

    $model_charge = User::find($model_id)->user_meta_array()['private_chat_charge'];
    $follower_bal = User::wallet(['user_id' => $follower_id])['balance'];
    $follower_detail = User::find($follower_id);
    $follower_sub_to_models = $follower_detail->check_subscribe_to_model($model_id);
    $data = [
      'follower_id' => $follower_id,
      'model_id' => $model_id,
      'model_charge' => $model_charge,
      'follower_bal' => $follower_bal,
      'insufficient_balance' => true,
      'follower_sub_to_models' => $follower_sub_to_models,
      'follower_detail' => $follower_detail,
    ];
    if ($follower_bal != '' && $follower_bal != 0 && $follower_bal >= $model_charge) {
      $data['insufficient_balance'] = false;
    }
    // dd($model_charge,$follower_bal);
    $opentok_data = ['apiKey' => '', 'apiSecret' => '', 'sessionId' => '', 'token' => ''];
    $apiKey = $this->meta_data['settings']['settings_opentok_api_key'];
    $live_session = Live_session::where('user_id', $model_id)->first();
    if (isset($live_session->id)) {
      $opentok_data = ['apiKey' => $apiKey, 'sessionId' => $live_session->session_id, 'token' => $live_session->token];
      $data['opentok_data'] = $opentok_data;
      $follower_spent_so_far =  GroupChat::where('model_id',$model_id)->where('follower_id',$follower_id)->sum('coins');
      $data['follower_spent_so_far'] = $follower_spent_so_far;
    }
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
    // return ['request'=>$request->all()];
  }

  public function ajaxpost_accept_private_chat_req($request)
  {

    $follower_id = $request->follower_id;
    $model_id = $request->model_id;
    $live_session = Live_session::where('user_id', $model_id)->first();
    $live_session_history = Live_session_history::where('user_id', $live_session->user_id)->where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();

    $private_chat = PrivateChat::create([
      'follower_id' => $follower_id,
      'live_session_history_id' => $live_session_history->id,
    ]);
    $data = ['private_chat_id' => $private_chat->id];
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
    /*   $model_charge = User::find($model_id)->user_meta_array()['private_chat_charge'];
        $follower_bal = User::wallet(['user_id'=>$follower_id])['balance'];
        $follower_detail = User::find($follower_id);
        $follower_sub_to_models = $follower_detail->check_subscribe_to_model($model_id);
        $data = [
            'follower_id' => $follower_id,
            'model_id' => $model_id,
            'model_charge' => $model_charge,
            'follower_bal' => $follower_bal,
            'insufficient_balance' => true,
            'follower_sub_to_models' => $follower_sub_to_models,
            'follower_detail' => $follower_detail,
        ];
        if($follower_bal !='' && $follower_bal !=0 && $follower_bal >= $model_charge){
            $data['insufficient_balance'] = false;
        }
        dd($model_charge,$follower_bal);
        echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
        return ['request'=>$request->all()]; */
  }

  public function ajaxpost_update_order($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $order_id = trim($request->order_id);
    $status = trim($request->status);
    $data = [];
    $order = Order::where('vip_member_id', $user_id)->where('id', $order_id)->update(['status' => $status]);
    Laravel_session::flash('success', 'Order successfully updated');
    echo json_encode(['success' => 1, 'data' => $data, 'message' => '']);
  }

  // ***** ajaxget *****

  public function ajaxget_search_subscribers($request)
  {
    $user_id = $this->user_data['id'] ?? '';
    $search = trim($request->search);
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $return_all = $request->return_all;
    if ($return_all == '') $return_all = false;
    if ($request->user_id != '') $user_id = $request->user_id;
    $limit_start = ($cur_page - 1) * $per_page;
    $data = [];
    $subscribers = Subscriber::select('u.id', 'u.first_name', 'u.last_name', 'u.username', 'u.display_name', 'u.email')->leftJoin('users as u', 'u.id', 'subscribers.subscriber_id')->where('u.status', '1')->where('subscribers.user_id', $user_id);
    $subscribers->where(function ($query) use ($search) {
      $query->orWhere(DB::raw("CONCAT(u.first_name, ' ', u.last_name)"), 'like', '%' . $search . '%');
    });
    $total_subscribers = $subscribers->count();
    if ($return_all != true)
      $subscribers = $subscribers->offset($limit_start)->limit($per_page);
    $subscribers = $subscribers->get();
    foreach ($subscribers as $key => $value) {
      $data[] = ['id' => $value->id, 'text' => ($value->first_name . ' ' . $value->last_name)];
    }
    echo json_encode(['results' => $data, 'total_data' => $total_subscribers]);
  }


  public function ajaxget_select2_test($request)
  {
    $search = trim($request->search);
    $cur_page = trim($request->cur_page);
    $per_page = trim($request->per_page);
    $data = [];
    for ($i = 1; $i <= $per_page; $i++) {
      $data[] = ['id' => ('a' . $i), 'text' => ($search . ' text ' . $i)];
    }
    echo json_encode(['results' => $data, 'total_data' => 25]);
  }


  public function ajaxpost_send_msg_to_subscriber($request)
  {
    $subscriber_id = $request->subscriber_id;
    $model_id = Auth::user()->id;
    if ($subscriber_id == 0 || $subscriber_id == '0') {
      $all_subscribers = Subscriber::where('user_id', $model_id)->get();
      foreach ($all_subscribers as $key => $value) {
        Notification::create([
          'user_id' => $value->subscriber_id, 'object_type' => 'user', 'object_id' => $model_id, 'action' => 'sendMessageToSubscriber', 'message' => $request->message, 'json_data' => json_encode(['user_id' => $model_id]), 'created_at' => date('Y-m-d H:i:s')
        ]);
      }
    } else {
      Notification::create([
        'user_id' => $subscriber_id, 'object_type' => 'user', 'object_id' => $model_id, 'action' => 'sendMessageToSubscriber', 'message' => $request->message, 'json_data' => json_encode(['user_id' => $model_id]), 'created_at' => date('Y-m-d H:i:s')
      ]);
    }
    echo json_encode(['success' => 1, 'message' => 'message sent successfully']);
  }

  public function ajaxpost_opentok_end_session_for_follower($request){
    //dd($request->all());
    $live_session = Live_session::where('user_id', $request->model_id)->first();
    if($live_session){
      $live_session_history = Live_session_history::where('user_id', $live_session->user_id)->where('session_id', $live_session->session_id)->where('token', $live_session->token)->first();

      $chek_group_chat =GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$request->model_id)->where('follower_id',$request->follower_id)->where('exit_session',1)->first();
      if($chek_group_chat){
        GroupChat::where('live_session_history_id',$live_session_history->id)->where('model_id',$request->model_id)->where('follower_id',$request->follower_id)->where('exit_session',1)->update(['exit_session'=>0,'exit_session_time'=>date('Y-m-d H:i:s')]);
      }
    }
    echo json_encode(['success' => 1, 'message' => 'message sent successfully']);
  }

  
}
