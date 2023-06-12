function loginModal() {
    $(document).on('click', '.loginBtn', function() {
        $(".loginArea").addClass("open");
        $('.loginBody[for_forgot_password]').hide();
        $('.loginBody[for_login]').show();
        $("body").addClass("scrollHide");
    });
    $(document).on('click', '.loginArea .x-close', function() {
        $(".loginArea").removeClass("open");
        $("body").removeClass("scrollHide");
    });
    $(document).on('click', '.loginArea .to_forgot_password', function() {
        $('.loginBody[for_forgot_password]').show();
        $('.loginBody[for_login]').hide();
    });
    $(document).on('click', '.loginArea .to_login', function() {
        $('.loginBody[for_forgot_password]').hide();
        $('.loginBody[for_login]').show();
    });
    $('.loginArea form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    $(document).on('click', '.login_submit', function() {
        var role = $('.loginArea [for_login] select[name="role"]').val();
        var email = $.trim($('.loginArea [for_login] input[name="email"]').val());
        var password = $.trim($('.loginArea [for_login] input[name="password"]').val());
        var remember = $('.loginArea [for_login] input[name="remember"]:checked').length;
        //var g_recaptcha_response = $.trim($('.loginArea textarea[name="g-recaptcha-response"]').val());
        var error = 0;
        var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
        $(".error").remove();
        $('.loginArea .response').html('');
        if (email_pattern.test(email) == false) {
            $('.loginArea [for_login] input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
            error = 1;
        }
        if (password == "") {
            $('.loginArea [for_login] input[name="password"]').closest('.form-group').append("<div class='error'>Enter password</div>");
            error = 1;
        }
        /* if (g_recaptcha_response == '') {
            $('.loginArea textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>Validate captcha</div>");
            error = 1;
        } */
        if (error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'login_check');
            data.append('role', role);
            data.append('email', email);
            data.append('password', password);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == 0) {
                        $(".mw_loader").hide();
                        $('.loginArea [for_login] .response').html('<div class="error">' + data.message + '</div>');
                    }
                    if (data.success == 1) {
                        $('.loginArea [for_login] form').submit();
                    }
                }
            });
        }
    });
    $(document).on('click', '.forgot_password_submit', function() {
        var email = $.trim($('.loginArea [for_forgot_password] input[name="email"]').val());
        var error = 0;
        var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
        $(".error").remove();
        $('.loginArea .response').html('');
        if (email_pattern.test(email) == false) {
            $('.loginArea [for_forgot_password] input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
            error = 1;
        }
        if (error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'forgot_password');
            data.append('email', email);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".mw_loader").hide();
                    if (data.success == 0) {
                        $('.loginArea [for_forgot_password] .response').html('<div class="error">' + data.message + '</div>');
                    }
                    if (data.success == 1) {
                        $('.loginArea [for_forgot_password] .response').html('<div class="success">' + data.message + '</div>');
                    }
                }
            });
        }
    });
    $(document).on('click', '[social_login]', function() {
        var role = $('.loginArea [for_login] select[name="role"]').val();
        var type = $(this).attr('social_login');
        var error = 0;
        $(".error").remove();
        $('.loginArea .response').html('');
        if (error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'social_login');
            data.append('role', 3);
            data.append('type', type);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1')
                        window.location.href = data.data.redirect_url;
                }
            });
        }
    });
}

