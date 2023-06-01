<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;

use App\Live_session_tip;
use App\Order;
use App\Post_paid_user;
use App\Post_tip;
use App\PrivateChat;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
class CoinTransactionExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($model,$follower,$type)
    {
        $this->model=$model;
        $this->follower=$follower;
        $this->type=$type;
    }
    public function view(): View
    {
        if ($this->type == "order") {
            $data = new Order;
            $data = $data->join('users as model', 'model.id', '=', 'orders.vip_member_id');
            $data = $data->join('users as follower', 'follower.id', '=', 'orders.user_id');
            if ($this->model != '' && $this->model != 'all') {
                $data = $data->where('vip_member_id', $this->model);
            }
            if ($this->follower != '' && $this->follower != 'all') {
                $data = $data->where('user_id', $this->follower);
            }
            $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'orders.total_amount as token_coins', 'orders.status as status', 'orders.created_at as created_at');
            $data = $data->get();
            $display_type = 'Product Order';

        }
        if ($this->type == "post_tips") {
            $data = new Post_tip;
            $data = $data->join('user_earnings', 'post_tips.id', '=', 'user_earnings.post_tips_id');
            $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
            $data = $data->join('users as follower', 'follower.id', '=', 'post_tips.tipper_id');
            if ($this->model != '' && $this->model != 'all') {
                $data = $data->where('user_earnings.user_id', $this->model);
            }
            if ($this->follower != '' && $this->follower != 'all') {
                $data = $data->where('post_tips.tipper_id', $this->follower);
            }
            $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'post_tips.created_at as created_at');
            $data = $data->get();
            $display_type = 'Post Tips';

        }
        if ($this->type == "post_unlock") {
            $data = new Post_paid_user;
            $data = $data->join('user_earnings', 'post_paid_users.id', '=', 'user_earnings.post_paid_users_id');
            $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
            $data = $data->join('users as follower', 'follower.id', '=', 'post_paid_users.user_id');
            if ($this->model != '' && $this->model != 'all') {
                $data = $data->where('user_earnings.user_id', $this->model);
            }
            if ($this->follower != '' && $this->follower != 'all') {
                $data = $data->where('post_paid_users.user_id', $this->follower);
            }
            $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'post_paid_users.created_at as created_at');
            $data = $data->get();
            $display_type = 'Post Unlock';


        }
        if ($this->type == "chat_tips") {
            $data = new Live_session_tip;
            $data = $data->join('user_earnings', 'live_session_tips.id', '=', 'user_earnings.live_session_tips_id');
            $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
            $data = $data->join('users as follower', 'follower.id', '=', 'live_session_tips.tipper_id');
            if ($this->model != '' && $this->model != 'all') {
                $data = $data->where('user_earnings.user_id', $this->model);
            }
            if ($this->follower != '' && $this->follower != 'all') {
                $data = $data->where('live_session_tips.tipper_id', $this->follower);
            }
            $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'live_session_tips.created_at as created_at');
            $data = $data->get();
            $display_type = 'Chat Tips';


        }
        if ($this->type == "private_chat") {
            $data = new PrivateChat;
            $data = $data->join('user_earnings', 'private_chat.id', '=', 'user_earnings.private_chat_id');
            $data = $data->join('users as model', 'model.id', '=', 'user_earnings.user_id');
            $data = $data->join('users as follower', 'follower.id', '=', 'private_chat.follower_id');
            if ($this->model != '' && $this->model != 'all') {
                $data = $data->where('user_earnings.user_id', $this->model);
            }
            if ($this->follower != '' && $this->follower != 'all') {
                $data = $data->where('private_chat.follower_id', $this->follower);
            }
            $data = $data->select('model.first_name as model_first_name', 'model.last_name as model_last_name', 'follower.first_name as follower_first_name', 'follower.last_name as follower_last_name', 'user_earnings.token_coins as token_coins', 'private_chat.created_at as created_at');
            $data = $data->get();
            $display_type = 'Private Session';

        }
        // dd($data);
        return view('admin.export.coin_transaction', [
            'data' => $data,
            'type' =>$this->type,
            'display_type'=>$display_type,
        ]);
    }
}
