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
?>

<div class="profile_form">
  <div class="from-wrap-page">
    <h4>Profile</h4>
    <div class="form-group from-input-wrap">
      <label for="">First Name</label>
      <input type="text" name="first_name" id="" class="input-3" placeholder="First Name" value="{{ $user_data['first_name'] }}" />
    </div>
    <div class="form-group from-input-wrap">
      <label for="">Last Name</label>
      <input type="text" name="last_name" id="" class="input-3" placeholder="Last Name" value="{{ $user_data['last_name'] }}" />
    </div>
    <div class="form-group from-input-wrap">
      <label for="">Username</label>
      <input type="text" name="" id="" class="input-3" value="{{ $user_data['username'] }}" readonly="readonly" />
    </div>
    <!--<div class="form-group from-input-wrap">
      <label for="">Email</label>
      <input type="text" name="email" id="" class="input-3" placeholder="Email" value="{{ $user_data['email'] }}" />
    </div>-->
    <div class="form-group from-input-wrap relative email-box">
      <input type="hidden" name="email_otp_verify" id="email_otp_verify" value="true" />
      <label for="input-email">Email</label>
      <input type="text" name="email" id="input-email" class="input-3" placeholder="Email" value="{{ $user_data['email'] }}" readonly="readonly">
      <div class="vfWrap verifybtn" style="display:none"><a href="javascript:;" class="vfBtn sendEmailOtp"><span>Confirm text to</span>Verify</a></div>
      <div class="vfWrap verifybtn"><a href="javascript:;" class="vfBtn editEmail"> <i class="fas fa-edit"></i> Edit</a></div>
      <span class="errMsg" id="email_otp_error" style="display:none">Error</span> </div>
    
    <!--<div class="form-group from-input-wrap">
      <label for="">Phone Number</label>
      <input type="text" name="mobile" id="" class="input-3" placeholder="Phone Number" value="{{ $user_data['phone'] }}" />
  </div>-->
    <div class="form-group from-input-wrap relative phone-box">
      <input type="hidden" name="phone_otp_verify" id="phone_otp_verify" value="true" />
      <label for="">Phone Number</label>
      <input type="tel" name="mobile" id="input-phone" class="input-3" placeholder="Phone Number" value="{{ $user_data['phone'] }}" />
      <input type="hidden" id="countrycode" name="countrycode" value="1" >
      <div class="vfWrap verifybtn" style="display:none"><a href="javascript:;" class="vfBtn sendPhoneOtp"><span>Confirm text to</span>Verify</a></div>
      <div class="vfWrap verifybtn"><a href="javascript:;" class="vfBtn editPhone"> <i class="fas fa-edit"></i> Edit</a></div>
      <span class="errMsg" id="phone_otp_error" style="display:none">Error</span> </div>
    <div class="form-group from-input-wrap mt-3">
      <label for="">Profile Picture</label>
      <?php
    $profile_photo = $user_data['meta_data']['profile_photo'] ?? '';
    ?>
      <div class="file-upload {{ $profile_photo != '' ? 'active' : '' }}" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" preview>
        <div class="file-preview"> <img src="{{ url('public/uploads/profile_photo/' . $profile_photo) }}" class="sel_img" /> </div>
        <div class="file-select">
          <div class="file-select-button" id111="fileName">Choose File</div>
          <div class="file-select-name" id111="noFile">{{ $profile_photo }}</div>
          <input type="file" name="profile_photo" id="chooseFile">
          <input type="hidden" name="profile_photo_removed" value="" />
          <a href="javascript:;" class="fp-close">x</a> </div>
      </div>
    </div>
    <div class="interests----"> 
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
      <label for="">Location</label>
      <?php
      $location = $user_data['meta_data']['address_line_1'] ?? '';
      if(isset($user_data['meta_data']['country_id']))
        $location .= ', ' . $countries2[$user_data['meta_data']['country_id']]->iso_code_2;
      if(isset($user_data['meta_data']['zip_code']))
        $location .= ' ' . $user_data['meta_data']['zip_code'];
      ?>
      <input type="text" name="location" id="" class="form-control input-3" google_location_search_callback="follower_profile_google_location_search" value="{{ $location }}">
      <input type="hidden" name="address_line_1" value="{{ $user_data['meta_data']['address_line_1'] ?? '' }}" />
      <input type="hidden" name="country_code" value="{{ isset($user_data['meta_data']['country_id']) ? $countries2[$user_data['meta_data']['country_id']]->iso_code_2 : '' }}" />
      <input type="hidden" name="zip_code" value="{{ $user_data['meta_data']['zip_code'] ?? '' }}" />
    </div>
    <button type="button" class="commonBtn profile_submit">Update</button>
  </div>
</div>
@push('script') 
<script type="text/javascript">

var phone_number = window.intlTelInput(document.querySelector("#input-phone"), {
  separateDialCode: true,
  preferredCountries:["us"],
  //initialCountry: "us",
  hiddenInput: "full",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});
		