function validate_follower_register(params) {
    var step = params.step;
    var first_name = $.trim($('.registerArea input[name="first_name"]').val());
    var last_name = $.trim($('.registerArea input[name="last_name"]').val());
    var username = $.trim($('.registerArea input[name="username"]').val());
    var email = $.trim($('.registerArea input[name="email"]').val());
    var confirm_email = $.trim($('.registerArea input[name="confirm_email"]').val());
    //var mobile = $.trim($('.registerArea input[name="mobile"]').val());
    var mobile = phone_number2.getNumber(intlTelInputUtils.numberFormat.E164);

    var phone_otp_verify = $.trim($('.registerArea input[name="phone_otp_verify"]').val());
    var password = $.trim($('.registerArea input[name="password"]').val());
    var confirm_password = $.trim($('.registerArea input[name="confirm_password"]').val());
    var interest_cat_count = $('.registerArea input[name^="interest_cat_id"]:checked').length;
    var address_line_1 = $.trim($('.registerArea input[name="address_line_1"]').val());
    var country_code = $.trim($('.registerArea input[name="country_code"]').val());
    var zip_code = $.trim($('.registerArea input[name="zip_code"]').val());
    var agree_terms = $('.registerArea input[name="agree_terms"]:checked').length;
    var over_18 = $('.registerArea input[name="over_18"]:checked').length;
    var g_recaptcha_response = $.trim($('.registerArea textarea[name="g-recaptcha-response"]').val());
    var interest_cats = [];
    $('.registerArea input[name^="interest_cat_id"]:checked').each(function() {
        interest_cats.push($(this).val());
    });
    var error = 0;
    var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    var username_pattern = /^[a-z0-9_]{3,50}$/igm
    $(".error").remove();
    $('.registerArea .response').html('');
    if ($.inArray(step, [2, 3]) >= 0) {
        if (first_name == "") {
            $('.registerArea input[name="first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
            error = 1;
        }
        if (last_name == "") {
            $('.registerArea input[name="last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
            error = 1;
        }
        if (username_pattern.test(username) == false) {
            $('.registerArea input[name="username"]').closest('.form-group').append("<div class='error'>Enter valid username</div>");
            error = 1;
        }
        if (email_pattern.test(email) == false) {
            $('.registerArea input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
            error = 1;
        }
        if (email != '' && email != confirm_email) {
            $('.registerArea input[name="confirm_email"]').closest('.form-group').append("<div class='error'>Confirm email mismatch</div>");
            error = 1;
        }

        if (mobile != "" && phone_otp_verify == 'false') {
            $('.registerArea input[name="mobile"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
            error = 1;
        }
        if (mobile == "") {
            $('.registerArea input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter valid mobile</div>");
            error = 1;
        }


        if (password == "") {
            $('.registerArea input[name="password"]').closest('.form-group').append("<div class='error'>Enter password</div>");
            error = 1;
        }
        if (password != '' && password != confirm_password) {
            $('.registerArea input[name="confirm_password"]').closest('.form-group').append("<div class='error'>Confirm password mismatch</div>");
            error = 1;
        }
    }
    if (step == 3) {
        if (interest_cat_count == 0) {
            $('.registerArea .interests').append("<div class='error'>Choose at least one category</div>");
            error = 1;
        }
        if (address_line_1 == '' || country_code == '') {
            $('.registerArea input[name="location"]').closest('.form-group').append("<div class='error'>Type & choose address</div>");
            error = 1;
        }
        if (agree_terms == 0) {
            $('.registerArea input[name="agree_terms"]').closest('.checkbox').append("<div class='error'>Accept this</div>");
            error = 1;
        }
        if (over_18 == 0) {
            $('.registerArea input[name="over_18"]').closest('.checkbox').append("<div class='error'>Confirm this</div>");
            error = 1;
        }
        if (g_recaptcha_response == '') {
            $('.registerArea textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>Validate captcha</div>");
            error = 1;
        }
    }
    var ret = { 'data': { 'first_name': first_name, 'last_name': last_name, 'username': username, 'mobile': mobile, 'email': email, 'password': password, 'interest_cats': interest_cats, 'address_line_1': address_line_1, 'country_code': country_code, 'zip_code': zip_code, 'g_recaptcha_response': g_recaptcha_response } };
    ret.success = false;
    if (error == 0) ret.success = true;
    return ret;
}

function validate_vipmember_register(params) {
    var step = params.step;
    var email = $.trim($('.bcmVipArea input[name="email"]').val());
    var confirm_email = $.trim($('.bcmVipArea input[name="confirm_email"]').val());
    var password = $.trim($('.bcmVipArea input[name="password"]').val());
    var confirm_password = $.trim($('.bcmVipArea input[name="confirm_password"]').val());
    var username = $.trim($('.bcmVipArea input[name="username"]').val());
    var display_name = $.trim($('.bcmVipArea input[name="display_name"]').val());
    var dob = $.trim($('.bcmVipArea input[name="dob"]').val());
    var first_name = $.trim($('.bcmVipArea input[name="first_name"]').val());
    var last_name = $.trim($('.bcmVipArea input[name="last_name"]').val());
    //var mobile = $.trim($('.bcmVipArea input[name="mobile"]').val());

    var mobile = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);



    var phone_otp_verify = $.trim($('.bcmVipArea input[name="vipphone_otp_verify"]').val());
    var user_cat_id = $('.bcmVipArea select[name="user_cat_id"]').val();
    var interest_cat_count = $('.bcmVipArea input[name^="interest_cat_id"]:checked').length;
    var agree_terms = $('.bcmVipArea input[name="agree_terms"]:checked').length;
    var over_18 = $('.bcmVipArea input[name="over_18"]:checked').length;
    var g_recaptcha_response = $.trim($('.bcmVipArea textarea[name="g-recaptcha-response"]').val());
    var email_verification_code = $.trim($('.bcmVipArea input[name="email_verification_code"]').val());
    var id_proof_doc = $.trim($('.bcmVipArea input[name="id_proof_doc"]').val());
    var id_proof = $.trim($('.bcmVipArea input[name="id_proof"]').val());
    var agree_terms2 = $('.bcmVipArea input[name="agree_terms2"]:checked').length;
    var allow_vip_friend_request = $('.bcmVipArea input[name="allow_vip_friend_request"]:checked').length;
    //var subscription_price = $.trim($('.bcmVipArea input[name="subscription_price"]').val());
    var subscription_price_1m = $.trim($('.bcmVipArea input[name="subscription_price_1m"]').val());
    var subscription_price_3m = $.trim($('.bcmVipArea input[name="subscription_price_3m"]').val());
    var subscription_price_6m = $.trim($('.bcmVipArea input[name="subscription_price_6m"]').val());
    var subscription_price_12m = $.trim($('.bcmVipArea input[name="subscription_price_12m"]').val());
    var subscription_min_price_1m = parseFloat($('.bcmVipArea input[name="subscription_price_1m"]').attr('min'));
    var subscription_max_price_1m = parseFloat($('.bcmVipArea input[name="subscription_price_1m"]').attr('max'));
    var subscription_min_price_3m = parseFloat($('.bcmVipArea input[name="subscription_price_3m"]').attr('min'));
    var subscription_max_price_3m = parseFloat($('.bcmVipArea input[name="subscription_price_3m"]').attr('max'));
    var subscription_min_price_6m = parseFloat($('.bcmVipArea input[name="subscription_price_6m"]').attr('min'));
    var subscription_max_price_6m = parseFloat($('.bcmVipArea input[name="subscription_price_6m"]').attr('max'));
    var subscription_min_price_12m = parseFloat($('.bcmVipArea input[name="subscription_price_12m"]').attr('min'));
    var subscription_max_price_12m = parseFloat($('.bcmVipArea input[name="subscription_price_12m"]').attr('max'));
    var twitter_url = $.trim($('.bcmVipArea input[name="twitter_url"]').val());
    var wishlist_url = $.trim($('.bcmVipArea input[name="wishlist_url"]').val());
    var address_line_1 = $.trim($('.bcmVipArea input[name="address_line_1"]').val());
    var country_code = $.trim($('.bcmVipArea input[name="country_code"]').val());
    var zip_code = $.trim($('.bcmVipArea input[name="zip_code"]').val());
    var about_bio = $.trim($('.bcmVipArea textarea[name="about_bio"]').val());
    var free_follower = $('.bcmVipArea input[name="free_follower"]:checked').length;
    var profile_keywords = $.trim($('.bcmVipArea input[name="profile_keywords"]').val());
    var bank_country_id = $('.bcmVipArea select[name="bank_country_id"]').val();
    var bank_account_name = $.trim($('.bcmVipArea input[name="bank_account_name"]').val());
    var bank_account_sort_code = $.trim($('.bcmVipArea input[name="bank_account_sort_code"]').val());
    var bank_account_number = $.trim($('.bcmVipArea input[name="bank_account_number"]').val());
    var interest_cats = [];
    $('.bcmVipArea input[name^="interest_cat_id"]:checked').each(function() {
        interest_cats.push($(this).val());
    });
    var error = 0;
    var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    var username_pattern = /^[a-z0-9_]{3,50}$/igm
    var bank_account_sort_code_pattern = /^(\d){2}-(\d){2}-(\d){2}$/
    var bank_account_number_pattern = /^(\d){8}$/
    $(".error").remove();
    $('.bcmVipArea .response').html('');
    if (step == 1) {

        if (email_pattern.test(email) == false) {
            $('.bcmVipArea input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
            //error = 1;
        }
        if (email != '' && email != confirm_email) {
            $('.bcmVipArea input[name="confirm_email"]').closest('.form-group').append("<div class='error'>Confirm email mismatch</div>");
            //error = 1;
        }﻿
        if (mobile != "" && phone_otp_verify == 'false') {
            $('.bcmVipArea input[name="mobile"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
            error = 1;
            console.log(1);
        }
        if (mobile == "") {
            //$('.bcmVipArea input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter valid mobile</div>");
            //error = 1;
        }

        if (mobile == "") {
            $('.bcmVipArea input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter phone number</div>");
            error = 1;
            console.log(2);
        }



        if (password == "") {
            $('.bcmVipArea input[name="password"]').closest('.form-group').append("<div class='error'>Enter password</div>");
            error = 1;
            console.log(3);
        }
        if (password != '' && password != confirm_password) {
            $('.bcmVipArea input[name="confirm_password"]').closest('.form-group').append("<div class='error'>Confirm password mismatch</div>");
            error = 1;
            console.log(4);
        }
        if (username_pattern.test(username) == false) {
            $('.bcmVipArea input[name="username"]').closest('.form-group').append("<div class='error'>Enter valid username</div>");
            error = 1;
            console.log(5);
        }
        if (display_name == "") {
            $('.bcmVipArea input[name="display_name"]').closest('.form-group').append("<div class='error'>Enter display name</div>");
            error = 1;
            console.log(6);
        }
        if (dob == "") {
            $('.bcmVipArea input[name="dob"]').closest('.form-group').append("<div class='error'>Enter date of birth</div>");
            error = 1;
            console.log(7);
        }
        if (first_name == "") {
            $('.bcmVipArea input[name="first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
            error = 1;
            console.log(8);
        }
        if (last_name == "") {
            $('.bcmVipArea input[name="last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
            error = 1;
            console.log(9);
        }

        if (user_cat_id == "") {
            $('.bcmVipArea select[name="user_cat_id"]').closest('.form-group').append("<div class='error'>Select category</div>");
            error = 1;
            console.log(10);
        }
        if (interest_cat_count == 0) {
            $('.bcmVipArea .interests').append("<div class='error'>Choose at least one category</div>");
            error = 1;
            console.log(11);
        }
        if (agree_terms == 0) {
            $('.bcmVipArea input[name="agree_terms"]').closest('.checkbox').append("<div class='error'>Accept this</div>");
            error = 1;
            console.log(12);

        }
        if (over_18 == 0) {
            $('.bcmVipArea input[name="over_18"]').closest('.checkbox').append("<div class='error'>Confirm this</div>");
            error = 1;
            console.log(13);
        }
        if (g_recaptcha_response == '') {
            $('.bcmVipArea textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>Validate captcha</div>");
            error = 1;
            console.log(14);
        }

        console.log(15);
    }
    if (step == '1_3') {
        if (email_verification_code == "") {
            //$('.bcmVipArea input[name="email_verification_code"]').closest('.form-group').append("<div class='error'>Enter verification code</div>");
            //error = 1;
        }
    }
    if (step == 2) {
        if (id_proof_doc == "") {
            $('.bcmVipArea input[name="id_proof_doc"]').closest('.file-upload').append("<div class='error'>Upload file</div>");
            error = 1;
        }
        if (id_proof == "") {
            $('.bcmVipArea input[name="id_proof"]').closest('.file-upload').append("<div class='error'>Upload file</div>");
            error = 1;
        }
    }
    if (step == 3) {
        if (agree_terms2 == 0) {
            $('.bcmVipArea input[name="agree_terms2"]').closest('.checkbox').append("<div class='error'>Accept this</div>");
            error = 1;
        }
        /*if(subscription_price_1m != '' && (parseFloat(subscription_price_1m) < subscription_min_price_1m || parseFloat(subscription_price_1m) > subscription_max_price_1m)) {
			$('.bcmVipArea input[name="subscription_price_1m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m.toFixed(2) + " or exceed " + subscription_max_price_1m.toFixed(2) + "</div>");
	    error = 1;
		}
		if(subscription_price_3m != '' && (parseFloat(subscription_price_3m) < subscription_min_price_3m || parseFloat(subscription_price_3m) > subscription_max_price_3m)) {
			$('.bcmVipArea input[name="subscription_price_3m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m.toFixed(2) + " or exceed " + subscription_max_price_3m.toFixed(2) + "</div>");
	    error = 1;
		}
		if(subscription_price_6m != '' && (parseFloat(subscription_price_6m) < subscription_min_price_6m || parseFloat(subscription_price_6m) > subscription_max_price_6m)) {
			$('.bcmVipArea input[name="subscription_price_6m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m.toFixed(2) + " or exceed " + subscription_max_price_6m.toFixed(2) + "</div>");
	    error = 1;
		}
		if(subscription_price_12m != '' && (parseFloat(subscription_price_12m) < subscription_min_price_12m || parseFloat(subscription_price_12m) > subscription_max_price_12m)) {
			$('.bcmVipArea input[name="subscription_price_12m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m.toFixed(2) + " or exceed " + subscription_max_price_12m.toFixed(2) + "</div>");
	    error = 1;
		}*/
        if (subscription_price_1m != '' && parseFloat(subscription_price_1m) < subscription_min_price_1m) {
            $('.bcmVipArea input[name="subscription_price_1m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m.toFixed(2) + "</div>");
            error = 1;
        }
        if (subscription_price_3m != '' && parseFloat(subscription_price_3m) < subscription_min_price_3m) {
            $('.bcmVipArea input[name="subscription_price_3m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m.toFixed(2) + "</div>");
            error = 1;
        }
        if (subscription_price_6m != '' && parseFloat(subscription_price_6m) < subscription_min_price_6m) {
            $('.bcmVipArea input[name="subscription_price_6m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m.toFixed(2) + "</div>");
            error = 1;
        }
        if (subscription_price_12m != '' && parseFloat(subscription_price_12m) < subscription_min_price_12m) {
            $('.bcmVipArea input[name="subscription_price_12m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m.toFixed(2) + "</div>");
            error = 1;
        }
        if (address_line_1 == '' || country_code == '') {
            $('.bcmVipArea input[name="location"]').closest('.form-group').append("<div class='error'>Type & choose address</div>");
            error = 1;
        }
        if (about_bio == "") {
            $('.bcmVipArea textarea[name="about_bio"]').closest('.form-group').append("<div class='error'>Put your bio</div>");
            error = 1;
        }
    }
    if (step == 4) {
        if (bank_account_name == "") {
            $('.bcmVipArea input[name="bank_account_name"]').closest('.form-group').append("<div class='error'>Put your bank account name</div>");
            error = 1;
        }
        if (bank_country_id == "222" && bank_account_sort_code_pattern.test(bank_account_sort_code) == false) {
            $('.bcmVipArea input[name="bank_account_sort_code"]').closest('.form-group').append("<div class='error'>Put your valid bank account sort code</div>");
            error = 1;
        }
        if (bank_country_id == "222" && bank_account_number_pattern.test(bank_account_number) == false) {
            $('.bcmVipArea input[name="bank_account_number"]').closest('.form-group').append("<div class='error'>Put your valid bank account number</div>");
            error = 1;
        }
    }
    var ret = { 'data': { 'email': email, 'password': password, 'username': username, 'display_name': display_name, 'first_name': first_name, 'last_name': last_name, 'mobile': mobile, 'dob': dob, 'user_cat_id': user_cat_id, 'interest_cats': interest_cats, 'g_recaptcha_response': g_recaptcha_response, 'email_verification_code': email_verification_code, 'allow_vip_friend_request': allow_vip_friend_request, 'subscription_price_1m': subscription_price_1m, 'subscription_price_3m': subscription_price_3m, 'subscription_price_6m': subscription_price_6m, 'subscription_price_12m': subscription_price_12m, 'twitter_url': twitter_url, 'wishlist_url': wishlist_url, 'address_line_1': address_line_1, 'country_code': country_code, 'zip_code': zip_code, 'about_bio': about_bio, 'free_follower': free_follower, 'profile_keywords': profile_keywords, 'bank_country_id': bank_country_id, 'bank_account_name': bank_account_name, 'bank_account_sort_code': bank_account_sort_code, 'bank_account_number': bank_account_number } };

    console.log(16);


    ret.success = false;
    if (error == 0) ret.success = true;
    return ret;
}

function registerModal() {
    $(document).on('click', 'header .register', function() {
        $('.registerArea .response').html('');
        $('.registerArea .passwordStrength').html('');
        $('.registerArea input[name="location"], .registerArea input[name="address_line_1"], .registerArea input[name="country_code"], .registerArea input[name="zip_code"]').val('');
        $(".registerArea .formBox-2, .registerArea .formBox-3").hide();
        $(".registerArea .formBox-1").show();
        $(".registerArea").addClass("open");
        $("body").addClass("scrollHide");
    });
    $(document).on('click', '.registerArea .x-close', function() {
        $(".registerArea").removeClass("open");
        $("body").removeClass("scrollHide");
    });
    $('.registerArea form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    $('.registerArea input[name="password"]').on('keyup', function(e) {
        var pass = $.trim($(this).val());
        if (pass == '') {
            $('.registerArea .passwordStrength').html('');
            return false;
        }
        var strg = passwordStrengthChecker(pass);
        if (strg.type == 'strong')
            $('.registerArea .passwordStrength').html('<span style="color: #008c00;">Strong</span>');
        else if (strg.type == 'medium')
            $('.registerArea .passwordStrength').html('<span style="color: #ad7900;">Medium</span>');
        else if (strg.type == 'weak')
            $('.registerArea .passwordStrength').html('<span style="color: red;">Weak</span>');
    });
    $('.registerArea input[name="location"]').on('keyup keypress', function(e) {
        $('.registerArea input[name="address_line_1"], .registerArea input[name="country_code"], .registerArea input[name="zip_code"]').val('');
    });
    $(document).on('click', '.registerArea .nextStepBtn-1', function() {
        var role = $('.registerArea input[name="role"]:checked').val();
        if (role == 2) {
            $('.registerArea .x-close').trigger('click');
            $('.bcmVipBtn').trigger('click');
        }
        if (role == 3) {
            $(".registerArea .formBox-2").show();
            $(".registerArea .formBox-1").hide();
        }
    });
    $(document).on('click', '.registerArea .nextStepBtn-2', function() {
        var validate = validate_follower_register({ 'step': 2 });
        if (validate.success) {
            $(".registerArea .formBox-3").show();
            $(".registerArea .formBox-2").hide();
        } else {
            $(".registerArea .formBox-2 .nextStepBtn-2").closest('.nextStep').append('<div class="error">There are some errors in form</div>');
        }
    });
    $(document).on('click', '.registerArea .register_follower', function() {
        var validate = validate_follower_register({ 'step': 3 });
        if (validate.success) {
            var profile_photo = $('.registerArea input[name="profile_photo"]')[0];
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'register');
            data.append('role', 3);
            data.append('data', JSON.stringify(validate.data));
            if (profile_photo.files.length > 0)
                data.append('profile_photo', profile_photo.files[0]);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".mw_loader").hide();
                    if (data.success == '1') {
                        $('.registerArea .response').html('<div class="success">' + data.message + '</div>');
                        setTimeout(function() { window.location.href = prop.url; }, 1000);
                    } else {
                        var step2_err = step3_err = 0;
                        $(data.errors).each(function(i, v) {
                            if (v.field == 'username') {
                                $('.registerArea input[name="username"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                step2_err = 1;
                            }
                            if (v.field == 'email') {
                                $('.registerArea input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                step2_err = 1;
                            }
                            if (v.field == 'g_recaptcha_response') {
                                $('.registerArea textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>" + v.message + "</div>");
                                step3_err = 1;
                            }
                        });
                        if (step2_err == 1) {
                            $(".registerArea .formBox-2").show();
                            $(".registerArea .formBox-3").hide();
                            $(".registerArea .formBox-2 .nextStepBtn-2").closest('.nextStep').append('<div class="error">There are some errors in form</div>');
                        } else if (step3_err == 1) {
                            $(".registerArea .formBox-3 .register_follower").closest('.nextStep').append('<div class="error">There are some errors in form</div>');
                        }
                    }
                }
            });
        } else {
            $(".registerArea .formBox-3 .register_follower").closest('.nextStep').append('<div class="error">There are some errors in form</div>');
        }
    });

    $(document).on('click', '.bcmFollowerBtn', function() {
        $('header .register').trigger('click');
    });
    $(document).on('click', '.bcmVipBtn', function() {
        $(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='3'], .bcmVipArea [step_details='4']").hide();
        $(".bcmVipArea [step_details='1']").show();
        $(".bcmVipArea [step_details='1'] .stepSec-2, .bcmVipArea [step_details='1'] .stepSec-3").hide();
        $(".bcmVipArea [step_details='1'] .stepSec-1").show();
        $(".bcmVipArea .bcmVipNav [step='2'], .bcmVipArea .bcmVipNav [step='3'], .bcmVipArea .bcmVipNav [step='4']").closest('li').removeClass('active');
        $(".bcmVipArea .bcmVipNav [step='1']").closest('li').addClass('active');
        $(".bcmVipArea").addClass("open");
        $("body").addClass("scrollHide");
        $(".bcmVipArea .passwordStrength").html('');
    });
    $(document).on('click', '.bcmVipArea .x-close', function() {
        $(".bcmVipArea").removeClass("open");
        $("body").removeClass("scrollHide");
    });
    $('.bcmVipArea form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
    $('.bcmVipArea input[name="password"]').on('keyup', function(e) {
        var pass = $.trim($(this).val());
        if (pass == '') {
            $('.bcmVipArea .passwordStrength').html('');
            return false;
        }
        var strg = passwordStrengthChecker(pass);
        if (strg.type == 'strong')
            $('.bcmVipArea .passwordStrength').html('<span style="color: #008c00;">Strong</span>');
        else if (strg.type == 'medium')
            $('.bcmVipArea .passwordStrength').html('<span style="color: #ad7900;">Medium</span>');
        else if (strg.type == 'weak')
            $('.bcmVipArea .passwordStrength').html('<span style="color: red;">Weak</span>');
    });
    $('.bcmVipArea input[name="location"]').on('keyup keypress', function(e) {
        $('.bcmVipArea input[name="address_line_1"], .bcmVipArea input[name="country_code"], .bcmVipArea input[name="zip_code"]').val('');
    });
    $(document).on('click', '.bcmVipArea .bcmVipNav [step]', function() {
        var mxvl = 0;
        $('.bcmVipArea .bcmVipNav li.active [step]').each(function() {
            if (parseInt($(this).attr('step')) > mxvl) mxvl = parseInt($(this).attr('step'));
        });
        var thisstep = parseInt($(this).attr('step'));
        if (thisstep < mxvl) {
            $('.bcmVipArea .bcmVipNav li').removeClass('active');
            for (var i = 1; i <= thisstep; i++) {
                $('.bcmVipArea .bcmVipNav li [step="' + i + '"]').closest('li').addClass('active');
            }
            $(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='3'], .bcmVipArea [step_details='4']").hide();
            if (thisstep == '1') {
                $(".bcmVipArea [step_details='1']").show();
                $(".bcmVipArea [step_details='1'] .stepSec-2, .bcmVipArea [step_details='1'] .stepSec-3").hide();
                $(".bcmVipArea [step_details='1'] .stepSec-1").show();
            }
            if (thisstep == '2') {
                $(".bcmVipArea [step_details='2']").show();
                $(".bcmVipArea [step_details='2'] .stepSec-2").hide();
                $(".bcmVipArea [step_details='2'] .stepSec-1").show();
            }
        }
    });
    onlyNumbersWithDecimal('.bcmVipArea input[name="subscription_price_1m"]');
    onlyNumbersWithDecimal('.bcmVipArea input[name="subscription_price_3m"]');
    onlyNumbersWithDecimal('.bcmVipArea input[name="subscription_price_6m"]');
    onlyNumbersWithDecimal('.bcmVipArea input[name="subscription_price_12m"]');
    $(document).on('click', '.bcmVipArea .vipRegSubmit-1', function() {
        var validate = validate_vipmember_register({ 'step': 1 });
        if (validate.success) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'validate_vipmember_register');
            data.append('step', 1);
            data.append('data', JSON.stringify(validate.data));
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".mw_loader").hide();
                    if (data.success == '1') {
                        /*if(typeof data.data.need_email_verification != 'undefined') {
							$(".bcmVipArea [step_details='1'] .stepSec-2").show();
    					$(".bcmVipArea [step_details='1'] .stepSec-1").hide();
						} else {
							$(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='2'] .stepSec-1").show();
    					$(".bcmVipArea [step_details='1'], .bcmVipArea [step_details='2'] .change_name, .bcmVipArea [step_details='2'] .stepSec-2").hide();
							$(".bcmVipArea .bcmVipNav [step='2']").closest('li').addClass('active');
						}*/

                        $(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='2'] .stepSec-1").show();
                        $(".bcmVipArea [step_details='1'], .bcmVipArea [step_details='2'] .change_name, .bcmVipArea [step_details='2'] .stepSec-2").hide();
                        $(".bcmVipArea .bcmVipNav [step='2']").closest('li').addClass('active');


                    } else {
                        $(data.errors).each(function(i, v) {
                            if (v.field == 'email') {
                                $('.bcmVipArea input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                            if (v.field == 'username') {
                                $('.bcmVipArea input[name="username"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                            if (v.field == 'g_recaptcha_response') {
                                $('.bcmVipArea textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>" + v.message + "</div>");
                            }
                        });
                        $(".vipRegSubmit-1").closest('.form-group').append('<div class="error">There are some errors in form</div>');
                    }
                }
            });
        } else {
            $(".vipRegSubmit-1").closest('.form-group').append('<div class="error">There are some errors in form</div>');
        }
    });
    $(document).on('click', '.bcmVipArea .vipRegSubmit-1_3', function() {
        var validate = validate_vipmember_register({ 'step': '1_3' });
        if (validate.success) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'validate_vipmember_register');
            data.append('step', '1_3');
            data.append('data', JSON.stringify(validate.data));
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".mw_loader").hide();
                    if (data.success == '1') {
                        $(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='2'] .stepSec-1").show();
                        $(".bcmVipArea [step_details='1'], .bcmVipArea [step_details='2'] .change_name, .bcmVipArea [step_details='2'] .stepSec-2").hide();
                        $(".bcmVipArea .bcmVipNav [step='2']").closest('li').addClass('active');
                    } else {
                        $(data.errors).each(function(i, v) {
                            if (v.field == 'email_verification_code') {
                                $('.bcmVipArea input[name="email_verification_code"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                        });
                        $(".vipRegSubmit-1_3").closest('.form-group').append('<div class="error">There are some errors in form</div>');
                    }
                }
            });
        } else {
            $(".vipRegSubmit-1_3").closest('.form-group').append('<div class="error">There are some errors in form</div>');
        }
    });
    $(document).on('click', '.bcmVipArea .changeNameBtn', function() {
        $('.bcmVipArea .change_name').toggle();
    });
    $(document).on('click', '.bcmVipArea .change_name .changeNameSubmit', function() {
        var first_name = $.trim($('.bcmVipArea .change_name input[name="change_first_name"]').val());
        var last_name = $.trim($('.bcmVipArea .change_name input[name="change_last_name"]').val());
        var error = 0;
        $(".error").remove();
        if (first_name == '') {
            $('.bcmVipArea .change_name input[name="change_first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
            error = 1;
        }
        if (last_name == '') {
            $('.bcmVipArea .change_name input[name="change_last_name"]').closest('.form-group').append("<div class='error'>Enter lastname</div>");
            error = 1;
        }
        if (error == 0) {
            $('.bcmVipArea input[name="first_name"]').val(first_name);
            $('.bcmVipArea input[name="last_name"]').val(last_name);
            $('.bcmVipArea .change_name').hide();
        }
    });
    $(document).on('click', '.bcmVipArea .vipRegSubmit-2', function() {
        var validate = validate_vipmember_register({ 'step': 2 });
        if (validate.success) {
            $(".bcmVipArea [step_details='3'], .bcmVipArea [step_details='3'] .stepSec-1").show();
            $(".bcmVipArea [step_details='2'], .bcmVipArea [step_details='2'] .change_name, .bcmVipArea [step_details='2'] .stepSec-2").hide();
            $(".bcmVipArea .bcmVipNav [step='3']").closest('li').addClass('active');
        } else {
            $(".vipRegSubmit-2").closest('.verifyIdentity').find('.form_msg').append('<div class="error">There are some errors in form</div>');
        }
    });
    $(document).on('click', '.bcmVipArea .vipRegSubmit-3', function() {
        var validate = validate_vipmember_register({ 'step': 3 });
        if (validate.success) {
            $(".bcmVipArea [step_details='4'], .bcmVipArea [step_details='4'] .stepSec-1").show();
            $(".bcmVipArea [step_details='3'], .bcmVipArea [step_details='3'] .stepSec-1").hide();
            $(".bcmVipArea .bcmVipNav [step='4']").closest('li').addClass('active');
        } else {
            $(".vipRegSubmit-3").closest('.profileDetails').find('.form_msg').append('<div class="error">There are some errors in form</div>');
        }
    });
    $(document).on('click', '.bcmVipArea .vipRegSubmit-4', function() {
        var validate = validate_vipmember_register({ 'step': 4 });
        if (validate.success) {
            var id_proof_doc = $('.bcmVipArea input[name="id_proof_doc"]')[0];
            var id_proof = $('.bcmVipArea input[name="id_proof"]')[0];
            var profile_photo = $('.bcmVipArea input[name="profile_photo"]')[0];
            var profile_video = $('.bcmVipArea input[name="profile_video"]')[0];
            var profile_banner = $('.bcmVipArea input[name="profile_banner"]')[0];
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'register');
            data.append('role', '2');
            data.append('data', JSON.stringify(validate.data));
            data.append('id_proof_doc', id_proof_doc.files[0]);
            data.append('id_proof', id_proof.files[0]);
            if (profile_photo.files.length > 0)
                data.append('profile_photo', profile_photo.files[0]);
            if (profile_video.files.length > 0)
                data.append('profile_video', profile_video.files[0]);
            if (profile_banner.files.length > 0)
                data.append('profile_banner', profile_banner.files[0]);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".mw_loader").hide();
                    if (data.success == '1') {
                        $(".bcmVipArea [step_details='4'] .stepSec-2").show();
                        $(".bcmVipArea [step_details='4'] .stepSec-1").hide();
                        $('.bcmVipArea input[type="text"], .bcmVipArea input[type="password"], .bcmVipArea input[type="hidden"], .bcmVipArea textarea').val('');
                        $('.bcmVipArea input[type="checkbox"]').prop('checked', false);
                        $('.bcmVipArea .file-upload .fp-close').trigger('click');
                    } else {
                        $(data.errors).each(function(i, v) {
                            if (v.field == 'email') {
                                $('.bcmVipArea input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                            if (v.field == 'username') {
                                $('.bcmVipArea input[name="username"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                        });
                        $(".bcmVipArea .bcmVipNav [step='1']").trigger('click');
                        $(".vipRegSubmit-1").closest('.form-group').append('<div class="error">There are some errors in form</div>');
                        $(".vipRegSubmit-4").closest('.setupPayments').find('.form_msg').append('<div class="error">There are some errors in form</div>');
                    }
                }
            });
        } else {
            $(".vipRegSubmit-4").closest('.setupPayments').find('.form_msg').append('<div class="error">There are some errors in form</div>');
        }
    });
}

function user_last_activity() {
    var data = new FormData();
    data.append('action', 'user_last_activity');
    data.append('_token', prop.csrf_token);
    $.ajax({ type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data) {} });
}

function change_password() {
    $('.change_password_form input[name="new_password"]').on('keyup', function(e) {
        var pass = $.trim($(this).val());
        if (pass == '') {
            $('.change_password_form .passwordStrength').html('');
            return false;
        }
        var strg = passwordStrengthChecker(pass);
        if (strg.type == 'strong')
            $('.change_password_form .passwordStrength').html('<span style="color: #008c00;">Strong</span>');
        else if (strg.type == 'medium')
            $('.change_password_form .passwordStrength').html('<span style="color: #ad7900;">Medium</span>');
        else if (strg.type == 'weak')
            $('.change_password_form .passwordStrength').html('<span style="color: red;">Weak</span>');
    });
    $(document).on('click', '.change_password_submit', function() {
        var current_password = $.trim($('input[name="current_password"]').val());
        var new_password = $.trim($('input[name="new_password"]').val());
        var confirm_new_password = $.trim($('input[name="confirm_new_password"]').val());
        var error = 0;
        $(".error").remove();
        if (current_password == '') {
            $('input[name="current_password"]').closest('.form-group').append("<div class='error'>Enter current password</div>");
            error = 1;
        }
        if (new_password == '') {
            $('input[name="new_password"]').closest('.form-group').append("<div class='error'>Enter new password</div>");
            error = 1;
        }
        if (new_password != '' && new_password != confirm_new_password) {
            $('input[name="confirm_new_password"]').closest('.form-group').append("<div class='error'>Confirm password mismatch</div>");
            error = 1;
        }
        if (error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'change_password');
            data.append('current_password', current_password);
            data.append('new_password', new_password);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '0') {
                        $(".mw_loader").hide();
                        $(data.errors).each(function(i, v) {
                            if (v.field == 'current_password') {
                                $('input[name="current_password"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                            if (v.field == 'new_password') {
                                $('input[name="new_password"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                            }
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
}

function profile_submit() {
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_1m"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_3m"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_6m"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_12m"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_1m_discounted"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_3m_discounted"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_6m_discounted"]');
    onlyNumbersWithDecimal('.profile_form input[name="subscription_price_12m_discounted"]');
    $('.profile_form input[name="location"]').on('keyup keypress', function(e) {
        $('.profile_form input[name="address_line_1"], .profile_form input[name="country_code"], .profile_form input[name="zip_code"]').val('');
    });
    $(document).on('click', '.profile_submit', function() {
        if (prop.user_data.role == '2') {
            var email_otp_verify = $.trim($('.profile_form input[name="email_otp_verify"]').val());
            var email = $.trim($('.profile_form input[name="email"]').val());
            var display_name = $.trim($('.profile_form input[name="display_name"]').val());
            var first_name = $.trim($('.profile_form input[name="first_name"]').val());
            var last_name = $.trim($('.profile_form input[name="last_name"]').val());
            var mobile = $.trim($('.profile_form input[name="mobile"]').val());
            var phone_otp_verify = $.trim($('.profile_form input[name="phone_otp_verify"]').val());
            var dob = $.trim($('.profile_form input[name="dob"]').val());
            var user_cat_id = $('.profile_form select[name="user_cat_id"]').val();
            var interest_cat_count = $('.profile_form input[name^="interest_cat_id"]:checked').length;
            var id_proof_doc = $.trim($('.profile_form input[name="id_proof_doc"]').val());
            var id_proof_doc_removed = $.trim($('.profile_form input[name="id_proof_doc_removed"]').val());
            var id_proof = $.trim($('.profile_form input[name="id_proof"]').val());
            var id_proof_removed = $.trim($('.profile_form input[name="id_proof_removed"]').val());
            var profile_photo_removed = $.trim($('.profile_form input[name="profile_photo_removed"]').val());
            var profile_video_removed = $.trim($('.profile_form input[name="profile_video_removed"]').val());
            var profile_banner_removed = $.trim($('.profile_form input[name="profile_banner_removed"]').val());
            var allow_vip_friend_request = $('.profile_form input[name="allow_vip_friend_request"]:checked').length;
            var subscription_price_1m = $.trim($('.profile_form input[name="subscription_price_1m"]').val());
            var subscription_min_price_1m = parseFloat($('.profile_form input[name="subscription_price_1m"]').attr('min'));
            var subscription_max_price_1m = parseFloat($('.profile_form input[name="subscription_price_1m"]').attr('max'));
            var subscription_price_1m_discounted = $.trim($('.profile_form input[name="subscription_price_1m_discounted"]').val());
            var subscription_min_price_1m_discounted = parseFloat($('.profile_form input[name="subscription_price_1m_discounted"]').attr('min'));
            var subscription_price_1m_discounted_todate = $.trim($('.profile_form input[name="subscription_price_1m_discounted_todate"]').val());
            var subscription_price_3m = $.trim($('.profile_form input[name="subscription_price_3m"]').val());
            var subscription_min_price_3m = parseFloat($('.profile_form input[name="subscription_price_3m"]').attr('min'));
            var subscription_max_price_3m = parseFloat($('.profile_form input[name="subscription_price_3m"]').attr('max'));
            var subscription_price_3m_discounted = $.trim($('.profile_form input[name="subscription_price_3m_discounted"]').val());
            var subscription_min_price_3m_discounted = parseFloat($('.profile_form input[name="subscription_price_3m_discounted"]').attr('min'));
            var subscription_price_3m_discounted_todate = $.trim($('.profile_form input[name="subscription_price_3m_discounted_todate"]').val());
            var subscription_price_6m = $.trim($('.profile_form input[name="subscription_price_6m"]').val());
            var subscription_min_price_6m = parseFloat($('.profile_form input[name="subscription_price_6m"]').attr('min'));
            var subscription_max_price_6m = parseFloat($('.profile_form input[name="subscription_price_6m"]').attr('max'));
            var subscription_price_6m_discounted = $.trim($('.profile_form input[name="subscription_price_6m_discounted"]').val());
            var subscription_min_price_6m_discounted = parseFloat($('.profile_form input[name="subscription_price_6m_discounted"]').attr('min'));
            var subscription_price_6m_discounted_todate = $.trim($('.profile_form input[name="subscription_price_6m_discounted_todate"]').val());
            var subscription_price_12m = $.trim($('.profile_form input[name="subscription_price_12m"]').val());
            var subscription_min_price_12m = parseFloat($('.profile_form input[name="subscription_price_12m"]').attr('min'));
            var subscription_max_price_12m = parseFloat($('.profile_form input[name="subscription_price_12m"]').attr('max'));
            var subscription_price_12m_discounted = $.trim($('.profile_form input[name="subscription_price_12m_discounted"]').val());
            var subscription_min_price_12m_discounted = parseFloat($('.profile_form input[name="subscription_price_12m_discounted"]').attr('min'));
            var subscription_price_12m_discounted_todate = $.trim($('.profile_form input[name="subscription_price_12m_discounted_todate"]').val());
            var private_session_price = $.trim($('.profile_form input[name="private_session_price"]').val());
            var twitter_url = $.trim($('.profile_form input[name="twitter_url"]').val());
            var wishlist_url = $.trim($('.profile_form input[name="wishlist_url"]').val());
            var address_line_1 = $.trim($('.profile_form input[name="address_line_1"]').val());
            var country_code = $.trim($('.profile_form input[name="country_code"]').val());
            var zip_code = $.trim($('.profile_form input[name="zip_code"]').val());
            var short_bio = $.trim($('.profile_form input[name="short_bio"]').val());
            var about_bio = $.trim($('.profile_form textarea[name="about_bio"]').val());
            var free_follower = $('.profile_form input[name="free_follower"]:checked').length;
            var profile_keywords = $.trim($('.profile_form input[name="profile_keywords"]').val());
            var interest_cats = [];
            $('.profile_form input[name^="interest_cat_id"]:checked').each(function() {
                interest_cats.push($(this).val());
            });
            var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var error = 0;
            $(".error").remove();
            if (email_pattern.test(email) == false) {
                $('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
                error = 1;
            }

            if (email != "" && email_otp_verify == 'false') {
                $('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }
            if (display_name == "") {
                $('.profile_form input[name="display_name"]').closest('.form-group').append("<div class='error'>Enter display name</div>");
                error = 1;
            }
            if (first_name == "") {
                $('.profile_form input[name="first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
                error = 1;
            }
            if (last_name == "") {
                $('.profile_form input[name="last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
                error = 1;
            }

            if (mobile != "" && phone_otp_verify == 'false') {
                $('.profile_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }

            if (mobile == "") {
                $('.profile_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter phone number</div>");
                error = 1;
            }
            if (dob == "") {
                $('.profile_form input[name="dob"]').closest('.form-group').append("<div class='error'>Enter DOB</div>");
                error = 1;
            }
            if (user_cat_id == "") {
                $('.profile_form select[name="user_cat_id"]').closest('.form-group').append("<div class='error'>Select category</div>");
                error = 1;
            }
            if (interest_cat_count == 0) {
                $('.profile_form .interests').append("<div class='error'>Choose at least one category</div>");
                error = 1;
            }
            /*if(subscription_price_1m != '' && (parseFloat(subscription_price_1m) < subscription_min_price_1m || parseFloat(subscription_price_1m) > subscription_max_price_1m)) {
				$('.profile_form input[name="subscription_price_1m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m.toFixed(2) + " or exceed " + subscription_max_price_1m.toFixed(2) + "</div>");
		    error = 1;
			}
			if(subscription_price_3m != '' && (parseFloat(subscription_price_3m) < subscription_min_price_3m || parseFloat(subscription_price_3m) > subscription_max_price_3m)) {
				$('.profile_form input[name="subscription_price_3m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m.toFixed(2) + " or exceed " + subscription_max_price_3m.toFixed(2) + "</div>");
		    error = 1;
			}
			if(subscription_price_6m != '' && (parseFloat(subscription_price_6m) < subscription_min_price_6m || parseFloat(subscription_price_6m) > subscription_max_price_6m)) {
				$('.profile_form input[name="subscription_price_6m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m.toFixed(2) + " or exceed " + subscription_max_price_6m.toFixed(2) + "</div>");
		    error = 1;
			}
			if(subscription_price_12m != '' && (parseFloat(subscription_price_12m) < subscription_min_price_12m || parseFloat(subscription_price_12m) > subscription_max_price_12m)) {
				$('.profile_form input[name="subscription_price_12m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m.toFixed(2) + " or exceed " + subscription_max_price_12m.toFixed(2) + "</div>");
		    error = 1;
			}*/
            if (subscription_price_1m != '' && parseFloat(subscription_price_1m) < subscription_min_price_1m) {
                $('.profile_form input[name="subscription_price_1m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_3m != '' && parseFloat(subscription_price_3m) < subscription_min_price_3m) {
                $('.profile_form input[name="subscription_price_3m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_6m != '' && parseFloat(subscription_price_6m) < subscription_min_price_6m) {
                $('.profile_form input[name="subscription_price_6m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_12m != '' && parseFloat(subscription_price_12m) < subscription_min_price_12m) {
                $('.profile_form input[name="subscription_price_12m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_1m_discounted != '' && parseFloat(subscription_price_1m_discounted) < subscription_min_price_1m_discounted) {
                $('.profile_form input[name="subscription_price_1m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_3m_discounted != '' && parseFloat(subscription_price_3m_discounted) < subscription_min_price_3m_discounted) {
                $('.profile_form input[name="subscription_price_3m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_6m_discounted != '' && parseFloat(subscription_price_6m_discounted) < subscription_min_price_6m_discounted) {
                $('.profile_form input[name="subscription_price_6m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_12m_discounted != '' && parseFloat(subscription_price_12m_discounted) < subscription_min_price_12m_discounted) {
                $('.profile_form input[name="subscription_price_12m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (address_line_1 == '' || country_code == '') {
                $('.profile_form input[name="location"]').closest('.form-group').append("<div class='error'>Type & choose address</div>");
                error = 1;
            }
            if (about_bio == "") {
                $('.profile_form textarea[name="about_bio"]').closest('.form-group').append("<div class='error'>Put your bio</div>");
                error = 1;
            }
            if (error == 0) {
                var id_proof_doc = $('.profile_form input[name="id_proof_doc"]')[0];
                var id_proof = $('.profile_form input[name="id_proof"]')[0];
                var profile_photo = $('.profile_form input[name="profile_photo"]')[0];
                var profile_video = $('.profile_form input[name="profile_video"]')[0];
                var profile_banner = $('.profile_form input[name="profile_banner"]')[0];
                $(".mw_loader").show();
                var data = new FormData();
                data.append('action', 'update_profile');
                data.append('email', email);
                data.append('display_name', display_name);
                data.append('first_name', first_name);
                data.append('last_name', last_name);
                data.append('mobile', mobile);
                data.append('dob', dob);
                data.append('user_cat_id', user_cat_id);
                data.append('interest_cats', JSON.stringify(interest_cats));
                data.append('allow_vip_friend_request', allow_vip_friend_request);
                data.append('subscription_price_1m', subscription_price_1m);
                data.append('subscription_price_1m_discounted', subscription_price_1m_discounted);
                data.append('subscription_price_1m_discounted_todate', subscription_price_1m_discounted_todate);
                data.append('subscription_price_3m', subscription_price_3m);
                data.append('subscription_price_3m_discounted', subscription_price_3m_discounted);
                data.append('subscription_price_3m_discounted_todate', subscription_price_3m_discounted_todate);
                data.append('subscription_price_6m', subscription_price_6m);
                data.append('subscription_price_6m_discounted', subscription_price_6m_discounted);
                data.append('subscription_price_6m_discounted_todate', subscription_price_6m_discounted_todate);
                data.append('subscription_price_12m', subscription_price_12m);
                data.append('subscription_price_12m_discounted', subscription_price_12m_discounted);
                data.append('subscription_price_12m_discounted_todate', subscription_price_12m_discounted_todate);
                data.append('private_session_price', private_session_price);
                data.append('address_line_1', address_line_1);
                data.append('country_code', country_code);
                data.append('zip_code', zip_code);
                data.append('twitter_url', twitter_url);
                data.append('wishlist_url', wishlist_url);
                data.append('short_bio', short_bio);
                data.append('about_bio', about_bio);
                data.append('free_follower', free_follower);
                data.append('profile_keywords', profile_keywords);
                data.append('id_proof_doc', id_proof_doc.files[0]);
                data.append('id_proof', id_proof.files[0]);
                if (profile_photo.files.length > 0)
                    data.append('profile_photo', profile_photo.files[0]);
                if (profile_video.files.length > 0)
                    data.append('profile_video', profile_video.files[0]);
                if (profile_banner.files.length > 0)
                    data.append('profile_banner', profile_banner.files[0]);
                data.append('profile_photo_removed', profile_photo_removed);
                data.append('profile_video_removed', profile_video_removed);
                data.append('profile_banner_removed', profile_banner_removed);
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == '0') {
                            $(".mw_loader").hide();
                            $(data.errors).each(function(i, v) {
                                if (v.field == 'email') {
                                    $('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'subscription_price') {
                                    $('.profile_form input[name="subscription_price"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'id_proof_doc') {
                                    $('.profile_form input[name="id_proof_doc"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'id_proof') {
                                    $('.profile_form input[name="id_proof"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'profile_photo') {
                                    $('.profile_form input[name="profile_photo"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'profile_video') {
                                    $('.profile_form input[name="profile_video"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'profile_banner') {
                                    $('.profile_form input[name="profile_banner"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                            });
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        }
        if (prop.user_data.role == '3') {
            var first_name = $.trim($('.profile_form input[name="first_name"]').val());
            var last_name = $.trim($('.profile_form input[name="last_name"]').val());
            var email_otp_verify = $.trim($('.profile_form input[name="email_otp_verify"]').val());
            var email = $.trim($('.profile_form input[name="email"]').val());
            var mobile = $.trim($('.profile_form input[name="mobile"]').val());
            var phone_otp_verify = $.trim($('.profile_form input[name="phone_otp_verify"]').val());
            var interest_cat_count = $('.profile_form input[name^="interest_cat_id"]:checked').length;
            var address_line_1 = $.trim($('.profile_form input[name="address_line_1"]').val());
            var country_code = $.trim($('.profile_form input[name="country_code"]').val());
            var zip_code = $.trim($('.profile_form input[name="zip_code"]').val());
            var profile_photo_removed = $.trim($('.profile_form input[name="profile_photo_removed"]').val());
            var interest_cats = [];
            $('.profile_form input[name^="interest_cat_id"]:checked').each(function() {
                interest_cats.push($(this).val());
            });
            var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var error = 0;
            $(".error").remove();
            if (first_name == "") {
                $('.profile_form input[name="first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
                error = 1;
            }
            if (last_name == "") {
                $('.profile_form input[name="last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
                error = 1;
            }
            if (email_pattern.test(email) == false) {
                $('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
                error = 1;
            }

            if (email != "" && email_otp_verify == 'false') {
                $('.profile_form input[name="email"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }
            /*if(mobile == "") {
              $('.profile_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter phone number</div>");
              error = 1;
            }*/
            if (mobile != "" && phone_otp_verify == 'false') {
                $('.profile_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }
            if (mobile == "") {
                $('.profile_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter valid mobile</div>");
                error = 1;
            }




            if (interest_cat_count == 0) {
                $('.profile_form .interests').append("<div class='error'>Choose at least one category</div>");
                error = 1;
            }
            if (address_line_1 == '' || country_code == '') {
                $('.profile_form input[name="location"]').closest('.form-group').append("<div class='error'>Type & choose address</div>");
                error = 1;
            }
            if (error == 0) {
                var profile_photo = $('.profile_form input[name="profile_photo"]')[0];
                var dialCode = $('#countrycode').val()
                $(".mw_loader").show();
                var data = new FormData();


                data.append('action', 'update_profile');
                data.append('first_name', first_name);
                data.append('last_name', last_name);
                data.append('email', email);
                data.append('mobile', phone_number.getNumber(intlTelInputUtils.numberFormat.E164));

                data.append('interest_cats', JSON.stringify(interest_cats));
                data.append('address_line_1', address_line_1);
                data.append('country_code', country_code);
                data.append('zip_code', zip_code);
                if (profile_photo.files.length > 0)
                    data.append('profile_photo', profile_photo.files[0]);
                data.append('profile_photo_removed', profile_photo_removed);
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == '0') {
                            $(".mw_loader").hide();
                            $(data.errors).each(function(i, v) {
                                if (v.field == 'email') {
                                    $('input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                }
                            });
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        }
    });
}

function settings_submit() {
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_1m"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_3m"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_6m"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_12m"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_1m_discounted"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_3m_discounted"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_6m_discounted"]');
    onlyNumbersWithDecimal('.settings_form input[name="subscription_price_12m_discounted"]');
    $('.settings_form input[name="location"]').on('keyup keypress', function(e) {
        $('.settings_form input[name="address_line_1"], .settings_form input[name="country_code"], .settings_form input[name="zip_code"]').val('');
    });
    $(document).on('click', '.settings_submit', function() {
        if (prop.user_data.role == '2') {

            var email_otp_verify = $.trim($('.settings_form input[name="email_otp_verify"]').val());
            var email = $.trim($('.settings_form input[name="email"]').val());

            var display_name = $.trim($('.settings_form input[name="display_name"]').val());
            var first_name = $.trim($('.settings_form input[name="first_name"]').val());
            var last_name = $.trim($('.settings_form input[name="last_name"]').val());
            var mobile = $.trim($('.settings_form input[name="mobile"]').val());
            var phone_otp_verify = $.trim($('.settings_form input[name="phone_otp_verify"]').val());



            var dob = $.trim($('.settings_form input[name="dob"]').val());
            var user_cat_id = $('.settings_form select[name="user_cat_id"]').val();
            var interest_cat_count = $('.settings_form input[name^="interest_cat_id"]:checked').length;
            var id_proof_doc = $.trim($('.settings_form input[name="id_proof_doc"]').val());
            var id_proof_doc_removed = $.trim($('.settings_form input[name="id_proof_doc_removed"]').val());
            var id_proof = $.trim($('.settings_form input[name="id_proof"]').val());
            var id_proof_removed = $.trim($('.settings_form input[name="id_proof_removed"]').val());
            var profile_photo_removed = $.trim($('.settings_form input[name="profile_photo_removed"]').val());
            var profile_video_removed = $.trim($('.settings_form input[name="profile_video_removed"]').val());
            //var profile_banner_removed = $.trim($('.settings_form input[name="profile_banner_removed"]').val());
            var allow_vip_friend_request = $('.settings_form input[name="allow_vip_friend_request"]:checked').length;
            var subscription_price_1m = $.trim($('.settings_form input[name="subscription_price_1m"]').val());
            var subscription_min_price_1m = parseFloat($('.settings_form input[name="subscription_price_1m"]').attr('min'));
            var subscription_max_price_1m = parseFloat($('.settings_form input[name="subscription_price_1m"]').attr('max'));
            var subscription_price_1m_discounted = $.trim($('.settings_form input[name="subscription_price_1m_discounted"]').val());
            var subscription_min_price_1m_discounted = parseFloat($('.settings_form input[name="subscription_price_1m_discounted"]').attr('min'));
            var subscription_price_1m_discounted_todate = $.trim($('.settings_form input[name="subscription_price_1m_discounted_todate"]').val());
            var subscription_price_3m = $.trim($('.settings_form input[name="subscription_price_3m"]').val());
            var subscription_min_price_3m = parseFloat($('.settings_form input[name="subscription_price_3m"]').attr('min'));
            var subscription_max_price_3m = parseFloat($('.settings_form input[name="subscription_price_3m"]').attr('max'));
            var subscription_price_3m_discounted = $.trim($('.settings_form input[name="subscription_price_3m_discounted"]').val());
            var subscription_min_price_3m_discounted = parseFloat($('.settings_form input[name="subscription_price_3m_discounted"]').attr('min'));
            var subscription_price_3m_discounted_todate = $.trim($('.settings_form input[name="subscription_price_3m_discounted_todate"]').val());
            var subscription_price_6m = $.trim($('.settings_form input[name="subscription_price_6m"]').val());
            var subscription_min_price_6m = parseFloat($('.settings_form input[name="subscription_price_6m"]').attr('min'));
            var subscription_max_price_6m = parseFloat($('.settings_form input[name="subscription_price_6m"]').attr('max'));
            var subscription_price_6m_discounted = $.trim($('.settings_form input[name="subscription_price_6m_discounted"]').val());
            var subscription_min_price_6m_discounted = parseFloat($('.settings_form input[name="subscription_price_6m_discounted"]').attr('min'));
            var subscription_price_6m_discounted_todate = $.trim($('.settings_form input[name="subscription_price_6m_discounted_todate"]').val());
            var subscription_price_12m = $.trim($('.settings_form input[name="subscription_price_12m"]').val());
            var subscription_min_price_12m = parseFloat($('.settings_form input[name="subscription_price_12m"]').attr('min'));
            var subscription_max_price_12m = parseFloat($('.settings_form input[name="subscription_price_12m"]').attr('max'));
            var subscription_price_12m_discounted = $.trim($('.settings_form input[name="subscription_price_12m_discounted"]').val());
            var subscription_min_price_12m_discounted = parseFloat($('.settings_form input[name="subscription_price_12m_discounted"]').attr('min'));
            var subscription_price_12m_discounted_todate = $.trim($('.settings_form input[name="subscription_price_12m_discounted_todate"]').val());
            var private_session_price = $.trim($('.settings_form input[name="private_session_price"]').val());
            var twitter_url = $.trim($('.settings_form input[name="twitter_url"]').val());
            var wishlist_url = $.trim($('.settings_form input[name="wishlist_url"]').val());
            var address_line_1 = $.trim($('.settings_form input[name="address_line_1"]').val());
            var country_code = $.trim($('.settings_form input[name="country_code"]').val());
            var zip_code = $.trim($('.settings_form input[name="zip_code"]').val());
            var short_bio = $.trim($('.settings_form input[name="short_bio"]').val());
            var about_bio = $.trim($('.settings_form textarea[name="about_bio"]').val());
            var thank_you_msg = $.trim($('.settings_form textarea[name="thank_you_msg"]').val());
            var private_chat_charge = $.trim($('.settings_form input[name="private_chat_charge"]').val());
            // alert(private_chat_charge)
            var free_follower = $('.settings_form input[name="free_follower"]:checked').length;
            var group_chat_charge = $.trim($('.settings_form input[name="group_chat_charge"]').val());
            var profile_keywords = $.trim($('.settings_form input[name="profile_keywords"]').val());
            var interest_cats = [];
            $('.settings_form input[name^="interest_cat_id"]:checked').each(function() {
                interest_cats.push($(this).val());
            });
            var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
            var error = 0;
            $(".error").remove();
            if (email_pattern.test(email) == false) {
                $('.settings_form input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
                error = 1;
            }
            if (email != "" && email_otp_verify == 'false') {
                $('.settings_form input[name="email"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }
            if (display_name == "") {
                $('.settings_form input[name="display_name"]').closest('.form-group').append("<div class='error'>Enter display name</div>");
                error = 1;
            }
            if (first_name == "") {
                $('.settings_form input[name="first_name"]').closest('.form-group').append("<div class='error'>Enter forename</div>");
                error = 1;
            }
            if (last_name == "") {
                $('.settings_form input[name="last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
                error = 1;
            }
            if (mobile == "") {
                $('.settings_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Enter phone number</div>");
                error = 1;
            }
            if (mobile != "" && phone_otp_verify == 'false') {
                $('.settings_form input[name="mobile"]').closest('.form-group').append("<div class='error'>Mobile not verified</div>");
                error = 1;
            }
            if (dob == "") {
                $('.settings_form input[name="dob"]').closest('.form-group').append("<div class='error'>Enter DOB</div>");
                error = 1;
            }
            if (user_cat_id == "") {
                $('.settings_form select[name="user_cat_id"]').closest('.form-group').append("<div class='error'>Select category</div>");
                error = 1;
            }
            if (interest_cat_count == 0) {
                $('.settings_form .interests').append("<div class='error'>Choose at least one category</div>");
                error = 1;
            }
            if (subscription_price_1m != '' && parseFloat(subscription_price_1m) < subscription_min_price_1m) {
                $('.settings_form input[name="subscription_price_1m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_3m != '' && parseFloat(subscription_price_3m) < subscription_min_price_3m) {
                $('.settings_form input[name="subscription_price_3m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_6m != '' && parseFloat(subscription_price_6m) < subscription_min_price_6m) {
                $('.settings_form input[name="subscription_price_6m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_12m != '' && parseFloat(subscription_price_12m) < subscription_min_price_12m) {
                $('.settings_form input[name="subscription_price_12m"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_1m_discounted != '' && parseFloat(subscription_price_1m_discounted) < subscription_min_price_1m_discounted) {
                $('.settings_form input[name="subscription_price_1m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_1m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_3m_discounted != '' && parseFloat(subscription_price_3m_discounted) < subscription_min_price_3m_discounted) {
                $('.settings_form input[name="subscription_price_3m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_3m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_6m_discounted != '' && parseFloat(subscription_price_6m_discounted) < subscription_min_price_6m_discounted) {
                $('.settings_form input[name="subscription_price_6m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_6m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (subscription_price_12m_discounted != '' && parseFloat(subscription_price_12m_discounted) < subscription_min_price_12m_discounted) {
                $('.settings_form input[name="subscription_price_12m_discounted"]').closest('.form-group').append("<div class='error'>Amount can not less than " + subscription_min_price_12m_discounted.toFixed(2) + "</div>");
                error = 1;
            }
            if (address_line_1 == '' || country_code == '') {
                $('.settings_form input[name="location"]').closest('.form-group').append("<div class='error'>Type & choose address</div>");
                error = 1;
            }
            if (about_bio == "") {
                $('.settings_form textarea[name="about_bio"]').closest('.form-group').append("<div class='error'>Put your bio</div>");
                error = 1;
            }
            if (thank_you_msg == "") {
                $('.settings_form textarea[name="thank_you_msg"]').closest('.form-group').append("<div class='error'>Put your message</div>");
                error = 1;
            }
            if (private_chat_charge == "") {
                $('.settings_form input[name="private_chat_charge"]').closest('.form-group').append("<div class='error'>Put your charge</div>");
                error = 1;
            }
            if (isNaN(private_chat_charge)) {
                $('.settings_form input[name="private_chat_charge"]').closest('.form-group').append("<div class='error'>Put your charge in number</div>");
                error = 1;
            }
            if (group_chat_charge == "") {
                $('.settings_form input[name="group_chat_charge"]').closest('.form-group').append("<div class='error'>Put your group chat charge</div>");
                error = 1;
            }
            if (isNaN(group_chat_charge)) {
                $('.settings_form input[name="group_chat_charge"]').closest('.form-group').append("<div class='error'>Put your group chat charge in number</div>");
                error = 1;
            }
            if (error == 0) {
                var id_proof_doc = $('.settings_form input[name="id_proof_doc"]')[0];
                var id_proof = $('.settings_form input[name="id_proof"]')[0];
                var profile_photo = $('.settings_form input[name="profile_photo"]')[0];
                var profile_video = $('.settings_form input[name="profile_video"]')[0];
                //var profile_banner = $('.settings_form input[name="profile_banner"]')[0];
                $(".mw_loader").show();
                var data = new FormData();
                data.append('action', 'update_settings');
                data.append('email', email);
                data.append('display_name', display_name);
                data.append('first_name', first_name);
                data.append('last_name', last_name);
                //data.append('mobile', mobile);
                data.append('mobile', phone_number.getNumber(intlTelInputUtils.numberFormat.E164));
                data.append('dob', dob);
                data.append('user_cat_id', user_cat_id);
                data.append('interest_cats', JSON.stringify(interest_cats));
                data.append('allow_vip_friend_request', allow_vip_friend_request);
                data.append('subscription_price_1m', subscription_price_1m);
                data.append('subscription_price_1m_discounted', subscription_price_1m_discounted);
                data.append('subscription_price_1m_discounted_todate', subscription_price_1m_discounted_todate);
                data.append('subscription_price_3m', subscription_price_3m);
                data.append('subscription_price_3m_discounted', subscription_price_3m_discounted);
                data.append('subscription_price_3m_discounted_todate', subscription_price_3m_discounted_todate);
                data.append('subscription_price_6m', subscription_price_6m);
                data.append('subscription_price_6m_discounted', subscription_price_6m_discounted);
                data.append('subscription_price_6m_discounted_todate', subscription_price_6m_discounted_todate);
                data.append('subscription_price_12m', subscription_price_12m);
                data.append('subscription_price_12m_discounted', subscription_price_12m_discounted);
                data.append('subscription_price_12m_discounted_todate', subscription_price_12m_discounted_todate);
                data.append('private_session_price', private_session_price);
                data.append('address_line_1', address_line_1);
                data.append('country_code', country_code);
                data.append('zip_code', zip_code);
                data.append('twitter_url', twitter_url);
                data.append('wishlist_url', wishlist_url);
                data.append('short_bio', short_bio);
                data.append('about_bio', about_bio);
                data.append('thank_you_msg', thank_you_msg);
                data.append('private_chat_charge', private_chat_charge);
                data.append('group_chat_charge', group_chat_charge);
                data.append('free_follower', free_follower);
                data.append('profile_keywords', profile_keywords);
                data.append('id_proof_doc', id_proof_doc.files[0]);
                data.append('id_proof', id_proof.files[0]);
                if (profile_photo.files.length > 0)
                    data.append('profile_photo', profile_photo.files[0]);
                if (profile_video.files.length > 0)
                    data.append('profile_video', profile_video.files[0]);
                /* if (profile_banner.files.length > 0)
                    data.append('profile_banner', profile_banner.files[0]); */
                data.append('profile_photo_removed', profile_photo_removed);
                data.append('profile_video_removed', profile_video_removed);
                //data.append('profile_banner_removed', profile_banner_removed);
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == '0') {
                            $(".mw_loader").hide();
                            $(data.errors).each(function(i, v) {
                                if (v.field == 'email') {
                                    $('.settings_form input[name="email"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'subscription_price') {
                                    $('.settings_form input[name="subscription_price"]').closest('.form-group').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'id_proof_doc') {
                                    $('.settings_form input[name="id_proof_doc"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'id_proof') {
                                    $('.settings_form input[name="id_proof"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'profile_photo') {
                                    $('.settings_form input[name="profile_photo"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                if (v.field == 'profile_video') {
                                    $('.settings_form input[name="profile_video"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                }
                                /* if (v.field == 'profile_banner') {
                                    $('.settings_form input[name="profile_banner"]').closest('.file-upload').append("<div class='error'>" + v.message + "</div>");
                                } */
                            });
                        } else {
                            location.reload();
                        }
                    }
                });
            }
        }
    });
}

function bank_details_submit() {
    $(document).on('click', '.bank_details_submit', function() {
        var bank_country_id = $('.bank_details_form select[name="bank_country_id"]').val();
        var bank_account_name = $.trim($('.bank_details_form input[name="bank_account_name"]').val());
        var bank_account_sort_code = $.trim($('.bank_details_form input[name="bank_account_sort_code"]').val());
        var bank_account_number = $.trim($('.bank_details_form input[name="bank_account_number"]').val());
        var error = 0;
        $(".error").remove();
        var bank_account_sort_code_pattern = /^(\d){2}-(\d){2}-(\d){2}$/
        var bank_account_number_pattern = /^(\d){8}$/
        if (bank_account_name == "") {
            $('.bank_details_form input[name="bank_account_name"]').closest('.form-group').append("<div class='error'>Put your bank account name</div>");
            error = 1;
        }
        if (bank_country_id == "222" && bank_account_sort_code_pattern.test(bank_account_sort_code) == false) {
            $('.bank_details_form input[name="bank_account_sort_code"]').closest('.form-group').append("<div class='error'>Put your valid bank account sort code</div>");
            error = 1;
        }
        if (bank_country_id == "222" && bank_account_number_pattern.test(bank_account_number) == false) {
            $('.bank_details_form input[name="bank_account_number"]').closest('.form-group').append("<div class='error'>Put your valid bank account number</div>");
            error = 1;
        }
        if (error == 0) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_bank_details');
            data.append('bank_country_id', bank_country_id);
            data.append('bank_account_name', bank_account_name);
            data.append('bank_account_sort_code', bank_account_sort_code);
            data.append('bank_account_number', bank_account_number);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '0') {
                        $(".mw_loader").hide();
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
}

function about_submit() {
    if (prop.user_data.role == '2') {
        $(document).on('click', '.about_form .about_submit', function() {
            var gender = $('.about_form select[name="gender"]').val();
            var sexual_orientation_id = $('.about_form select[name="sexual_orientation_id"]').val();
            var language_spoken = $.trim($('.about_form input[name="language_spoken"]').val());
            var zodiac_id = $('.about_form select[name="zodiac_id"]').val();
            var height = $.trim($('.about_form input[name="height"]').val());
            var weight = $.trim($('.about_form input[name="weight"]').val());
            var eye_color = $.trim($('.about_form input[name="eye_color"]').val());
            var hair_color = $.trim($('.about_form input[name="hair_color"]').val());
            var build = $.trim($('.about_form input[name="build"]').val());
            var ethnicity = $.trim($('.about_form input[name="ethnicity"]').val());
            var cupsize_id = $('.about_form select[name="cupsize_id"]').val();
            var public_hair = $.trim($('.about_form input[name="public_hair"]').val());
            var measurements = $.trim($('.about_form input[name="measurements"]').val());
            var model_attributes = [];
            $('.about_form input[name^="model_attributes"]:checked').each(function(i, v) {
                model_attributes.push($(this).val());
            });
            var composite_banners = {};
            $('.about_form .crop_image_uploader').each(function() {
                var composite_order = $(this).attr('composite_order');
                composite_banners[('ban_' + composite_order)] = '';
                if ($(this).hasClass('active')) {
                    composite_banners[('ban_' + composite_order)] = $(this).find('img.imgpreviewPrf').attr('src');
                }
            });
            var error = 0;
            $(".error").remove();
            if (gender == '') {
                $('.about_form select[name="gender"]').closest('.form-group').append("<div class='error'>Select gender</div>");
                error = 1;
            }
            if (error == 0) {
                $(".mw_loader").show();
                var data = new FormData();
                data.append('action', 'update_vip_member_about');
                data.append('composite_banners', JSON.stringify(composite_banners));
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
                data.append('cupsize_id', cupsize_id);
                data.append('public_hair', public_hair);
                data.append('measurements', measurements);
                data.append('model_attributes', model_attributes);
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success == 1) {
                            location.reload();
                        }
                    }
                });
            }
        });
    }
}

function vip_member_search() {
    $(document).on('click', 'header .search_submit', function() {
        var s = $.trim($('header input[name="s"]').val());
        window.location.href = prop.url + '/search?s=' + s;
    });
    $(document).on('click', '.search_modal .adv_search_submit', function() {
        var s = $.trim($('.search_modal input[name="s"]').val());
        var st = '';
        $('.search_modal input[name^="st"]:checked').each(function() {
            if (st != '') st += ',';
            st += $(this).val();
        });
        var lst = '';
        $('.search_modal input[name^="lst"]:checked').each(function() {
            if (lst != '') lst += ',';
            lst += $(this).val();
        });
        var uc = '';
        $('.search_modal input[name^="uc"]:checked').each(function() {
            if (uc != '') uc += ',';
            uc += $(this).val();
        });
        var uo = '';
        $('.search_modal input[name^="uo"]:checked').each(function() {
            if (uo != '') uo += ',';
            uo += $(this).val();
        });
        var age = '';
        $('.search_modal input[name^="age"]:checked').each(function() {
            if (age != '') age += ',';
            age += $(this).val();
        });
        window.location.href = prop.url + '/search?s=' + s + '&st=' + st + '&lst=' + lst + '&uc=' + uc + '&uo=' + uo + '&age=' + age;
    });
}

function create_post() {
    /*select2_ajax({'element': '.post_visibility_modal select[name="subscriber_id[]"]'});
    $(document).on('click', '.type-post .post_visibility', function(){
    	$(".post_visibility_modal").modal({
        backdrop: 'static',
        keyboard: false
      });
    });
    $(document).on('change', '.post_visibility_modal input[name="visibility"]', function(){
    	$('.post_visibility_modal .subscriber_except_options').hide();
    	if($(this).val() == 'subscriber_except')
    		$('.post_visibility_modal .subscriber_except_options').show();
    });
    $(document).on('click', '.post_visibility_modal .set_post_visibility', function(){
    	var input = $('.post_visibility_modal input[name="visibility"]:checked');
    	var subscriber_except_ids = [];
    	$('.post_visibility_modal select[name="subscriber_id[]"] option:selected').each(function(){
    		subscriber_except_ids.push($(this).val());
    	});
    	$('.type-post .post_visibility').attr('visibility', input.val()).attr('subscriber_ids', subscriber_except_ids.join(',')).find('span').text(input.closest('.checkbox').find('label').text());
    	$(".post_visibility_modal").modal('hide');
    });*/
    select2_ajax({ 'element': '.post_option_modal select[name="subscriber_id[]"]' });
    $(document).on('change', '.post_option_modal input[name="visibility"]', function() {
        $('.post_option_modal .subscriber_except_options').hide();
        if ($(this).val() == 'subscriber_except')
            $('.post_option_modal .subscriber_except_options').show();
    });
    $(document).on('click', '.type-post [post_type]', function() {
        var post_type = $(this).attr('post_type');
        $('.type-post input[name="post_type"]').val(post_type);
        $('.type-post .post_type_file').hide();
        $('.type-post .post_type_file .file-upload .fp-close').trigger('click');
        $('.type-post .post_type_file[for="' + post_type + '"]').show();
        $('.type-post .post_type_file[for="' + post_type + '"] .file-upload:nth-of-type(1) .file-select-button').trigger('click');
    });
    onlyNumbersWithDecimal('.post_option_modal input[name="post_price"]');
    $(document).on('click', '.type-post .post_option', function() {
        $(".post_option_modal").modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $(document).on('change', '.post_option_modal input[name="post_value"]', function() {
        $('.post_option_modal .post_value_options').hide();
        if ($(this).val() == '1')
            $('.post_option_modal .post_value_options').show();
    });
    $(document).on('click', '.post_option_modal .post_submit', function() {
        var post_content = $.trim($('.type-post textarea[name="post_content"]').val());
        var post_type = $.trim($('.type-post input[name="post_type"]').val());
        /*var post_visibility = $('.type-post .post_visibility').attr('visibility');
        var post_visibility_subscriber_ids = $('.type-post .post_visibility').attr('subscriber_ids');*/
        var post_visibility = $('.post_option_modal input[name="visibility"]:checked').val();
        var subscriber_except_ids = [];
        if (post_visibility == 'subscriber_except') {
            $('.post_option_modal select[name="subscriber_id[]"] option:selected').each(function() {
                subscriber_except_ids.push($(this).val());
            });
        }
        var post_visibility_subscriber_ids = subscriber_except_ids.join(',');
        var post_priced = $('.post_option_modal input[name="post_value"]:checked').val();

        var post_price = $.trim($('.post_option_modal input[name="post_price"]').val());
        var post_head = $.trim($('.post_option_modal textarea[name="post_head"]').val());
        if (post_priced == 1) {
            if (post_price == "") {
                $('.post_value_options input[name="post_price"]').closest('.from-input-wrap').find('.error').remove();
                $('.post_value_options input[name="post_price"]').closest('.from-input-wrap').append("<div class='error'>Enter coin amount</div>");
                return false;
            }

            if (post_head == "") {
                $('.post_value_options textarea[name="post_head"]').closest('.from-input-wrap').find('.error').remove();
                $('.post_value_options textarea[name="post_head"]').closest('.from-input-wrap').append("<div class='error'>Enter head title</div>");
                return false;
            }
        }

        //$('.post_value_options input[name="post_price"]').closest('.from-input-wrap').append("<div class='error'>Enter few words</div>");




        var error = 0;
        $(".error").remove();
        if (post_content == "") {
            $('.type-post textarea[name="post_content"]').closest('.post-input-wrap').append("<div class='error'>Enter few words</div>");
            error = 1;
        }



        if (error == 1) $(".post_option_modal").modal('hide');
        if (error == 0) {
            var post_media_video = $('.type-post input[name="post_media_video"]')[0];
            var post_media_video_thumbnail = $('.type-post input[name="post_media_video_thumbnail"]')[0];
            var post_media_photo = $('.type-post input[name="post_media_photo"]')[0];
            var post_media_doc = $('.type-post input[name="post_media_doc"]')[0];
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'create_post');
            data.append('post_type', post_type);
            data.append('post_head', post_head);
            data.append('post_content', post_content);
            data.append('visibility', post_visibility);
            data.append('visibility_subscriber_ids', post_visibility_subscriber_ids);
            data.append('priced', post_priced);
            data.append('price', post_price);
            if (post_media_video.files.length > 0)
                data.append('media_video', post_media_video.files[0]);
            if (post_media_video_thumbnail.files.length > 0)
                data.append('media_video_thumbnail', post_media_video_thumbnail.files[0]);
            if (post_media_photo.files.length > 0)
                data.append('media_photo', post_media_photo.files[0]);
            if (post_media_doc.files.length > 0)
                data.append('media_doc', post_media_doc.files[0]);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        location.reload();
                    }
                }
            });
        }
    });
}

function load_vip_member_own_posts() {
    var ajax_running = $('.post_list_box').attr('ajax_running');
    var total_page = $('.post_list_box').attr('total_page');
    var cur_page = $('.post_list_box').attr('cur_page');
    var per_page = $('.post_list_box').attr('per_page');
    cur_page = parseInt(cur_page);
    if (total_page != '')
        total_page = parseInt(total_page);
    if (ajax_running == '0' && total_page > cur_page) {
        cur_page = cur_page + 1;
        $('.post_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_own_posts');
        data.append('cur_page', cur_page);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.post_list_box').attr('ajax_running', '0');
                $('.post_list_box').attr('total_page', data.data.total_page);
                $('.post_list_box').attr('cur_page', cur_page);
                $('.post_list_box').append(data.data.html);
                $("time.timeago").timeago();
                $('.video-js').each(function() {
                    var vid = $(this).attr('id');
                    videojs(vid);
                });
                emoji_pad();
            }
        });
    }
}

function vip_member_own_posts() {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.post_list_box').offset().top + $('.post_list_box').height();
        /*console.log('-------------------------------');
        console.log($(window).scrollTop());
        console.log($(window).height());
        console.log($('.post_list_box').offset().top);
        console.log($('.post_list_box').height());*/
        if ((plb_scr - win_scr) < 100) {
            //console.log('call ajax');
            load_vip_member_own_posts();
        }
    });
    $(document).on('click', '.own_post .delete_post', function() {
        var post_id = $(this).closest('.own_post').attr('post_id');
        var that = this;
        if (confirm('Are you sure to delete this post?')) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'delete_post');
            data.append('post_id', post_id);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        $(".mw_loader").hide();
                        $(that).closest('.own_post').remove();
                    }
                }
            });
        }
    });
}


function load_vip_member_profile_posts(params) {
    var ajax_running = $('.post_list_box').attr('ajax_running');
    var total_page = $('.post_list_box').attr('total_page');
    var cur_page = $('.post_list_box').attr('cur_page');
    var per_page = $('.post_list_box').attr('per_page');
    cur_page = parseInt(cur_page);
    if (total_page != '')
        total_page = parseInt(total_page);
    if (ajax_running == '0' && total_page > cur_page) {
        cur_page = cur_page + 1;
        $('.post_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_profile_posts');
        data.append('vip_member_id', params.vip_member_id);
        data.append('section', params.section);
        data.append('age', params.age);
        data.append('ordby', params.ordby);
        data.append('ord', params.ord);
        data.append('cur_page', cur_page);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.post_list_box').attr('ajax_running', '0');
                $('.post_list_box').attr('total_page', data.data.total_page);
                $('.post_list_box').attr('cur_page', cur_page);
                $('.post_list_box').append(data.data.html);
                $("time.timeago").timeago();
                $('.video-js').each(function() {
                    var vid = $(this).attr('id');
                    videojs(vid);
                });
                emoji_pad();
            }
        });
    }
}

function vip_member_profile_posts(params) {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.post_list_box').offset().top + $('.post_list_box').height();
        /*console.log('-------------------------------');
        console.log($(window).scrollTop());
        console.log($(window).height());
        console.log($('.post_list_box').offset().top);
        console.log($('.post_list_box').height());*/
        if ((plb_scr - win_scr) < 100) {
            //console.log('call ajax');
            load_vip_member_profile_posts(params);
        }
    });
}

function load_vip_member_favorite_posts(params) {
    var ajax_running = $('.post_list_box').attr('ajax_running');
    var total_page = $('.post_list_box').attr('total_page');
    var cur_page = $('.post_list_box').attr('cur_page');
    var per_page = $('.post_list_box').attr('per_page');
    cur_page = parseInt(cur_page);
    if (total_page != '')
        total_page = parseInt(total_page);
    if (ajax_running == '0' && total_page > cur_page) {
        cur_page = cur_page + 1;
        $('.post_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_posts');
        data.append('favorite', '1');
        data.append('cur_page', cur_page);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.post_list_box').attr('ajax_running', '0');
                $('.post_list_box').attr('total_page', data.data.total_page);
                $('.post_list_box').attr('cur_page', cur_page);
                $('.post_list_box').append(data.data.html);
                $("time.timeago").timeago();
                $('.video-js').each(function() {
                    var vid = $(this).attr('id');
                    videojs(vid);
                });
                emoji_pad();
            }
        });
    }
}

function vip_member_favorite_posts(params) {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.post_list_box').offset().top + $('.post_list_box').height();
        if ((plb_scr - win_scr) < 100) {
            load_vip_member_favorite_posts(params);
        }
    });
}

function load_vip_member_following_posts(params) {
    var ajax_running = $('.post_list_box').attr('ajax_running');
    var total_page = $('.post_list_box').attr('total_page');
    var cur_page = $('.post_list_box').attr('cur_page');
    var per_page = $('.post_list_box').attr('per_page');
    cur_page = parseInt(cur_page);
    if (total_page != '')
        total_page = parseInt(total_page);
    if (ajax_running == '0' && total_page > cur_page) {
        cur_page = cur_page + 1;
        $('.post_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_posts');
        data.append('cur_page', cur_page);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.post_list_box').attr('ajax_running', '0');
                $('.post_list_box').attr('total_page', data.data.total_page);
                $('.post_list_box').attr('cur_page', cur_page);
                $('.post_list_box').append(data.data.html);
                $("time.timeago").timeago();
                $('.video-js').each(function() {
                    var vid = $(this).attr('id');
                    videojs(vid);
                });
                emoji_pad();
            }
        });
    }
}

function vip_member_following_posts(params) {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.post_list_box').offset().top + $('.post_list_box').height();
        if ((plb_scr - win_scr) < 100) {
            load_vip_member_following_posts(params);
        }
    });
}

function set_user_fav() {
    $(document).on('click', '.set_user_fav', function() {
        if (typeof prop.user_data.id == 'undefined') return false;
        var fav_user_id = $(this).attr('user_id');
        var data = new FormData();
        data.append('action', 'set_user_fav');
        data.append('fav_user_id', fav_user_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                //console.log('test');
                if (data.success == '1') {

                    $('.set_user_fav[user_id="' + fav_user_id + '"]').removeClass('fa').removeClass('fas');
                    $('.set_user_fav[user_id="' + fav_user_id + '"]').attr('data-original-title', 'Add to hot list');
                    //console.log(1);
                    if (data.data.fav == '0') {
                        $('.set_user_fav[user_id="' + fav_user_id + '"]').addClass('fa');
                        $('.set_user_fav[user_id="' + fav_user_id + '"]').attr('data-original-title', 'Add to hot list');
                    } else if (data.data.fav == '1') {
                        $('.set_user_fav[user_id="' + fav_user_id + '"]').addClass('fas');
                        $('.set_user_fav[user_id="' + fav_user_id + '"]').attr('data-original-title', 'Remove from hot list');
                    }
                    location.reload();
                }
            }
        });
    });
    $(document).on('click', '.add_to_hotlist', function() {
        if (typeof prop.user_data.id == 'undefined') return false;
        var fav_user_id = $(this).attr('fav_user_id');
        var data = new FormData();
        data.append('action', 'set_user_fav');
        data.append('fav_user_id', fav_user_id);
        data.append('fav', '1');
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $('.set_user_fav[user_id="' + fav_user_id + '"]').removeClass('far').addClass('fas');
                }
            }
        });
    });
}

function set_user_follow() {
    $(document).on('click', '.set_user_follow', function() {
        if (typeof prop.user_data.id == 'undefined') return false;
        var follow_user_id = $(this).attr('user_id');
        var data = new FormData();
        data.append('action', 'set_user_follow');
        data.append('follow_user_id', follow_user_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $('.set_user_follow[user_id="' + follow_user_id + '"]').html('');
                    if (data.data.follow == '0')
                        $('.set_user_follow[user_id="' + follow_user_id + '"]').html('<i class="fas fa-thumbs-up"></i> Follow');
                    if (data.data.follow == '1')
                        $('.set_user_follow[user_id="' + follow_user_id + '"]').html('<i class="fas fa-thumbs-down"></i> Unfollow');
                }
            }
        });
    });
}

function user_subscription() {
    $(document).on('click', '.show_subscribe_options', function() {
        if ($(this).attr('enabled') == '1')
            $("#subscribeModal").modal({ backdrop: 'static', keyboard: false });
    });
    $(document).on('click', '#subscribeModal .buy_subscription', function() {
        var duration = $(this).attr('duration');
        var price = parseFloat($(this).attr('price'));
        var title = '';
        if (duration == '1m') title = '1 Month';
        if (duration == '3m') title = '3 Months';
        if (duration == '6m') title = '6 Months';
        if (duration == '12m') title = '12 Months';
        var desc = title + ' : £' + price.toFixed(2);
        $('#buySubscriptionModal input[name="duration"]').val(duration);
        $("#buySubscriptionModal .ajax_response").html('');
        $("#buySubscriptionModal .payment_desc").html(desc);
        $("#subscribeModal").modal('hide').on('hidden.bs.modal', function() {
            $("#buySubscriptionModal").modal({ backdrop: 'static', keyboard: false });
            $("#subscribeModal").unbind('hidden.bs.modal');
        });
    });
    $(document).on('click', '.pay_buy_subscription', function() {
        var duration = $.trim($('#buySubscriptionModal input[name="duration"]').val());
        var user_id = $.trim($('#buySubscriptionModal input[name="user_id"]').val());
        var card_number = $.trim($('#buySubscriptionModal input[name="card_number"]').val());
        var exp_month = $('#buySubscriptionModal select[name="exp_month"]').val();
        var exp_year = $('#buySubscriptionModal select[name="exp_year"]').val();
        var card_cvv = $.trim($('#buySubscriptionModal input[name="card_cvv"]').val());
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'pay_buy_subscription');
        data.append('user_id', user_id);
        data.append('duration', duration);
        data.append('card_number', card_number);
        data.append('exp_month', exp_month);
        data.append('exp_year', exp_year);
        data.append('card_cvv', card_cvv);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $(".mw_loader").hide();
                if (data.success == '1') {
                    $("#buySubscriptionModal .ajax_response").html('<p class="text-success">' + data.message + '</p>');
                } else {
                    $("#buySubscriptionModal .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
                }
            }
        });
    });
    $(document).on('click', '.set_user_unsubscribe', function() {
        if (typeof prop.user_data.id == 'undefined') return false;
        if (confirm('Are you sure to remove this subscription?')) {
            var that = $(this);
            var subscription_user_id = $(this).attr('user_id');
            var data = new FormData();
            data.append('action', 'set_user_unsubscribe');
            data.append('subscription_user_id', subscription_user_id);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        that.remove();
                        $('.show_subscribe_options').text('Subscribe').attr('enabled', '1');
                    }
                }
            });
        }
    });
}

function modal_nav(params) {
    var that = params.that;
    var total_elem = $('.post .view_media').length;
    var index = $(that).closest('.post').index();
    var nav = pnv = nnv = '';
    var pnv_s = 'display: none;';
    if (index > 0) pnv_s = '';
    pnv = '<a href="javascript:;" class="prev" tot="' + total_elem + '" cur="' + (index + 1) + '" style="' + pnv_s + '"><span class="ti-angle-left"></span></a>';
    var nnv_s = 'display: none;';
    if ((index + 1) < total_elem) nnv_s = '';
    nnv = '<a href="javascript:;" class="next" tot="' + total_elem + '" cur="' + (index + 1) + '" style="' + nnv_s + '"><span class="ti-angle-right"></span></a>';
    nav = '<div class="text-center modal_nav">' + pnv + nnv + '</div>';
    return nav;
}

function post_actions() {
    $(document).on('click', '.post .view_media[image_url]', function(e) {
        // alert();
        if ($(e.target).hasClass('unlock_post')) return false;
        var post_id = $(this).closest('.post').attr('post_id');
        var that = this;
        $('.mw_loader').show();
        var data = new FormData();
        data.append('action', 'get_post');
        data.append('post_id', post_id);
        data.append('view_type', 'media_modal');
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.mw_loader').hide();
                // alert();
                if (data.success == '1') {
                    var html = '<div class="post_list_box">' + data.data.html + '</div>' + modal_nav({ 'that': that });
                    $('#mediaViewModal .modal-body .media_details').html(html);
                    $("#mediaViewModal").modal({ backdrop: 'static', keyboard: false });
                    $("time.timeago").timeago();
                    emoji_pad();
                    $(document).on('click', '#mediaViewModal .close', function() {
                        $('#mediaViewModal .modal-body .media_details').html('');
                        $("#mediaViewModal").modal('hide');
                    });
                }
            }
        });
        /*var html = '<div class="text-center"><img src="' + $(this).attr('image_url') + '" class="img-fluid" /></div>' + modal_nav({that: this});
        $('#mediaViewModal .modal-body .media_details').html(html);
        $("#mediaViewModal").modal({ backdrop: 'static', keyboard: false });
        $(document).on('click', '#mediaViewModal .close', function(){
        	$('#mediaViewModal .modal-body .media_details').html('');
        	$("#mediaViewModal").modal('hide');
        });*/
    });
    $(document).on('click', '.post .view_media[video_url]', function(e) {
        if ($(e.target).hasClass('unlock_post')) return false;
        var post_id = $(this).closest('.post').attr('post_id');
        var that = this;
        $('.mw_loader').show();
        var data = new FormData();
        data.append('action', 'get_post');
        data.append('post_id', post_id);
        data.append('view_type', 'media_modal');
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.mw_loader').hide();
                if (data.success == '1') {
                    var html = '<div class="post_list_box">' + data.data.html + '</div>' + modal_nav({ 'that': that });
                    $('#mediaViewModal .modal-body .media_details').html(html);
                    $("#mediaViewModal").modal({ backdrop: 'static', keyboard: false });
                    $("time.timeago").timeago();
                    $('.video-js').each(function() {
                        var vid = $(this).attr('id');
                        videojs(vid);
                    });
                    emoji_pad();
                    $(document).on('click', '#mediaViewModal .close', function() {
                        $('#mediaViewModal .modal-body .media_details').html('');
                        $("#mediaViewModal").modal('hide');
                    });
                }
            }
        });
        /*var post_id = $(this).closest('.post').attr('post_id');
		var vid = 'example_video_' + post_id;
		var html = `<div class="text-center"><div class="homeVideoInner">
        <video id="` + vid + `" class="video-js" controls preload="none" poster="` + $(this).find('img').attr('src') + `" data-setup="{}" autoplay>
            <source src="` + $(this).attr('video_url') + `" type="video/mp4">
          </video>
    </div></div>` + modal_nav({that: this});
		$('#mediaViewModal .modal-body .media_details').html(html);
		$("#mediaViewModal").modal({ backdrop: 'static', keyboard: false });
		videojs(vid);
		$(document).on('click', '#mediaViewModal .close', function(){
			$('#mediaViewModal .modal-body .media_details').html('');
			$("#mediaViewModal").modal('hide');
		});*/
    });
    $(document).on('click', '#mediaViewModal .modal_nav .prev', function() {
        var cur = parseInt($(this).attr('cur'));
        $('.post').eq(((cur - 1) - 1)).find('.view_media').trigger('click');
    });
    $(document).on('click', '#mediaViewModal .modal_nav .next', function() {
        var cur = parseInt($(this).attr('cur'));
        $('.post').eq(cur).find('.view_media').trigger('click');
    });
    $(document).on('click', '.post .set_react', function() {
        var post_id = $(this).closest('.post').attr('post_id');
        var data = new FormData();
        data.append('action', 'set_post_react');
        data.append('post_id', post_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $('.post[post_id="' + post_id + '"] .set_react').html('');
                    if (data.data.react == '0')
                        $('.post[post_id="' + post_id + '"] .set_react').html('<span class="far fa-heart"></span> ' + data.data.react_count);
                    if (data.data.react == '1')
                        $('.post[post_id="' + post_id + '"] .set_react').html('<span class="fas fa-heart"></span> ' + data.data.react_count);
                }
            }
        });
    });
    $(document).on('click', '.post .comment_counter', function() {
        var post_id = $(this).closest('.post').attr('post_id');
        if ($(this).closest('.post').find('.commentWrap .commentWrapCont').html() == '') {
            var that = this;
            var data = new FormData();
            data.append('action', 'get_post_comments');
            data.append('post_id', post_id);
            data.append('top_parent_comment_id', '0');
            data.append('start_after_comment_id', '-1');
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        $(that).closest('.post').find('.commentWrap .commentWrapCont').html(data.data.comment_html);
                        $(that).closest('.post').find('.commentWrap').attr('total_comments', data.data.comment_count);
                        if (data.data.comment_count > $(that).closest('.post').find('.commentWrap .commentWrapCont .commentWrapInner').length)
                            $(that).closest('.post').find('.commentWrap .load_more_comments').show();
                        $("time.timeago").timeago();
                        emoji_pad();
                    }
                }
            });
        } else {
            $(this).closest('.post').find('.commentWrap .commentWrapCont').html('');
            $(this).closest('.post').find('.commentWrap .load_more_comments').hide();
        }
    });
    $(document).on('click', '.post .load_more_comments', function() {
        var post_id = $(this).closest('.post').attr('post_id');
        var start_after_comment_id = $(this).closest('.post').find('.commentWrapCont .commentWrapInner').last().attr('post_comment_id');
        var that = this;
        var data = new FormData();
        data.append('action', 'get_post_comments');
        data.append('post_id', post_id);
        data.append('top_parent_comment_id', '0');
        data.append('start_after_comment_id', start_after_comment_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $(that).closest('.post').find('.commentWrap .commentWrapCont').append(data.data.comment_html);
                    var total_comments = $(that).closest('.post').find('.commentWrap').attr('total_comments');
                    if (parseInt(total_comments) == $(that).closest('.post').find('.commentWrap .commentWrapCont .commentWrapInner').length)
                        $(that).closest('.post').find('.commentWrap .load_more_comments').hide();
                    $("time.timeago").timeago();
                    emoji_pad();
                }
            }
        });
    });
    $(document).on('click', '.post .load_more_subcomments', function() {
        var post_id = $(this).closest('.post').attr('post_id');
        var top_parent_comment_id = $(this).closest('.commentWrapInner').attr('post_comment_id');
        var start_after_comment_id = '-1';
        if ($(this).closest('.commentWrapInner').find('.subcomment_list .subComent').length)
            var start_after_comment_id = $(this).closest('.commentWrapInner').find('.subcomment_list .subComent').last().attr('post_comment_id');
        var that = this;
        var data = new FormData();
        data.append('action', 'get_post_comments');
        data.append('post_id', post_id);
        data.append('top_parent_comment_id', top_parent_comment_id);
        data.append('start_after_comment_id', start_after_comment_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $(that).closest('.commentWrapInner').find('.subcomment_list').append(data.data.comment_html);
                    if (start_after_comment_id == '-1') {
                        $(that).closest('.commentWrapInner').find('.subcomment_list').attr('total_comments', data.data.comment_count);
                    }
                    var total_comments = $(that).closest('.commentWrapInner').find('.subcomment_list').attr('total_comments');
                    if (parseInt(total_comments) == $(that).closest('.commentWrapInner').find('.subcomment_list .subComent').length)
                        $(that).closest('.commentWrapInner').find('.load_more_subcomments').hide();
                    $("time.timeago").timeago();
                    emoji_pad();
                }
            }
        });
    });
    $(document).on('click', '.post .set_post_comment_react', function() {
        var post_comment_id = $(this).attr('post_comment_id');
        var data = new FormData();
        data.append('action', 'set_post_comment_react');
        data.append('post_comment_id', post_comment_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $('.set_post_comment_react[post_comment_id="' + post_comment_id + '"]').html('');
                    if (data.data.react == '0')
                        $('.set_post_comment_react[post_comment_id="' + post_comment_id + '"]').html('<i class="far fa-heart"></i> ' + data.data.react_count + ' <span>Like</span>');
                    if (data.data.react == '1')
                        $('.set_post_comment_react[post_comment_id="' + post_comment_id + '"]').html('<i class="fas fa-heart"></i> ' + data.data.react_count + ' <span>Unlike</span>');
                }
            }
        });
    });
    $(document).on('click', '.post .replyLike .reply', function() {
        $(this).closest('.post').find('.reply_box').not($(this).closest('.replyLike').find('.reply_box')).hide();
        $(this).closest('.replyLike').find('.reply_box').toggle();
    });
    $(document).on('click', '[delete_comment]', function() {
        var comment_id = $(this).attr('delete_comment');
        if (confirm('are you sure to delete this comment?')) {
            var data = new FormData();
            data.append('action', 'delete_comment');
            data.append('comment_id', comment_id);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        $('.subComent[post_comment_id="' + comment_id + '"]').remove();
                        $('.commentWrapInner[post_comment_id="' + comment_id + '"] > .commentImg, .commentWrapInner[post_comment_id="' + comment_id + '"] > .commentDtls > .commentDtlsTop, .commentWrapInner[post_comment_id="' + comment_id + '"] > .commentDtls > .replyLike').remove();
                        $('.post[post_id="' + data.data.post_id + '"] .comment_counter').html('<span class="far fa-comment-alt"></span> ' + data.data.comment_count);
                    }
                }
            });
        }
    });
    $(document).on('click', '.report_user', function() {
        if (confirm('are you sure you want to report this user?')) {
            var data = new FormData();
            data.append('action', 'add_report_item');
            data.append('type', '1');
            data.append('item_id', $(this).attr('user_id'));
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1')
                        alert(data.message);
                }
            });
        }
    });
    $(document).on('click', '.report_post', function() {
        if (confirm('are you sure you want to report this post?')) {
            var data = new FormData();
            data.append('action', 'add_report_item');
            data.append('type', '2');
            data.append('item_id', $(this).attr('post_id'));
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1')
                        alert(data.message);
                }
            });
        }
    });
    $(document).on('click', '.unlock_post', function() {
        var post_id = $(this).closest('.post').attr('post_id');
        var coin = $(this).data('coin');
        var that = $(this);
        $.confirm({
            content: `<h5 class="alert-content">I'm happy to purchase for (${coin} coins)</h5>`,
            title: '<span class="alert-logo"><img src="' + prop.url + '/public/uploads/settings/settings_website_logo/f32ed34d-5e31-494f-b20a-65de6c6c981f.png"></span>',
            buttons: {
                cancel: {
                    btnClass: 'btn-red',
                    action: function() {}
                },
                Agree: {
                    btnClass: 'btn-blue', // multiple classes.
                    action: function() {

                        $('.unlock_post_ajax_response').html('');

                        $('.mw_loader').show();
                        var data = new FormData();
                        data.append('action', 'unlock_post');
                        data.append('post_id', post_id);
                        data.append('_token', prop.csrf_token);
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: prop.ajaxurl,
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                $('.mw_loader').hide();
                                if (data.success == '1') {
                                    $('.post[post_id="' + post_id + '"]').find('.post-wrap-box-middle').replaceWith(data.data.list_html);
                                    $('.post[post_id="' + post_id + '"]').find('.view_media').removeClass('locked').replaceWith(data.data.grid_html);
                                    $('.video-js').each(function() {
                                        var vid = $(this).attr('id');
                                        videojs(vid);
                                    });
                                    $('.balanceBtn span').html(data.data.wallet_coin + " Coins")
                                } else {
                                    that.closest('.post').find('.unlock_post_ajax_response').html('<div class="text-danger">' + data.message + '</div>');
                                }
                            }
                        });
                    }
                },
            }
        });

        // if (confirm('Are you sure want to unlock this post?')) {
        //     var post_id = $(this).closest('.post').attr('post_id');
        //     $('.unlock_post_ajax_response').html('');
        //     var that = $(this);
        //     $('.mw_loader').show();
        //     var data = new FormData();
        //     data.append('action', 'unlock_post');
        //     data.append('post_id', post_id);
        //     data.append('_token', prop.csrf_token);
        //     $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
        //             $('.mw_loader').hide();
        //             if(data.success == '1'){
        //                 $('.post[post_id="' + post_id + '"]').find('.post-wrap-box-middle').replaceWith(data.data.list_html);
        //                 $('.post[post_id="' + post_id + '"]').find('.view_media').removeClass('locked').replaceWith(data.data.grid_html);
        //                 $('.video-js').each(function(){
        //                     var vid = $(this).attr('id');
        //                     videojs(vid);
        //                 });
        //                 $('.balanceBtn span').html(data.data.wallet_coin + " Coins")
        //             } else {
        //                 that.closest('.post').find('.unlock_post_ajax_response').html('<div class="text-danger">' + data.message + '</div>');
        //             }
        //         }
        //     });
        // }

    });
    onlyNumbers('#sendTipModal input[name="token_coin"]');
    $(document).on('click', '.send_tip', function() {
        var post_id = $(this).attr('post_id');
        $("#sendTipModal input[name='post_id']").val(post_id);
        $("#sendTipModal .ajax_response").html('');
        $("#sendTipModal").modal({ backdrop: 'static', keyboard: false });
    });
    $(document).on('click', '#sendTipModal .pay_send_tip', function() {
        var post_id = $("#sendTipModal input[name='post_id']").val();
        var token_coin = $.trim($("#sendTipModal input[name='token_coin']").val());
        if (token_coin != '') {
            $('.mw_loader').show();
            var data = new FormData();
            data.append('action', 'pay_send_tip');
            data.append('post_id', post_id);
            data.append('token_coin', token_coin);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('.mw_loader').hide();
                    if (data.success == '1') {
                        $("#sendTipModal .ajax_response").html('<p class="text-success">' + data.message + '</p>');
                        $('.balanceBtn span').html(data.data.wallet_coin + " Coins");
                    } else {
                        $("#sendTipModal .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
                    }
                }
            });
        }
    });
}

