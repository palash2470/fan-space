<?php
$countries = \App\Country::orderBy('name')->get();
$countries2 = [];
foreach ($countries as $key => $value) {
  $countries2[$value->country_id] = $value;
}
$interest_cats = \App\User_category::all();
$user_interest_cats = [];
foreach ($meta_data['interest_cats'] as $key => $value) {
  $user_interest_cats[$value->user_cat_id] = $value;
}
$own_cats = [];
foreach ($meta_data['own_cats'] as $key => $value) {
  $own_cats[$value->user_cat_id] = $value;
}
?>
<div class="profile_form">

<div class="from-wrap-page">
  <h4>Profile</h4>
  <div class="form-group from-input-wrap">
      <label for="">Email</label>
      <input type="text" name="email" id="" class="input-3" placeholder="Email" value="{{ $user_data['email'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Username</label>
      <input type="text" name="" id="" class="input-3" value="{{ $user_data['username'] }}" readonly="readonly" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Display Name</label>
      <input type="text" name="display_name" id="" class="input-3" placeholder="Display Name" value="{{ $user_data['display_name'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">First Name</label>
      <input type="text" name="first_name" id="" class="input-3" placeholder="First Name" value="{{ $user_data['first_name'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Last Name</label>
      <input type="text" name="last_name" id="" class="input-3" placeholder="Last Name" value="{{ $user_data['last_name'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Phone Number</label>
      <input type="text" name="mobile" id="" class="input-3" placeholder="Phone Number" value="{{ $user_data['phone'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <?php
      $min_dob_year = (date('Y') - 100);
      if(date('Y', strtotime($user_data['dob'])) < $min_dob_year)
        $min_dob_year = date('Y', strtotime($user_data['dob']));
      ?>
      <label for="">DOB</label>
      <input type="text" class="input-3 datepicker_single" name="dob" placeholder="dd-mm-yyyy" yearRange="{{ $min_dob_year . ':' . date('Y') }}" maxDate="{{ date('d-m-Y', strtotime("-18 year")) }}" value="{{ date('d-m-Y', strtotime($user_data['dob'])) }}" readonly />
  </div>
  <div class="form-group">
      <label for="">Category</label>
      <select name="user_cat_id" id="" class="selectMd-2">
          <option value="">-select-</option>
          <?php
          foreach ($interest_cats as $key => $value) {
            $sel = '';
            if(isset($own_cats[$value->id])) $sel = 'selected="selected"';
              ?>
              <option value="{{ $value->id }}" {{ $sel }}>{{ $value->title }}</option>
          <?php } ?>
      </select>
  </div>
  <div class="interests">
      <!-- <h3>Interests</h3> -->
      <h4>Interests</h4>
      <div class="row">
          <?php
          foreach ($interest_cats as $key => $value) {
            $chk = '';
            if(isset($user_interest_cats[$value->id])) $chk = 'checked="checked"';
              ?>
              <div class="col-lg-4 col-md-6 col-sm-6">
                  <div class="checkbox checkbox-info">
                      <input id="intcat_{{ $value->id }}" class="styled" type="checkbox" name="interest_cat_id[]" value="{{ $value->id }}" {{ $chk }} />
                      <label for="intcat_{{ $value->id }}">{{ $value->title }}</label>
                  </div>
              </div>
          <?php } ?>
      </div>
  </div> 
  <div class="form-group from-input-wrap mt-3">
    <label for="">Photo of your ID Document</label>
    <div class="file-upload active" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" no_preview>
        <div class="file-preview--- relative text-left">
            <a href="{{ url('public/uploads/id_proof_doc/' . $user_data['meta_data']['id_proof_doc']) }}" target="_blank">View / Download</a>
        </div>
        <div class="file-select">
            <div class="file-select-button" id111="fileName">Choose File</div>
            <div class="file-select-name" id111="noFile">{{ $user_data['meta_data']['id_proof_doc'] }}</div>
            <input type="file" name="id_proof_doc" id="chooseFile">
            <input type="hidden" name="id_proof_doc_removed" value="" />
            <a href="javascript:;" class="fp-close">x</a>
        </div>
    </div>
  </div>
  <div class="form-group from-input-wrap mt-3">
    <label for="">Photo of your picture ID Document (i.e. Passport or Driving License)</label>
    <div class="file-upload active" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" no_preview>
        <div class="file-preview--- relative text-left">
            <a href="{{ url('public/uploads/id_proof/' . $user_data['meta_data']['id_proof']) }}" target="_blank">View / Download</a>
        </div>
        <div class="file-select">
            <div class="file-select-button" id111="fileName">Choose File</div>
            <div class="file-select-name" id111="noFile">{{ $user_data['meta_data']['id_proof'] }}</div>
            <input type="file" name="id_proof" id="chooseFile">
            <input type="hidden" name="id_proof_removed" value="" />
            <a href="javascript:;" class="fp-close">x</a>
        </div>
    </div>
  </div>
  <div class="form-group from-input-wrap mt-3">
    <label for="">Profile Picture</label>
    <?php
    $profile_photo = $user_data['meta_data']['profile_photo'];
    ?>
    <div class="file-upload {{ $profile_photo != '' ? 'active' : '' }}" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" preview>
        <div class="file-preview">
            <img src="{{ url('public/uploads/profile_photo/' . $profile_photo) }}" class="sel_img" />
        </div>
        <div class="file-select">
            <div class="file-select-button" id111="fileName">Choose File</div>
            <div class="file-select-name" id111="noFile">{{ $profile_photo }}</div>
            <input type="file" name="profile_photo" id="chooseFile">
            <input type="hidden" name="profile_photo_removed" value="" />
            <a href="javascript:;" class="fp-close">x</a>
        </div>
    </div>
  </div>
  <div class="form-group from-input-wrap mt-3">
    <label for="">Profile Video</label>
    <?php
    $profile_video = $user_data['meta_data']['profile_video'];
    ?>
    <div class="file-upload {{ $profile_video != '' ? 'active' : '' }}" allowedExt="mp4" maxSize="20971520" maxSize_txt="20mb" no_preview>
      <div class="file-preview--- relative text-left">
            <a href="{{ url('public/uploads/profile_video/' . $profile_video) }}" target="_blank">View / Download</a>
        </div>
        <div class="file-select">
            <div class="file-select-button" id111="fileName">Choose File</div>
            <div class="file-select-name" id111="noFile">{{ $profile_video }}</div>
            <input type="file" name="profile_video" id="chooseFile">
            <input type="hidden" name="profile_video_removed" value="" />
            <a href="javascript:;" class="fp-close">x</a>
        </div>
    </div>
  </div>
  <div class="form-group from-input-wrap mt-3">
    <label for="">Profile Banner</label>
    <?php
    $profile_banner = $user_data['meta_data']['profile_banner'];
    ?>
    <div class="file-upload {{ $profile_banner != '' ? 'active' : '' }}" allowedExt="jpg,png" maxSize="10485760" maxSize_txt="10mb" preview>
        <div class="file-preview">
            <img src="{{ url('public/uploads/profile_banner/' . $profile_banner) }}" class="sel_img" />
        </div>
        <div class="file-select">
            <div class="file-select-button" id111="fileName">Choose File</div>
            <div class="file-select-name" id111="noFile">{{ $profile_banner }}</div>
            <input type="file" name="profile_banner" id="chooseFile">
            <input type="hidden" name="profile_banner_removed" value="" />
            <a href="javascript:;" class="fp-close">x</a>
        </div>
    </div>
  </div>
  <div class="form-group">
      <div class="checkbox checkbox-info mb-3">
          <input id="agreeCb4" class="styled" type="checkbox" name="allow_vip_friend_request" value="1" {{ $user_data['meta_data']['allow_vip_friend_request'] == '1' ? 'checked="checked"' : '' }} />
          <label for="agreeCb4">I would like to allow VIPs to send me friend requests</label>
      </div>
  </div>
  <?php
  /*<div class="form-group from-input-wrap">
      <label for="">Subscription Price</label>
      <input type="text" name="subscription_price" id="" class="input-3" placeholder="Subscription Price" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price'] }}" />
  </div>*/
  ?>
  <h5>Subscription Price Coins</h5>
  <!-- <div class="row">
    <div class="col-md-6 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">1 Month</label>
            <input type="text" name="subscription_price_1m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_1m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">3 Months</label>
            <input type="text" name="subscription_price_3m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_3m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">6 Months</label>
            <input type="text" name="subscription_price_6m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_6m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">12 Months</label>
            <input type="text" name="subscription_price_12m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_12m'] ?? '' }}" />
        </div>
    </div>
  </div> -->
  <h6>1 Month</h6>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Regular</label>
            <input type="text" name="subscription_price_1m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_1m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discounted</label>
            <input type="text" name="subscription_price_1m_discounted" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_1m_discounted'] ?? '' }}" />
        </div>
    </div>
    <?php
    $todate = $user_data['meta_data']['subscription_price_1m_discounted_todate'] ?? '';
    if($todate != '') $todate = date('d-m-Y', $todate);
    ?>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discount Upto</label>
            <input type="text" class="input-3 datepicker_single" name="subscription_price_1m_discounted_todate" placeholder="dd-mm-yyyy" yearRange="" maxDate="" value="{{ $todate }}" readonly />
        </div>
    </div>
  </div>
  <h6>3 Months</h6>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Regular</label>
            <input type="text" name="subscription_price_3m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_3m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discounted</label>
            <input type="text" name="subscription_price_3m_discounted" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_3m_discounted'] ?? '' }}" />
        </div>
    </div>
    <?php
    $todate = $user_data['meta_data']['subscription_price_3m_discounted_todate'] ?? '';
    if($todate != '') $todate = date('d-m-Y', $todate);
    ?>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discount Upto</label>
            <input type="text" class="input-3 datepicker_single" name="subscription_price_3m_discounted_todate" placeholder="dd-mm-yyyy" yearRange="" maxDate="" value="{{ $todate }}" readonly />
        </div>
    </div>
  </div>
  <h6>6 Months</h6>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Regular</label>
            <input type="text" name="subscription_price_6m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_6m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discounted</label>
            <input type="text" name="subscription_price_6m_discounted" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_6m_discounted'] ?? '' }}" />
        </div>
    </div>
    <?php
    $todate = $user_data['meta_data']['subscription_price_6m_discounted_todate'] ?? '';
    if($todate != '') $todate = date('d-m-Y', $todate);
    ?>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discount Upto</label>
            <input type="text" class="input-3 datepicker_single" name="subscription_price_6m_discounted_todate" placeholder="dd-mm-yyyy" yearRange="" maxDate="" value="{{ $todate }}" readonly />
        </div>
    </div>
  </div>
  <h6>12 Months</h6>
  <div class="row">
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Regular</label>
            <input type="text" name="subscription_price_12m" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_12m'] ?? '' }}" />
        </div>
    </div>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discounted</label>
            <input type="text" name="subscription_price_12m_discounted" id="" class="input-3" placeholder="" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}" value="{{ $user_data['meta_data']['subscription_price_12m_discounted'] ?? '' }}" />
        </div>
    </div>
    <?php
    $todate = $user_data['meta_data']['subscription_price_12m_discounted_todate'] ?? '';
    if($todate != '') $todate = date('d-m-Y', $todate);
    ?>
    <div class="col-md-4 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
            <label for="">Discount Upto</label>
            <input type="text" class="input-3 datepicker_single" name="subscription_price_12m_discounted_todate" placeholder="dd-mm-yyyy" yearRange="" maxDate="" value="{{ $todate }}" readonly />
        </div>
    </div>
  </div>
  <div class="form-group formInfo mt-3">
      <p><span><i class="fas fa-info-circle"></i></span> Minimum {{ number_format($meta_data['global_settings']['settings_model_subscription_min_price'], 2) }}. Maximum {{ number_format($meta_data['global_settings']['settings_model_subscription_max_price'], 2) }}. This is a monthly price admirers must pay to access your content. You dictate how much you want to charge. The money you earn will be subject to our fees and paid out to you on a weekly basis. See our T&C's for more information about this.</p>
  </div>
  <div class="form-group from-input-wrap mt-3">
      <label for="">Private Session Price Coins</label>
      <input type="text" name="private_session_price" class="form-control input-3" value="{{ $user_data['meta_data']['private_session_price'] ?? '' }}">
  </div>
  <div class="form-group from-input-wrap mt-3">
      <label for="">Location</label>
      <?php
      $location = $user_data['meta_data']['address_line_1'] ?? '';
      if(isset($user_data['meta_data']['country_id']))
        $location .= ', ' . $countries2[$user_data['meta_data']['country_id']]->iso_code_2;
      if(isset($user_data['meta_data']['zip_code']))
        $location .= ' ' . $user_data['meta_data']['zip_code'];
      ?>
      <input type="text" name="location" id="" class="form-control input-3" google_location_search_callback="vipmember_profile_google_location_search" value="{{ $location }}">
      <input type="hidden" name="address_line_1" value="{{ $user_data['meta_data']['address_line_1'] ?? '' }}" />
      <input type="hidden" name="country_code" value="{{ isset($user_data['meta_data']['country_id']) ? $countries2[$user_data['meta_data']['country_id']]->iso_code_2 : '' }}" />
      <input type="hidden" name="zip_code" value="{{ $user_data['meta_data']['zip_code'] ?? '' }}" />
  </div>  
  <div class="form-group from-input-wrap">
      <label for="">Twitter Url</label>
      <input type="text" name="twitter_url" id="" class="input-3" placeholder="Twitter Url" value="{{ $user_data['meta_data']['twitter_url'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Wishlist Url</label>
      <input type="text" name="wishlist_url" id="" class="input-3" placeholder="Wishlist Url" value="{{ $user_data['meta_data']['wishlist_url'] }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Short Bio</label>
      <input type="text" name="short_bio" id="" class="input-3" placeholder="Short Bio" value="{{ $user_data['meta_data']['short_bio'] ?? '' }}" />
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Bio</label>
      <textarea rows="" cols="" class="textArea" name="about_bio">{{ $user_data['meta_data']['about_bio'] }}</textarea>
  </div>
  <div class="form-group from-input-wrap">
      <div class="checkbox checkbox-info mb-3">
          <input id="agreeCb5" class="styled" type="checkbox" name="free_follower" value="1" {{ $user_data['meta_data']['free_follower'] == '1' ? 'checked="checked"' : '' }} />
          <label for="agreeCb5">Enable Free Followers</label>
      </div>
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Tags</label>
      <input type="text" name="profile_keywords" id="" class="input-3" placeholder="Tags" value="{{ $user_data['meta_data']['profile_keywords'] }}" />
  </div>

  <button type="button" class="commonBtn profile_submit">Update</button>                    
