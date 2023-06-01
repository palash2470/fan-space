<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\User_meta;
use App\Post;

class Post_comment extends Model
{
    //
    //public $timestamps = false;

    protected $table = 'post_comments';

    protected $fillable = ['post_id', 'user_id', 'top_parent_comment_id', 'parent_comment_id', 'comment'];

    public static function get_post_comments($param = []) {
    	$post_comment_id = $param['post_comment_id'] ?? '';
    	$post_id = $param['post_id'] ?? '';
    	$top_parent_comment_id = $param['top_parent_comment_id'] ?? '';
    	$start_after_comment_id = $param['start_after_comment_id'] ?? '';
    	$per_page = $param['per_page'] ?? '';
    	$current_user_id = $param['current_user_id'] ?? '0';
      $post = Post::find($post_id);
    	$data = self::select('post_comments.*', DB::raw('(select count(pc.id) from post_comments as pc where pc.parent_comment_id = post_comments.id) as has_children'), 'u.first_name as author_first_name', 'u.last_name as author_last_name', 'u.display_name as author_display_name', 'u.username as author_username', 'u.role as author_role', DB::raw('(select count(pcr.id) from post_comment_reacts as pcr where pcr.post_comment_id = post_comments.id) as react_count'), DB::raw('(select count(pcr.id) from post_comment_reacts as pcr where pcr.post_comment_id = post_comments.id and pcr.user_id = ' . $current_user_id . ') as my_react'))->leftJoin('users as u', 'u.id', 'post_comments.user_id')
    	->where('post_comments.post_id', $post_id)->where('top_parent_comment_id', $top_parent_comment_id);
    	if($start_after_comment_id > 0)
    		$data->where('post_comments.id', '<', $start_after_comment_id);
    	if($post_comment_id != '')
    		$data->where('post_comments.id', $post_comment_id);
    	$data->orderBy('post_comments.id', 'desc')->limit($per_page)->offset(0);
    	$total_data = $data->count();
    	$data = $data->get();
    	$author_ids = [];
    	foreach ($data as $key => $value) {
    		if(!in_array($value->user_id, $author_ids)) $author_ids[] = $value->user_id;
    	}
    	$author_profile_photos = [];
    	if(count($author_ids) > 0) {
    		$usermeta = User_meta::whereIn('user_id', $author_ids)->where('key', 'profile_photo')->get();
    		foreach ($usermeta as $key => $value) {
    			$author_profile_photos[$value->user_id] = $value->value;
    		}
    	}
    	foreach ($data as $key => $value) {
    		$data[$key]->author_profile_photo = $author_profile_photos[$value->user_id] ?? '';
    	}
    	$return = ['total_data' => $total_data, 'data' => $data, 'post' => $post];
      return $return;
    }