function set_comment(comment, params) {
    params = JSON.parse(params);
    var top_parent_comment_id = parent_comment_id = '0';
    if (typeof params.top_parent_comment_id != 'undefined')
        top_parent_comment_id = params.top_parent_comment_id;
    if (typeof params.parent_comment_id != 'undefined')
        parent_comment_id = params.parent_comment_id;
    comment = $.trim(comment);
    if (comment != '') {
        var data = new FormData();
        data.append('action', 'set_post_comment');
        data.append('post_id', params.post_id);
        data.append('top_parent_comment_id', top_parent_comment_id);
        data.append('parent_comment_id', parent_comment_id);
        data.append('comment', comment);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    $('.post[post_id="' + params.post_id + '"] .comment_counter').html('<span class="far fa-comment-alt"></span> ' + data.data.comment_count);
                    $('.post[post_id="' + params.post_id + '"] .reply_box').hide();
                    if ($('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapCont').html() == '') {
                        $('.post[post_id="' + params.post_id + '"] .comment_counter').trigger('click');
                    } else {
                        if (top_parent_comment_id == 0) {
                            var total_comments = parseInt($('.post[post_id="' + params.post_id + '"] .commentWrap').attr('total_comments'));
                            $('.post[post_id="' + params.post_id + '"] .commentWrap').attr('total_comments', (total_comments + 1));
                            $('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapCont').prepend(data.data.comment_html);
                        } else {
                            if ($('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapInner[post_comment_id="' + top_parent_comment_id + '"]').find('.subcomment_list').html() == '') {
                                $('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapInner[post_comment_id="' + top_parent_comment_id + '"]').find('.load_more_subcomments').trigger('click');
                            } else {
                                var total_comments = parseInt($('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapInner[post_comment_id="' + top_parent_comment_id + '"]').find('.subcomment_list').attr('total_comments'));
                                $('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapInner[post_comment_id="' + top_parent_comment_id + '"]').find('.subcomment_list').attr('total_comments', (total_comments + 1))
                                $('.post[post_id="' + params.post_id + '"] .commentWrap .commentWrapInner[post_comment_id="' + top_parent_comment_id + '"]').find('.subcomment_list').prepend(data.data.comment_html);
                            }
                        }
                        $("time.timeago").timeago();
                        emoji_pad();
                    }
                }
            }
        });
    }
}