var input1 = document.querySelector("#input-phone");		
input1.addEventListener('blur', function() {
    var countryData = phone_number.getSelectedCountryData();
   
    if (input1.value.trim()) {
      //console.log(countryData);
        //$(".countryiso").val(countryData.iso2);
        $("#countrycode").val(countryData.dialCode);
     
    }
  });		

$(document).on('click', '.editPhone', function() {
    var phone = $('#input-phone').attr('readonly', false);
	$('.verifybtn').show();
	$('.editPhone').parent().hide();
	$('#phone_otp_verify').val(false);
    
});

$(document).on('click', '.sendPhoneOtp', function() {
    var phone = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
    if (phone=='') {
        $('#input-phone').removeClass('black_border').addClass('red_border');
    } else {
        $('#input-phone').removeClass('red_border').addClass('black_border');
        $('#phone_otp_password').val('');
        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                action: 'send_phone_otp',
                phone: phone,
                _token: prop.csrf_token
            },
            dataType: 'json',
            beforeSend: function() {
                $(".mw_loader").fadeIn();
            },
            complete: function() {
                $('.mw_loader').fadeOut();
            },
            success: function(data) {
				if(data.error!=''){
					alert(data.error);
				}
                if (data.success == 0) {
                    $('#phone_otp_error').show().text(data.msg);
                    $('#input-phone').removeClass('black_border').addClass('red_border');

                } else {
                    $('#phone_otp_error').hide().text('');
                    $('#input-phone').removeClass('red_border').addClass('black_border');
                    $('#phoneOtpVerifyModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('.mw_loader').fadeOut();
            }
        });
    }
});

function matchPhoneOtpPassword() {
    var otp_password = $('#phone_otp_password').val();
    if (otp_password == '') {
        $('#phone_otp_password').removeClass('black_border').addClass('red_border');
    } else {
        $.ajax({
			url: prop.ajaxurl,
            type: 'post',
            data: {
                action: 'phoneOtpValidation',
                otp_password: otp_password,
                _token: prop.csrf_token
            },
            dataType: 'json',
            success: function(data) {
				console.log(data.action);
                if (data.action == 1) {
					$('.profile_form input[name="mobile"]').closest('.form-group').find('.error').hide();
                    $('#error_phone_otp_msg').hide();
                    $('.sendPhoneOtp').parent().addClass('verified');
                    $('.verified').html('<a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a>');
                    $('#phone_otp_verify').val('true');
                    $('#phone_otp_password').removeClass('red_border').addClass('black_border');
                    $('#phoneOtpVerifyModal').modal('hide');
                } else {
                    $('#error_phone_otp_msg').text('Not macth!');
                    $('#phone_otp_password').removeClass('black_border').addClass('red_border');
                }
            }
        });

    }
}  

$(document).on('click', '.editEmail', function() {
    var email = $('#input-email').attr('readonly', false).val('');
	$('.sendEmailOtp').parent().show();
	$('.editEmail').parent().hide();
	$('#email_otp_verify').val(false);
    
});

$(document).on('click', '.sendEmailOtp', function() {
    var email 	= $('#input-email').val();
	var role 	= 3;
    if (email=='') {
        $('#input-email').removeClass('black_border').addClass('red_border');
    } else {
        $('#input-email').removeClass('red_border').addClass('black_border');
        $('#email_otp_password').val('');
        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                action: 'send_email_otp',
				role: role,
                email: email,
                _token: prop.csrf_token
            },
            dataType: 'json',
            beforeSend: function() {
                $(".mw_loader").fadeIn();
            },
            complete: function() {
                $('.mw_loader').fadeOut();
            },
            success: function(data) {
				$('.profile_form input[name="email"]').closest('.form-group').find('.error').hide();
                if (data.success == 0) {
					$('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>"+data.error_msg+"</div>");
                    $('#input-email').removeClass('black_border').addClass('red_border');
                } else {
                    $('#email_otp_error').hide().text('');
                    $('#input-email').removeClass('red_border').addClass('black_border');
                    $('#emailOtpVerifyModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                $('.mw_loader').fadeOut();
            }
        });
    }
});

function matchEmailOtpPassword() {
    var otp_password = $('#email_otp_password').val();
    if (otp_password == '') {
        $('#email_otp_password').removeClass('black_border').addClass('red_border');
    } else {
        $.ajax({
			url: prop.ajaxurl,
            type: 'post',
            data: {
                action: 'emailOtpValidation',
                otp_password: otp_password,
                _token: prop.csrf_token
            },
            dataType: 'json',
            success: function(data) {
				console.log(data.action);
                if (data.action == 1) {					
					$('.profile_form input[name="email"]').closest('.form-group').find('.error').hide();
                    $('#error_otp_msg').hide();
                    $('.sendEmailOtp').parent().addClass('verified');
                    $('.verified').html('<a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a>');
                    $('#email_otp_verify').val('true');
                    $('#email_otp_password').removeClass('red_border').addClass('black_border');
                    $('#emailOtpVerifyModal').modal('hide');
					$('#input-email').attr('readonly', true);
                } else {
                    $('#error_otp_msg').text('Not macth!').show();
                    $('#email_otp_password').removeClass('black_border').addClass('red_border');
                }
            }
        });

    }
}




    function follower_profile_google_location_search() {
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