    public static function get_html($param = []) {
    	$post_comments = $param['post_comments'];
    	$post_id = $param['post_id'] ?? '';
    	$top_parent_comment_id = $param['top_parent_comment_id'] ?? '';
    	$start_after_comment_id = $param['start_after_comment_id'] ?? '';
    	$per_page = $param['per_page'] ?? '';
    	$current_user_id = $param['current_user_id'] ?? '0';
      $post = $post_comments['post'];
    	$current_user_profile_photo = User_meta::where('user_id', $current_user_id)->where('key', 'profile_photo')->pluck('value')->first();
    	if($current_user_profile_photo != '')
    		$current_user_profile_photo = url('public/uploads/profile_photo/' . $current_user_profile_photo);
    	else
    		$current_user_profile_photo = url('public/front/images/user-placeholder.jpg');
    	$html = '';
    	foreach ($post_comments['data'] as $key => $value) {
    		$profile_photo = url('public/front/images/user-placeholder.jpg');
    		if($value->author_profile_photo != '')
    			$profile_photo = url('public/uploads/profile_photo/' . $value->author_profile_photo);
    		$author_name = $value->author_display_name;
    		if($value->author_role == 3)
    			$author_name = $value->author_first_name . ' ' . $value->author_last_name;
    		$like = '<i class="far fa-heart"></i> ' . $value->react_count . ' <span>Like</span>';
  			if($value->my_react == 1)
  				$like = '<i class="fas fa-heart"></i> ' . $value->react_count . ' <span>Unlike</span>';
        $comment_options = '';
        if($current_user_id == $value->user_id || $current_user_id == $post->user_id) {
          $comment_options = '<div class="option-top-rgt">
              <div class="option-top-rgt-wrap relative">
                  <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                  <ul class="option-rgt-details">
                      <li><a href="javascript:;" delete_comment="' . $value->id . '">Delete</a></li>
                  </ul>
              </div>
          </div>';
        }
    		if($top_parent_comment_id == 0) {
    			$load_more = '<a href="javascript:;" class="load_more_subcomments" style="' . ($value->has_children == 0 ? 'display: none;' : '') . '">Load more replies</a>';
					$html .= '<div class="commentWrapInner d-flex" post_comment_id="' . $value->id . '">
              <div class="commentImg">
                  <span><a href="' . url('u/' . $value->author_username) . '"><img src="' . $profile_photo . '" alt=""></a></span>
              </div>
              <div class="commentDtls">
                  <div class="commentDtlsTop">
                      ' . $comment_options . '
                      <h5><a href="' . url('u/' . $value->author_username) . '">' . $author_name . '</a></h5>
                      <p>' . nl2br($value->comment) . '</p>
                  </div>
                  <div class="replyLike w-100">
                      <ul class="d-flex">
                          <li><a href="javascript:;" class="like set_post_comment_react" post_comment_id="' . $value->id . '">' . $like . '</a></li>
                          <li><a href="javascript:;" class="reply">Reply</a></li>
                          <li><a class="min" href="javascript:;"><time class="timeago" datetime="' . (str_replace(' ', 'T', $value->created_at) . 'Z') . '"></time></a></li>
                      </ul>
                      <div class="reply_box" style="display: none;">
                      <div class="compose-new-post d-flex align-items-center w-100" style="display: none;">
                          <div class="post-user">
                              <span><img src="' . $current_user_profile_photo . '" alt=""></span>
                          </div>
                          <div class="post-input-wrap">
                              <div class="emoji_pad" save_action="set_comment" save_params=\'' . json_encode(['post_id' => $post_id, 'top_parent_comment_id' => $value->id, 'parent_comment_id' => $value->id]) . '\'>
			                          <textarea placeholder="write your reply"></textarea>
			                          <div class="emoji_container" id="emoji_container_pc' . $value->id . '"></div>
			                          <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
			                      	</div>
                          </div>
                      </div>
                      </div>
                  </div>
                  <div class="subcomment_list"></div><!-- subcomment_list ends -->
                  ' . $load_more . '
              </div>
          </div>';
    		} else {
    			$html .= '<div class="subComent" post_comment_id="' . $value->id . '">
              <div class="d-flex w-100">
                  <div class="commentImg">
                      <span><a href="' . url('u/' . $value->author_username) . '"><img src="' . $profile_photo . '" alt=""></a></span>
                  </div>
                  <div class="commentDtls">
                      <div class="commentDtlsTop w-100">
                          ' . $comment_options . '
                          <h5><a href="' . url('u/' . $value->author_username) . '">' . $author_name . '</a></h5>
                          <p>' . nl2br($value->comment) . '</p>
                      </div>
                      <div class="replyLike w-100">
                          <ul class="d-flex">
                              <li><a href="javascript:;" class="like set_post_comment_react" post_comment_id="' . $value->id . '">' . $like . '</a></li>
                              <li><a href="javascript:;" class="reply">Reply</a></li>
                              <li><a class="min" href="javascript:;"><time class="timeago" datetime="' . (str_replace(' ', 'T', $value->created_at) . 'Z') . '"></time></a></li>
                          </ul>
                          <div class="reply_box" style="display: none;">
                          <div class="compose-new-post d-flex align-items-center w-100">
	                          <div class="post-user">
	                              <span><img src="' . $current_user_profile_photo . '" alt=""></span>
	                          </div>
	                          <div class="post-input-wrap">
	                              <div class="emoji_pad" save_action="set_comment" save_params=\'' . json_encode(['post_id' => $post_id, 'top_parent_comment_id' => $top_parent_comment_id, 'parent_comment_id' => $value->id]) . '\'>
				                          <textarea placeholder="write your reply"></textarea>
				                          <div class="emoji_container" id="emoji_container_pc' . $value->id . '"></div>
				                          <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
				                      	</div>
	                          </div>
	                      	</div>
	                      	</div>
                      </div>
                  </div>
              </div>
          </div>';
    		}
    	}
    	return ['html' => $html];
    }

}
