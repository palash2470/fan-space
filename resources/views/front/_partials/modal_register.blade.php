<?php 
$interest_cats = \App\User_category::all();
?>
<section class="registerArea">
    <div class="registerBox">
        <form action="" method="get">
            <div class="registerBoxInner w-100 ">
                <div class="formBox-1 text-center">
                    <div class="registerHeader relative w-100">
                        <h2>membership signup <span> <a class="x-close" href="javascript:;">x</a></span></h2>
                    </div>
                    <div class="registerBoxBody">
                        <div class="welcomeTop w-100">
                            <div class="userImg">
                                <span><i class="fas fa-user-circle"></i></span>
                            </div>
                        </div>
                        <div class="welcomeSignup relative w-100">
                            <span>Welcome to Signup</span>
                        </div>
                        <div class="collectionPlatform relative text-center w-100">
                            <span>Select Your Talent Collection Platform</span>
                        </div>
                        <div class="radioArea relative">
                            <ul class="d-flex flex-wrap justify-content-center w-100">
                                <li class="radio radio-info">
                                    <input type="radio" name="role" id="radio1" value="3" checked="">
                                    <label for="radio1"> Become a Follower </label>
                                </li>
                                <!-- <li class="radio radio-danger">
                            <input type="radio" name="radio1" id="radio2" value="option2">
                            <label for="radio2"> For Audience</label>
                        </li> -->
                                <li class="radio radio-info">
                                    <input type="radio" name="role" id="radio3" value="2">
                                    <label for="radio3"> Become a VIP</label>
                                </li>
                            </ul>
                        </div>
                        <div class="nextStep">
                            <button type="button" class="nextStepBtn nextStepBtn-1">Next Step <i class="fas fa-chevron-circle-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="formBox-2" style="display: none;">
                    <div class="registerHeader relative w-100">
                        <h2><span> <a class="x-close" href="javascript:;">x</a></span></h2>
                    </div>
                    <div class="registerBoxBody">
                        <h2>get started</h2>
                        <div class="form-group">
                            <label for="">Forenames</label>
                            <input type="text" name="first_name" id="" class="form-control input-3" placeholder="Forenames">
                        </div>
                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" name="last_name" id="" class="form-control input-3" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <label for="">Username</label>
                            <input type="text" name="username" id="" class="form-control input-3" placeholder="Username">
                        </div>
                        <div class="form-group formInfo">
                            <p><span><i class="fas fa-info-circle"></i></span>Your username is what your account will be identified with, (@username)<br><br>Once set, this cannot be changed. Your username should be 50 characters or min 3 characters and contain only letters and numbers</p>
                        </div>
                        
                        <div class="form-group relative vrfy-btn-add">
                      <input type="hidden" name="email_otp_verify" id="email_otp_verify" value="false" />
                        <label for="input-email">Email</label>
                        <input type="text" name="email" id="input-email" class="input-3" placeholder="Email">
                        <div class="vfWrap verifybtn"><a href="javascript:;" class="vfBtn sendEmailOtp"><span>Confirm text to</span>Verify</a></div>
                        <!--<div class="vfWrap verifybtn verified"><a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a></div>-->
                        <span class="errMsg" id="email_otp_error" style="display:none">Error</span>
                      </div>
                        
                        <div class="form-group">
                            <label for="">Confirm Your Email</label>
                            <input type="text" name="confirm_email" id="" class="form-control input-3">
                        </div>
                        <div class="form-group relative vrfy-btn-add phone-box">
                      <input type="hidden" name="phone_otp_verify" id="phone_otp_verify" value="false" />
                        <label for="">Phone Number</label>
                        <input type="text" name="mobile" id="input-phone" class="input-3" placeholder="Phone Number">
                        <div class="vfWrap verifybtn"><a href="javascript:;" class="vfBtn sendPhoneOtp"><span>Confirm text to</span>Verify</a></div>
                        <span class="errMsg" id="phone_otp_error" style="display:none">Error</span>
                      </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" id="" class="form-control input-3">
                        </div>
                        <div class="passwordStrength"></div>
                        <!-- <div class="form-group formInfo">
                            <p><span><i class="fas fa-info-circle"></i></span>Please ensure your password is at least 8 characters and contains at least one uppercase letter and at least one number. </p>
                        </div> -->
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input type="password" name="confirm_password" id="" class="form-control input-3">
                        </div>
                        <!-- <div class="form-group">
                            <div class="checkbox checkbox-info">
                                <input id="formBox-2-cb" class="styled" type="checkbox" name="two_fact_auth" value="1">
                                <label for="formBox-2-cb">Enable Two Factor authantication. You will be require to verify all logins from any new device or browser via email message.</label>
                            </div>
                        </div> -->
                        <div class="nextStep text-center">
                            <button type="button" class="nextStepBtn nextStepBtn-2">Next Step <i class="fas fa-chevron-circle-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="formBox-3" style="display: none;">
                    <div class="registerHeader relative w-100">
                        <h2><span> <a class="x-close" href="javascript:;">x</a></span></h2>
                    </div>
                    <div class="registerBoxBody">
                        <div class="w-100 resercherDiv mt-3">
                            <label for="">Update Profile Picture</label>
                            <div class="file-upload" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" preview>
                                <div class="file-preview">
                                    <img src="" class="sel_img" />
                                </div>
                                <div class="file-select">
                                    <div class="file-select-button" id111="fileName">Choose File</div>
                                    <div class="file-select-name" id111="noFile"></div>
                                    <input type="file" name="profile_photo" id="chooseFile">
                                    <input type="hidden" name="profile_photo_removed" value="" />
                                    <a href="javascript:;" class="fp-close">x</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group formInfo mt-3">
                            <p><span><i class="fas fa-info-circle"></i></span>This image will represent your profile and be visible to the public. Please don't include any nudity in profile pictures... save that for your paying admirers!
                                The ideal size for your profile picture is 450x450 pixels.</p>
                        </div>
                        <div class="interests">
                            <h3>Interests</h3>
                            <div class="row">
                                <?php
                                foreach ($interest_cats as $key => $value) {
                                    ?>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <div class="checkbox checkbox-info">
                                            <input id="intcat_{{ $value->id }}" class="styled" type="checkbox" name="interest_cat_id[]" value="{{ $value->id }}" />
                                            <label for="intcat_{{ $value->id }}">{{ $value->title }}</label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="checkbox checkbox-info">
                                        <input id="Female-cb" class="styled" type="checkbox">
                                        <label for="Female-cb">Female</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="checkbox checkbox-info">
                                        <input id="male-cb" class="styled" type="checkbox">
                                        <label for="male-cb">Male</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="checkbox checkbox-info">
                                        <input id="couples-cb" class="styled" type="checkbox">
                                        <label for="couples-cb">Couples</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-6">
                                    <div class="checkbox checkbox-info">
                                        <input id="lgbtq-cb" class="styled" type="checkbox">
                                        <label for="lgbtq-cb">LGBTQ+</label>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="form-group formInfo">
                            <p><span><i class="fas fa-info-circle"></i></span>Please select the categories you are interested in-only these categories will be shown to you within the VIP listing. If you leave this empty all categories will be shown to
                                you.
                            </p>
                        </div>
                        <div class="form-group relative mt-3 mb-4">
                            <label for="">Location</label>
                            <!-- <select name="" id="" class="selectMd-2">
                                <option value="">Select Here</option>
                            </select> -->
                            <input type="text" name="location" id="" class="form-control input-3" google_location_search_callback="follower_register_google_location_search">
                            <input type="hidden" name="address_line_1" value="" />
                            <input type="hidden" name="country_code" value="" />
                            <input type="hidden" name="zip_code" value="" />
                        </div>
                        <div class="form-group relative mt-3 mb-4">
                            <div class="checkbox checkbox-info mb-3">
                                <input id="pleaseTick" class="styled" type="checkbox" name="agree_terms" value="1">
                                <label for="pleaseTick">Please tick to confirm you agree to our <a href="{{ url('/terms_conditions') }}" target="_blank">Terms and Conditions</a>.</label>
                            </div>
                            <div class="checkbox checkbox-info">
                                <input id="over-18" class="styled" type="checkbox" name="over_18" value="1">
                                <label for="over-18">I confirm that i am over 18</label>
                            </div>
                        </div>
                        <div class="form-group reChapcha">
                            <!-- <img src="{{ URL::asset('/public/front/images/recaptcha.jpg') }}" alt=""> -->
                            <div class="g-recaptcha" data-sitekey="<?php echo $meta_data['global_settings']['settings_google_recaptcha_site_key']; ?>"></div>
                        </div>
                        <div class="nextStep text-center">
                            <button type="button" class="nextStepBtn nextStepBtn-2--- register_follower">SUBMIT</button>
                        </div>
                        <div class="response">ajax response ajax response ajax response </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@push('script')
<script type="text/javascript">
var phone_number2 = window.intlTelInput(document.querySelector("#input-phone"), {
  separateDialCode: true,
  preferredCountries:["us"],
  //initialCountry: "us",
  hiddenInput: "full",
  utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
});
$(document).on('click', '.sendPhoneOtp', function() {
    var phone = phone_number2.getNumber(intlTelInputUtils.numberFormat.E164);
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
					$('.registerArea input[name="mobile"]').closest('.form-group').find('.error').hide();
					$('.bcmVipArea input[name="mobile"]').closest('.form-group').find('.error').hide();
                    $('#error_phone_otp_msg').hide();
                    $('.sendPhoneOtp').parent().addClass('verified');
                    $('.verified').html('<a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a>');
                    $('#phone_otp_verify').val('true');
                    $('#phone_otp_password').removeClass('red_border').addClass('black_border');
                    $('#phoneOtpVerifyModal').modal('hide');
					$('#input-phone').attr('readonly', true);
                } else {
                    $('#error_phone_otp_msg').text('Not macth!');
                    $('#phone_otp_password').removeClass('black_border').addClass('red_border');
                }
            }
        });
    }
}  
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
				$('.registerArea input[name="email"]').closest('.form-group').find('.error').hide();
                if (data.success == 0) {
					$('.bcmVipArea input[name="email"]').closest('.form-group').append("<div class='error'>"+data.error_msg+"</div>");
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
					$('.registerArea input[name="email"]').closest('.form-group').find('.error').hide();
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
</script>
<script type="text/javascript">
    function follower_register_google_location_search() {
        autocomplete = new google.maps.places.Autocomplete($('.registerArea input[name="location"]')[0], {
            //componentRestrictions: { country: ["us", "ca"] },
            //fields: ["address_components", "geometry"],
            //types: ["address"],
        });
        autocomplete.addListener("place_changed", function(){
            var place = autocomplete.getPlace();
            console.log(place);
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
            $('.registerArea input[name="address_line_1"]').val(address_line_1.join(', '));
            $('.registerArea input[name="country_code"]').val(country_code);
            $('.registerArea input[name="zip_code"]').val(zipcode);
        });
    }
    $(document).ready(function(){
        
    });
</script>
@endpush