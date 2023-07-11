<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB AS DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Session;

use App\Http\Helpers;
use App\User;
use App\Setting;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */

    public function redirectToFacebook() {
        if($user = Auth::user())
            return redirect(url('/'));
        return Socialite::driver('facebook')->redirect();
    }

    public function redirectToGoogle() {
        if($user = Auth::user())
            return redirect(url('/'));
        return Socialite::driver('google')->redirect();
    }

    public function facebookCallback() {
        /*$user = Socialite::driver('facebook')->user();
        echo "<pre>";
        print_r( $user); die;*/
        if($user = Auth::user())
            return redirect(url('/'));
        $social_login = json_decode(($_COOKIE['social_login'] ?? '{}'), true);
        if(count($social_login) == 0 || (isset($social_login['role']) && $social_login['role'] == 1)) {
          request()->session()->flash('error',__('Failed to login.'));
          return redirect(url('/'));
        }
        $social_user = Socialite::driver('facebook')->user();
        if(isset($social_user->email)){
            $user = User::where('email', $social_user->email)->get();
            $user_id = $user[0]->id ?? '';
            if($user_id == '') {
                $time = time();
                $name = explode(' ', $social_user->name);
                $status = 0;
                if($social_login['role'] == 3) $status = 1;
                $user_arr = ['first_name' => $name[0], 'last_name' => ($name[1] ?? ''), 'email' => $social_user->email, 'social_login' => 'facebook', 'role' => $social_login['role'], 'password' => Str::random(), 'status' => $status];
                $user = User::create($user_arr);
                $user_id = $user->id;
                User::where('id', $user->id)->update(['username' => 'user-' . $user->id . $time]);
                $settings = Setting::get_list();
                $heading = '';
                if($social_login['role'] == 2)
                    $heading = 'You are successfully registered as VIP member.';
                if($social_login['role'] == 3)
                    $heading = 'You are successfully registered as follower.';
                $mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $settings['settings_website_logo']), 'title' => 'User Registration', 'heading' => $heading, 'message' => ''));
                $mail_data = array(
                  'to' => array(
                      array($social_user->email, "")
                  ),
                  'subject' => 'User Registration',
                  'body' => $mail_body,
                );
                Helpers::pmail($mail_data);
            } else {
                $user = $user[0];
            }
            Auth::login($user);
            if(in_array($user->role, [2, 3]))
                return redirect()->intended('dashboard/home');
        }else{
            request()->session()->flash('error',__('Failed to login.'));
            return redirect(url('/'));
        }
    }

    public function GoogleCallback() {
        /*$user = Socialite::driver('google')->user();
        echo "<pre>";
        print_r( $user); die;*/
        if($user = Auth::user())
            return redirect(url('/'));
        $social_login = json_decode(($_COOKIE['social_login'] ?? '{}'), true);
        if(count($social_login) == 0 || (isset($social_login['role']) && $social_login['role'] == 1)) {
          request()->session()->flash('error',__('Failed to login.'));
          return redirect(url('/'));
        }
        $social_user = Socialite::driver('google')->user();
        if(isset($social_user->email)){
            $user = User::where('email', $social_user->email)->get();
            $user_id = $user[0]->id ?? '';
            if($user_id == '') {
                $time = time();
                $name = explode(' ', $social_user->name);
                $status = 0;
                if($social_login['role'] == 3) $status = 1;
                $user_arr = ['first_name' => $name[0], 'last_name' => ($name[1] ?? ''), 'email' => $social_user->email, 'social_login' => 'google', 'role' => $social_login['role'], 'password' => Str::random(), 'status' => $status];
                $user = User::create($user_arr);
                $user_id = $user->id;
                User::where('id', $user->id)->update(['username' => 'user-' . $user->id . $time]);
                $settings = Setting::get_list();
                $heading = '';
                if($social_login['role'] == 2)
                    $heading = 'You are successfully registered as VIP member.';
                if($social_login['role'] == 3)
                    $heading = 'You are successfully registered as follower.';
                $mail_body = view('email_template/general', array('logo' => url('/public/uploads/settings/settings_website_logo/' . $settings['settings_website_logo']), 'title' => 'User Registration', 'heading' => $heading, 'message' => ''));
                $mail_data = array(
                  'to' => array(
                      array($social_user->email, "")
                  ),
                  'subject' => 'User Registration',
                  'body' => $mail_body,
                );
                Helpers::pmail($mail_data);
            } else {
                $user = $user[0];
            }
            Auth::login($user);
            if(in_array($user->role, [2, 3]))
                return redirect()->intended('dashboard/home');
        }else{
            request()->session()->flash('error',__('Failed to login.'));
            return redirect(url('/'));
        }
    }


    public function login(Request $request) {
        //dd($request->all());
        $role = trim($request->role);
        $email = trim($request->email);
        $password = trim($request->password);
        $remember = trim($request->remember);
        if($remember == 1) $remember = true;
        else $remember = false;
        $data = ['email' => $email, 'password' => $password, 'role' => $role, 'status' => 1];
        if(Auth::attempt($data, $remember)){
            $user = Auth::getLastAttempted();
            if($user->last_activity == null){
                if($user->role == 1) return redirect('admin/dashboard');
                if(in_array($user->role, [2, 3])) return redirect('dashboard');
            }else{
                $to = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_activity);
                $from = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now());
                $diffInMinutes = $to->diffInMinutes($from);
                if($diffInMinutes > 2 ){
                    if($user->role == 1) return redirect('admin/dashboard');
                    if(in_array($user->role, [2, 3])) return redirect('dashboard');
                }else{
                    Auth::logout();
                    return back()->withInput()->with(['error' => 'Login failed. other device already login']);
                }
            }
            
        } else {
            return back()->withInput()->with(['error' => 'Login failed']);
        }
    }

}