function profile_filter_post(params) {
    $(document).on('click', '.filter_post_submit', function() {
        var age = ordby = ord = '';
        if ($('.filterPostModal input[name="age"]:checked').length)
            age = $('.filterPostModal input[name="age"]:checked').val();
        if ($('.filterPostModal input[name="ordby"]:checked').length)
            ordby = $('.filterPostModal input[name="ordby"]:checked').val();
        if ($('.filterPostModal input[name="ord"]:checked').length)
            ord = $('.filterPostModal input[name="ord"]:checked').val();
        window.location.href = prop.url + '/u/' + params.vip_member_username + '/' + params.section + '?scrollto=Cprofile-sec&age=' + age + '&ordby=' + ordby + '&ord=' + ord;
    });
}

function product_vip_member() {
    onlyNumbers('.product_vip_form input[name="stock"]');
    onlyNumbers('.product_vip_form input[name="price"]');
    $(document).on('change', '.product_vip_form select[name="type"]', function() {
        $('.product_vip_form .product_attachment').hide();
        $('.product_vip_form .product_attachment .file-upload').attr('allowedExt', '');
        if ($(this).val() == 1) {
            $('.product_vip_form .product_attachment').show();
            $('.product_vip_form .product_attachment .file-upload').attr('allowedExt', 'pdf,jpg,png,jpeg');
        }
        if ($(this).val() == 2) {
            $('.product_vip_form .product_attachment').show();
            $('.product_vip_form .product_attachment .file-upload').attr('allowedExt', 'mp3');
        }
        if ($(this).val() == 3) {
            $('.product_vip_form .product_attachment').show();
            $('.product_vip_form .product_attachment .file-upload').attr('allowedExt', 'mp4');
        }
        noPrevFileUpload();
    });
    $(document).on('click', '.product_vip_form .product_submit', function() {
        var type = $('.product_vip_form select[name="type"]').val();
        var stock = $.trim($('.product_vip_form input[name="stock"]').val());
        var title = $.trim($('.product_vip_form input[name="title"]').val());
        var price = $.trim($('.product_vip_form input[name="price"]').val());
        var id = $.trim($('.product_vip_form input[name="id"]').val());
        var description = $.trim($('.product_vip_form textarea[name="product_desc"]').val());
        var error = 0;
        $(".error").remove();
        if (type == 4 && stock == "") {
            $('.product_vip_form input[name="stock"]').closest('li').append("<div class='error'>Enter stock amount</div>");
            error = 1;
        }
        if (title == "") {
            $('.product_vip_form input[name="title"]').closest('li').append("<div class='error'>Enter title</div>");
            error = 1;
        }
        if (price == "") {
            $('.product_vip_form input[name="price"]').closest('li').append("<div class='error'>Enter coin amount</div>");
            error = 1;
        }
        if (description == "") {
            $('.product_vip_form textarea[name="description"]').closest('li').append("<div class='error'>Enter Product Description</div>");
            error = 1;
        }
        if (error == 0) {
            var thumbnail = $('.product_vip_form input[name="thumbnail"]')[0];
            var thumbnail_removed = $.trim($('.product_vip_form input[name="thumbnail_removed"]').val());
            var attachment = $('.product_vip_form input[name="attachment"]')[0];
            var attachment_removed = $.trim($('.product_vip_form input[name="attachment_removed"]').val());
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'update_product');
            data.append('type', type);
            data.append('stock', stock);
            data.append('title', title);
            data.append('price', price);
            data.append('description', description);
            if (thumbnail.files.length > 0)
                data.append('thumbnail', thumbnail.files[0]);
            data.append('thumbnail_removed', thumbnail_removed);
            if (attachment.files.length > 0)
                data.append('attachment', attachment.files[0]);
            data.append('attachment_removed', attachment_removed);
            data.append('id', id);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        location.reload();
                    }
                }
            });
        }
    });
    $(document).on('click', '.product_item [toggle_status]', function() {
        var product_id = $(this).attr('toggle_status');
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'product_toggle_status');
        data.append('product_id', product_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    location.reload();
                }
            }
        });
    });
    $(document).on('click', '.product_item [product_delete]', function() {
        var product_id = $(this).attr('product_delete');
        if (confirm('Are you sure to delete this product?')) {
            $(".mw_loader").show();
            var data = new FormData();
            data.append('action', 'delete_product');
            data.append('product_id', product_id);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success == '1') {
                        location.reload();
                    }
                }
            });
        }
    });
}

