{{-- <style>
.overlay {
  background: rgba(0, 0, 0, 0.5) ;
  display: none;
  height: 100%;
  left: 0;
  overflow-y: auto;
  position: fixed;
  top: 0;
  width: 100%;
}
.innerArea {
	max-width:600px;
	min-height:400px;
	background:#fff;
	margin:100px auto 100px;
	position:relative;
	padding:30px;
	
	}
.dismiss {
  background: #f00 none repeat scroll 0 0;
  border-radius: 50%;
  color: #fff;
  font-size: 30px;
  height: 50px;
  line-height: 50px;
  position: absolute;
  right: -25px;
  text-align: center;
  text-decoration: none;
  top: -25px;
  width: 50px;
}		
</style> --}}


<?php
// dd('ddd');
$section = $_GET['section'] ?? '';
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
$model_attributes2 = $meta_data['model_attributes'];
$vip_member_model_attributes = $meta_data['vip_member_model_attributes'];
$vip_member_model_attribute_ids = [];
foreach ($vip_member_model_attributes as $key => $value) {
  $vip_member_model_attribute_ids[] = $value->model_attribute_id;
}
$banner = $usermeta['profile_banner'] ?? '';
if($banner != '') $banner = url('public/uploads/profile_banner/' . $banner);
//$user_data['meta_data']
?>


<?php $composite_banners = json_decode(($user_data['meta_data']['composite_banners'] ?? '{}'), true); ?>
<section class="profileBanner relative bCover d-flex align-items-center justify-content-center">
    <div class="profileBannerInner">
        <div class="row">
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
        </div>
        <!-- <a href="javascript:;" class="commonBtn profile_banner_edit">Edit</a> -->
    </div>
    <?php
    $profile_photo = URL::asset('/public/front/images/user-placeholder.jpg');
    if(isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '')
      $profile_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
    ?>
    <div class="profile-banner-wrap relative">
        {{-- <div class="option-top-rgt">
            <div class="option-top-rgt-wrap relative">
                <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                <ul class="option-rgt-details">
                    <li><a href="#">option1</a></li>
                    <li><a href="#">option2</a></li>
                    <li><a href="javascript:;" class="report_user" user_id="{{ $user->id }}">Report User</a></li>
                </ul>
            </div>
        </div> --}}
        <div class="profileBannerWrapInner d-flex flex-wrap w-100">
            <div class="profile-banner-lft d-flex">
            <div class="pblLeft">
                <span><img src="{{ $profile_photo??'' }}" alt=""></span>
            </div>
            <div class="pblRight">
                <h3>{{ $user->display_name }}
                    <?php if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                        <span><i class="{{ in_array($user->id, $my_fav_user_ids) ? 'fas' : 'far' }} fa-heart set_user_fav" user_id="{{ $user->id }}"></i></span>
                    <?php } ?>
                </h3>
                <h5>{{ '@' . $user->username }}</h5>
                <p>{{ $user_data['meta_data']['short_bio'] ?? '' }}</p>

            </div>

            </div>
            <div class="profile-banner-rgt">
                <ul>
                    <?php /*if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                        <li><a class="subscribe-Btn" href="javascript:;" data-toggle="modal" data-target="#subscribeModal">Subscribe for £ 4.50</a></li>
                        <li><a href="javascript:;" class="follow-Btn set_user_follow" user_id="{{ $user->id }}">{!! in_array($user->id, $my_follow_user_ids) ? '<i class="fas fa-thumbs-down"></i> Unfollow' : '<i class="fas fa-thumbs-up"></i> Follow' !!}</a></li>
                    <?php }*/ ?>
                </ul>
                <a href="javascript:;" class="commonBtn profile_banner_cont_edit">Edit</a>
            </div>
        </div>
    </div>
</section>
<a href="javascript:;" class="commonBtn m-t-10 profile_banner_edit">Edit</a>

