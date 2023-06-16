{{-- @dd('jjj') --}}
@extends('layouts.front')
@section('content')

<?php
$section = Request::segment(3);
$helper_settings = $meta_data['helper_settings'];
$my_fav_user_ids = [];
if(isset($user_data['id'])) {
    $my_fav_user_ids = \App\User_fav_user::where('user_id', $user_data['id'])->pluck('fav_user_id')->toArray();
}
$my_follow_user_ids = [];
if(isset($user_data['id'])) {
    $my_follow_user_ids = \App\User_follow_user::where('user_id', $user_data['id'])->pluck('follow_user_id')->toArray();
}

$countries = \App\Country::orderBy('name')->get();
$countries2 = [];
foreach ($countries as $key => $value) {
    $countries2[$value->country_id] = $value;
}
$cupsizes = $meta_data['cupsizes'];
$cupsizes2 = [];
foreach ($cupsizes as $key => $value) {
    $cupsizes2[$value->id] = $value;
}
$sexual_orientations = $meta_data['sexual_orientations'];
$sexual_orientations2 = [];
foreach ($sexual_orientations as $key => $value) {
    $sexual_orientations2[$value->id] = $value;
}
$zodiacs = $meta_data['zodiacs'];
$zodiacs2 = [];
foreach ($zodiacs as $key => $value) {
    $zodiacs2[$value->id] = $value;
}
$user = $meta_data['vip_member']['user'];
$usermeta = $meta_data['vip_member']['usermeta'];
$model_attributes = $meta_data['vip_member']['model_attributes'];
$live_session = $meta_data['vip_member']['live_session'];
$banner = $usermeta['profile_banner'] ?? '';
if($banner != '') $banner = url('public/uploads/profile_banner/' . $banner);
// dd($usermeta);
?>

<!-- <section class="profileBanner relative bCover d-flex align-items-center justify-content-center" style="background: url({{ $banner }}) center center no-repeat #000;">
    <div class="profile-banner-wrap d-flex flex-wrap">
        <div class="profile-banner-lft">
            <h3>{{ $user->display_name }} <span><i class="{{ in_array($user->id, $my_fav_user_ids) ? 'fas' : 'far' }} fa-heart set_user_fav" user_id="{{ $user->id }}"></i></span></h3>
            <p>{{ '@' . $user->username }}</p>
        </div>
        <div class="profile-banner-rgt">
            <ul>
                <li><a class="subscribe-Btn" href="#">Subscribe for £ 4.50</a></li>
                <li><a href="javascript:;" class="follow-Btn set_user_follow" user_id="{{ $user->id }}">{!! in_array($user->id, $my_follow_user_ids) ? '<i class="fas fa-thumbs-down"></i> Unfollow' : '<i class="fas fa-thumbs-up"></i> Follow' !!}</a></li>
            </ul>
        </div>
    </div>
</section> -->