function load_vip_member_profile_products(params) {
    var ajax_running = $('.product_list_box').attr('ajax_running');
    var total_page = $('.product_list_box').attr('total_page');
    var cur_page = $('.product_list_box').attr('cur_page');
    var per_page = $('.product_list_box').attr('per_page');
    cur_page = parseInt(cur_page);
    if (total_page != '')
        total_page = parseInt(total_page);
    if (ajax_running == '0' && total_page > cur_page) {
        cur_page = cur_page + 1;
        $('.product_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_profile_products');
        data.append('vip_member_id', params.vip_member_id);
        data.append('in_stock', '');
        data.append('cur_page', cur_page);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.product_list_box').attr('ajax_running', '0');
                $('.product_list_box').attr('total_page', data.data.total_page);
                $('.product_list_box').attr('cur_page', cur_page);
                $('.product_list_box').append(data.data.html);
            }
        });
    }
}

function vip_member_profile_products(params) {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.product_list_box').offset().top + $('.product_list_box').height();
        /*console.log('-------------------------------');
        console.log($(window).scrollTop());
        console.log($(window).height());
        console.log($('.post_list_box').offset().top);
        console.log($('.post_list_box').height());*/
        if ((plb_scr - win_scr) < 100) {
            //console.log('call ajax');
            load_vip_member_profile_products(params);
        }
    });
}