<section class="profile-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="profile-details-wrap">
                    <div class="profile-details">
                        <h3>{{ $user->display_name }}
                            <?php /*if(isset($user_data['role']) && $user_data['role'] == 3) { ?>
                                <span><i class="{{ in_array($user->id, $my_fav_user_ids) ? 'fas' : 'far' }} fa-heart set_user_fav" user_id="{{ $user->id }}"></i></span>
                            <?php }*/ ?>
                        </h3>
                        <p>{{ '@' . $user->username }}</p>
                        <ul>
                            <li><a href="javascript:;"><span><i class="ti-location-pin"></i></span>{{ $user_data['meta_data']['address_line_1'].',' ?? '' }} {{ $countries2[$user_data['meta_data']['country_id']]->iso_code_2 }}</a></li>

                        </ul>

                    </div>

                    <div class="personal-details">
                        <h4><strong>BIO</strong></h4>
                        <ul>
                            <?php
                            $gender = '';
                            if($user->gender == 1) $gender = 'Male';
                            if($user->gender == 2) $gender = 'Female';
                            if($user->gender == 3) $gender = 'Transgender';
                            if($user->gender == 4) $gender = 'Non-binary';
                            ?>
                            <li>Gender :  <span>{{ $gender }}</span></li>
                            <li>Sexual Orientation :  <span>{{ isset($user_data['meta_data']['sexual_orientation_id']) ? $sexual_orientations2[$user_data['meta_data']['sexual_orientation_id']]->title : '' }}</span></li>
                            <li>Language spoken :  <span>{{ $user_data['meta_data']['language_spoken'] ?? '' }}</span></li>
                            <li>Zodiac:  <span>{{ isset($user_data['meta_data']['zodiac_id']) ? $zodiacs2[$user_data['meta_data']['zodiac_id']]->title : '' }}</span></li>
                        </ul>
                    </div>
                    <div class="personal-details">
                        <h4>Appearance</h4>
                        <ul>
                            <li>Height :  <span>{{ $user_data['meta_data']['height'] ?? '' }} ft</span></li>
                            {{-- <li>Weight :  <span>{{ $user_data['meta_data']['weight'] ?? '' }}kg</span></li> --}}
                            <li>Hair Color :  <span>{{ $user_data['meta_data']['hair_color'] ?? '' }}</span></li>
                            <li>Eye color :  <span>{{ $user_data['meta_data']['eye_color'] ?? '' }}</span></li>
                            <li>Build :  <span>{{ $user_data['meta_data']['build'] ?? '' }}</span></li>
                            <li>Ethnicity :  <span>{{ $user_data['meta_data']['ethnicity'] ?? '' }}</span></li>
                            <!--<li>Cup Size :  <span>{{-- isset($user_data['meta_data']['cupsize_id']) ? $cupsizes2[$user_data['meta_data']['cupsize_id']]->title : '' --}}{{ $user_data['meta_data']['cupsize'] ?? '' }}</span></li>
                            <li>Pubic Hair :  <span>{{ $user_data['meta_data']['public_hair'] ?? '' }}</span></li>
                            <li>Measurements :  <span>{{ $user_data['meta_data']['measurements'] ?? '' }}inch</span></li>-->
                            <li>Likes/enjoys :   <span><?php foreach ($model_attributes as $key => $value) {
                                if($key > 0) echo ', ';
                                echo $value->title;
                            } ?></span></li>

                            <li>More likes/enjoys :  <span>{{ $user_data['meta_data']['other_attribute'] ?? '' }}</span></li>

                        </ul>
                        <a href="javascript:;" class="commonBtn profile_leftcont_edit">Edit</a>
                        <a href="javascript:;" class="clickBtn profile_edit_for_mobile">EDIT</a>
                    </div>
                    <!-- <div class="personal-details">
                        <h4>Appearance</h4>
                        <ul>
                            <li>High Definition Video</li>
                        </ul>
                    </div> -->
                    <!-- <div class="image-wrap relative">
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
                    </div> -->
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-6 col-12">
                <div class="pfr-menu">
                    <ul class="d-flex justify-content-between">
                        <li class="{{ $section == '' ? 'active' : '' }}"><a href="{{ url('dashboard/profile/?scrollto=Cprofile-sec') }}">Home</a></li>
                        <li class="{{ $section == 'posts' ? 'active' : '' }}"><a href="{{ url('dashboard/profile/?scrollto=Cprofile-sec&section=posts') }}">Posts</a></li>
                        <li class="{{ $section == 'photos' ? 'active' : '' }}"><a href="{{ url('dashboard/profile/?scrollto=Cprofile-sec&section=photos') }}">Photo</a></li>
                        <li class="{{ $section == 'videos' ? 'active' : '' }}"><a href="{{ url('dashboard/profile/?scrollto=Cprofile-sec&section=videos') }}">Video</a></li>
                        <!-- <li class="{{ $section == 'store' ? 'active' : '' }}"><a href="{{ url('u/' . $user->username . '/store?scrollto=Cprofile-sec') }}">My Store</a></li> -->
                        <?php /*if((time() - strtotime($user->last_activity)) < $helper_settings['user_online_time_flag']) { ?>
                            <li><a class="live-btn" href="javascript:;">live<span></span></a></li>
                        <?php }*/ ?>
                    </ul>
                </div>
                <div class="post-wrap">
                    <?php
                    if($section == '') {
                        echo '<p>' . nl2br(($usermeta['about_bio'] ?? '')) . '</p>
                        <a href="javascript:;" class="commonBtn profile_rightcont_edit">Edit</a>';
                    }
                    if(in_array($section, ['posts', 'photos', 'videos'])) { ?>
                        @include('user.includes.vip_member.profile.posts')
                    <?php } ?>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modalOverlay" id="modalOverlay">
	<div class="innerArea">
    
    <div class="row">
    <div class="col-12">
        <h4>Edit</h4>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <?php
                            $opt1 = $opt2 = $opt3 = '';
                            if($user_data['gender'] == 1) $opt1 = 'selected="selected"';
                            if($user_data['gender'] == 2) $opt2 = 'selected="selected"';
                            if($user_data['gender'] == 3) $opt3 = 'selected="selected"';
                            ?>
        <label for="">Gender</label>
        <select name="gender" id="" class="selectMd-2">
            <option value="">-- Select --</option>
            <option value="1" {{ $opt1 }}>Male</option>
            <option value="2" {{ $opt2 }}>Female</option>
        </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Sexual Orientation</label>
        <select name="sexual_orientation_id" id="" class="selectMd-2">
            <option value="">--- Select ---</option>
            <?php
                                foreach ($sexual_orientations as $key => $value) {
                                    $sel = '';
                                    if(isset($user_data['meta_data']['sexual_orientation_id']) && $user_data['meta_data']['sexual_orientation_id'] == $value->id)
                                    $sel = 'selected="selected"';
                                    echo '<option value="' . $value->id . '" ' . $sel . '>' . $value->title . '</option>';
                                }
                                ?>
        </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Language spoken</label>
        <input type="text" name="language_spoken" id="" class="input-3" placeholder="Language Spoken" value="{{ $user_data['meta_data']['language_spoken'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Zodiac</label>
        <select name="zodiac_id" id="" class="selectMd-2">
            <option value="">--- Select ---</option>
            <?php
                                foreach ($zodiacs as $key => $value) {
                                    $sel = '';
                                    if(isset($user_data['meta_data']['zodiac_id']) && $user_data['meta_data']['zodiac_id'] == $value->id)
                                    $sel = 'selected="selected"';
                                    echo '<option value="' . $value->id . '" ' . $sel . '>' . $value->title . '</option>';
                                }
                                ?>
        </select>
        </div>
    </div>
    <div class="col-12">
        <h4>Appearance</h4>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Height (in cm)</label>
        <input type="text" name="height" id="" class="input-3" placeholder="Height (in cm)" value="{{ $user_data['meta_data']['height'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Weight (in kg)</label>
        <input type="text" name="weight" id="" class="input-3" placeholder="Weight (in kg)" value="{{ $user_data['meta_data']['weight'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Eye Color</label>
        <input type="text" name="eye_color" id="" class="input-3" placeholder="Eye Color" value="{{ $user_data['meta_data']['eye_color'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Hair Color</label>
        <input type="text" name="hair_color" id="" class="input-3" placeholder="Hair Color" value="{{ $user_data['meta_data']['hair_color'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Build</label>
        {{-- <input type="text" name="build" id="" class="input-3" placeholder="Build" value="{{ $user_data['meta_data']['build'] ?? '' }}" /> --}}

        <select name="build" id="build" class="selectMd-2">
            <option value="">--- Select ---</option>
            <option value="Athletic" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Athletic') selected @endif>Athletic</option>

            <option value="Toned" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Toned') selected @endif>Toned</option>

            <option value="Slim" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Slim') selected @endif>Slim</option>

            <option value="Average" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Average') selected @endif>Average</option>

            <option value="Curvy" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Curvy') selected @endif>Curvy</option>

            <option value="Ample" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Ample') selected @endif>Ample</option>

            <option value="Large" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Large') selected @endif>Large</option>

            <option value="Muscular" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Muscular') selected @endif>Muscular</option>
        </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Ethnicity</label>
        <input type="text" name="ethnicity" id="" class="input-3" placeholder="Ethnicity" value="{{ $user_data['meta_data']['ethnicity'] ?? '' }}" />
        </div>
    </div>
    
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
        <label for="">Likes/enjoys</label>
        </div>
    </div>
    <?php
        foreach ($model_attributes2 as $key => $value) {
                        $chk = '';
                        if(in_array($value->id, $vip_member_model_attribute_ids))
                            $chk = 'checked="checked"';
                        echo '<div class="form-group from-input-wrap col-md-6 col-lg-4 col-sm-12 col-12">
                        <div class="checkbox checkbox-info">
                            <input id="vmmaii' . $key . '" class="styled" type="checkbox" name="model_attributes[' . $value->id . ']" value="' . $value->id . '" ' . $chk . ' />
                            <label for="vmmaii' . $key . '">' . $value->title . '</label>
                        </div>
                        </div>';
                        }
            ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
        <label for="">More Likes</label>
        <input type="text" name="other_attribute" id="other_attribute" class="input-3" placeholder="Other Attribute" value="{{ $user_data['meta_data']['other_attribute'] ?? '' }}" />
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
    <a href="javascript:;" class="commonBtn update_profile_leftcont">Save</a>
    </div>
    </div>
    <a href="javascript:;" class="dismiss">x</a>
    </div>