<?php $composite_banners = json_decode(($usermeta['composite_banners'] ?? '{}'), true); ?>
<section class="profileBanner relative bCover d-flex align-items-center justify-content-center">
    <div class="profileBannerInner">
        <div class="row">
            <input type="hidden" name="model_low_alert" id="model_low_alert" value="no">
            <?php
            for($i = 1; $i <= 8; $i++) {
                $img = url('public/front/images/profle_banner_placeholder.jpg');
                if(isset($composite_banners[('ban_' . $i)]) && $composite_banners[('ban_' . $i)] != '')
                    $img = url('public/uploads/profile_banner/' . $composite_banners[('ban_' . $i)]);
                echo '<div class="proImgBox">
                    <div class="proImgBoxInner">
                        <img src="' . $img . '" alt="">
                    </div>
                </div>';
            }
            ?>
            <!-- <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/1.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/2.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/3.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/4.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/5.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/6.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/7.jpg') }}" alt="">
                </div>
            </div>
            <div class="proImgBox">
                <div class="proImgBoxInner">
                    <img src="{{ url('public/front/images/profile-db/8.jpg') }}" alt="">
                </div>
            </div> -->
        </div>
    </div>
    <?php
    $profile_photo = URL::asset('/public/front/images/user-placeholder.jpg');
    if(isset($usermeta['profile_photo']) && $usermeta['profile_photo'] != '')
      $profile_photo = url('public/uploads/profile_photo/' . $usermeta['profile_photo']);
    ?>
    <div class="profile-banner-wrap relative">
        <div class="option-top-rgt">
            <div class="option-top-rgt-wrap relative">
                <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                <ul class="option-rgt-details">
                    <li><a href="javascript:;" class="report_user" user_id="{{ $user->id }}">Report User</a></li>
                </ul>
            </div>
        </div>
        <div class="profileBannerWrapInner d-flex flex-wrap w-100">
            <div class="profile-banner-lft d-flex">
            <div class="pblLeft">
                <span><img src="{{ $profile_photo }}" alt=""></span>
            </div>
            <div class="pblRight">
                <h3>{{ $user->display_name }}
                    <?php if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                        <span><i class="{{ in_array($user->id, $my_fav_user_ids) ? 'fas' : 'fa' }} fa-fire set_user_fav" data-toggle="tooltip" data-original-title="{{ in_array($user->id, $my_fav_user_ids) ? 'Remove from hot list' : 'Add to hot list' }}" user_id="{{ $user->id }}"></i></span>
                    <?php } ?>
                </h3>
                <h5>{{ '@' . $user->username }}</h5>
                <p>{{ $usermeta['short_bio'] ?? '' }}</p>
            </div>

            </div>
            <div class="profile-banner-rgt">
                <ul>
                    <?php if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                        <?php
                        $subscribe_title = 'Subscribe';
                        if(isset($meta_data['subscriber']->id)) {
                            $subscribe_title = 'Subscribed<br><small>Next renewal date: ' . date('d-m-Y', strtotime($meta_data['subscriber']->next_renewal_date)) . '</small>';
                        ?>
                          <li><a href="javascript:;" class="follow-Btn set_user_unsubscribe" user_id="{{ $user->id }}">Unsubscribe</a></li>
                        <?php } else { ?>

                        <?php } ?>
                        <li><a class="subscribe-Btn show_subscribe_options" href="javascript:;" data-toggle11111="modal" data-target1111="#subscribeModal" enabled="{{ isset($meta_data['subscriber']->id) ? '0' : '1' }}">{!! $subscribe_title !!}</a></li>
                        <li><a href="javascript:;" class="follow-Btn set_user_follow" user_id="{{ $user->id }}">{!! in_array($user->id, $my_follow_user_ids) ? '<i class="fas fa-thumbs-down"></i> Unfollow' : '<i class="fas fa-thumbs-up"></i> Follow' !!}</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php if($section == 'live') { ?>
  <section class="live-sec m-t-40 follower-section">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-12">
          <div class="video_area relative">
            <div id="opentok_subscriber" class="opentok_player_area" style="display: none;"></div>
            <a href="javascript:void(0);" class="commonBtn2 opentok_start_session" user_id="{{ $user->id }}" style="display: none;">Start session</a>
            <div class="opentok_placeholder_img" style="background: url({{ $profile_photo }}) center center no-repeat; background-size: contain;">
              <div class="offCont">
                <h3>I am currently offline</h3>
              </div>
            </div>
          </div>
            <div class="private-chat" style="display: none;">
                <ul class="d-flex justify-content-center">
                    <li><a href="javascript:;" class="prv-chat-btn" data-toggle="tooltip" data-placement="top" title="{{ $usermeta['private_chat_charge'] }} coin per minute">private chat</a></li>
                </ul>
            </div>
            <div class="private-chat-msg" style="display: none;">
                <ul class="d-flex justify-content-center">
                    <li>You have requested for private chat. wait for model response</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4 col-12">
          <!-- <h3>Messages</h3> -->
          <div class="chatbox offline blk-user-wrap" vip_member_id="{{ $user->id }}" ts="{{ time() }}">
            <div class="chatoffline">Chatting unavailable. Model is not live</div>
            <div class="chatlist"></div>
            <div class="chatfields">

              <a href="javascript:;" class="chat_tips"><i class="fas fa-coins"></i></a>
              <!-- <input type="text" class="form-control" placeholder="Say Something ...." name="chat_input"> -->
              <div class="emoji_pad" save_action="live_session_chat_send_message" save_params='{!! json_encode(['vip_member_id' => $user->id]) !!}'>
                <textarea placeholder="Say Something ...."></textarea>
                <div class="emoji_container" id="emoji_container_live"></div>
                <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
              </div>
              <div class="chat_tips_popup">
                <div class="chat_tips_popup_content">
                  <div class="row no-gutters">
                    <div class="col-md-9 col-12">
                      <input type="text" class="form-control" name="chat_tips_amount" value="" placeholder="Enter coin amount" />
                    </div>
                    <div class="col-md-3 col-12">
                      <a href="javascript:;" class="chat_tips_submit"><i class="far fa-paper-plane"></i></a>
                    </div>
                  </div>
                  <div class="ajax_response"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<?php } ?>