function set_notification_seen(params) {
    if (params.notification_ids.length > 0) {
        var data = new FormData();
        data.append('action', 'set_notification_seen');
        data.append('notification_ids', params.notification_ids.join(','));
        data.append('_token', prop.csrf_token);
        $.ajax({ type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false });
    }
}

function load_notification_lists(params) {
    var ajax_running = $('.notification_list_box').attr('ajax_running');
    var end_list = $('.notification_list_box').attr('end_list');
    var per_page = $('.notification_list_box').attr('per_page');
    var notification_type = $('.notifcat_nav .active .show_notification').attr('notification_type');
    var notifmark_id = '';
    if ($('.notification_list_box .notification_item').length > 0)
        notifmark_id = $('.notification_list_box .notification_item:last').attr('notification_id');
    end_list = parseInt(end_list);
    if (ajax_running == '0' && end_list == 0) {
        $('.notification_list_box').attr('ajax_running', '1');
        var data = new FormData();
        data.append('action', 'get_notifications');
        data.append('notification_type', notification_type);
        data.append('notifmark_id', notifmark_id);
        data.append('per_page', per_page);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $('.notification_list_box').attr('ajax_running', '0');
                if (data.data.total_data == 0)
                    $('.notification_list_box').attr('end_list', '1');
                $('.notification_list_box').append(data.data.html);
                $("time.timeago").timeago();
                var notif_ids = [];
                $(data.data.data).each(function(i, v) { notif_ids.push(v.id); });
                set_notification_seen({ 'notification_ids': notif_ids });
            }
        });
    }
}