</div>
@include('user.includes.vip_member._partials.profile_edit_modals')
@include('front._partials.modal_crop_image')


@push('script')

<script type="text/javascript">
    $(document).ready(function(){

        $(".clickBtn").click(function(){
	  	$(".modalOverlay").fadeIn();
	  });
	  
	  $(".dismiss , .modalOverlay").click(function(){
		  $(".modalOverlay").fadeOut();
	  });
	  
	  $( ".innerArea" ).click(function( event ) {
		  event.stopPropagation();
		  // Do something
		  });

        $(document).on('click', '.profile_banner_edit', function(){
          $("#bannerEditModal").modal({
              backdrop: 'static',
              keyboard: false
            });
        });

        $(document).on('click', '#bannerEditModal .update_profile_banner', function(){
          var composite_banners = {};
          $('#bannerEditModal .crop_image_uploader').each(function(){
            var composite_order = $(this).attr('composite_order');
            composite_banners[('ban_' + composite_order)] = '';
            if($(this).hasClass('active')) {
              composite_banners[('ban_' + composite_order)] = $(this).find('img.imgpreviewPrf').attr('src');
            }
          });
          var data = new FormData();
          data.append('action', 'update_vip_member_profile_modal_banner');
          data.append('composite_banners', JSON.stringify(composite_banners));
          data.append('_token', prop.csrf_token);
          $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
              if(data.success == 1) {
                location.reload();
              }
            }
          });
        });


        $(document).on('click', '.profile_banner_cont_edit', function(){
          $("#bannercontEditModal").modal({
              backdrop: 'static',
              keyboard: false
            });
        });

        $(document).on('click', '#bannercontEditModal .update_profile_banner_cont', function(){
          var display_name = $.trim($('#bannercontEditModal input[name="display_name"]').val());
          var profile_photo_removed = $.trim($('#bannercontEditModal input[name="profile_photo_removed"]').val());
          var short_bio = $.trim($('#bannercontEditModal textarea[name="short_bio"]').val());
          var error = 0;
          $(".error").remove();
          if(display_name == "") {
            $('#bannercontEditModal input[name="display_name"]').closest('.form-group').append("<div class='error'>Enter display name</div>");
            error = 1;
          }
          if(error == 0) {
            var profile_photo = $('#bannercontEditModal input[name="profile_photo"]')[0];
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_vip_member_profile_modal_banner_cont');
            data.append('display_name', display_name);
            data.append('short_bio', short_bio);
            if(profile_photo.files.length > 0)
              data.append('profile_photo', profile_photo.files[0]);
            data.append('profile_photo_removed', profile_photo_removed);
            data.append('_token', prop.csrf_token);
            $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
                if(data.success == '1') {
                  location.reload();
                }
              }
            });
          }
        });


        $(document).on('click', '.profile_rightcont_edit', function(){
          $("#bannerRightcontEditModal").modal({
              backdrop: 'static',
              keyboard: false
            });
        });

        $(document).on('click', '#bannerRightcontEditModal .update_profile_rightcont', function(){
          var about_bio = $.trim($('#bannerRightcontEditModal textarea[name="about_bio"]').val());
          var error = 0;
          $(".error").remove();
          if(about_bio == "") {
            $('#bannerRightcontEditModal textarea[name="about_bio"]').closest('.form-group').append("<div class='error'>Put your bio</div>");
            error = 1;
          }
          if(error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_vip_member_profile_modal_rightcont');
            data.append('about_bio', about_bio);
            data.append('_token', prop.csrf_token);
            $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
                if(data.success == '1') {
                  location.reload();
                }
              }
            });
          }
        });

        $(document).on('click', '.profile_leftcont_edit', function(){
          $("#bannerLeftcontEditModal").modal({
              backdrop: 'static',
              keyboard: false
            });
        });

        $(document).on('click', '#bannerLeftcontEditModal .update_profile_leftcont', function(){
          var gender = $('#bannerLeftcontEditModal select[name="gender"]').val();
          var sexual_orientation_id = $('#bannerLeftcontEditModal select[name="sexual_orientation_id"]').val();
          var language_spoken = $.trim($('#bannerLeftcontEditModal input[name="language_spoken"]').val());
          var zodiac_id = $('#bannerLeftcontEditModal select[name="zodiac_id"]').val();
          var height = $.trim($('#bannerLeftcontEditModal input[name="height"]').val());
          var weight = $.trim($('#bannerLeftcontEditModal input[name="weight"]').val());
          var eye_color = $.trim($('#bannerLeftcontEditModal input[name="eye_color"]').val());
          var hair_color = $.trim($('#bannerLeftcontEditModal input[name="hair_color"]').val());
          var build = $.trim($('#bannerLeftcontEditModal select[name="build"]').val());
          var ethnicity = $.trim($('#bannerLeftcontEditModal input[name="ethnicity"]').val());
          //var cupsize_id = $('#bannerLeftcontEditModal select[name="cupsize_id"]').val();
          var cupsize = $.trim($('#bannerLeftcontEditModal input[name="cupsize"]').val());
          var public_hair = $.trim($('#bannerLeftcontEditModal input[name="public_hair"]').val());
          var measurements = $.trim($('#bannerLeftcontEditModal input[name="measurements"]').val());
		  var other_attribute = $.trim($('#bannerLeftcontEditModal input[name="other_attribute"]').val());

		  var address_line_1 = $.trim($('#bannerLeftcontEditModal input[name="address_line_1"]').val());
		  var country_id = $.trim($('#bannerLeftcontEditModal select[name="country_id"]').val());


          var model_attributes = [];
          $('#bannerLeftcontEditModal input[name^="model_attributes"]:checked').each(function(i, v){
            model_attributes.push($(this).val());
          });
          console.log(model_attributes);
          var error = 0;
          $(".error").remove();
          if(gender == '') {
            $('#bannerLeftcontEditModal select[name="gender"]').closest('.form-group').append("<div class='error'>Select gender</div>");
            error = 1;
          }
          if(error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_vip_member_profile_modal_leftcont');
            data.append('gender', gender);
            data.append('sexual_orientation_id', sexual_orientation_id);
            data.append('language_spoken', language_spoken);
            data.append('zodiac_id', zodiac_id);
            data.append('height', height);
            data.append('weight', weight);
            data.append('eye_color', eye_color);
            data.append('hair_color', hair_color);
            data.append('build', build);
            data.append('ethnicity', ethnicity);
            //data.append('cupsize_id', cupsize_id);
            data.append('cupsize', cupsize);
            data.append('public_hair', public_hair);
            data.append('measurements', measurements);
			data.append('model_attributes', model_attributes);
            data.append('other_attribute', other_attribute);
            data.append('address_line_1', address_line_1);
            data.append('country_id', country_id);
            data.append('_token', prop.csrf_token);
            $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
                if(data.success == 1) {
                  location.reload();
                }
              }
            });
          }
        });

        $('#modalOverlay .update_profile_leftcont').click(function(){
            var gender = $('#modalOverlay select[name="gender"]').val();
          var sexual_orientation_id = $('#modalOverlay select[name="sexual_orientation_id"]').val();
          var language_spoken = $.trim($('#modalOverlay input[name="language_spoken"]').val());
          var zodiac_id = $('#modalOverlay select[name="zodiac_id"]').val();
          var height = $.trim($('#modalOverlay input[name="height"]').val());
          var weight = $.trim($('#modalOverlay input[name="weight"]').val());
          var eye_color = $.trim($('#modalOverlay input[name="eye_color"]').val());
          var hair_color = $.trim($('#modalOverlay input[name="hair_color"]').val());
          var build = $.trim($('#modalOverlay select[name="build"]').val());
          var ethnicity = $.trim($('#modalOverlay input[name="ethnicity"]').val());
          //var cupsize_id = $('#bannerLeftcontEditModal select[name="cupsize_id"]').val();
          var cupsize = $.trim($('#modalOverlay input[name="cupsize"]').val());
          var public_hair = $.trim($('#modalOverlay input[name="public_hair"]').val());
          var measurements = $.trim($('#modalOverlay input[name="measurements"]').val());
		  var other_attribute = $.trim($('#modalOverlay input[name="other_attribute"]').val());
          var model_attributes = [];
          $('#modalOverlay input[name^="model_attributes"]:checked').each(function(i, v){
            model_attributes.push($(this).val());
          });
          var error = 0;
          $(".error").remove();
          if(gender == '') {
            $('#modalOverlay select[name="gender"]').closest('.form-group').append("<div class='error'>Select gender</div>");
            error = 1;
          }
          if(error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_vip_member_profile_modal_leftcont');
            data.append('gender', gender);
            data.append('sexual_orientation_id', sexual_orientation_id);
            data.append('language_spoken', language_spoken);
            data.append('zodiac_id', zodiac_id);
            data.append('height', height);
            data.append('weight', weight);
            data.append('eye_color', eye_color);
            data.append('hair_color', hair_color);
            data.append('build', build);
            data.append('ethnicity', ethnicity);
            //data.append('cupsize_id', cupsize_id);
            data.append('cupsize', cupsize);
            data.append('public_hair', public_hair);
            data.append('measurements', measurements);
			data.append('model_attributes', model_attributes);
            data.append('other_attribute', other_attribute);
            data.append('_token', prop.csrf_token);
            $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
                if(data.success == 1) {
                  location.reload();
                }
              }
            });
          }

        })

    });
    </script>