</div>

</div>

@push('script')

<script type="text/javascript">
    function vipmember_profile_google_location_search() {
        autocomplete_vip = new google.maps.places.Autocomplete($('.profile_form input[name="location"]')[0], {
            //componentRestrictions: { country: ["us", "ca"] },
            //fields: ["address_components", "geometry"],
            //types: ["address"],
        });
        autocomplete_vip.addListener("place_changed", function(){
            var place = autocomplete_vip.getPlace();
            //console.log(place.address_components);
            var address_line_1 = [];
            var country_code = '';
            var zipcode = '';
            $(place.address_components).each(function(i, v){
                console.log(v);
                if($.inArray('subpremise', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('street_number', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('route', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('sublocality_level_2', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('sublocality_level_1', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('locality', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('administrative_area_level_2', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('administrative_area_level_1', v.types) >= 0) address_line_1.push(v.long_name);
                if($.inArray('country', v.types) >= 0) country_code = v.short_name;
                if($.inArray('postal_code', v.types) >= 0) zipcode = v.long_name;
            });
            /*console.log(address_line_1);
            console.log(country_code);
            console.log(zipcode);*/
            $('.profile_form input[name="address_line_1"]').val(address_line_1.join(', '));
            $('.profile_form input[name="country_code"]').val(country_code);
            $('.profile_form input[name="zip_code"]').val(zipcode);
        });
    }


    $(document).ready(function(){

        

    });
</script>

@endpush