function notification_lists(params) {
    $(window).on('scroll', function() {
        var win_scr = $(window).scrollTop() + $(window).height();
        var plb_scr = $('.notification_list_box').offset().top + $('.notification_list_box').height();
        if ((plb_scr - win_scr) < 100) {
            load_notification_lists(params);
        }
    });
    $(document).on('click', '.show_notification', function() {
        $('.notifcat_nav .active').removeClass('active');
        $(this).closest('li').addClass('active');
        $('.notification_list_box').attr('end_list', '0');
        $('.notification_list_box').attr('ajax_running', '0');
        $('.notification_list_box').html('');
        load_notification_lists({});
    });
}

function product_actions() {
    $(document).on('click', '.show_product_details', function() {
        var product_id = $(this).attr('product_id');
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'get_product');
        data.append('product_id', product_id);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $(".mw_loader").hide();
                if (data.success == '1') {
                    var product = data.data.product;
                    $("#productDetailsModal .modal-body .imgContainer img").attr('src', product.thumbnail_url);
                    $("#productDetailsModal .modal-body .brandDtls").text(product.title);
                    $("#productDetailsModal .modal-body .description p").text(product.description);
                    $("#productDetailsModal .modal-body .proFutures ul").html('');
                    $("#productDetailsModal .modal-body .proFutures ul").append('<li><span>Stock: </span>' + (product.in_stock == '1' ? 'In stock' : 'Out of stock') + '</li>');
                    var attachment_type = '';
                    if (product.attachment != '' && product.type == '1') attachment_type = 'Document';
                    if (product.attachment != '' && product.type == '2') attachment_type = 'Audio';
                    if (product.attachment != '' && product.type == '3') attachment_type = 'Video';
                    if (attachment_type != '')
                        $("#productDetailsModal .modal-body .proFutures ul").append('<li><span>Attachment Type: </span>' + attachment_type + '</li>');
                    $("#productDetailsModal .modal-body .price_info .price_show").text(product.price + ' coin(s) each');
                    $("#productDetailsModal .modal-body .quantity, #productDetailsModal .modal-body .aTCArea").hide();
                    $('#productDetailsModal .modal-body .add_to_cart').attr('product_id', product.id);
                    if (product.in_stock == '1') {
                        $("#productDetailsModal .modal-body .aTCArea").show();
                        $('#productDetailsModal .modal-body .quantity input[name="qty"]').val('1');
                        var stock = 1;
                        if (product.type == '4') {
                            stock = product.stock;
                            $("#productDetailsModal .modal-body .quantity").show();
                        } else {}
                        $('#productDetailsModal .modal-body .quantity input[name="qty"]').attr('max_limit', stock);
                    }
                    $("#productDetailsModal .ajax_response").html('');
                    $("#productDetailsModal").modal({ backdrop: 'static', keyboard: false });
                }
            }
        });
    });
    $(document).on('click', '#productDetailsModal .quantity .qty_plus', function() {
        var qtyEl = $('#productDetailsModal .modal-body .quantity input[name="qty"]');
        var max_limit = parseInt(qtyEl.attr('max_limit'));
        if (parseInt(qtyEl.val()) < max_limit) {
            qtyEl.val((parseInt(qtyEl.val()) + 1));
        }
    });
    $(document).on('click', '#productDetailsModal .quantity .qty_minus', function() {
        var qtyEl = $('#productDetailsModal .modal-body .quantity input[name="qty"]');
        if (parseInt(qtyEl.val()) > 1) {
            qtyEl.val((parseInt(qtyEl.val()) - 1));
        }
    });
    $(document).on('click', '#productDetailsModal .add_to_cart', function() {
        var product_id = $(this).attr('product_id');
        var qty = $('#productDetailsModal .modal-body .quantity input[name="qty"]').val();
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'add_to_cart');
        data.append('product_id', product_id);
        data.append('qty', qty);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $(".mw_loader").hide();
                if (data.success == '1') {
                    var total_cart_items = parseInt(data.data.total_cart_items);
                    if (total_cart_items > 99) total_cart_items = '99+';
                    $('header .cartBtn span').text(total_cart_items).show();
                    $("#productDetailsModal .ajax_response").html('<p class="text-success">' + data.message + '</p>');
                } else {
                    $("#productDetailsModal .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
                }
            }
        });
    });
}

function handleOpentokError(error) {
    if (error) {
        alert(error.message);
    }
}

function reset_opentok_player_area() {
    var allht = 0;
    $('.opentok_player_area, .opentok_placeholder_img').each(function() {
        var wd = $(this).width();
        var ht = wd * 56.25 / 100;
        if (ht > allht)
            allht = ht;
        $(this).height(ht);
    });
    allht -= 8;
    // $('.chatbox').height(allht);
    //var cfht = $('.chatbox .chatfields').height();
    var cfht = 82;
    // $('.chatbox .chatlist').height((allht - cfht));
}