<script type="text/javascript">
  $(document).ready(function() {

    $('#upload-demo').croppie({
        viewport: {
            width: 400,
            height: 271,
            // type: 'circle'
        },
        enforceBoundary: false,
        enableExif: true
    });

    $(document).on('click', '.crop_image_uploader .remove_photo', function(){
      var def_img = $(this).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('def_img');
      $(this).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', def_img);
      $(this).closest('.crop_image_uploader').removeClass('active');
    });


    $('.item-img').unbind('change').on('change', function() {
      var that = this;
      var def_img = $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('def_img');
      $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', def_img);
      $(that).closest('.crop_image_uploader').removeClass('active');
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.upload-demo').addClass('ready');
            $('#cropImagePop').modal('show');
            rawImg = e.target.result;
            $('#cropImagePop').unbind('shown.bs.modal').on('shown.bs.modal', function() {
              $('.cr-slider-wrap p').remove();
              $('.cr-slider-wrap').prepend('<p>Image Zoom</p>');
              $('#upload-demo').croppie('bind', {
                  url: rawImg
              }).then(function() {
                  console.log('jQuery bind complete');
              });
            });
            $('.replacePhoto').unbind('click').on('click', function() {
                $('#cropImagePop').modal('hide');
                $(that).trigger('click');
            });
            $('#cropImageBtn').unbind('click').on('click', function(ev) {
                $('#upload-demo').croppie('result', {
                    type: 'base64',
                    format: 'jpeg',
                    size: {
                        width: 400,
                        height: 271,
                    }
                }).then(function(resp) {
                  $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', resp);
                  $(that).closest('.crop_image_uploader').addClass('active');
                  $('#cropImagePop').modal('hide');
                });
            });
        }
        reader.readAsDataURL(this.files[0]);
      }
    });



  });
</script>

@endpush
