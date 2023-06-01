<?php
$profile_photo = url('/public/front/images/user-placeholder.jpg');
if(isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
    $profile_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
?>

<h4>Favorites</h4>
<div class="">
  <ul class="d-flex align-items-center catagry-button">
    <li><a class="{{empty(request()->get('post_type'))?'active':''}}" href="{{ url('dashboard/favorites') }}">All</a></li>
    <li><a href="{{ url('dashboard/favorites?post_type=photo') }}" class="{{(request()->get('post_type')=='photo')?'active':''}}">Photos</a></li>
    <li><a href="{{ url('dashboard/favorites?post_type=video') }}" class="{{(request()->get('post_type')=='video')?'active':''}}">Videos</a></li>
  </ul>
</div>
<?php
	$total_page = ceil($meta_data['total_posts'] / $meta_data['per_page']);
?>
<div class="post-wrap">
  <div class="col-12">
    <div class="row">
      <?php foreach ($meta_data['posts'] as $key => $value) { ?>
      <?php
		$post 			= $value;
      	$user_data 		= $user_data ?? [];
	  	$post_user_id	= isset($user_data['id'])?$user_data['id']:'';
		$user_photo 	= url('/public/front/images/user-placeholder.jpg');
		if(isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != ''){
			$user_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
		}
		$author_photo = url('/public/front/images/user-placeholder.jpg');
		if(isset($post['author_meta']['profile_photo']) && $post['author_meta']['profile_photo'] != ''){
			$author_photo = url('public/uploads/profile_photo/' . $post['author_meta']['profile_photo']);
		}
		$visibility_text = '';
		if($post->visibility == 'public') $visibility_text = 'Public';
		if($post->visibility == 'subscriber') $visibility_text = 'Subscribers';
		if($post->visibility == 'subscriber_except') $visibility_text = 'Subscribers Except';
		
		$price_text = 'Free';
		if($post->price > 0) $price_text = $post->price;
		$thumbnail = url('public/front/images/video_placeholder.jpg');
		
		if($post->media_thumbnail_file != ''){
			$thumbnail = url('public/uploads/post_media/' . $post->media_thumbnail_file);
		}
		
		$is_locked='N';
		if($post->price > 0 && $post->post_paid == 0) {
			if($post_user_id==$post->user_id){
				$is_locked='N';
			}else{
				$is_locked='Y';
			}
		}
		
		
		
	?>
      <div class="col-lg-4 col-md-6 col-sm-12 col-12 post post_type_{{$post->post_type}}" post_id="{{$post->id}}">
        <?php if($is_locked=='Y'){ ?>
        <?php echo '<div href="javascript:;" class="relative view_media locked" ' . ($post->post_type == 'photo' ? 'image_url' : '') . ($post->post_type == 'video' ? 'video_url' : '') . '="">'?>
        <div class="info text-center">
          <p><i class="fas fa-lock"></i></p>
          <button class="commonBtn unlock_post">unlock with 10 coins</button>
          <div class="unlock_post_ajax_response"></div>
        </div>
      </div>
      <?php }else{ ?>
      <?php if($post->post_type == 'photo') {?>
      <div class="favorites-box autoHeight">
        <div class="favorites-box-media" image_url="{{url('public/uploads/post_media/' . $post->media_file)}}"> <a href="javascript:;" class="view_media" image_url="{{url('public/uploads/post_media/' . $post->media_file)}}"><img src="{{url('public/uploads/post_media/' . $post->media_file)}}" alt=""></a> </div>
        <div class="favorites-box-des">
          <div class="box-des-content when-media"> <a href="javascript:;">{{nl2br($post->post_content)}}</a> </div>
          <div class="favorites-user d-flex align-items-center">
            <div class="favorites-user-img"> <span><img src="{{$author_photo}}" alt=""></span> </div>
            <div class="favorites-user-details">
              <h3>{{$post->author_display_name}}</h3>
              <ul class="d-flex user-mdl-deatils">
                <li><a href="{{url('u/' . $post->author_username)}}">@ {{$post->author_username}}</a></li>
                <li><i class="fa fa-eye"></i> <span>{{$visibility_text}}</span></li>
                <li><i class="fas fa-coins"></i> {{$price_text}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php }else if($post->post_type == 'video') { ?>
      <div class="favorites-box autoHeight">
        <div class="favorites-box-media">
          <div class="favorite-post-video">
            <video id="example_video_{{$post->id}}" class="video-js" controls preload="none" poster="{{$thumbnail}}" data-setup="{}">
              <source src="{{url('public/uploads/post_media/' . $post->media_file)}}" type="video/mp4">
            </video>
          </div>
        </div>
        <div class="favorites-box-des">
          <div class="box-des-content when-media"> <a href="javascript:;" class="view_media" video_url="{{url('public/uploads/post_media/' . $post->media_file)}}">{{nl2br($post->post_content)}}</a> </div>
          <div class="favorites-user d-flex align-items-center">
            <div class="favorites-user-img"> <span><img src="{{$author_photo}}" alt=""></span> </div>
            <div class="favorites-user-details">
              <h3>{{$post->author_display_name}}</h3>
              <ul class="d-flex user-mdl-deatils">
                <li><a href="{{url('u/' . $post->author_username)}}">@ {{$post->author_username}}</a></li>
                <li><i class="fa fa-eye"></i> <span>{{$visibility_text}}</span></li>
                <li><i class="fas fa-coins"></i> {{$price_text}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php }else if($post->post_type == 'text') { ?>
      <div class="favorites-box autoHeight">
        <div class="favorites-box-des">
          <div class="box-des-content no-media"> <a href="javascript:;" class="view_media" video_url="">{{nl2br($post->post_content)}}</a> </div>
          <div class="favorites-user d-flex align-items-center">
            <div class="favorites-user-img"> <span><img src="{{$author_photo}}" alt=""></span> </div>
            <div class="favorites-user-details">
              <h3>{{$post->author_display_name}}</h3>
              <ul class="d-flex user-mdl-deatils">
                <li><a href="{{url('u/' . $post->author_username)}}">@ {{$post->author_username}}</a></li>
                <li><i class="fa fa-eye"></i> <span>{{$visibility_text}}</span></li>
                <li><i class="fas fa-coins"></i> {{$price_text}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php }else if($post->post_type == 'doc') { ?>
      <div class="favorites-box autoHeight">
        <div class="favorites-box-des">
          <div class="relative">
            <div class="box-des-content no-media add-btn"> <p>{{nl2br($post->post_content)}}</p></div>
            <div class="download-attachment"> <a href="{{url('public/uploads/post_media/' . $post->media_file)}}" class="downloadBtn" target="_blank">View / Download Attachment</a> </div>
          </div>
          <div class="favorites-user d-flex align-items-center">
            <div class="favorites-user-img"> <span><img src="{{$author_photo}}" alt=""></span> </div>
            <div class="favorites-user-details">
              <h3>{{$post->author_display_name}}</h3>
              <ul class="d-flex user-mdl-deatils">
                <li><a href="{{url('u/' . $post->author_username)}}">@ {{$post->author_username}}</a></li>
                <li><i class="fa fa-eye"></i> <span>{{$visibility_text}}</span></li>
                <li><i class="fas fa-coins"></i> {{$price_text}}</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <!--<div class="col-lg-4 col-md-6 col-sm-12 col-12">
        <div class="favorites-box autoHeight">
          <div class="favorites-box-des">
            <div class="box-des-content no-media">
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis nulla veritatis, aliquid mollitia dolorum aperiam eveniet quo facilis at cumque magnam commodi nostrum enim, </p>
            </div>
            <div class="favorites-user d-flex align-items-center">
              <div class="favorites-user-img"> <span><img src="http://onlyfans.biplab.aqualeafitsol.com/public/uploads/profile_photo/9_profile_photo.jpg" alt=""></span> </div>
              <div class="favorites-user-details">
                <h3>neka1</h3>
                <ul class="d-flex user-mdl-deatils">
                  <li><a href="http://onlyfans.biplab.aqualeafitsol.com/u/nnnn">@nnnn</a></li>
                  <li><i class="fa fa-eye"></i> <span>Subscribers</span></li>
                  <li><i class="fas fa-coins"></i> 4</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>-->
    
    <?php } ?>
  </div>
</div>
<div class="tab-content m-t-10">
  <!--<div class="tab-pane active post_list_box" id="tabs-1" role="tabpanel" ajax_running="0" total_page="{{ $total_page }}" cur_page="{{ $meta_data['cur_page'] }}" per_page="{{ $meta_data['per_page'] }}">-->
    <?php //foreach ($meta_data['posts'] as $key => $value) {
                //$post_html = \App\Post::post_html(['post' => $value, 'user_data' => $user_data]);
                //echo $post_html['html'];
            ?>
    <?php //} ?>
 <!-- </div>-->
  <div class="tab-pane" id="tabs-2" role="tabpanel"> </div>
</div>
</div>
<div class="modal fade media_modal" id="mediaViewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"> 
      <!-- Modal body -->
      <div class="modal-body relative"> <a href="javascript:;" class="close">x</a>
        <div class="media_details"></div>
      </div>
    </div>
  </div>
</div>
@push('script') 
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script> 
<script type="text/javascript">
      $(document).ready(function() {

        vip_member_favorite_posts({});

      });
  </script> 
<script>
      $(document).ready(function () {
        var m_p = 0;
        $('.autoHeight').each(function () {
            if ($(this).outerHeight() >= m_p) {
                m_p = $(this).outerHeight();
            }
        });
        $('.autoHeight').css({
            'min-height': m_p
        })
    });
  </script> 
@endpush 