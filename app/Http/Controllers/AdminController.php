<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use Illuminate\Support\Facades\Auth;
use Hash;
use Session;
use App\Http\Helpers;

use App\User;
use App\Country;
use App\State;
use App\Setting;
use App\Coin_price_plan;
use App\ContactUS;
use App\DynamicContent;
use App\Exports\CoinTransactionExport;
use App\Exports\MoneyTransactionExport;
use App\Exports\SalesReport;
use App\Faq;
use App\Live_session_tip;
use App\Order;
use App\Order_item;
use App\Payment;
use App\Reported_item;
use App\Post;
use App\Post_paid_user;
use App\Post_tip;
use App\PrivateChat;
use App\Product;
use App\Subscriber;
use App\Ticket;
use App\TicketChat;
use App\User_payout;
use App\AdminNotification;
use App\CancelSubscriber;

use DataTables;
use Excel;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    private $user_data = array();

    private $meta_data = array();

    function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::user()) {
                $getData = User::find(Auth::id());
                $logout_url = '';
                /*if($getData->role == 1)
                    $logout_url = url('/admin/logout');
                if(in_array($getData->role, [2, 3]))
                    $logout_url = url('/user/logout');*/
                $logout_url = url('logout');
                $this->user_data = array(
                    'id' => $getData->id,
                    'first_name' => $getData->first_name,
                    'last_name' => $getData->last_name,
                    'name' => $getData->first_name . ' ' . $getData->last_name,
                    'email' => $getData->email,
                    'role' => $getData->role,
                    'logout_url' => $logout_url
                );
            }
            return $next($request);
        });

        $this->meta_data['settings'] = array();
        $settings = DB::table('settings')->get();
        foreach ($settings as $key => $value) {
            $this->meta_data['settings'][$value->key] = $value->value;
        }
    }

    public function index(Request $request, $slug)
    {
        // dd('hi');
        $user_data = $this->user_data;
        $meta_data = [];
        $helper_settings = Helpers::get_settings();
        $slug = $request->segment(2);
        if ($slug == '')
            return redirect('admin/dashboard');
        $page_title = 'Dashboard';
        $function_data = $this->{$slug}($request);
        if (!is_array($function_data))
            return $function_data;
        $page_title = $function_data['page_title'];
        $meta_data = $function_data;
        $meta_data['global_settings'] = $this->meta_data['settings'];
        $meta_data['helper_settings'] = $helper_settings;
        $meta_data['slug'] = $slug;
        $meta_data['notification'] = AdminNotification::where('seen', 0)->orderBy('id', 'desc')->limit(10)->get();

        // dd($meta_data);
        return view('admin.dashboard', ['user_data' => $user_data, 'meta_data' => $meta_data, 'page_title' => $page_title]);
    }
    public function send_email($request)
    {
        // dd($request->all());
        $subject = $request->subject;
        $message = $request->message;
        if ($request->eloquent == "user") {
            $user = User::find($request->eloquent_id);
            $to_email = $user->email;
        }
        if ($request->eloquent == "contactus") {
            $contact = ContactUS::find($request->eloquent_id);
            $to_email = $contact->email;
        }
        $mail_data = array(
            'to' => array(
                array($to_email, "")
            ),
            'subject' => $subject,
            'body' => $message,
        );
        Helpers::pmail($mail_data);
        return redirect()->back()->with('success', 'Email sent successfully !');
    }
    public function list_subscription($request)
    {
        if ($request->ajax()) {
            // dd($request->type);
            $data = new Subscriber;
            $data = $data->with('model_detail', 'subscriber_detail');
            $data = $data->get();

            $data2 = new CancelSubscriber;
            $data2 = $data2->with('model_detail', 'subscriber_detail');
            $data2 = $data2->get();

            $merged = $data->merge($data2);

            $result = $merged->all();
            return DataTables::of($result)
                ->addIndexColumn()
                ->editColumn('subscriber_name', function ($row) {
                    return $row->subscriber_detail->full_name;
                })
                ->editColumn('model_name', function ($row) {
                    return $row->model_detail->full_name;
                })
                ->editColumn('validity_date', function ($row) {
                    return date('d/m/Y H:i', strtotime($row->validity_date));
                })
                ->editColumn('next_renewal_date', function ($row) {
                    return date('d/m/Y H:i', strtotime($row->next_renewal_date));
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<button class="btn btn-success" type="button">Active</button>' : '<button class="btn btn-danger" type="button">Canceled</button>';
                })
                ->editColumn('actions', function ($row) {
                    $html = '';
                    if ($row->status == 1) {
                        $html .= '<a class="btn btn-danger cancel_subscription" data-id="' . $row->id . '" href="javascript:;"><i class="fa fa-times"></i></a>';
                    }
                    return $html;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Subscription List';
            $return['general_settings'] = ['selected_nav' => ['nav_list_subscription']];
            return $return;
        }
    }
    public function cancel_subscription($request)
    {
        // dd($request->all());
        $subscriber = Subscriber::find($request->id);
        // dd($subscriber);
        if (isset($subscriber->id)) {
            $stripe_subscription = Helpers::stripe_subscription_cancel(['subscription_id' => $subscriber->stripe_subscription_id]);
            if ($stripe_subscription['success'] == '1') {

                CancelSubscriber::create([
                    'user_id' => $subscriber->user_id,
                    'subscriber_id' => $subscriber->subscriber_id,
                    'validity_date' => $subscriber->validity_date,
                    'next_renewal_date' => $subscriber->next_renewal_date,
                    'stripe_subscription_id' => $subscriber->stripe_subscription_id,
                    'stripe_plan_id' => $subscriber->stripe_plan_id,
                    'duration_days' => $subscriber->duration_days,
                    'status' => 0,
                ]);
                Subscriber::destroy($subscriber->id);
            } else {
                echo json_encode(['success' => 0]);
                die;
            }
        } else {
            echo json_encode(['success' => 0]);
            die;
        }
        echo json_encode(['success' => 1]);
    }
    public function follower_list($request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 3)->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->full_name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('user_name', function ($row) {
                    return $row->username;
                })
                ->editColumn('mobile', function ($row) {
                    return $row->phone;
                })
                ->editColumn('address', function ($row) {
                    return $row->user_meta_array()['address_line_1'];
                })
                ->editColumn('status', function ($row) {
                    // return $row->status;
                    return $row->status == 1 ? '<div class="toggle-wrap"><label class="switch"><input class="user_status" type="checkbox" value="' . $row->id . '" checked><span class="slider round"></span></label></div>' : '<div class="toggle-wrap"><label class="switch"><input class="user_status" type="checkbox" value="' . $row->id . '"><span class="slider round"></span></label></div>';
                })
                ->editColumn('actions', function ($row) {
                    return '<div class="delete-user-wrap"><a href="javascript:;" class="delete-user" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a></div>';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Follower List';
            $return['general_settings'] = ['selected_nav' => ['nav_follower']];
            return $return;
        }
    }
    public function sales_report($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Sales Report';
        $return['product'] = Product::all();
        $return['model'] = User::where('role', 2)->get();
        $return['general_settings'] = ['selected_nav' => ['nav_sales_report']];
        return $return;
    }
    public function store($request)
    {
        if ($request->ajax()) {
            $data = Product::with('user_details');
            if ($request->model != "all") {
                $data = $data->where('user_id', $request->model);
            }

            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('model', function ($row) {
                    return $row->user_details->full_name;
                })
                ->editColumn('image', function ($row) {
                    if ($row->thumbnail) {
                        return '<img style="max-width:100px;" src="' . url('public/uploads/product/' . $row->thumbnail) . '">';
                    } else {
                        return '<img style="max-width:100px;" src="' . url('public/uploads/product/product-placeholder.jpg') . '">';
                    }
                })
                ->editColumn('product_name', function ($row) {
                    return $row->title;
                })
                ->editColumn('stock', function ($row) {
                    return $row->stock;
                })
                ->editColumn('price', function ($row) {
                    return $row->price;
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1 ? '<button type="button" class="btn btn-success">	<i class="fa fa-check"></i></button>' : '<button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button>';
                })
                ->editColumn('actions', function ($row) {
                    return '<a href="' . url('admin/store_order?p_id=' . encrypt($row->id)) . '" class="btn btn-primary">Oder List</a>';
                })
                ->rawColumns(['image', 'status', 'actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Store';
            $return['general_settings'] = ['selected_nav' => ['nav_store']];
            $return['model'] = User::where('role', 2)->get();
            AdminNotification::where('seen', 0)->where('type', 'order')->update([
                'seen' => 1
            ]);
            return $return;
        }
    }
    public function store_order($request)
    {
        $p_id = decrypt($request->p_id);
        if ($request->ajax()) {
            $data = Order_item::with('order_details', 'product_details', 'order_details.order_by', 'order_details.order_address')->where('product_id', $p_id)->select('*', 'order_items.price as item_price')->get();
            // dd($data[0]->order_details->order_address[0]->address_line_1);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('order_by', function ($row) {
                    return !empty($row->order_details->order_by->full_name) ? $row->order_details->order_by->full_name : '';
                })
                ->editColumn('order_address', function ($row) {
                    return !empty($row->order_details->order_address->address_line_1) ? $row->order_details->order_address->address_line_1 : '';
                })
                ->editColumn('item_price', function ($row) {
                    return $row->price;
                })
                ->editColumn('qty', function ($row) {
                    return $row->quantity;
                })
                ->editColumn('total', function ($row) {
                    return $row->price * $row->quantity;
                })
                ->editColumn('order_date', function ($row) {
                    return $row->order_details->created_at;
                })
                ->editColumn('status', function ($row) {
                    $status = '';
                    if ($row->order_details->status == 0) {
                        $status = 'Inactive';
                    }
                    if ($row->order_details->status == 1) {
                        $status = 'Pending';
                    }
                    if ($row->order_details->status == 3) {
                        $status = 'Cancelled';
                    }
                    if ($row->order_details->status == 4) {
                        $status = 'Completed';
                    }
                    return $status;
                })
                ->rawColumns(['status'])
                ->make(true);
        } else {

            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Store Order';
            $return['general_settings'] = ['selected_nav' => ['nav_store']];
            $return['product_id'] = $p_id;
            $return['product_details'] = Product::with('user_details')->find($p_id);
            return $return;
        }
    }
    public function edit_faq($request)
    {

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'question' => 'required',
                'answer' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            Faq::find($request->id)->update($request->all());
            return redirect()->back()->with('success', 'FAQ Updated Successfully !');
        } else {
            $id = decrypt($request->id);
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Add FAQ';
            $return['general_settings'] = ['selected_nav' => ['nav_faq']];
            $return['faq'] = Faq::find($id);
            return $return;
        }
    }
    public function faq($request)
    {

        if ($request->ajax()) {
            // dd($request->type);
            $data = Faq::all();
            // $data = [['name' => 'abc', 'email' => 'vvv'], ['name' => 'abcd', 'email' => 'vvvd']];
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('question', function ($row) {
                    return $row->question;
                })
                ->editColumn('answer', function ($row) {
                    return $row->answer;
                })
                ->editColumn('date', function ($row) {
                    return $row->created_at->format('d/m/Y');
                })
                ->editColumn('actions', function ($row) {
                    return '<div class="dashboard-btn-list-wrap"><ul><li><a class="btn btn-primary" href="' . url('admin/edit_faq?id=' . encrypt($row->id)) . '"><i class="fa fa-pencil-square-o"></i></a></li><li> <a href="#" class="del-faq btn btn-danger" data-url="' . url('admin/delete_faq?id=' . encrypt($row->id)) . '"><i class="fa fa-trash"></i></a></li></ul></div>';
                })
                ->rawColumns(['answer', 'actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'FAQ List';
            $return['general_settings'] = ['selected_nav' => ['nav_faq']];
            return $return;
        }
    }
    public function delete_faq($request)
    {
        $id = decrypt($request->id);
        Faq::find($id)->delete();
        return redirect()->back()->with('success', 'FAQ deleted successfully !');
    }
    public function add_faq($request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'question' => 'required',
                'answer' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }
            Faq::create($request->all());
            return redirect()->back()->with('success', 'FAQ Created Successfully !');
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Add FAQ';
            $return['general_settings'] = ['selected_nav' => ['nav_faq']];
            return $return;
        }
    }
    public function contact_us($request)
    {

        if ($request->ajax()) {
            // dd($request->type);
            $data = ContactUS::all();
            // $data = [['name' => 'abc', 'email' => 'vvv'], ['name' => 'abcd', 'email' => 'vvvd']];
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('ph_no', function ($row) {
                    return $row->ph_no;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('message', function ($row) {
                    return $row->message;
                })
                ->editColumn('date', function ($row) {
                    return $row->created_at->format('d/m/Y');
                })
                ->editColumn('actions', function ($row) {
                    return '<div class="dashboard-btn-list-wrap"><ul><li><a class="btn btn-info" href="javascript:;" data-toggle="modal" data-target="#myModal"
                    data-id="' . $row->id . '" data-eloquent="contactus" class="send-email"><i
                        class="fa fa-envelope"></i></a></li></ul></div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Contact Us List';
            $return['general_settings'] = ['selected_nav' => ['nav_contact_us']];
            return $return;
        }
    }
    public function dynamic_home_page_content($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Home Page Dynamic Content';
        $return['general_settings'] = ['selected_nav' => ['nav_dynamic_content', 'home_page_dynamic_content']];
        $return['page_content'] = DynamicContent::where('page_name', 'HOME')->first();
        return $return;
    }
    public function about_us_dynamic_content($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'About Us Dynamic Content';
        $return['general_settings'] = ['selected_nav' => ['nav_dynamic_content', 'about_us_dynamic_content']];
        $return['page_content'] = DynamicContent::where('page_name', 'ABOUTUS')->first();
        return $return;
    }
    public function term_condition_dynamic_content($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Tems & Condition Dynamic Content';
        $return['general_settings'] = ['selected_nav' => ['nav_dynamic_content', 'term_condition_dynamic_content']];
        $return['page_content'] = DynamicContent::where('page_name', 'TERMS_CONDITION')->first();
        return $return;
    }
    public function privacy_policy_dynamic_content($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Tems & Condition Dynamic Content';
        $return['general_settings'] = ['selected_nav' => ['nav_dynamic_content', 'privacy_policy_dynamic_content']];
        $return['page_content'] = DynamicContent::where('page_name', 'PRIVACY_POLICY')->first();
        return $return;
    }
    public function contact_us_dynamic_content($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Contact Us Dynamic Content';
        $return['general_settings'] = ['selected_nav' => ['nav_dynamic_content', 'contact_us_dynamic_content']];
        $return['page_content'] = DynamicContent::where('page_name', 'CONTACT_US')->first();
        return $return;
    }
    public function dynamic_content(Request $request)
    {
        $content = [];
        // $content['banner_background_image'] = $request->banner_background_image;
        if ($request->page_type == 'TERMS_CONDITION') {
            $content['content'] = $request->content ?? '';
            $banner_img = $request->old_banner_img ?? '';
            if ($request->hasFile('banner_img')) {
                $imageName = 'banner_img' . time() . '.' . request()->banner_img->getClientOriginalExtension();
                request()->banner_img->move(public_path('admin/dist/img/terms_condition'), $imageName);
                $banner_img = $imageName;
            }
            $content['banner_img'] = $banner_img;
            DynamicContent::where('page_name', 'TERMS_CONDITION')->update([
                'content' => $content
            ]);
        }
        if ($request->page_type == 'CONTACT_US') {
            $content['content'] = $request->content ?? '';
            $content['ph_no'] = $request->ph_no ?? '';
            $content['email'] = $request->email ?? '';
            $banner_img = $request->old_banner_img ?? '';
            if ($request->hasFile('banner_img')) {
                $imageName = 'banner_img' . time() . '.' . request()->banner_img->getClientOriginalExtension();
                request()->banner_img->move(public_path('admin/dist/img/contact_us'), $imageName);
                $banner_img = $imageName;
            }
            $content['banner_img'] = $banner_img;
            DynamicContent::where('page_name', 'CONTACT_US')->update([
                'content' => $content
            ]);
        }
        if ($request->page_type == 'PRIVACY_POLICY') {
            $content['content'] = $request->content ?? '';
            $banner_img = $request->old_banner_img ?? '';
            if ($request->hasFile('banner_img')) {
                $imageName = 'banner_img' . time() . '.' . request()->banner_img->getClientOriginalExtension();
                request()->banner_img->move(public_path('admin/dist/img/terms_condition'), $imageName);
                $banner_img = $imageName;
            }
            $content['banner_img'] = $banner_img;
            DynamicContent::where('page_name', 'PRIVACY_POLICY')->update([
                'content' => $content
            ]);
        }
        if ($request->page_type == 'ABOUTUS') {
            $content['content'] = $request->content ?? '';
            $banner_img = $request->old_banner_img ?? '';
            if ($request->hasFile('banner_img')) {
                $imageName = 'banner_img' . time() . '.' . request()->banner_img->getClientOriginalExtension();
                request()->banner_img->move(public_path('admin/dist/img/about_us'), $imageName);
                $banner_img = $imageName;
            }

            $about_img = $request->old_about_img ?? '';
            if ($request->hasFile('about_img')) {
                $imageName = 'about_img' . time() . '.' . request()->about_img->getClientOriginalExtension();
                request()->about_img->move(public_path('admin/dist/img/about_us'), $imageName);
                $about_img = $imageName;
            }

            $content['banner_img'] = $banner_img;
            $content['about_img'] = $about_img;
            DynamicContent::where('page_name', 'ABOUTUS')->update([
                'content' => $content
            ]);
        }
        if ($request->page_type == 'HOME') {
            $content['youtube_video_link'] = $request->youtube_video_link ?? '';
            $content['content'] = $request->content ?? '';
            // $content['second_banner_background_image'] = $request->second_banner_background_image;
            $content['button_link'] = $request->button_link ?? '';
            $content['second_banner_content'] = $request->second_banner_content ?? '';
            $banner_background_image = $request->old_banner_background_image ?? '';
            if ($request->hasFile('banner_background_image')) {
                $imageName = 'banner_background_image_' . time() . '.' . request()->banner_background_image->getClientOriginalExtension();
                request()->banner_background_image->move(public_path('admin/dist/img/dynamic-home-content'), $imageName);
                $banner_background_image = $imageName;
            }
            $second_banner_background_image = $request->old_second_banner_background_image ?? '';
            if ($request->hasFile('second_banner_background_image')) {
                $imageName = 'second_banner_background_image_' . time() . '.' . request()->second_banner_background_image->getClientOriginalExtension();
                request()->second_banner_background_image->move(public_path('admin/dist/img/dynamic-home-content'), $imageName);
                $second_banner_background_image = $imageName;
            }
            $content['second_banner_background_image'] = $second_banner_background_image;
            $content['banner_background_image'] = $banner_background_image;
            DynamicContent::where('page_name', 'HOME')->update([
                'content' => $content
            ]);
        }
        return redirect()->back()->with('success', 'Content successfully updated !');
    }
    public function money_transaction_history($request)
    {
        if ($request->ajax()) {
            // dd($request->type);
            $data = new Payment;
            $data = $data->with('getUser');
            if ($request->type != '' && $request->type != 'all') {
                $data = $data->where('type', $request->type);
            }
            if ($request->user != '' && $request->user != 'all') {
                $data = $data->where('user_id', $request->user);
            }
            $data = $data->get();
            // $data = [['name' => 'abc', 'email' => 'vvv'], ['name' => 'abcd', 'email' => 'vvvd']];
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                })
                ->editColumn('type', function ($row) {
                    $type = '';
                    $type = $row->type == 1 ? 'Buy Coins' : 'Buy Subscription';
                    return $type;
                })
                ->editColumn('txn', function ($row) {
                    return $row->txn_id;
                })
                ->editColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->editColumn('date', function ($row) {
                    return $row->created_at->format('d/m/Y');
                })
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Money Transaction History';
            $return['general_settings'] = ['selected_nav' => ['money_transaction_history', 'nav_transaction_history']];
            $return['follower'] = User::where('role', 3)->get();
            return $return;
        }
    }
    public function coin_transaction_history($request)
    {
        if ($request->ajax()) {
            if ($request->type == "order") {
                $data = new Order;
                $data = $data->join('users as model', 'model.id', '=', 'orders.vip_member_id');
                $data = $data->join('users as follower', 'follower.id', '=', 'orders.user_id');
                if ($request->model != '' && $request->model != 'all') {
                    $data = $data->where('vip_member_id', $request->model);
                }
                if ($request->follower != '' && $request->follower != 'all') {
                    $data = $data->where('user_id', $request->follower);
                }
                $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'orders.total_amount as total_amount', 'orders.status as status', 'orders.created_at as created_at');
                $data = $data->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('model_name', function ($row) {
                        // return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                        return $row->model_first_name . ' ' . $row->model_last_name;
                    })
                    ->editColumn('follower_name', function ($row) {
                        // return $row->user_id;
                        return $row->follower_first_name . ' ' . $row->follower_last_name;
                    })
                    ->editColumn('amount', function ($row) {
                        return $row->total_amount;
                    })
                    ->editColumn('status', function ($row) {
                        $status = "";
                        if ($row->status == 0) {
                            $status = "Inactive";
                        }
                        if ($row->status == 1) {
                            $status = "Pending";
                        }
                        if ($row->status == 2) {
                            $status = "Cancelled";
                        }
                        if ($row->status == 3) {
                            $status = "Completed";
                        }
                        return $status;
                    })
                    ->editColumn('date', function ($row) {
                        return $row->created_at->format('d/m/Y');
                    })
                    ->editColumn('type', function ($row) {
                        return 'Product Order';
                    })
                    ->make(true);
            }
            if ($request->type == "post_tips") {
                $data = new Post_tip;
                $data = $data->join('user_earnings', 'post_tips.id', '=', 'user_earnings.post_tips_id');
                $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
                $data = $data->join('users as follower', 'follower.id', '=', 'post_tips.tipper_id');
                if ($request->model != '' && $request->model != 'all') {
                    $data = $data->where('user_earnings.user_id', $request->model);
                }
                if ($request->follower != '' && $request->follower != 'all') {
                    $data = $data->where('post_tips.tipper_id', $request->follower);
                }
                $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'post_tips.created_at as created_at');
                $data = $data->get();
                // dd($data);
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('model_name', function ($row) {
                        // return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                        return $row->model_first_name . ' ' . $row->model_last_name;
                    })
                    ->editColumn('follower_name', function ($row) {
                        return $row->follower_first_name . ' ' . $row->follower_last_name;
                    })
                    ->editColumn('amount', function ($row) {
                        return $row->token_coins;
                    })
                    ->editColumn('status', function ($row) {
                        return '';
                    })
                    ->editColumn('date', function ($row) {
                        return $row->created_at->format('d/m/Y');
                    })
                    ->editColumn('type', function ($row) {
                        return 'Post Tips';
                    })
                    ->make(true);
            }
            if ($request->type == "post_unlock") {
                $data = new Post_paid_user;
                $data = $data->join('user_earnings', 'post_paid_users.id', '=', 'user_earnings.post_paid_users_id');
                $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
                $data = $data->join('users as follower', 'follower.id', '=', 'post_paid_users.user_id');
                if ($request->model != '' && $request->model != 'all') {
                    $data = $data->where('user_earnings.user_id', $request->model);
                }
                if ($request->follower != '' && $request->follower != 'all') {
                    $data = $data->where('post_paid_users.user_id', $request->follower);
                }
                $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'post_paid_users.created_at as created_at');
                $data = $data->get();
                // dd($data);
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('model_name', function ($row) {
                        // return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                        return $row->model_first_name . ' ' . $row->model_last_name;
                    })
                    ->editColumn('follower_name', function ($row) {
                        return $row->follower_first_name . ' ' . $row->follower_last_name;
                    })
                    ->editColumn('amount', function ($row) {
                        return $row->token_coins;
                    })
                    ->editColumn('status', function ($row) {
                        return '';
                    })
                    ->editColumn('date', function ($row) {
                        return $row->created_at->format('d/m/Y');
                    })
                    ->editColumn('type', function ($row) {
                        return 'Post Unlock';
                    })
                    ->make(true);
            }
            if ($request->type == "chat_tips") {
                $data = new Live_session_tip;
                $data = $data->join('user_earnings', 'live_session_tips.id', '=', 'user_earnings.live_session_tips_id');
                $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
                $data = $data->join('users as follower', 'follower.id', '=', 'live_session_tips.tipper_id');
                if ($request->model != '' && $request->model != 'all') {
                    $data = $data->where('user_earnings.user_id', $request->model);
                }
                if ($request->follower != '' && $request->follower != 'all') {
                    $data = $data->where('live_session_tips.tipper_id', $request->follower);
                }
                $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'live_session_tips.created_at as created_at');
                $data = $data->get();
                // dd($data);
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('model_name', function ($row) {
                        // return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                        return $row->model_first_name . ' ' . $row->model_last_name;
                    })
                    ->editColumn('follower_name', function ($row) {
                        return $row->follower_first_name . ' ' . $row->follower_last_name;
                    })
                    ->editColumn('amount', function ($row) {
                        return $row->token_coins;
                    })
                    ->editColumn('status', function ($row) {
                        return '';
                    })
                    ->editColumn('date', function ($row) {
                        return $row->created_at->format('d/m/Y');
                    })
                    ->editColumn('type', function ($row) {
                        return 'Chat Tips';
                    })
                    ->make(true);
            }
            if ($request->type == "private_chat") {
                $data = new PrivateChat;
                $data = $data->join('user_earnings', 'private_chat.id', '=', 'user_earnings.private_chat_id');
                $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
                $data = $data->join('users as follower', 'follower.id', '=', 'private_chat.follower_id');
                if ($request->model != '' && $request->model != 'all') {
                    $data = $data->where('user_earnings.user_id', $request->model);
                }
                if ($request->follower != '' && $request->follower != 'all') {
                    $data = $data->where('private_chat.follower_id', $request->follower);
                }
                $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'private_chat.created_at as created_at');
                $data = $data->get();
                // dd($data);
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('model_name', function ($row) {
                        // return $row->getUser->first_name . ' ' . $row->getUser->last_name;
                        return $row->model_first_name . ' ' . $row->model_last_name;
                    })
                    ->editColumn('follower_name', function ($row) {
                        return $row->follower_first_name . ' ' . $row->follower_last_name;
                    })
                    ->editColumn('amount', function ($row) {
                        return $row->token_coins;
                    })
                    ->editColumn('status', function ($row) {
                        return '';
                    })
                    ->editColumn('date', function ($row) {
                        return $row->created_at->format('d/m/Y');
                    })
                    ->editColumn('type', function ($row) {
                        return 'Private Session';
                    })
                    ->make(true);
            }
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Coin Transaction History';
            $return['general_settings'] = ['selected_nav' => ['coin_transaction_history', 'nav_transaction_history']];
            $return['follower'] = User::where('role', 3)->get();
            $return['model'] = User::where('role', 2)->get();
            return $return;
        }
    }

    public function exportMoneyTransaction(Request $request, $user, $type)
    {

        // dd($user);
        // return (new MoneyTransactionExport)->download('money-transaction-'.date('Y-m-d H:i:s').'.xlsx');
        return Excel::download(new MoneyTransactionExport($user, $type), 'money-transaction-' . date('Y-m-d-H-i-s') . '.xlsx');
    }

    public function exportCoinTransaction(Request $request, $model, $follower, $type)
    {

        // dd($user);
        // return (new MoneyTransactionExport)->download('money-transaction-'.date('Y-m-d H:i:s').'.xlsx');
        return Excel::download(new CoinTransactionExport($model, $follower, $type), 'coin-transaction-' . date('Y-m-d-H-i-s') . '.xlsx');
    }
    public function exportSalesReport(Request $request)
    {
        if ($request->type == 'date_wise') {
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
        }
        if ($request->type == 'product_wise') {
            $validator = Validator::make($request->all(), [
                'product' => 'required',
            ]);
        }
        if ($request->type == 'model_wise') {
            $validator = Validator::make($request->all(), [
                'model' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $data = $request->all();
            return Excel::download(new SalesReport($data), 'sales-report-' . date('Y-m-d-H-i-s') . '.xlsx');
        }
    }
    public function dashboard($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Dashboard';
        $return['general_settings'] = ['selected_nav' => ['nav_dashboard']];
        $return['total_model'] = User::where('role', 2)->get()->count();
        $return['total_follower'] = User::where('role', 3)->get()->count();
        $return['total_product'] = Product::all()->count();
        $return['latest_model'] = User::where('role', 2)->orderBy('id', 'desc')->limit(8)->get();
        $return['latest_follower'] = User::where('role', 3)->orderBy('id', 'desc')->limit(8)->get();
        $return['latest_product'] = Product::orderBy('id', 'desc')->limit(3)->get();
        $return['latest_contact_us'] = ContactUS::orderBy('id', 'desc')->limit(7)->get();
        $return['latest_ticket'] = Ticket::with('created_by_dtl')->orderBy('id', 'desc')->limit(7)->get();
        // dd($return);
        return $return;
    }

    public function ticket_chat($request)
    {
        $id = decrypt($request->id);
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Ticket Chat';
        $return['general_settings'] = ['selected_nav' => ['nav_ticket']];
        $return['ticket_dtl'] = Ticket::with('created_by_dtl', 'ticket_chat', 'ticket_chat.created_by_dtl')->find($id);
        // dd($return);
        return $return;
    }
    public function send_ticket_message($request)
    {
        // dd('hi');
        TicketChat::create([
            'ticket_id' => $request->ticket_id,
            'message' => $request->ticket_message,
            'created_by' => \Auth::user()->id,
        ]);
        return redirect()->back();
        // dd($request->all());
    }
    public function close_ticket($request)
    {
        // dd($request->all());
        if ($request->id) {
            Ticket::find($request->id)->update([
                'status' => 0
            ]);
        }
        echo json_encode(['success' => 1]);
    }
    public function ticket($request)
    {
        if ($request->ajax()) {
            // dd($request->type);
            $data = Ticket::all();
            // $data = [['name' => 'abc', 'email' => 'vvv'], ['name' => 'abcd', 'email' => 'vvvd']];
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('ticket_id', function ($row) {
                    return $row->auto_gen_id;
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('description', function ($row) {
                    return $row->description;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->created_by_dtl->full_name;
                })
                ->editColumn('created_date', function ($row) {
                    return date('d/m/Y H:i', strtotime($row->created_at));
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 0 ? 'Closed' : 'open';
                })
                ->editColumn('actions', function ($row) {
                    return '<div class="dashboard-btn-list-wrap"><ul><li><a href="' . url('admin/ticket_chat?id=' . encrypt($row->id)) . '" class="btn btn-primary"><i
                        class="fa fa-eye"></i></a></li></ul></div>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        } else {
            $return = array();
            $return['meta_data'] = array();
            $user_id = $this->user_data['id'];
            $return['page_title'] = 'Ticket List';
            $return['general_settings'] = ['selected_nav' => ['nav_ticket']];
            return $return;
        }
    }
    public function vip_member($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $search = trim($request->input('s'));
        $status = $request->input('status');
        $cur_page = $request->input('pg');
        $cur_page = $cur_page == '' ? 1 : $cur_page;
        $per_page = 20;
        $limit_start = ($cur_page - 1) * $per_page;
        $order_by = [];
        $params = ['role' => [2], 'search' => $search, 'cur_page' => $cur_page, 'per_page' => $per_page];
        $params['status'] = $status;
        $vip_member = User::get_list($params);
        $total_vip_members = $vip_member['total_data'];
        $vip_members = $vip_member['data'];
        $return['vip_members'] = $vip_members;
        $return['countries'] = Country::orderBy('name')->get();
        $return['pagination'] = array('per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $total_vip_members, 'page_url' => url('/admin/vip_member'), 'additional_params' => '&status=' . $status . '&s=' . $search);
        $return['page_title'] = 'VIP Member';
        $return['general_settings'] = ['selected_nav' => ['nav_vip_member']];
        return $return;
    }

    public function coin_plans($request)
    {
        if ($request->has('coin_plan_id') && $request->isMethod('post')) {
            $coin_plan_id = $request->input('coin_plan_id');
            $validate_rules = array();
            if (count($validate_rules) > 0)
                request()->validate($validate_rules);
            $title = trim($request->input('title'));
            $info = trim($request->input('info'));
            $coins = trim($request->input('coins'));
            $price = trim($request->input('price'));
            $coin_plan_id = trim($request->input('coin_plan_id'));
            if ($coin_plan_id == 0) {
                Coin_price_plan::create(['title' => $title, 'info' => $info, 'coins' => $coins, 'price' => $price, 'active' => 1]);
                $request->session()->flash('success', 'Coin plan successfully created');
                return redirect('admin/coin_plans?form');
            } else {
                Coin_price_plan::where(['id' => $coin_plan_id])->update(['title' => $title, 'info' => $info, 'coins' => $coins, 'price' => $price]);
                $request->session()->flash('success', 'Coin plan successfully updated');
                return redirect('admin/coin_plans');
            }
        }
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $coin_plan_id = $request->input('form');
        $coin_plan_id = $coin_plan_id == '' ? 0 : $coin_plan_id;
        $coin_plan = Coin_price_plan::find($coin_plan_id);
        $coin_plan_form = ['item' => $coin_plan];
        $cur_page = $request->input('pg');
        $cur_page = $cur_page == '' ? 1 : $cur_page;
        $per_page = 20;
        $limit_start = ($cur_page - 1) * $per_page;
        $coin_plans = Coin_price_plan::orderBy('price', 'asc')->limit($per_page)->offset($limit_start)->get();
        $total_coin_plans = Coin_price_plan::count();
        $return['coin_plans'] = $coin_plans;
        $return['coin_plan_form'] = $coin_plan_form;
        $return['pagination'] = array('per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $total_coin_plans, 'page_url' => url('/admin/coin_plans'), 'additional_params' => '');
        $return['page_title'] = 'Coin Plans';
        $return['general_settings'] = ['selected_nav' => ['nav_coin_plans']];
        return $return;
    }

    public function payouts($request)
    {
        if ($request->has('payout_id') && $request->isMethod('post')) {
            $payout_id = $request->input('payout_id');
            $transaction_id = trim($request->input('transaction_id'));
            $status = trim($request->input('status'));
            $time = time();
            if ($payout_id > 0) {
                $payout = User_payout::find($payout_id);
                $payout->transaction_id = $transaction_id;
                $payout->status = $status;
                $payout->save();
                $request->session()->flash('success', 'Payout successfully updated');
                return redirect('admin/payouts');
            }
        }
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $payout_id = $request->input('form');
        $payout_id = $payout_id == '' ? 0 : $payout_id;
        $payout = User_payout::select('user_payouts.*', 'u.first_name', 'u.last_name', 'u.role')->leftJoin('users as u', 'u.id', 'user_payouts.user_id')->where('user_payouts.id', $payout_id)->first();
        $payout_form = ['item' => $payout];
        $cur_page = $request->input('pg');
        $cur_page = $cur_page == '' ? 1 : $cur_page;
        $per_page = 50;
        $limit_start = ($cur_page - 1) * $per_page;
        $total_payouts = User_payout::count();
        $payouts = User_payout::select('user_payouts.*', 'u.first_name', 'u.last_name', 'u.role')->leftJoin('users as u', 'u.id', 'user_payouts.user_id')->orderBy('user_payouts.status')->orderBy('user_payouts.created_at', 'desc')->limit($per_page)->offset($limit_start)->get();
        $return['payouts'] = $payouts;
        $return['payout_form'] = $payout_form;
        $return['pagination'] = array('per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $total_payouts, 'page_url' => url('/admin/payouts'), 'additional_params' => '');
        $return['page_title'] = 'Payouts';
        $return['general_settings'] = ['selected_nav' => ['nav_payouts']];
        AdminNotification::where('seen', 0)->where('type', 'payout')->update([
            'seen' => 1
        ]);
        return $return;
    }

    public function reported_items($request)
    {
        $return = array();
        $return['meta_data'] = array();
        $user_id = $this->user_data['id'];
        $cur_page = $request->input('pg');
        $cur_page = $cur_page == '' ? 1 : $cur_page;
        $per_page = 20;
        $limit_start = ($cur_page - 1) * $per_page;
        $reported_items = Reported_item::select('reported_items.*', 'u.first_name', 'u.last_name', 'u.email')->leftJoin('users as u', 'u.id', 'reported_items.user_id')->orderBy('reported_items.created_at', 'desc')->limit($per_page)->offset($limit_start)->get();
        $type_1_ids = $type_2_ids = [];
        foreach ($reported_items as $key => $value) {
            if ($value->type == '1') $type_1_ids[] = $value->item_id;
            if ($value->type == '2') $type_2_ids[] = $value->item_id;
        }
        $reported_posts = $reported_users = [];
        if (count($type_1_ids) > 0) {
            $reported_users2 = User::whereIn('id', $type_1_ids)->get();
            foreach ($reported_users2 as $key => $value) {
                $reported_users[$value->id] = $value;
            }
        }
        if (count($type_2_ids) > 0) {
            $reported_posts2 = Post::whereIn('id', $type_2_ids)->get();
            foreach ($reported_posts2 as $key => $value) {
                $reported_posts[$value->id] = $value;
            }
        }
        foreach ($reported_items as $key => $value) {
            if ($value->type == '1') $reported_items[$key]->item_data = $reported_users[$value->item_id] ?? new \stdClass;
            if ($value->type == '2') $reported_items[$key]->item_data = $reported_posts[$value->item_id] ?? new \stdClass;
        }
        $total_reported_items = Reported_item::count();
        $return['reported_items'] = $reported_items;
        $return['pagination'] = array('per_page' => $per_page, 'cur_page' => $cur_page, 'total_data' => $total_reported_items, 'page_url' => url('/admin/reported_items'), 'additional_params' => '');
        $return['page_title'] = 'Reported Items';
        $return['general_settings'] = ['selected_nav' => ['nav_reported_items']];
        return $return;
    }

    public function settings($request)
    {
        $return = array();
        $return['data'] = array('settings' => []);
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Settings';
        $return['general_settings'] = ['selected_nav' => ['nav_settings']];
        $settings = Setting::all();
        foreach ($settings as $value) {
            $return['data']['settings'][$value->key] = $value->value;
        }
        $return['data']['countries'] = Country::orderBy('name')->get();
        return $return;
    }

    public function account($request)
    {
        if ($request->has('current_password') && $request->isMethod('post')) {
            $user_data = $this->user_data;
            $current_password = $request->input('current_password');
            $new_password = $request->input('new_password');
            $user = User::find($user_data['id']);
            if (Hash::check($current_password, $user->password)) {
                $user->password = Hash::make($new_password);
                $user->save();
                /*$user_meta = new User_meta;
                $user_meta->update_usermeta($user_data['id'], ['password' => $new_password]);*/
                $request->session()->flash('success', 'Password successfully updated');
                return redirect('admin/account');
            } else {
                $request->session()->flash('error', 'Current password mismatch');
                return redirect('admin/account');
            }
        }
        $return = array();
        $return['data'] = array('settings' => []);
        $user_id = $this->user_data['id'];
        $return['page_title'] = 'Account';
        $return['general_settings'] = ['selected_nav' => ['nav_account']];
        return $return;
    }
}