<section class="profile-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                <div class="profile-details-wrap">
                    <div class="profile-details">
                        <h3>{{ $user->display_name }}
                            <?php if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                                <span><i class="{{ in_array($user->id, $my_fav_user_ids) ? 'fas' : 'fa' }} fa-fire set_user_fav" data-toggle="tooltip" data-original-title="{{ in_array($user->id, $my_fav_user_ids) ? 'Remove from hot list' : 'Add to hot list' }}" user_id="{{ $user->id }}"></i></span>
                            <?php } ?>
                        </h3>
                        <p>{{ '@' . $user->username }}</p>
                        <ul>
                            <li><a href="javascript:;"><span><i class="ti-location-pin"></i></span>{{ $usermeta['address_line_1'].',' ?? '' }}{{ $countries2[$usermeta['country_id']]->iso_code_2 }}</a></li>

                            <!-- <li><a href="#"><i class="fas fa-gift"></i> <strong>My Wishlist</strong></a></li> -->
                        </ul>
                        <!-- <h4>Stay in touch</h4>
                        <button class="privet-msg-btn"><i class="fas fa-envelope"></i>Private Message</button> -->
                    </div>
                    <!-- <div class="notification-wrap d-flex justify-content-between">
                        <div class="notification-heading">
                            <h4>Get notifications</h4>
                            <a href="#">Edit Settings</a>
                        </div>
                        <div class="notification-toggle">
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                              </label>
                        </div>
                    </div> -->
                    <div class="personal-details">
                        <h4>BIO</h4>
                        <ul>
                            <?php
                            $gender = '';
                            if($user->gender == 1) $gender = 'Male';
                            if($user->gender == 2) $gender = 'Female';
                            if($user->gender == 3) $gender = 'Transgender';
                            ?>
                            <li>Gender :  <span>{{ $gender }}</span></li>
                            <li>Sexual Orientation :  <span>{{ isset($usermeta['sexual_orientation_id']) ? $sexual_orientations2[$usermeta['sexual_orientation_id']]->title : '' }}</span></li>
                            <li>Language spoken :  <span>{{ $usermeta['language_spoken'] ?? '' }}</span></li>
                            <li>Zodiac:  <span>{{ isset($usermeta['zodiac_id']) ? $zodiacs2[$usermeta['zodiac_id']]->title : '' }}</span></li>
                        </ul>
                    </div>
                    <div class="personal-details">
                        <h4>Appearance</h4>
                        <ul>
                            <li>Height :  <span>{{ !empty($usermeta['height']) ?$usermeta['height']: '' }} {{ !empty($usermeta['height']) ? 'ft':'' }}</span></li>
                            {{-- <li>Weight :  <span>{{ !empty($usermeta['weight']) ?$usermeta['weight']: '' }} {{ !empty($usermeta['weight']) ? 'kg':'' }}</span></li> --}}
                            <li>Hair Color :  <span>{{ !empty($usermeta['hair_color']) ?$usermeta['hair_color']: '' }}</span></li>
                            <li>Eye color :  <span>{{ !empty($usermeta['eye_color']) ?$usermeta['eye_color']: '' }}</span></li>
                            <li>Build :  <span>{{ !empty($usermeta['build']) ?$usermeta['build']: '' }}</span></li>
                            <li>Ethnicity :  <span>{{ !empty($usermeta['ethnicity']) ?$usermeta['ethnicity']: '' }}</span></li>
                            <li>Likes/enjoys :   <span><?php foreach ($model_attributes as $key => $value) {
                                if($key > 0) echo ', ';
                                echo $value->title;
                            } ?></span></li>
                            <li>More likes/enjoys :  <span>{{ !empty($usermeta['other_attribute']) ?$usermeta['other_attribute']: '' }}</span></li>
                        </ul>
                    </div>
                    <!-- <div class="personal-details">
                        <h4>Appearance</h4>
                        <ul>
                            <li>High Definition Video</li>
                        </ul>
                    </div> -->
                    <div class="image-wrap relative">
                        <img src="{{ url('public/front/images/prf-lft-img.png') }}" alt="">
                        <div class="percent-block">
                            Lorem  Ipsum <span>30%</span>
                        </div>
                    </div>
                    <div class="image-wrap relative">
                        <img src="{{ url('public/front/images/prf-lft-img2.png') }}" alt="">
                        <div class="percent-block2">
                            <h4>Lorem  Ipsum</h4>
                            <h4>60% OFF</h4>
                        </div>
                        <div class="description-block">
                            Lorem  Ipsum Lorem  Ipsum
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                <div class="pfr-menu">
                    <ul class="d-flex justify-content-between">
                        <li class="{{ $section == '' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '?scrollto=Cprofile-sec') }}" class="link">Home</a></li>
                        <li class="{{ $section == 'posts' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '/posts?scrollto=Cprofile-sec') }}" class="link">Posts</a></li>
                        <li class="{{ $section == 'photos' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '/photos?scrollto=Cprofile-sec') }}" class="link">Photo</a></li>
                        <li class="{{ $section == 'videos' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '/videos?scrollto=Cprofile-sec') }}" class="link">Video</a></li>
                        <li class="{{ $section == 'store' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '/store?scrollto=Cprofile-sec') }}" class="link">My Store</a></li>
                        <?php if(isset($live_session->id)) { ?>
                            {{-- <li><a class="live-btn" href="{{ url('u/' . $user->username . '/live?scrollto=Clive-sec') }}">live<span></span></a></li> --}}
                            <li><a class="live-btn" href="{{route('user_live_video',$user->username)}}" target="_blank">live<span></span></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="post-wrap">
                    <?php
                    if($section == '') {
                        echo '<p>' . nl2br(($usermeta['about_bio'] ?? '')) . '</p>';
                    }
                    if(in_array($section, ['posts', 'photos', 'videos'])) { ?>
                        @include('front.vip_member_profile.posts')
                    <?php } if(in_array($section, ['store'])) { ?>
                      @include('front.vip_member_profile.store')
                    <?php } ?>
                    <!-- <div class="type-post">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-auto">
                                <div class="back-new-post">
                                    <a class="back-new-post-btn" href="#"><span class="ti-arrow-left"></span>new post</a>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="new-post-btn">
                                    <button type="button" class="post-btn">post</button>
                                </div>
                            </div>
                        </div>
                        <div class="compose-new-post d-flex align-items-center">
                            <div class="post-user">
                                <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                            </div>
                            <div class="post-input-wrap">
                                <textarea class="form-control post-txtare-style" placeholder="compose new post.......... "></textarea>
                            </div>
                        </div>
                        <ul class="d-flex social-live-icon">
                            <li><a href="#"><span class="ti-video-camera"></span> Video</a></li>
                            <li><a href="#"><span class="ti-image"></span>Photo/Video</a></li>
                        </ul>
                    </div> -->
                    <!-- <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">30 post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">30 media</a>
                        </li>
                    </ul> -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            <!-- <div class="post-wrap-box">
                                <div class="post-wrap-box-top d-flex align-items-center">
                                    <div class="post-user-img">
                                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                    </div>
                                    <div class="post-user-details">
                                        <a href="#">
                                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                                            <p>@Bikiniwarrior</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="post-wrap-box-middle">
                                    <div class="post-only-text">
                                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio voluptatum aperiam soluta hic repudiandae, explicabo iusto laboriosam harum voluptate. Cupiditate modi accusantium iusto aliquam a officiis natus laboriosam dolore? Provident.</p>
                                    </div>
                                </div>
                                <div class="post-wrap-box-btm">
                                    <div class="row align-items-center justify-content-between post-time-wrap">
                                        <div class="col-auto">
                                            <div class="d-flex post-time-description">
                                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                                <p>Birthday Month</p>
                                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <p>1 minute 11 second</p>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                    </div>
                                    <div class="row align-items-center justify-content-between comment-love-wrap">
                                        <div class="col-auto">
                                            <ul class="d-flex comment-live-icon">
                                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-auto">
                                            <div class="option-btn-wrap relative">
                                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                                <ul class="option-btn-details">
                                                    <li><a href="#">Edit</a></li>
                                                    <li><a href="#">delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="no-comment-wrap">
                                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                                    </div>
                                    <div class="post-user-btm d-flex align-items-center">
                                        <div class="post-user-img">
                                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                        </div>
                                        <div class="comment-input-wrap relative">
                                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                                            <div class="comment-emoji">
                                                <button class="comment-emoji-btn" type="button">
                                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <!-- <div class="tab-pane" id="tabs-2" role="tabpanel">
                            <div class="post-wrap-box">
                                <div class="post-wrap-box-top d-flex align-items-center">
                                    <div class="post-user-img">
                                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                    </div>
                                    <div class="post-user-details">
                                        <a href="#">
                                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                                            <p>@Bikiniwarrior</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="post-wrap-box-middle">
                                    <div class="homeVideoInner">
                                        <video id="example_video_1" class="video-js" controls preload="none" poster="{{ url('public/front/images/video-img.jpg') }}" data-setup="{}">
                                            <source src="{{ url('public/front/media/1.mp4') }}" type="video/mp4">
                                          </video>
                                    </div>
                                </div>
                                <div class="post-wrap-box-btm">
                                    <div class="row align-items-center justify-content-between post-time-wrap">
                                        <div class="col-auto">
                                            <div class="d-flex post-time-description">
                                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                                <p>Birthday Month</p>
                                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <p>1 minute 11 second</p>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                    </div>
                                    <div class="row align-items-center justify-content-between comment-love-wrap">
                                        <div class="col-auto">
                                            <ul class="d-flex comment-live-icon">
                                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-auto">
                                            <div class="option-btn-wrap relative">
                                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                                <ul class="option-btn-details">
                                                    <li><a href="#">Edit</a></li>
                                                    <li><a href="#">delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="no-comment-wrap">
                                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                                    </div>
                                    <div class="post-user-btm d-flex align-items-center">
                                        <div class="post-user-img">
                                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                        </div>
                                        <div class="comment-input-wrap relative">
                                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                                            <div class="comment-emoji">
                                                <button class="comment-emoji-btn" type="button">
                                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="post-wrap-box">
                                <div class="post-wrap-box-top d-flex align-items-center">
                                    <div class="post-user-img">
                                        <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                    </div>
                                    <div class="post-user-details">
                                        <a href="#">
                                            <h3>KellyLouiseX <span><i class="ti-heart"></i></span></h3>
                                            <p>@Bikiniwarrior</p>
                                        </a>
                                    </div>
                                </div>
                                <div class="post-wrap-box-middle">
                                    <div class="post-subscribe-wrap-box relative d-flex align-items-center justify-content-center">
                                        <div class="post-subscribe-details">
                                            <i class="fas fa-lock"></i>
                                            <p class="post-subscribe">Please Subscribe See this user post</p>
                                            <div class="post-subscribe-btm-main">
                                                <div class="post-subscribe-btm d-flex align-items-center justify-content-center">
                                                    <p class="d-flex align-items-center"><span class="ti-video-camera"></span> <span class="live-number">2</span></p>
                                                    <a class="post-subscribe-btn" href="#">Unloke post for $6.69</a>
                                                    <p><i class="fas fa-lock"></i> $6.69</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-wrap-box-btm">
                                    <div class="row align-items-center justify-content-between post-time-wrap">
                                        <div class="col-auto">
                                            <div class="d-flex post-time-description">
                                                <span><img src="{{ url('public/front/images/confetti-lft.png') }}" alt=""></span>
                                                <p>Birthday Month</p>
                                                <span><img src="{{ url('public/front/images/confetti-rt.png') }}" alt=""></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <p>1 minute 11 second</p>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <p>Be sure to subscribe to see what i get upto my birthday <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                        <p>Plus tips & purchase are the best presents you can get me <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span></p>
                                    </div>
                                    <div class="row align-items-center justify-content-between comment-love-wrap">
                                        <div class="col-auto">
                                            <ul class="d-flex comment-live-icon">
                                                <li><a href="#"><span class="ti-heart"></span> 2</a></li>
                                                <li><a href="#"><span class="ti-comment"></span>2</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-auto">
                                            <div class="option-btn-wrap relative">
                                                <button type="button" class="option-btn"><span class="ti-more-alt"></span></button>
                                                <ul class="option-btn-details">
                                                    <li><a href="#">Edit</a></li>
                                                    <li><a href="#">delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="no-comment-wrap">
                                        <p><strong>No comments</strong> <i class="fas fa-circle"></i>yet Post a comment below</p>
                                    </div>
                                    <div class="post-user-btm d-flex align-items-center">
                                        <div class="post-user-img">
                                            <span><img src="{{ url('public/front/images/user-pic.jpg') }}" alt=""></span>
                                        </div>
                                        <div class="comment-input-wrap relative">
                                            <textarea class="form-control comment-txtare-style" placeholder="write a comment.......... "></textarea>
                                            <div class="comment-emoji">
                                                <button class="comment-emoji-btn" type="button">
                                                    <span class="inlove-emoji"><img src="{{ url('public/front/images/in-love.png') }}" alt=""></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$vip_member_price_data = [
  'subscription_price_1m' => $usermeta['subscription_price_1m'] ?? '',
  'subscription_price_1m_discounted' => $usermeta['subscription_price_1m_discounted'] ?? '',
  'subscription_price_1m_discounted_todate' => $usermeta['subscription_price_1m_discounted_todate'] ?? '',

  'subscription_price_3m' => $usermeta['subscription_price_3m'] ?? '',
  'subscription_price_3m_discounted' => $usermeta['subscription_price_3m_discounted'] ?? '',
  'subscription_price_3m_discounted_todate' => $usermeta['subscription_price_3m_discounted_todate'] ?? '',

  'subscription_price_6m' => $usermeta['subscription_price_6m'] ?? '',
  'subscription_price_6m_discounted' => $usermeta['subscription_price_6m_discounted'] ?? '',
  'subscription_price_6m_discounted_todate' => $usermeta['subscription_price_6m_discounted_todate'] ?? '',

  'subscription_price_12m' => $usermeta['subscription_price_12m'] ?? '',
  'subscription_price_12m_discounted' => $usermeta['subscription_price_12m_discounted'] ?? '',
  'subscription_price_12m_discounted_todate' => $usermeta['subscription_price_12m_discounted_todate'] ?? '',
];
$subscription_modal_data = ['vip_member' => $user, 'vip_member_price_data' => $vip_member_price_data];
?>

@include('front._partials.modal_subscribe')

@stop



@push('script')

<script>
$(document).on('click','.link',function(){
	//$(".mw_loader").show();
});
$(document).ready(function(){
	setTimeout(function() { $(".mw_loader").hide(); }, 300);
});

$(window).on('load', function() {
	//$(".mw_loader").show();
	//setTimeout(function() { $(".mw_loader").hide(); }, 1000);
});
</script>

<?php if($section == 'live') { ?>
  <script type="text/javascript">





  function session_is_offline() {
    $('#opentok_subscriber').hide();
    $('.opentok_placeholder_img').show();
    $('.chatbox').addClass('offline');
    $('.private-chat').css('display','none');
    $('.private-chat-msg').hide();
    clearInterval(myInterval);
    $('#model_low_alert').val('no');
  }


  function opentok_initializeSubSession(data) {
    var apiKey = data.apiKey;
    var sessionId = data.sessionId;
    var token = data.token;
    var session = OT.initSession(apiKey, sessionId);
    session.on('streamCreated', function(event) {
      $('#opentok_subscriber').html('');
      console.log('event',event);
      prop.opentok.subscriber = session.subscribe(event.stream, 'opentok_subscriber', {
        insertMode: 'append',
        width: '100%',
        height: '100%'
      }, handleOpentokError);
    });

    session.on('streamDestroyed', function(event) {
      //console.log('streamDestroyed = streamDestroyed');
      session_is_offline();
      reset_opentok_player_area();
    });

    session.connect(token, function(error) {
      if (error) {
        handleOpentokError(error);
      } else {
        //session.publish(publisher, handleOpentokError);
        // var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
          session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name, 'vip_id': {{ $user->id }}
          })});

      }
    });

    session.on('signal:global', function signalCallback(event) {
      console.log(event.data);
      var dt = JSON.parse(event.data);
      process_global_message(dt);
    });

    session.on('signal:msg', function signalCallback(event) {
      //console.log(event.data);
      var dt = JSON.parse(event.data);
      display_chatbox_message(dt);
    });


    /* var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
          session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name, 'vip_id': {{ $user->id }}
          })}); */
  }


  function check_user_session(params) {
    // in model details page for visitors
    var user_id = params.user_id;
    var data = new FormData();
    data.append('action', 'check_user_session');
    data.append('user_id', user_id);
    data.append('_token', prop.csrf_token);
    $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
        var ot = data.data.opentok_data;
        if(typeof prop.opentok.subscriber != 'undefined' && prop.opentok.subscriber != null) {
          var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
          session.unsubscribe(prop.opentok.subscriber);
        }
        prop.opentok.apiKey = ot.apiKey;
        prop.opentok.sessionId = ot.sessionId;
        prop.opentok.token = ot.token;
        if(ot.sessionId != '') {
          $('#opentok_subscriber').show();
          $('.opentok_placeholder_img').hide();
          $('.chatbox').removeClass('offline');
          $('.private-chat').css('display','block');
          reset_opentok_player_area();
          opentok_initializeSubSession(ot);
            // console.log(OT);
         /*  var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
          session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name, 'vip_id': {{ $user->id }}
          })}); */
        } else {
          session_is_offline();
        }
      }
    });
  }

  $(document).ready(function(){

      check_user_session({'user_id': {{ $user->id }}});

      /*  var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
          session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name, 'vip_id': {{ $user->id }}
          })}); */

  });

  $('.private-chat').click(()=>{
    //   alert();
    let conf = confirm("Are you sure you want private chat ? it charges {{ $usermeta['private_chat_charge'] }} coin per minute");
    if (conf == true) {
        // $('#block_user_'+user_id).html('blocked');
        let follower_id = prop.user_data.id;
        let vip_id = "{{ $user->id }}";

        var data = new FormData();
        data.append('action', 'check_follower_balance_for_private_chat');
        data.append('follower_id', follower_id);
        data.append('vip_id', vip_id);
        data.append('_token', prop.csrf_token);
        $.ajax(
            {type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false,
            success: function(data){
                // console.log(data);
                if(!data.data.insufficient_balance){
                    var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                    session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_private_chat_request', 'follower_id': follower_id, 'vip_id': vip_id,'follower_bal':data.data.follower_bal,'model_charge':data.data.model_charge,'follower_sub_to_models':data.data.follower_sub_to_models,'follower_detail':data.data.follower_detail})});
                }else{
                    alert('Insufficient balance for this private chat !');
                }
            }
        });
        // alert(vip_id);
        // var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
        // session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_chat_block', 'user_id': user_id, 'model_id': model_id})});


    }
  });
  </script>
<?php } ?>

@endpush