function opentok_live_stream() {
    reset_opentok_player_area();
    onlyNumbers('.chat_tips_popup input[name="chat_tips_amount"]');
    $(document).on('click', '.chatbox .chat_tips', function() {
        $('.chatbox .chat_tips_popup .ajax_response').html('');
        if ($('.chatbox .chat_tips_popup').is(':visible'))
            $('.chatbox .chat_tips_popup').hide();
        else
            $('.chatbox .chat_tips_popup').css('display', 'flex');
    });
    $(document).on('click', '.chatbox .chat_tips_submit', function() {
        var chat_tips_amount = $.trim($('.chat_tips_popup input[name="chat_tips_amount"]').val());
        var vip_member_id = $('.chatbox').attr('vip_member_id');
        if (chat_tips_amount != '') {
            $('.mw_loader').show();
            var data = new FormData();
            data.append('action', 'pay_send_chat_tip');
            data.append('vip_member_id', vip_member_id);
            data.append('token_coin', chat_tips_amount);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('.mw_loader').hide();
                    if (data.success == '1') {
                        $('.chatbox .chat_tips_popup').hide();
                        var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                        session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_chat_tip', 'tips_amount': chat_tips_amount, 'user_id': prop.user_data.id, 'first_name': prop.user_data.first_name, 'last_name': prop.user_data.last_name, 'username': prop.user_data.username, 'display_name': prop.user_data.display_name, 'role': prop.user_data.role, 'msg': '' }) });
                        $('.balanceBtn span').html(data.data.wallet_coin + " Coins");

                    } else {
                        $(".chat_tips_popup .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
                    }
                }
            });
        }
    });
    $(document).on('click', '.tip_send_btn', function() {
        var chat_tips_amount = $.trim($('.video_chat_tips_popup input[name="vide_chat_tips_amount"]').val());
        var vip_member_id = $('.video_chat_tips_popup').attr('vip_member_id');
        if (chat_tips_amount != '') {
            $('.mw_loader').show();
            var data = new FormData();
            data.append('action', 'pay_send_chat_tip');
            data.append('vip_member_id', vip_member_id);
            data.append('token_coin', chat_tips_amount);
            data.append('_token', prop.csrf_token);
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: prop.ajaxurl,
                data: data,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('.mw_loader').hide();
                    if (data.success == '1') {
                        $('.video_chat_tips_popup input[name="vide_chat_tips_amount"]').val('');
                        //$('.chatbox .chat_tips_popup').hide();
                        $('.send-tips-wrap').slideToggle();
                        var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                        session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_chat_tip', 'tips_amount': chat_tips_amount, 'user_id': prop.user_data.id, 'first_name': prop.user_data.first_name, 'last_name': prop.user_data.last_name, 'username': prop.user_data.username, 'display_name': prop.user_data.display_name, 'role': prop.user_data.role, 'msg': '' }) });
                        $('.balanceBtn span').html(data.data.wallet_coin + " Coins");

                    } else {
                        $(".video_chat_tips_popup .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
                    }
                }
            });
        }
    });
}

function process_global_message(data) {
    var d = new Date();
    d = Date.parse(d);
    /*if(data.type == 'user_join') {
    	if(typeof window['live_viewer'] == 'undefined') window['live_viewer'] = {};
    	if(typeof window['live_viewer'][data.user_id] == 'undefined')
    		window['live_viewer'][data.user_id] = '';
    	window['live_viewer'][data.user_id] = d;
    }*/
}

function live_session_chat_send_message(message, params) {
    params = JSON.parse(params);
    var vip_member_id = params.vip_member_id;
    message = $.trim(message);
    if (message != '') {
        var data = new FormData();
        data.append('action', 'set_live_session_chat_message');
        data.append('vip_member_id', vip_member_id);
        data.append('message', message);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.success == '1') {
                    var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                    session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_chat_message', 'user_id': prop.user_data.id, 'first_name': prop.user_data.first_name, 'last_name': prop.user_data.last_name, 'username': prop.user_data.username, 'display_name': prop.user_data.display_name, 'role': prop.user_data.role, 'msg': message }) });
                }
            }
        });
    }
}
var myInterval = null;

function display_chatbox_message(data) {
    //console.log(data);
    var name = 'Anonymous';
    if (data.role == '2') name = data.display_name;
    if (data.role == '3') name = data.first_name + ' ' + data.last_name;
    var action = data.action;
    var cls = '';
    if (typeof prop.user_data.id != 'undefined' && prop.user_data.id == data.user_id)
        cls = 'own';
    /*if(typeof data.user_join != 'undefined' && data.user_join == '1') {
    	$('.chatbox .chatlist').append('<div class="chatitem text-center" user_id="' + data.user_id + '"><i><b>' + name + ': </b> joined</i></div>');
    }*/
    if (action == 'live_session_chat_message')
        $('.chatbox .chatlist').append('<div class="chatitem ' + cls + '" user_id="' + data.user_id + '"><div class="chatitemin relative"><b>' + name + ': </b><br>' + data.msg.replace(/\n\r?/g, '<br />') + ((prop.user_data.role == '2' || prop.user_data.role == 2) && (data.role == '3' || data.role == 3) ? '<span class="block_user" id="block_user_' + data.user_id + '" onclick="block_user(' + data.user_id + ',' + prop.user_data.id + ')"><button type="button"><i class="fas fa-user-slash"></i></button></span>' : '') + '</div></div>');
    if (action == 'live_session_chat_tip') {
        var tips_amount = parseInt(data.tips_amount);
        $('.chatbox .chatlist').append('<div class="chatitem info" user_id="' + data.user_id + '"><div class="chatitemin"><b>' + name + ' </b>has tipped ' + tips_amount + ' ' + (tips_amount > 1 ? 'coins' : 'coin') + '</div></div>');
    }
    if (action == 'live_session_follower_join') {
        //console.log('live session');
        $('.chatbox .chatlist').append('<div class="user_join" ><div class="joining"><b>' + data.follower_name + ' </b>has joined </div></div>');
    }
    if (action == 'live_session_follower_join_for_group') {
        //console.log('live session');
        $('.chatbox .chatlist').append('<div class="user_join" ><div class="joining"><b>' + data.follower_name + ' </b>has joined </div></div>');
        group_chat_balance_update(data.vip_id, data.follower_id, data.sessionId, prop.user_data.id, data.token);
        myInterval = setInterval(function() { group_chat_balance_update(data.vip_id, data.follower_id, data.sessionId, prop.user_data.id, data.token); }, 61 * 1000);
    }
    if (action == 'live_session_chat_block') {
        if (prop.user_data.id == data.user_id) {
            $('.chatfields').html('<div class="block-user">you have been blocked by the host !</div>');
            $('.private-chat').hide();
        }
    }
    if (action == 'live_session_private_chat_request') {
        //console.log('vip', data.vip_id, prop.user_data.id);
        if (prop.user_data.id == data.follower_id) {
            $('.private-chat').hide();
            $('.private-chat-msg').show();
        }
        if (prop.user_data.id == data.vip_id) {
            $('.private-chat-req').css('display', 'block');

            $('.private-req-tbody').append(`
            <tr>
                <td>${data.follower_detail.first_name} ${data.follower_detail.last_name}</td>
                <td class="text-center">${data.follower_detail.gender==1?'Male':(data.follower_detail.gender==2?'Female':(data.follower_detail.gender==3?'Transgender':'Undefined'))}</td>
                <td class="text-center">${data.follower_sub_to_models?'<span class="badge badge-success">Subscriber</span>':'<span class="badge badge-danger">Non Subscriber</span>'}</td>
                <td>
                    <div class="request-list-rgt">
                        <ul class="d-flex justify-content-end">
                            <li><button type="button" data-follower-id="${data.follower_id}" data-model-id="${data.vip_id}" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>

                            <li><button type="button" data-follower-id="${data.follower_id}" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                        </ul>
                    </div>
                </td>
            </tr>
            `);
        }
    }
    if (action == 'live_session_private_chat_accept') {
        if (prop.user_data.id != data.follower_id && prop.user_data.role != 2 && prop.user_data.role != '2') {
            $('#opentok_subscriber').hide();
            $('.opentok_placeholder_img').show();
            $('.follower-section .chatbox').addClass('offline');
            $('.private-chat').css('display', 'none');
            $('.private-chat-msg').hide();
            alert('model is on private chat');
        }
        if (prop.user_data.id == data.follower_id) {
            alert('your private chat request has been accepted. now you are on private chat');
        }
        // if(prop.user_data.id==data.model_id){
        // alert(data.private_chat_id);
        private_chat_balance_update(data.model_id, data.follower_id, data.private_chat_id, prop.user_data.id);
        myInterval = setInterval(function() { private_chat_balance_update(data.model_id, data.follower_id, data.private_chat_id, prop.user_data.id); }, 61 * 1000);
        // }

    }
    if (action == 'live_session_private_chat_reject') {
        if (prop.user_data.id == data.follower_id) {
            alert('your private chat request has been rejected. you can request again');
            $('.private-chat').css('display', 'block');
            $('.private-chat-msg').hide();
        }
    }
    //if(atbottom == 1)
    $('.chatbox .chatlist')[0].scrollTop = $('.chatbox .chatlist')[0].scrollHeight;
}


function private_chat_balance_update(model_id = null, follower_id = null, private_chat_id = null, created_by = null) {

    var data = new FormData();
    data.append('action', 'private_chat_balance_update');
    data.append('_token', prop.csrf_token);
    data.append('follower_id', follower_id);
    data.append('model_id', model_id);
    data.append('created_by', created_by);
    data.append('private_chat_id', private_chat_id);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: prop.ajaxurl,
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            console.log('private balance', res);
            // let low_alert=0;
            if (res.data.low_balance_alert) {
                console.log(prop.user_data.id, follower_id, model_id);
                if (prop.user_data.id == follower_id) {
                    var model_low_alert = $('#model_low_alert').val();
                    console.log('follower_id', follower_id);
                    if (model_low_alert == 'no') {
                        alert('your account balance is low. please recharge to continue this chat');
                    }
                    $('#model_low_alert').val('yes');
                }
                if (prop.user_data.id == model_id) {
                    var model_low_alert = $('#model_low_alert').val();
                    console.log('model_id', model_id, follower_id);
                    if (model_low_alert == 'no') {
                        alert('user account balance is low. the session will be disconnected at any time');
                    }
                    $('#model_low_alert').val('yes');
                }
            }
            if (res.data.insufficient_bal) {
                var data = new FormData();
                data.append('action', 'opentok_end_session');
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (prop.user_data.id == model_id) {

                            opentok_destroyPubSession();
                            $('.opentok_start_session').show();
                            $('.opentok_end_session').hide();
                            $('.chatbox').addClass('offline');
                            $('.chatbox .chatlist').html('');
                            $('.private-chat-req').css('display', 'none');
                            $('.private-req-tbody').html('');
                            //window['live_viewer'] = {};
                            window['live_viewer_count'] = 0;
                            $('.golive_page .view_counter span').text('0');
                        }

                        clearInterval(myInterval);
                        $('#model_low_alert').val('no');
                    }
                });
                // }
            }
        }
    });
}

function group_chat_balance_update(model_id = null, follower_id = null, sessionId = null, created_by = null, token = null) {

    var data = new FormData();
    data.append('action', 'group_chat_balance_update');
    data.append('_token', prop.csrf_token);
    data.append('follower_id', follower_id);
    data.append('model_id', model_id);
    data.append('created_by', created_by);
    data.append('sessionId', sessionId);
    data.append('token', token);
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: prop.ajaxurl,
        data: data,
        processData: false,
        contentType: false,
        success: function(res) {
            //console.log('private balance', res);
            // let low_alert=0;
            console.log(res.data.follower_coin);
            if (res.data.follower_coin != 0) {
                $("#follower_wallet_coins").html(res.data.follower_coin + ' Coins');
            }
            if (res.data.low_balance_alert) {
                //console.log(prop.user_data.id, follower_id, model_id);
                //console.log('low');
                if (prop.user_data.id == follower_id) {
                    var model_low_alert = $('#model_low_alert').val();
                    if (model_low_alert == 'no') {
                        alert('your account balance is low. please recharge to continue this chat');
                    }
                    $('#model_low_alert').val('yes');
                }

                /* if (prop.user_data.id == model_id) {
                    var model_low_alert = $('#model_low_alert').val();
                    //console.log('model_id', model_id, follower_id);
                    if (model_low_alert == 'no') {
                        alert('user account balance is low. the session will be disconnected at any time');
                    }
                    $('#model_low_alert').val('yes');
                } */
            }
            if (res.data.insufficient_bal) {
                //update follower group chat by ajax call
                var data = new FormData();
                let follower_id = prop.user_data.id;
                data.append('action', 'opentok_end_session_for_follower');
                data.append('follower_id', follower_id);
                data.append('model_id', model_id);
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // console.log(data);
                        var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                        session.disconnect();
                        prop.opentok.apiKey = '';
                        prop.opentok.sessionId = '';
                        prop.opentok.token = '';
                        $('#opentok_subscriber').hide();
                        $('.opentok_placeholder_img').show();
                        $('.chatbox').addClass('offline');
                        $('.exit_session_btn').css('display', 'none');
                        $('.send_tip_btn').css('display', 'none');
                        //$('.private-chat').css('display','none');
                        //$('.private-chat-msg').hide();
                        clearInterval(myInterval);
                        $('#model_low_alert').val('no');
                        location.reload();

                    }
                });


                //var data = new FormData();
                //data.append('action', 'opentok_end_session_for_follower');
                //data.append('_token', prop.csrf_token);
                /* var data = new FormData();
                data.append('action', 'opentok_end_session');
                data.append('_token', prop.csrf_token);
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: prop.ajaxurl,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (prop.user_data.id == model_id) {

                            opentok_destroyPubSession();
                            $('.opentok_start_session').show();
                            $('.opentok_end_session').hide();
                            $('.chatbox').addClass('offline');
                            $('.chatbox .chatlist').html('');
                            $('.private-chat-req').css('display', 'none');
                            $('.private-req-tbody').html('');
                            //window['live_viewer'] = {};
                            window['live_viewer_count'] = 0;
                            $('.golive_page .view_counter span').text('0');
                        }

                        clearInterval(myInterval);
                        $('#model_low_alert').val('no');
                    }
                }); */

            }
        }
    });
}

$(document).ready(function() {

    datepicker_single();
    noPrevFileUpload();
    prevFileUpload();
    textareaAutoHeight();
    $("time.timeago").timeago();
    emoji_pad();
    //pubnub_init();

    loginModal();
    registerModal();
    change_password();

    profile_submit();
    settings_submit();
    bank_details_submit();
    about_submit();
    product_vip_member();

    vip_member_search();
    set_user_fav();
    set_user_follow();
    user_subscription();
    create_post();
    post_actions();
    product_actions();

    opentok_live_stream();


});

// $(document).on('click','.block_user',()=>{
//     console.log('user',$(this).attr('data-follower'));
//     // let confirm = confirm("Are you sure you want to block this user?");
//     // if (confirm == true) {
//     // //delete

//     // } else {
//     // //don't delete
//     // }
// });

function block_user(user_id, model_id) {
    // alert();
    // console.log('blocked user',data);
    var conf = confirm("Are you sure you want to block this user?");
    if (conf == true) {
        $('#block_user_' + user_id).html('blocked');
        var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
        session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_chat_block', 'user_id': user_id, 'model_id': model_id }) });


    }
}

// redirect to buy coin
$(document).on('click', ".balanceBtn", () => {
    if (prop.user_data.role == '3' || prop.user_data.role == 3)
        var a = confirm("Would you like to purchase coins?");
    if (a == true) {
        window.location.href = prop.url + '/dashboard/buy_coins';


    }
});
$(document).on('click', '.reject-private-chat-req', function() {
    var a = confirm("Are you want to cancel this request?");
    if (a == true) {
        let follower_id = $(this).attr('data-follower-id');
        // console.log(follower_id_new);
        $(this).closest('tr').remove();
        var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
        session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_private_chat_reject', 'follower_id': follower_id }) });
    }
});

$(document).on('click', '.accept-private-chat-req', function() {
    var a = confirm("Are you want to accept this request?");
    if (a == true) {
        let follower_id = $(this).attr('data-follower-id');
        let model_id = $(this).attr('data-model-id');

        $('.private-chat-req').css('display', 'none');
        $('.private-req-tbody').html('');

        // console.log(follower_id_new);
        // $(this).closest('tr').remove();
        var data = new FormData();
        data.append('action', 'accept_private_chat_req');
        data.append('_token', prop.csrf_token);
        data.append('follower_id', follower_id);
        data.append('model_id', model_id);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                console.log('private_id ', data.data.private_chat_id);
                var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
                session.signal({ type: 'msg', data: JSON.stringify({ 'action': 'live_session_private_chat_accept', 'follower_id': follower_id, 'model_id': model_id, 'private_chat_id': data.data.private_chat_id }) });
            }
        });
        // var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
        // session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_private_chat_reject', 'follower_id': follower_id})});
    }
});


$(document).on('click', '#send_message', function() {
    var subscriber_id = $('#subscriber_id').val();
    var message = $('#message').val();

    var error = 0;
    $(".error").remove();
    if (message == '') {
        $('#message').closest('.form-group').append("<div class='error'>Enter message</div>");
        error = 1;

    }
    if (error == 0) {
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'send_msg_to_subscriber');
        data.append('subscriber_id', subscriber_id);
        data.append('message', message);
        data.append('_token', prop.csrf_token);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: prop.ajaxurl,
            data: data,
            processData: false,
            contentType: false,
            success: function(data) {
                $(".mw_loader").hide();
                console.log(data);
                if (data.success == '1') {
                    alert(data.message);
                    $('#message').val('');
                    $('.close').click();
                }
                /* if(data.success == '1') {
				  var total_cart_items = parseInt(data.data.total_cart_items);
				  if(total_cart_items > 99) total_cart_items = '99+';
				  $('header .cartBtn span').text(total_cart_items).show();
				  $("#productDetailsModal .ajax_response").html('<p class="text-success">' + data.message + '</p>');
			  } else {
				  $("#productDetailsModal .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
			  } */
            }
        });
    }
});



$(document).ready(function() {
    let footer_height = $('footer').height() + 40;
    $('body').css('margin-bottom', footer_height);

    $('.wrap-rgt').css('min-height', $(window).height() - ($('header').height() + $('footer').height()));

});
// $(document).on('click','.profile_leftcont_edit',function(){
// 	setTimeout(() => {
// 	$('#bannerLeftcontEditModal').height($('#bannerLeftcontEditModal .modal-dialog').height()+500);

// 	}, 200);
// });