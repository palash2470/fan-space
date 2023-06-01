<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User as User;
use App\User_meta as User_meta;
use App\Country as Country;
use App\State as State;
use App\Cms as Cms;
use App\Setting as Setting;
use App\Http\Helpers;
use App\Coin_price_plan;
use App\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Session;
use Hash;
//use Auth;
use Illuminate\Support\Facades\Auth;
use Mail;
use Mpdf;
use App\Reported_item;


class AdminajaxController extends Controller
{

    private $user_data = array();

    private $meta_data = array();

    public function __construct()
    {
        $this->meta_data['settings'] = array();
        $settings = DB::table('settings')->get();
        foreach ($settings as $key => $value) {
            $this->meta_data['settings'][$value->key] = $value->value;
        }
        //date_default_timezone_set($this->meta_data['settings']['settings_website_timezone']);
    }

    public function slug(Request $request)
    {
        $slug = $request->segment(2);
        $this->{$slug}($request);
    }

    public function change_user_status($request)
    {
        $data = $request->input('data');
        $user_id = $data['user_id'];
        $user = User::find($user_id);
        $status = '';
        if ($user->status == '0') $status = 1;
        if ($user->status == '1') $status = 0;
        User::where('id', $user_id)->update(['status' => $status]);
        $request->session()->flash('success', 'User status successfully changed');
        echo json_encode(array('success' => 1));
    }

    public function delete_user($request)
    {
        // dd($request->all());
        $id = $request->id;
        User::find($id)->delete();
        $request->session()->flash('success', 'User deleted successfully');
        echo json_encode(array('success' => 1));
    }
    public function coin_plan_delete($request)
    {
        $data = $request->input('data');
        $coin_plan_id = $data['coin_plan_id'];
        Coin_price_plan::destroy($coin_plan_id);
        $request->session()->flash('success', 'Coin plan successfully deleted');
        echo json_encode(array('success' => 1));
    }

    public function delete_reported_item($request)
    {
        // dd($request->all());

        $data = $request->input('data');
        $item_id = $data['item_id'];
        $type = $data['type'];
        // dd($item_id, $type);
        if ($type == 'reject') {
            $hh = Reported_item::find($item_id)->update([
                'status' => 'Rejected'
            ]);
            // dd($hh);
            $request->session()->flash('success', 'Item successfully rejected');
        }
        if ($type == 'block') {
            $item = Reported_item::find($item_id);
            if ($item->type == '1') {
                User::find($item->item_id)->update([
                    'status' => 0
                ]);
            }
            if ($item->type == '2') {
                Post::find($item->item_id)->update([
                    'status' => 0
                ]);
            }
            Reported_item::find($item_id)->update([
                'status' => 'Rejected'
            ]);
            $request->session()->flash('success', 'Item successfully rejected');
        }
        // Reported_item::destroy($item_id);

        echo json_encode(array('success' => 1));
    }


    public function settings($request)
    {
        $data = json_decode($request->input('data'), true);
        /*print_r($_POST);
		print_r($_FILES);
		print_r($data); die;*/
        $setting_old = Setting::all();
        $setting_old2 = array();
        foreach ($setting_old as $value) {
            $setting_old2[$value->key] = $value->value;
        }
        foreach ($data['files'] as $f) {
            $file = ($setting_old2[$f['field_name']] ?? '');
            @unlink(public_path('uploads/settings/' . $f['field_name'] . '/' . $file));
            $new_file = '';
            if (isset($_FILES[$f['field_name']])) {
                $new_file = $_FILES[$f['field_name']]['name'];
                $request->file($f['field_name'])->move(public_path('uploads/settings/' . $f['field_name']), $new_file);
            }
            $setting = Setting::updateOrCreate(
                ['key' => $f['field_name']],
                ['value' => $new_file]
            );
        }
        /*$settings_paypal_email = $data['settings_paypal_email'];
		$settings_paypal_pdt_token = $data['settings_paypal_pdt_token'];
		$settings_paypal_payment_mode = $data['settings_paypal_payment_mode'];*/
        $settings_data = array();
        foreach ($data as $key => $value) {
            if ($key != 'files') {
                $setting = Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }
        $update_logo = 0;
        foreach ($data['files'] as $f) {
            if ($f['field_name'] == 'settings_website_logo')
                $update_logo = 1;
        }
        echo json_encode(array('success' => 1));
    }


    public function test()
    {
        $data = Input::get('data');
        echo json_encode(array('test' => 'test data = ' . $data['test']));
    }
}
