<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\User;
use App\Post;
use App\User_meta;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Notification extends Model
{
    //
    public $timestamps = false;
    protected $table = 'notifications';
    protected $fillable = ['user_id', 'object_type', 'object_id', 'action', 'message', 'json_data', 'seen', 'created_at','deleted_at'];

    /*
    object_type + action
    ---------------------------
    'post' = all about post
    	'create' = new post created
    	'paid' = a user paid to a post
      'tip' = a user gave tip to a post
    	'react' = user reacted on a post
      'comment' = user commented on a post
      'commentReply' = user replied on a comment
      'commentReact' = user reacted on a comment

    'user' = user on user profile action
        //'follow' = someone follow to someone
        'subscriber' = someone subscribed to someone
        'subscriber_message' =  model send message to subcribers
        //'subscription' = alerts about existing subscription
        //'tip' = someone tipped to someone

    'order' = user on order action
        'create' = user placed an order
    'live_session' = user on live session action
        'tip' = user gave tip against an live session

    */

	public static function get_friend_chat($friend_user_id){
		$user_id = Auth::user()->id;

		$user = User::find($friend_user_id);
		$user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $friend_user_id)->pluck('value')->first();
		$user_img=url('/public/front/images/user-placeholder.jpg');
		if($user_profile_photo != ''){
			$user_img=url('public/uploads/profile_photo/' . $user_profile_photo);
		}
		$user_title = $user->first_name . ' ' . $user->last_name;

		$messages=self::where('action','sendMessageToSubscriber')->where(function ($query) use ($user_id, $friend_user_id) {
			$query->where('user_id', '=', $user_id)->where('object_id', '=', $friend_user_id);
			})->orWhere(function ($query) use ($user_id, $friend_user_id) {
				$query->where('user_id', '=', $friend_user_id)->where('object_id', '=', $user_id);
			})->orderBy('id', 'asc')->get();

		$data=[];
		if(count($messages)>0){
			foreach($messages as $row){
				$is_me='N';
				if($row->object_id==$user_id){
					$is_me='Y';
				}

				$data[]=array(
					'id'		=>$row->id,
					'from_id'	=> $row->user_id,
					'to_id'		=> $row->object_id,
					'is_me'		=> $is_me,
					'msg'		=> $row->message,
					'seen'		=> $row->seen,
					'action'	=> $row->action,
					'time'		=> Carbon::parse($row->created_at)->format('H:i a'),
				);
			}
		}



		$result = array(
			'statusCode'	=> '200',
			'result'		=> $data,
			'message'		=> 'Success'
		);




		return response()->json($result);
	}


	public static function get_friend_list($params = []){
		$user_id = $params['user_id'] ?? '';
		$friend_result = DB::select(DB::raw("SELECT DISTINCT object_id FROM notifications WHERE user_id = '" . (int)$user_id. "' AND object_type='user' ORDER BY `id` DESC"));

		$friend_result2 = DB::select(DB::raw("SELECT DISTINCT user_id FROM notifications WHERE object_id = '" . (int)$user_id. "'  AND object_type='user' ORDER BY `id` DESC"));

		$last_result = DB::select(DB::raw("SELECT * FROM notifications WHERE (user_id = '" . (int)$user_id. "' OR object_type = '" . (int)$user_id. "') AND object_type='user' ORDER BY `id` DESC limit 1"));

		$active_user_id='';
		if(isset($last_result[0]->id)){
			if($last_result[0]->id!=''){
				$active_user_id=$last_result[0]->user_id;
				if($last_result[0]->user_id==$user_id){
					$active_user_id=$last_result[0]->object_id;
				}
			}
		}

		$friends_ids=[];

		$friends_id_arr1=[];
		$friends_id_arr2=[];

		foreach($friend_result as $row){
			$friends_id_arr1[]=$row->object_id;
		}
		foreach($friend_result2 as $row){
			$friends_id_arr2[]=$row->user_id;
		}

		if(count($friends_id_arr1) > 0 && count($friends_id_arr2) > 0){
			$result = array_intersect($friends_id_arr1, $friends_id_arr2);
		}else if(count($friends_id_arr1) > 0){
			$result = $friends_id_arr1;
		}else if(count($friends_id_arr2) > 0){
			$result = $friends_id_arr2;
		}



		if($active_user_id!=''){
			$result = array_diff($result, [$active_user_id]);

			$user = User::find($active_user_id);
			$user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $active_user_id)->pluck('value')->first();

			$user_img=url('/public/front/images/user-placeholder.jpg');
			if($user_profile_photo != ''){
				$user_img=url('public/uploads/profile_photo/' . $user_profile_photo);
			}
			$user_title = $user->first_name . ' ' . $user->last_name;

			$friends_ids[]=array(
				'user_id'	=> $active_user_id,
				'name_name'	=> $user_title,
				'user_img'	=> $user_img,
                'username' => $user->username
			);

		}
    if(!empty($result)){
      foreach($result as $key=>$val){

        $user = User::find($val);
        $user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $val)->pluck('value')->first();
  
        $user_img=url('/public/front/images/user-placeholder.jpg');
        if($user_profile_photo != ''){
          $user_img=url('public/uploads/profile_photo/' . $user_profile_photo);
        }
        $user_title = $user->first_name . ' ' . $user->last_name;
  
        $friends_ids[]=array(
          'user_id'	=> $val,
          'name_name'	=> $user_title,
          'user_img'	=> $user_img
        );
  
  
  
      }
    }
		

		//print_r($friends_ids);exit;

		return $friends_ids;

	}

    public static function get_list($params = []) {
      $user_id = $params['user_id'] ?? '';
      $notifmark_id = $params['notifmark_id'] ?? '';
      $object_types = $params['object_types'] ?? [];
      $actions = $params['actions'] ?? [];
      $object_actions = $params['object_actions'] ?? [];
      $per_page = $params['per_page'] ?? 20;
      $object_q = 'CASE
          WHEN notifications.object_type in ("post") THEN (select post_content from posts where id = notifications.object_id)
          WHEN notifications.object_type in ("user") THEN (select concat(first_name, " ", last_name) from users where id = notifications.object_id)
          ELSE ""
      END as object_title,
      "" as object_slug';
      /*$object_q = 'CASE
          WHEN notifications.object_type in ("articleEdit", "articleNewPendingEdit", "articleUpdatePendingEdit") THEN (select title from articles where id = notifications.object_id)
          WHEN notifications.object_type in ("friendUser") THEN (select name from users where id = notifications.object_id)
          ELSE ""
      END as object_title,
      CASE
          WHEN notifications.object_type in ("articleEdit", "articleNewPendingEdit", "articleUpdatePendingEdit") THEN (select slug from articles where id = notifications.object_id)
          WHEN notifications.object_type in ("friendUser") THEN (select username from users where id = notifications.object_id)
          ELSE ""
      END as object_slug';*/
      $notifications = self::select('notifications.*', DB::raw($object_q))->where('notifications.user_id', $user_id);
      if(count($object_types) > 0)
        $notifications->whereIn('notifications.object_type', $object_types);
      if(count($actions) > 0)
        $notifications->whereIn('notifications.action', $actions);
      if(count($object_actions) > 0) {
        $notifications->where(function($q) use ($object_actions){
          foreach ($object_actions as $k => $v) {
            $q->orWhere(function($q2) use ($v){
              $q2->where('notifications.object_type', $v['object_type'])->where('notifications.action', $v['action']);
            });
          }
        });
      }
      if($notifmark_id != '')
        $notifications->where('notifications.id', '<', $notifmark_id);
      $total_data = $notifications->count();
      //$notifications = $notifications->orderBy('notifications.created_at', 'desc')->offset(0)->limit($per_page)->get();
      $notifications = $notifications->orderBy('notifications.id', 'desc')->offset(0)->limit($per_page)->get();
      foreach ($notifications as $key => $value) {
        $json_data = json_decode($value->json_data, true);
        $object_permalink = '';
        $meta_data = [];
        if(in_array($value->object_type, ['post'])) {
          $post = Post::find($value->object_id);
          if(isset($post->user_id)){
            $post->author = User::find($post->user_id);
            $author_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $post->user_id)->pluck('value')->first();
            if($author_profile_photo == '')
              $post->author_profile_photo = url('/public/front/images/user-placeholder.jpg');
            else
              $post->author_profile_photo = url('public/uploads/profile_photo/' . $author_profile_photo);
            $object_permalink = url('dashboard/post?post_id=' . $value->object_id);
            $meta_data['post'] = $post;
          }
        }
        if(isset($json_data['user_id'])) {
          $user = User::find($json_data['user_id']);
          $user_profile_photo = User_meta::where('key', 'profile_photo')->where('user_id', $json_data['user_id'])->pluck('value')->first();
          if($user_profile_photo == '')
            $user->profile_photo = url('/public/front/images/user-placeholder.jpg');
          else
            $user->profile_photo = url('public/uploads/profile_photo/' . $user_profile_photo);
          $meta_data['user'] = $user;
        }
        /*if(in_array($value->object_type, ['articleEdit', 'articleNewPendingEdit', 'articleUpdatePendingEdit'])) {
          $object_permalink = route('article.show', ['slug' => $value->object_slug, 'article' => $value->object_id]);
          $meta_data['point_level_data'] = User::get_point_level_data(['user_id' => 1]);
        }
        if(in_array($value->object_type, ['friendUser'])) {
          $friend = User::find($value->object_id);
          $object_permalink = url('/author/' . $value->object_slug);
          $meta_data['friend_profile_image'] = $friend->profileImage();
          $meta_data['point_level_data'] = User::get_point_level_data(['user_id' => $value->object_id]);
        }
		  if(in_array($value->object_type, ['chatStatus'])) {
          $friend = User::find($value->object_id);
          $object_permalink = url('/author/' . $value->object_slug);
          $meta_data['friend_profile_image'] = $friend->profileImage();
          $meta_data['point_level_data'] = User::get_point_level_data(['user_id' => $value->object_id]);
        }*/
        $notifications[$key]->json_data = json_decode($value->json_data, true);
        $notifications[$key]->object_permalink = $object_permalink;
        $notifications[$key]->meta_data = $meta_data;
      }
      $return = ['data' => $notifications, 'total_data' => $total_data];
      return $return;
    }

    public static function get_notification_html($params) {
      $notification = $params['notification'];
      $html = '';
      $user_img = $user_title = $notification_text = '';
      if(in_array($notification->object_type, ['post'])) {
        if(in_array($notification->action, ['create'])) {
          $user_img = @$notification->meta_data['post']->author_profile_photo;
          $user_title = @$notification->meta_data['post']->author->display_name;
          $notification_text = 'Created a post';
        }
        if(in_array($notification->action, ['paid', 'tip', 'react', 'comment', 'commentReact', 'commentReply'])) {
          $user_img = $notification->meta_data['user']->profile_photo;
          $user_title = $notification->meta_data['user']->first_name . ' ' . $notification->meta_data['user']->last_name;
        }
        if($notification->action == 'paid')
          $notification_text = 'Paid to unlock your post';
        if($notification->action == 'tip')
          $notification_text = 'Gave Tipped ' . $notification->json_data['token_coin'] . ' ' . ($notification->json_data['token_coin'] == 1 ? 'coin' : 'coins') . ' to your post';
        if($notification->action == 'react')
          $notification_text = 'Reacted on your post';
        if($notification->action == 'comment')
          $notification_text = 'Commented on your post';
        if($notification->action == 'commentReact')
          $notification_text = 'Reacted on your comment';
        if($notification->action == 'commentReply')
          $notification_text = 'Replied on your comment';
      }

      if(in_array($notification->object_type, ['user'])) {
        if(in_array($notification->action, ['subscriber'])) {
          $user_img = $notification->meta_data['user']->profile_photo;
          $user_title = $notification->meta_data['user']->first_name . ' ' . $notification->meta_data['user']->last_name;
        }
        if(in_array($notification->action, ['sendMessageToSubscriber'])) {
            $user_img = $notification->meta_data['user']->profile_photo;
            $user_title = $notification->meta_data['user']->first_name . ' ' . $notification->meta_data['user']->last_name;
            $notification_text = $notification->message;
          }
        if($notification->action == 'subscriber') {
          $notification_text = 'subscribed for';
          if($notification->json_data['duration'] == '1m') $notification_text .= ' 1 month';
          if($notification->json_data['duration'] == '3m') $notification_text .= ' 3 months';
          if($notification->json_data['duration'] == '6m') $notification_text .= ' 6 months';
          if($notification->json_data['duration'] == '12m') $notification_text .= ' 12 months';
        }
      }
      if(in_array($notification->object_type, ['order'])) {
        if(in_array($notification->action, ['create'])) {
          $user_img = $notification->meta_data['user']->profile_photo;
          $user_title = $notification->meta_data['user']->first_name . ' ' . $notification->meta_data['user']->last_name;
        }
        if($notification->action == 'create') {
          $total_amount = $notification->json_data['total_amount'];
          $notification_text = 'placed an order of ' . $total_amount . ' ' . ($total_amount > 1 ? 'coins' : 'coin');
        }
      }
      if(in_array($notification->object_type, ['live_session'])) {
        if(in_array($notification->action, ['tip'])) {
          $user_img = $notification->meta_data['user']->profile_photo;
          $user_title = $notification->meta_data['user']->first_name . ' ' . $notification->meta_data['user']->last_name;
        }
        if($notification->action == 'tip')
          $notification_text = 'Gave Tipped ' . $notification->json_data['token_coin'] . ' ' . ($notification->json_data['token_coin'] == 1 ? 'coin' : 'coins') . ' to your live session';
      }

	  //echo '<pre>';print_r($notification);exit;


      $html .= '<div class="notification-wrap-box  notification_item ' . ($notification->seen == '1' ? 'seen' : '') .'" notification_id="' . $notification->id . '" >
          <a href="' . ($notification->object_permalink == '' ? 'javascript:;' : $notification->object_permalink) . '">
              <div class="notification-user-img">
                  <span><img src="' . $user_img . '" alt=""></span>
              </div>
              <div class="notification-details-wrap">
			   <div class="d-flex justify-content-between align-items-center">
			   <div class="notification-user-details-left">
                  <div class="notification-user-details d-flex">
                      <h3 class="user_title">' . $user_title . '<!--  <span><i class="ti-heart"></i></span> --></h3>
                      <!-- <p>@Bikiniwarrior</p> -->
                  </div>
                  <div class="notification-text">
                      <p>' . $notification_text . '</p>
                      <!-- <span class="nofication-date"><time class="timeago" datetime="' . (str_replace(' ', 'T', $notification->created_at) . 'Z') . '"></time></span> -->
                      <span class="nofication-date">'.$notification->created_at.'</span>
                  </div></div>';

				   if($notification->action=='sendMessageToSubscriber'){
					  $html .= '<div class="comment_reply-sec-right"><button class="comment_reply '.$notification->action.'" data-user_id='.$notification->object_id.' data-user_title='.$user_title.'><i class="fas fa-comment-alt"></i>Send Message</button></div>';
				  }

				  $html .= '</div>
              </div>
          </a>
      </div>';
      return ['html' => $html];
    }

}
