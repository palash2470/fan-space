<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User_meta;
use App\Country;
use App\Post_react;
use App\Post_comment;
use App\Post_comment_react;
use App\Subscribers;
use App\User_follow_user;

class Post extends Model
{
  //
  //public $timestamps = false;

  protected $table = 'posts';

  protected $fillable = ['user_id', 'post_head', 'post_content', 'post_type', 'visibility', 'visibility_except', 'price', 'media_file', 'media_thumbnail_file', 'status'];

  public static function get_own_posts($param = [])
  {
    $user_id = $param['user_id'] ?? '1';
    $cur_page = $param['cur_page'] ?? '1';
    $per_page = $param['per_page'] ?? 10;
    $return_all = $param['return_all'] ?? false;
    $order_by = $param['order_by'] ?? [["posts.created_at", 'desc']];
    $limit_start = ($cur_page - 1) * $per_page;
    $countries = Country::all();
    $country_data = [];
    foreach ($countries as $key => $value) {
      $country_data[$value->country_id] = $value;
    }
    $posts = self::select('posts.*', 'u.first_name as author_first_name', 'u.last_name as author_last_name', 'u.username as author_username', 'u.display_name as author_display_name', DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id and pr.user_id = ' . $user_id . ') as my_react'), DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id) as total_reacts'), DB::raw('(select count(pc.id) from post_comments as pc where pc.post_id = posts.id) as total_comments'), DB::raw('(select count(ppu.id) from post_paid_users as ppu where ppu.post_id = posts.id and ppu.user_id = ' . $user_id . ') as post_paid'))->leftJoin('users as u', 'u.id', 'posts.user_id')->where('posts.user_id', $user_id)->where('posts.status', '1');
    foreach ($order_by as $key => $value) {
      if (isset($value[2]) && $value[2] == '1') {
        $posts = $posts->orderBy(DB::raw($value[0]), $value[1]);
      } else {
        $posts = $posts->orderBy($value[0], $value[1]);
      }
    }
    $total_posts = $posts->count();
    if ($return_all != true)
      $posts = $posts->offset($limit_start)->limit($per_page);
    $posts = $posts->get();
    $visibility_except_users = $authors = [];
    foreach ($posts as $key => $value) {
      $authors[] = $value->user_id;
      if ($value->visibility_except != '')
        $visibility_except_users = array_merge($visibility_except_users, explode(',', $value->visibility_except));
    }
    $visibility_except_users = array_values(array_unique($visibility_except_users));
    $veu = [];
    if (count($visibility_except_users) > 0) {
      $visibility_except_users = User::whereIn('id', $visibility_except_users)->get();
      foreach ($visibility_except_users as $key => $value) {
        $veu[$value->id] = $value;
      }
    }
    $authors = array_values(array_unique(array_filter($authors)));
    $authors_metadata = [];
    $usermeta = User_meta::whereIn('user_id', $authors)->get();
    foreach ($usermeta as $key => $value) {
      if (!isset($authors_metadata[$value->user_id]))
        $authors_metadata[$value->user_id] = [];
      $authors_metadata[$value->user_id][$value->key] = $value->value;
    }
    foreach ($posts as $key => $value) {
      $author_meta = $authors_metadata[$value->user_id];
      if (isset($author_meta['country_id'])) {
        $author_meta['country_data'] = $country_data[$author_meta['country_id']];
      }
      $posts[$key]->author_meta = $author_meta;
      $temp_veu = [];
      if ($value->visibility_except != '') {
        $vexu = explode(',', $value->visibility_except);
        $vexu = array_filter($vexu);
        foreach ($vexu as $k => $v) {
          $temp_veu[] = $veu[$v];
        }
      }
      $posts[$key]->visibility_except_users = $temp_veu;
    }
    $return = ['total_data' => $total_posts, 'data' => $posts];
    return $return;
  }

  public static function get_profile_posts($param = [])
  {
    $user_id = $param['user_id'] ?? '0';
    $post_ids = $param['post_ids'] ?? [];
    $logged_user_id = $param['logged_user_id'] ?? '';
    $post_type = $param['post_type'] ?? [];
    $age = $param['age'] ?? '';
    $ordby = $param['ordby'] ?? '';
    $ord = $param['ord'] ?? '';
    $cur_page = $param['cur_page'] ?? '1';
    $per_page = $param['per_page'] ?? 10;
    $return_all = $param['return_all'] ?? false;
    //$order_by = $param['order_by'] ?? [["posts.created_at", 'desc']];
    $order_by = [];
    if (!in_array($age, ['3m', '1m', '1w'])) $age = '';
    if (!in_array($ordby, ['latest', 'liked', 'tip'])) $ordby = 'latest';
    if (!in_array($ord, ['asc', 'desc'])) $ord = 'desc';
    if ($ordby == 'latest') $order_by[0] = ['posts.created_at', $ord];
    $limit_start = ($cur_page - 1) * $per_page;
    //   if($logged_user_id == '')
    //     return ['total_data' => 0, 'data' => []];
    $logged_subscriber = Subscriber::where('user_id', $user_id)->where('subscriber_id', $logged_user_id)->count();
    $countries = Country::all();
    $country_data = [];
    foreach ($countries as $key => $value) {
      $country_data[$value->country_id] = $value;
    }
    $posts = self::select('posts.*', 'u.first_name as author_first_name', 'u.last_name as author_last_name', 'u.username as author_username', 'u.display_name as author_display_name', DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id and pr.user_id = ' . $logged_user_id . ') as my_react'), DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id) as total_reacts'), DB::raw('(select count(pc.id) from post_comments as pc where pc.post_id = posts.id) as total_comments'), DB::raw('(select count(ppu.id) from post_paid_users as ppu where ppu.post_id = posts.id and ppu.user_id = ' . $logged_user_id . ') as post_paid'))->leftJoin('users as u', 'u.id', 'posts.user_id')->where('posts.user_id', $user_id)->where('posts.status', '1');
    if (count($post_ids) > 0)
      $posts->whereIn('posts.id', $post_ids);
    if (count($post_type) > 0)
      $posts->whereIn('posts.post_type', $post_type);
    if ($logged_subscriber == 1) {
      $posts->where(function ($q) use ($logged_user_id) {
        $q->whereIn('posts.visibility', ['public', 'subscriber'])->orWhere(function ($q2) use ($logged_user_id) {
          $q2->whereIn('posts.visibility', ['subscriber_except'])->where(function ($q3) use ($logged_user_id) {
            $q3->where(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'), '0')->orWhereNull(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'));
          });
        });
      });
    } else {
      $posts->whereIn('posts.visibility', ['public']);
    }
    $age_start = $age_end = '';
    if ($age == '3m') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-3 month"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age == '1m') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-1 month"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age == '1w') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-1 week"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age != '') $posts->where('posts.created_at', '>=', $age_start)->where('posts.created_at', '<=', $age_end);
    foreach ($order_by as $key => $value) {
      if (isset($value[2]) && $value[2] == '1') {
        $posts = $posts->orderBy(DB::raw($value[0]), $value[1]);
      } else {
        $posts = $posts->orderBy($value[0], $value[1]);
      }
    }
    $total_posts = $posts->count();
    if ($return_all != true)
      $posts = $posts->offset($limit_start)->limit($per_page);
    $posts = $posts->get();
    $visibility_except_users = $authors = [];
    foreach ($posts as $key => $value) {
      $authors[] = $value->user_id;
      if ($value->visibility_except != '')
        $visibility_except_users = array_merge($visibility_except_users, explode(',', $value->visibility_except));
    }
    $visibility_except_users = array_values(array_unique($visibility_except_users));
    $veu = [];
    if (count($visibility_except_users) > 0) {
      $visibility_except_users = User::whereIn('id', $visibility_except_users)->get();
      foreach ($visibility_except_users as $key => $value) {
        $veu[$value->id] = $value;
      }
    }
    $authors = array_values(array_unique(array_filter($authors)));
    $authors_metadata = [];
    $usermeta = User_meta::whereIn('user_id', $authors)->get();
    foreach ($usermeta as $key => $value) {
      if (!isset($authors_metadata[$value->user_id]))
        $authors_metadata[$value->user_id] = [];
      $authors_metadata[$value->user_id][$value->key] = $value->value;
    }
    foreach ($posts as $key => $value) {
      $author_meta = $authors_metadata[$value->user_id];
      if (isset($author_meta['country_id'])) {
        $author_meta['country_data'] = $country_data[$author_meta['country_id']];
      }
      $posts[$key]->author_meta = $author_meta;
      $temp_veu = [];
      if ($value->visibility_except != '') {
        $vexu = explode(',', $value->visibility_except);
        $vexu = array_filter($vexu);
        foreach ($vexu as $k => $v) {
          $temp_veu[] = $veu[$v];
        }
      }
      $posts[$key]->visibility_except_users = $temp_veu;
    }
    $return = ['total_data' => $total_posts, 'data' => $posts];
    return $return;
  }

  public static function get_posts($param = [])
  {
    $user_ids = $param['user_ids'] ?? [];
    $post_ids = $param['post_ids'] ?? [];
    $logged_user_id = $param['logged_user_id'] ?? '';
    $favorite = $param['favorite'] ?? '0';
    $post_type = $param['post_type'] ?? [];
    $age = $param['age'] ?? '';
    $ordby = $param['ordby'] ?? '';
    $ord = $param['ord'] ?? '';
    $cur_page = $param['cur_page'] ?? '1';
    $per_page = $param['per_page'] ?? 10;
    $return_all = $param['return_all'] ?? false;
    //$order_by = $param['order_by'] ?? [["posts.created_at", 'desc']];
    $order_by = [];
    if (!in_array($age, ['3m', '1m', '1w'])) $age = '';
    if (!in_array($ordby, ['latest', 'liked', 'tip'])) $ordby = 'latest';
    if (!in_array($ord, ['asc', 'desc'])) $ord = 'desc';
    if ($ordby == 'latest') $order_by[0] = ['posts.created_at', $ord];
    $limit_start = ($cur_page - 1) * $per_page;
    if ($logged_user_id == '')
      return ['total_data' => 0, 'data' => []];
    $followed_ids = User_follow_user::where('user_id', $logged_user_id)->pluck('follow_user_id')->toArray();
    $subscribed_ids = Subscriber::where('subscriber_id', $logged_user_id)->pluck('user_id')->toArray();
    $countries = Country::all();
    $country_data = [];
    foreach ($countries as $key => $value) {
      $country_data[$value->country_id] = $value;
    }
    $posts = self::select('posts.*', 'u.first_name as author_first_name', 'u.last_name as author_last_name', 'u.username as author_username', 'u.display_name as author_display_name', DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id and pr.user_id = ' . $logged_user_id . ') as my_react'), DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id) as total_reacts'), DB::raw('(select count(pc.id) from post_comments as pc where pc.post_id = posts.id) as total_comments'), DB::raw('(select count(ppu.id) from post_paid_users as ppu where ppu.post_id = posts.id and ppu.user_id = ' . $logged_user_id . ') as post_paid'))->leftJoin('users as u', 'u.id', 'posts.user_id')->where('posts.status', '1');
    //$posts->whereIn('posts.user_id', (count($followed_ids) > 0 ? $followed_ids : [0]));
    $posts->whereIn('posts.user_id', array_merge([$logged_user_id], $followed_ids));
    if (count($post_type) > 0)
      $posts->whereIn('posts.post_type', $post_type);
    if (count($user_ids) > 0)
      $posts->whereIn('posts.user_id', $user_ids);
    if (count($post_ids) > 0)
      $posts->whereIn('posts.id', $post_ids);
    $posts->where(function ($q) use ($logged_user_id, $subscribed_ids) {
      $q->orWhere(function ($q2) use ($logged_user_id, $subscribed_ids) {
        $q2->whereIn('posts.visibility', ['public'])->whereNotIn('posts.user_id', (count($subscribed_ids) > 0 ? $subscribed_ids : [0]));
      })->orWhere(function ($q2) use ($logged_user_id, $subscribed_ids) {
        $q2->whereIn('posts.visibility', ['public', 'subscriber'])->whereIn('posts.user_id', (count($subscribed_ids) > 0 ? $subscribed_ids : [0]));
      })->orWhere(function ($q2) use ($logged_user_id, $subscribed_ids) {
        $q2->whereIn('posts.visibility', ['subscriber_except'])->whereIn('posts.user_id', (count($subscribed_ids) > 0 ? $subscribed_ids : [0]))->where(function ($q3) use ($logged_user_id) {
          $q3->where(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'), '0')->orWhereNull(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'));
        });
      })->orWhere(function ($q2) use ($logged_user_id) {
        $q2->where('posts.user_id', $logged_user_id);
      });
    });
    /*$posts->where(function($q) use ($logged_user_id){
        $q->whereIn('posts.visibility', ['public', 'subscriber'])->orWhere(function($q2) use ($logged_user_id){
          $q2->whereIn('posts.visibility', ['subscriber_except'])->where(function($q3) use ($logged_user_id){
            $q3->where(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'), '0')->orWhereNull(DB::raw('FIND_IN_SET(' . $logged_user_id . ', posts.visibility_except)'));
          });
        });
      });*/
    if ($favorite == 1) {
      $posts->where(DB::raw('(select count(pr.id) from post_reacts as pr where pr.post_id = posts.id and pr.user_id = ' . $logged_user_id . ')'), '1');
    }
    $age_start = $age_end = '';
    if ($age == '3m') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-3 month"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age == '1m') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-1 month"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age == '1w') {
      $age_start = date('Y-m-d 00:00:00', strtotime("-1 week"));
      $age_end = date('Y-m-d H:i:s');
    }
    if ($age != '') $posts->where('posts.created_at', '>=', $age_start)->where('posts.created_at', '<=', $age_end);
    foreach ($order_by as $key => $value) {
      if (isset($value[2]) && $value[2] == '1') {
        $posts = $posts->orderBy(DB::raw($value[0]), $value[1]);
      } else {
        $posts = $posts->orderBy($value[0], $value[1]);
      }
    }
    $total_posts = $posts->count();
    if ($return_all != true)
      $posts = $posts->offset($limit_start)->limit($per_page);
    $posts = $posts->get();
    $visibility_except_users = $authors = [];
    foreach ($posts as $key => $value) {
      $authors[] = $value->user_id;
      if ($value->visibility_except != '')
        $visibility_except_users = array_merge($visibility_except_users, explode(',', $value->visibility_except));
    }
    $visibility_except_users = array_values(array_unique($visibility_except_users));
    $veu = [];
    if (count($visibility_except_users) > 0) {
      $visibility_except_users = User::whereIn('id', $visibility_except_users)->get();
      foreach ($visibility_except_users as $key => $value) {
        $veu[$value->id] = $value;
      }
    }
    $authors = array_values(array_unique(array_filter($authors)));
    $authors_metadata = [];
    $usermeta = User_meta::whereIn('user_id', $authors)->get();
    foreach ($usermeta as $key => $value) {
      if (!isset($authors_metadata[$value->user_id]))
        $authors_metadata[$value->user_id] = [];
      $authors_metadata[$value->user_id][$value->key] = $value->value;
    }
    foreach ($posts as $key => $value) {
      $author_meta = $authors_metadata[$value->user_id];
      if (isset($author_meta['country_id'])) {
        $author_meta['country_data'] = $country_data[$author_meta['country_id']];
      }
      $posts[$key]->author_meta = $author_meta;
      $temp_veu = [];
      if ($value->visibility_except != '') {
        $vexu = explode(',', $value->visibility_except);
        $vexu = array_filter($vexu);
        foreach ($vexu as $k => $v) {
          $temp_veu[] = $veu[$v];
        }
      }
      $posts[$key]->visibility_except_users = $temp_veu;
    }

    $return = ['total_data' => $total_posts, 'data' => $posts];
    return $return;
  }

  public static function delete_post($param = [])
  {
    $post_id = $param['post_id'];
    $post = self::find($post_id);
    @unlink(public_path('uploads/post_media/' . $post->media_file));
    Post_react::where('post_id', $post_id)->delete();
    $comment_ids = Post_comment::where('post_id', $post_id)->pluck('id')->toArray();
    if (count($comment_ids) > 0)
      Post_comment_react::whereIn('post_comment_id', $comment_ids)->delete();
    Post_comment::where('post_id', $post_id)->delete();
    self::destroy($post_id);
    return true;
  }

  public static function own_post_html($param = [])
  {


    $post = $param['post'];
    $user_data = $param['user_data'] ?? [];
    $user_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
      $user_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
    $author_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($post['author_meta']['profile_photo']) && $post['author_meta']['profile_photo'] != '')
      $author_photo = url('public/uploads/profile_photo/' . $post['author_meta']['profile_photo']);
    $visibility_text = '';
    if ($post->visibility == 'public') $visibility_text = 'Public';
    if ($post->visibility == 'subscriber') $visibility_text = 'Subscribers';
    if ($post->visibility == 'subscriber_except') $visibility_text = 'Subscribers Except';
    $price_text = 'Free';
    if ($post->price > 0) $price_text = $post->price;
    $thumbnail = url('public/front/images/video_placeholder.jpg');
    if ($post->media_thumbnail_file != '')
      $thumbnail = url('public/uploads/post_media/' . $post->media_thumbnail_file);
    $html = '<div class="post-wrap-box post own_post post_type_' . $post->post_type . '" post_id="' . $post->id . '">
	        <div class="post-wrap-box-top d-flex align-items-center">
	            <div class="post-user-img">
	                <span><img src="' . $author_photo . '" alt=""></span>
	            </div>
	            <div class="post-user-details">
	                <a href="' . url('u/' . $post->author_username) . '">
	                    <h3>' . $post->author_display_name . ' <!--<span><i class="ti-heart"></i></span>--></h3>
	                </a>
                  <ul class="d-flex mdl-deatils">
                    <li><a href="' . url('u/' . $post->author_username) . '">@' . $post->author_username . '</a></li>
                    <li><i class="fa fa-eye"></i> <span>' . $visibility_text . '</span></li>
                    <li><i class="fas fa-coins"></i> ' . $price_text . '</li>
                  </ul>
	            </div>
              <div class="option-btn-wrap relative">
                  <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                  <ul class="option-btn-details">
                      <li><a href="javascript:;" data-url="'.url('dashboard/post_details?id='.$post->id).'" data-toggle="modal" data-target="#shareModal'.$post->id.'" class="socialShareModal">Share Post</a></li>
                      <li><a href="javascript:;" class="delete_post">delete</a></li>
                  </ul>
              </div>
	        </div>';
    if ($post->post_type == 'text') {
      $html .= '<div class="post-wrap-box-middle">
            <div class="post-only-text">
                <p>' . nl2br($post->post_content) . '</p>
            </div>
        </div>';
    }
    if ($post->post_type == 'photo') {
      $html .= '<div class="post-wrap-box-middle">
            <div class="post-only-text">
                <p>' . nl2br($post->post_content) . '</p>
            </div>
            <div class="post-media-wrap view_full_media" data-src="'. url('public/uploads/post_media/' . $post->media_file) .'"><img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="post-media" /></div>
        </div>';
    }
    if ($post->post_type == 'video') {
      $html .= '<div class="post-wrap-box-middle">
            <div class="post-only-text">
                <p>' . nl2br($post->post_content) . '</p>
            </div>
            <div class="homeVideoInner">
              <video id="example_video_' . $post->id . '" class="video-js" controls preload="none" poster="' . $thumbnail . '" data-setup="{}">
                  <source src="' . url('public/uploads/post_media/' . $post->media_file) . '" type="video/mp4">
                </video>
          </div>
        </div>';
    }
    if ($post->post_type == 'doc') {
      $html .= '<div class="post-wrap-box-middle">
            <div class="post-only-text">
                <p>' . nl2br($post->post_content) . '</p>
                <div class=""><a href="' . url('public/uploads/post_media/' . $post->media_file) . '" target="_blank">View / Download Attachment</a></div>
            </div>
        </div>';
    }
    $html .= '<div class="post-wrap-box-btm">
	            <div class="row align-items-center justify-content-between post-time-wrap">
	                <div class="col-auto">
                  
	                </div>
	                <div class="col-auto">
	                    <p><time class="timeago" datetime="' . (str_replace(' ', 'T', $post->created_at) . 'Z') . '"></time></p>
	                </div>
	            </div>
	            <!--<div class="post-content">
	            </div>-->
	            <div class="row align-items-center justify-content-between comment-love-wrap">
	                <div class="col-auto">
	                    <ul class="d-flex comment-live-icon">
	                        <li><a href="javascript:;" class="set_react"><span class="' . ($post->my_react == 0 ? 'far' : '') . ($post->my_react == 1 ? 'fas' : '') . ' fa-heart"></span> ' . $post->total_reacts . '</a></li>
	                        <li><a href="javascript:;" class="comment_counter"><span class="far fa-comment-alt"></span> ' . $post->total_comments . '</a></li>
	                    </ul>
	                </div>
	                <!--<div class="col-auto">
	                    <div class="option-btn-wrap relative">
	                        <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
	                        <ul class="option-btn-details">
	                            <li><a href="javascript:;" class="delete_post">delete</a></li>
	                        </ul>
	                    </div>
	                </div>-->
	            </div>
	            <!--<div class="no-comment-wrap">
	                <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
	            </div>-->

              <div class="commentWrap w-100" top_parent_comment_id="0">
                <div class="commentWrapCont"></div><!-- commentWrapCont ends -->
                <a href="javascript:;" class="load_more_comments" style="display: none;">Load more comments</a>
              </div>

	            <div class="post-user-btm d-flex align-items-center">
	                <div class="post-user-img">
	                    <span><img src="' . $user_photo . '" alt=""></span>
	                </div>
	                <div class="comment-input-wrap relative">
                      <div class="emoji_pad" save_action="set_comment" save_params=\'' . json_encode(['post_id' => $post->id]) . '\'>
                          <textarea placeholder="COMMENT"></textarea>
                          <div class="emoji_container" id="emoji_container_p' . $post->id . '"></div>
                          <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
                      </div>
	                </div>
	            </div>
	        </div>
          <div class="modal fade" id="shareModal'.$post->id.'">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Social Share</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body social-share">
                  '. \Share::page(
                    url('dashboard/post_details?id='.$post->id),
                    'Your share text comes here',
                    )
                    ->facebook()
                    ->twitter()
                    ->linkedin()
                    ->telegram()
                    ->whatsapp()        
                    ->reddit().'
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
          
              </div>
            </div>
          </div>
	    </div>';
    //   $html.='<div class="modal fade" id="shareModal'.$post->id.'">
    //   <div class="modal-dialog">
    //     <div class="modal-content">
    //       <div class="modal-header">
    //         <h4 class="modal-title">Modal Heading</h4>
    //         <button type="button" class="close" data-dismiss="modal">&times;</button>
    //       </div>
    //       <div class="modal-body">
    //         '. \Share::page(
    //           url('dashboard/post_details?id='.$post->id),
    //           'Your share text comes here',
    //           )
    //           ->facebook()
    //           ->twitter()
    //           ->linkedin()
    //           ->telegram()
    //           ->whatsapp()        
    //           ->reddit().'
    //       </div>
    //       <div class="modal-footer">
    //         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    //       </div>
    
    //     </div>
    //   </div>
    // </div>';
    return ['html' => $html];
  }

  public static function post_html($param = [])
  {
    //echo '<pre>';print_r($param);exit;

    $post     = $param['post'];
    $grid_view   = $param['grid_view'] ?? 0;
    $user_data   = $param['user_data'] ?? [];
    $post_user_id  = isset($user_data['id']) ? $user_data['id'] : '';

    //print_r($post_user_id);exit;

    $html = $unlock_html = $unlock_grid_html = '';
    $user_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
      $user_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
    $author_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($post['author_meta']['profile_photo']) && $post['author_meta']['profile_photo'] != '')
      $author_photo = url('public/uploads/profile_photo/' . $post['author_meta']['profile_photo']);
    $visibility_text = '';
    if ($post->visibility == 'public') $visibility_text = 'Public';
    if ($post->visibility == 'subscriber') $visibility_text = 'Subscribers';
    if ($post->visibility == 'subscriber_except') $visibility_text = 'Subscribers Except';
    $price_text = 'Free';
    if ($post->price > 0) $price_text = $post->price;
    $thumbnail = url('public/front/images/video_placeholder.jpg');
    if ($post->media_thumbnail_file != '')
      $thumbnail = url('public/uploads/post_media/' . $post->media_thumbnail_file);
    if ($grid_view == 0) {
      $html = '<div class="post-wrap-box post post_type_' . $post->post_type . '" post_id="' . $post->id . '">
            <div class="post-wrap-box-top d-flex align-items-center">
                <div class="post-user-img">
                    <span><img src="' . $author_photo . '" alt=""></span>
                </div>
                <div class="post-user-details">
                    <a href="' . url('u/' . $post->author_username) . '">
                        <h3>' . $post->author_display_name . ' <!--<span><i class="ti-heart"></i></span>--></h3>
                    </a>
                    <ul class="d-flex mdl-deatils">
                      <li><a href="' . url('u/' . $post->author_username) . '">@' . $post->author_username . '</a></li>
                      <li><i class="fa fa-eye"></i> <span>' . $visibility_text . '</span></li>
                      <li><i class="fas fa-coins"></i> ' . $price_text . '</li>
                    </ul>
                </div>
                <div class="option-btn-wrap relative">
                    <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                    <ul class="option-btn-details">
                        <li><a href="javascript:;" class="send_tip" post_id="' . $post->id . '">Give Tip</a></li>
                        <li><a href="javascript:;" class="report_post" post_id="' . $post->id . '">Report Post</a></li>
                        <li><a href="javascript:;" class="report_user" user_id="' . $post->user_id . '">Report User</a></li>
                    </ul>
                </div>
            </div>';


      if ($post->price > 0 && $post->post_paid == 0) {
        if ($post_user_id == $post->user_id) {

          if ($post->post_type == 'text') {
            $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
            </div>';
            $html .= $unlock_html;
          }
          if ($post->post_type == 'photo') {
            $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class="post-media-wrap view_full_media" data-src="' . url('public/uploads/post_media/' . $post->media_file) . '"><img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="post-media" /></div>
            </div>';
            $html .= $unlock_html;
          }
          if ($post->post_type == 'video') {
            $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class="homeVideoInner">
                  <video id="example_video_' . $post->id . '" class="video-js" controls preload="none" poster="' . $thumbnail . '" data-setup="{}">
                      <source src="' . url('public/uploads/post_media/' . $post->media_file) . '" type="video/mp4">
                    </video>
              </div>
            </div>';
            $html .= $unlock_html;
          }
          if ($post->post_type == 'doc') {
            $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                    <div class=""><a href="' . url('public/uploads/post_media/' . $post->media_file) . '" target="_blank">View / Download Attachment</a></div>
                </div>
            </div>';
            $html .= $unlock_html;
          }
        } else {

          $html .= '<div class="post-wrap-box-middle"><h4 class="post_head">' . $post->post_head . '</h4>
              <div class="post-subscribe-wrap-box relative d-flex align-items-center justify-content-center">
                  <div class="post-subscribe-details">
                      <i class="fas fa-lock"></i>
                      <p class="post-subscribe">Please pay coins to see this post</p>
                      <div class="post-subscribe-btm-main">
                          <div class="post-subscribe-btm d-flex align-items-center justify-content-center">
                              <!--<p class="d-flex align-items-center"><span class="ti-video-camera"></span> <span class="live-number">2</span></p>-->
                              ';
                            if (!empty(\Auth::user()->id)) {
                              $html .= '<a class="post-subscribe-btn unlock_post" data-coin="'.$post->price.'" href="javascript:;">Unlock post with ' . $post->price . ' coin' . ($post->price > 1 ? 's' : '') . '</a>';
                            }
                            $html .= '
                              <!--<p><i class="fas fa-lock"></i> $6.69</p>-->
                          </div>
                          <div class="unlock_post_ajax_response"></div>
                      </div>
                  </div>
              </div>
          </div>';
        }
      } else {
        if ($post->post_type == 'text') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'photo') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class="post-media-wrap view_full_media" data-src="' . url('public/uploads/post_media/' . $post->media_file) . '"><img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="post-media" /></div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'video') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class="homeVideoInner">
                  <video id="example_video_' . $post->id . '" class="video-js" controls preload="none" poster="' . $thumbnail . '" data-setup="{}">
                      <source src="' . url('public/uploads/post_media/' . $post->media_file) . '" type="video/mp4">
                    </video>
              </div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'doc') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                    <div class=""><a href="' . url('public/uploads/post_media/' . $post->media_file) . '" target="_blank">View / Download Attachment</a></div>
                </div>
            </div>';
          $html .= $unlock_html;
        }
      }


      $html .= '<div class="post-wrap-box-btm">
                <div class="row align-items-center justify-content-between post-time-wrap">
                    <div class="col-auto">
                    <ul class="d-flex comment-live-icon">
                            <li><a href="javascript:;" class="set_react"><span class="' . ($post->my_react == 0 ? 'far' : '') . ($post->my_react == 1 ? 'fas' : '') . ' fa-heart"></span> ' . $post->total_reacts . '</a></li>
                            <li><a href="javascript:;" class="comment_counter"><span class="far fa-comment-alt"></span> ' . $post->total_comments . '</a></li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <p><time class="timeago" datetime="' . (str_replace(' ', 'T', $post->created_at) . 'Z') . '"></time></p>
                    </div>
                </div>
                <!--<div class="post-content">
                </div>-->
                
                <!--<div class="no-comment-wrap">
                    <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                </div>-->

                <div class="commentWrap w-100" top_parent_comment_id="0">
                  <div class="commentWrapCont"></div><!-- commentWrapCont ends -->
                  <a href="javascript:;" class="load_more_comments" style="display: none;">Load more comments</a>
                </div>';
      if (!empty(\Auth::user()->id)) {
        $html .= '<div class="post-user-btm d-flex align-items-center">
                    <div class="post-user-img">
                        <span><img src="' . $user_photo . '" alt=""></span>
                    </div>';

        $html .= '<div class="comment-input-wrap relative">
                        <div class="emoji_pad" save_action="set_comment" save_params=\'' . json_encode(['post_id' => $post->id]) . '\'>
                            <textarea placeholder="COMMENT"></textarea>
                            <div class="emoji_container" id="emoji_container_p' . $post->id . '"></div>
                            <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
                        </div>
                    </div>

                </div>';
      }
      $html .= '</div>
        </div>';
    }
    if ($grid_view == 1) {
      /*$html = '';
        for($i = 0; $i < 10; $i++) {*/
      $html = '<div class="col-md-4 col-sm-6 col-12 post post_type_' . $post->post_type . '" post_id="' . $post->id . '">';



      if ($post->price > 0 && $post->post_paid == 0) {
        if ($post_user_id == $post->user_id) {

          if ($post->post_type == 'photo') {
            $unlock_grid_html = '<div href="javascript:;" class="relative view_media" image_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="img-fluid" />
            </div>';
            $html .= $unlock_grid_html;
          }
          if ($post->post_type == 'video') {
            $unlock_grid_html = '<div href="javascript:;" class="relative view_media" video_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . $thumbnail . '" class="img-fluid" />
              <span class="playIco"><i class="fas fa-play"></i></span>
            </div>';
            $html .= $unlock_grid_html;
          }
        } else {
          $html .= '<div href="javascript:;" class="relative view_media locked" ' . ($post->post_type == 'photo' ? 'image_url' : '') . ($post->post_type == 'video' ? 'video_url' : '') . '="">
            <div class="info text-center">
              <p><i class="fas fa-lock"></i></p>';
          if (!empty(\Auth::user()->id)) {
            $html .= '<button class="commonBtn unlock_post" data-coin="'.$post->price.'">unlock with ' . $post->price . ' coin' . ($post->price > 1 ? 's' : '') . '</button>';
          }

          $html .= '<div class="unlock_post_ajax_response"></div>
            </div></div>';
        }
      } else {
        if ($post->post_type == 'photo') {
          $unlock_grid_html = '<div href="javascript:;" class="relative view_media" image_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="img-fluid" />
            </div>';
          $html .= $unlock_grid_html;
        }
        if ($post->post_type == 'video') {
          $unlock_grid_html = '<div href="javascript:;" class="relative view_media" video_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . $thumbnail . '" class="img-fluid" />
              <span class="playIco"><i class="fas fa-play"></i></span>
            </div>';
          $html .= $unlock_grid_html;
        }
      }
      $html .= '</div>';
      //}
    }



    return ['html' => $html, 'unlock_html' => $unlock_html, 'unlock_grid_html' => $unlock_grid_html];
  }

  public static function post_html_old($param = [])
  {

    $post = $param['post'];
    $grid_view = $param['grid_view'] ?? 0;
    $user_data = $param['user_data'] ?? [];
    $html = $unlock_html = $unlock_grid_html = '';
    $user_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
      $user_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
    $author_photo = url('/public/front/images/user-placeholder.jpg');
    if (isset($post['author_meta']['profile_photo']) && $post['author_meta']['profile_photo'] != '')
      $author_photo = url('public/uploads/profile_photo/' . $post['author_meta']['profile_photo']);
    $visibility_text = '';
    if ($post->visibility == 'public') $visibility_text = 'Public';
    if ($post->visibility == 'subscriber') $visibility_text = 'Subscribers';
    if ($post->visibility == 'subscriber_except') $visibility_text = 'Subscribers Except';
    $price_text = 'Free';
    if ($post->price > 0) $price_text = $post->price;
    $thumbnail = url('public/front/images/video_placeholder.jpg');
    if ($post->media_thumbnail_file != '')
      $thumbnail = url('public/uploads/post_media/' . $post->media_thumbnail_file);
    if ($grid_view == 0) {
      $html = '<div class="post-wrap-box post post_type_' . $post->post_type . '" post_id="' . $post->id . '">
            <div class="post-wrap-box-top d-flex align-items-center">
                <div class="post-user-img">
                    <span><img src="' . $author_photo . '" alt=""></span>
                </div>
                <div class="post-user-details">
                    <a href="' . url('u/' . $post->author_username) . '">
                        <h3>' . $post->author_display_name . ' <!--<span><i class="ti-heart"></i></span>--></h3>
                    </a>
                    <ul class="d-flex mdl-deatils">
                      <li><a href="' . url('u/' . $post->author_username) . '">@' . $post->author_username . '</a></li>
                      <li><i class="fa fa-eye"></i> <span>' . $visibility_text . '</span></li>
                      <li><i class="fas fa-coins"></i> ' . $price_text . '</li>
                    </ul>
                </div>
                <div class="option-btn-wrap relative">
                    <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                    <ul class="option-btn-details">
                        <li><a href="javascript:;" class="send_tip" post_id="' . $post->id . '">Give Tip</a></li>
                        <li><a href="javascript:;" class="report_post" post_id="' . $post->id . '">Report Post</a></li>
                        <li><a href="javascript:;" class="report_user" user_id="' . $post->user_id . '">Report User</a></li>
                    </ul>
                </div>
            </div>';
      if ($post->price > 0 && $post->post_paid == 0) {
        $html .= '<div class="post-wrap-box-middle"><h4 class="post_head">' . $post->post_head . '</h4>
              <div class="post-subscribe-wrap-box relative d-flex align-items-center justify-content-center">
                  <div class="post-subscribe-details">
                      <i class="fas fa-lock"></i>
                      <p class="post-subscribe">Please pay coins to see this post</p>
                      <div class="post-subscribe-btm-main">
                          <div class="post-subscribe-btm d-flex align-items-center justify-content-center">
                              <!--<p class="d-flex align-items-center"><span class="ti-video-camera"></span> <span class="live-number">2</span></p>-->
                              <a class="post-subscribe-btn unlock_post" data-coin="'.$post->price.'" href="javascript:;">Unlock post with ' . $post->price . ' coin' . ($post->price > 1 ? 's' : '') . '</a>
                              <!--<p><i class="fas fa-lock"></i> $6.69</p>-->
                          </div>
                          <div class="unlock_post_ajax_response"></div>
                      </div>
                  </div>
              </div>
          </div>';
      } else {
        if ($post->post_type == 'text') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'photo') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class=""><img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="img-fluid" /></div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'video') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                </div>
                <div class="homeVideoInner">
                  <video id="example_video_' . $post->id . '" class="video-js" controls preload="none" poster="' . $thumbnail . '" data-setup="{}">
                      <source src="' . url('public/uploads/post_media/' . $post->media_file) . '" type="video/mp4">
                    </video>
              </div>
            </div>';
          $html .= $unlock_html;
        }
        if ($post->post_type == 'doc') {
          $unlock_html = '<div class="post-wrap-box-middle">
                <div class="post-only-text">
                    <p>' . nl2br($post->post_content) . '</p>
                    <div class=""><a href="' . url('public/uploads/post_media/' . $post->media_file) . '" target="_blank">View / Download Attachment</a></div>
                </div>
            </div>';
          $html .= $unlock_html;
        }
      }
      $html .= '<div class="post-wrap-box-btm">
                <div class="row align-items-center justify-content-between post-time-wrap">
                    <div class="col-auto">
                    </div>
                    <div class="col-auto">
                        <p><time class="timeago" datetime="' . (str_replace(' ', 'T', $post->created_at) . 'Z') . '"></time></p>
                    </div>
                </div>
                <!--<div class="post-content">
                </div>-->
                <div class="row align-items-center justify-content-between comment-love-wrap">
                    <div class="col-auto">
                        <ul class="d-flex comment-live-icon">
                            <li><a href="javascript:;" class="set_react"><span class="' . ($post->my_react == 0 ? 'far' : '') . ($post->my_react == 1 ? 'fas' : '') . ' fa-heart"></span> ' . $post->total_reacts . '</a></li>
                            <li><a href="javascript:;" class="comment_counter"><span class="far fa-comment-alt"></span> ' . $post->total_comments . '</a></li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <!--<div class="option-btn-wrap relative">
                            <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                            <ul class="option-btn-details">
                                <li><a href="javascript:;">delete</a></li>
                            </ul>
                        </div>-->
                    </div>
                </div>
                <!--<div class="no-comment-wrap">
                    <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                </div>-->

                <div class="commentWrap w-100" top_parent_comment_id="0">
                  <div class="commentWrapCont"></div><!-- commentWrapCont ends -->
                  <a href="javascript:;" class="load_more_comments" style="display: none;">Load more comments</a>
                </div>

                <div class="post-user-btm d-flex align-items-center">
                    <div class="post-user-img">
                        <span><img src="' . $user_photo . '" alt=""></span>
                    </div>
                    <div class="comment-input-wrap relative">
                        <div class="emoji_pad" save_action="set_comment" save_params=\'' . json_encode(['post_id' => $post->id]) . '\'>
                            <textarea placeholder="COMMENT"></textarea>
                            <div class="emoji_container" id="emoji_container_p' . $post->id . '"></div>
                            <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }
    if ($grid_view == 1) {
      /*$html = '';
        for($i = 0; $i < 10; $i++) {*/
      $html = '<div class="col-md-4 col-sm-6 col-12 post post_type_' . $post->post_type . '" post_id="' . $post->id . '">';
      if ($post->price > 0 && $post->post_paid == 0) {
        $html .= '<div href="javascript:;" class="relative view_media locked" ' . ($post->post_type == 'photo' ? 'image_url' : '') . ($post->post_type == 'video' ? 'video_url' : '') . '="">
            <div class="info text-center">
              <p><i class="fas fa-lock"></i></p>
              <button class="commonBtn unlock_post" data-coin="'.$post->price.'">unlock with ' . $post->price . ' coin' . ($post->price > 1 ? 's' : '') . '</button>
              <div class="unlock_post_ajax_response"></div>
            </div>
            </div>';
      } else {
        if ($post->post_type == 'photo') {
          $unlock_grid_html = '<div href="javascript:;" class="relative view_media" image_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . url('public/uploads/post_media/' . $post->media_file) . '" class="img-fluid" />
            </div>';
          $html .= $unlock_grid_html;
        }
        if ($post->post_type == 'video') {
          $unlock_grid_html = '<div href="javascript:;" class="relative view_media" video_url="' . url('public/uploads/post_media/' . $post->media_file) . '">
              <img src="' . $thumbnail . '" class="img-fluid" />
              <span class="playIco"><i class="fas fa-play"></i></span>
            </div>';
          $html .= $unlock_grid_html;
        }
      }
      $html .= '</div>';
      //}
    }
    return ['html' => $html, 'unlock_html' => $unlock_html, 'unlock_grid_html' => $unlock_grid_html];
  }
}
