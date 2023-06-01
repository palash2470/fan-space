@extends('layouts.front')
@section('content')
<section class="changePasswordSec admin_login_form">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="loginBody changePassword">
                    <div class="loginAreaInner">
                        <h2>Admin Login</h2>
                        <form method="post" action="{{ url('/login') }}">
                            @csrf
                            <div class="emailPassWrap-2 w-100">
                                <div class="form-group relative">
                                    <span class="selOpIcon"><i class="fas fa-envelope"></i></span>
                                    <input type="text" name="email" value="" placeholder="Enter Email Address" class="input-2">
                                </div>
                                <div class="form-group relative">
                                    <span class="selOpIcon"><i class="fas fa-key"></i></span>
                                    <input type="password" name="password" id="" placeholder="Password" class="input-2">
                                </div>
                                {{-- <div class="form-group relative reChapcha">
                                 
                                  <div class="g-recaptcha" data-sitekey="<?php //echo $meta_data['global_settings']['settings_google_recaptcha_site_key']; ?>"></div>
                                </div> --}}
                                <div class="form-group text-center">
                                  <input type="hidden" name="role" value="1" />
                                  <button class="logInBtn admin_login_submit" type="button">Log In</button>
                                  {{-- <button class="check" type="button">Check</button> --}}
                                </div>
                                <div class="response">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>
@stop
@push('script')
<script type="text/javascript">
$('.check').click(function(){
  alert($.trim($('textarea[name="g-recaptcha-response"]').val()));
});
    $(document).ready(function(){
      
      $('.admin_login_form form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });
      $(document).on('click', '.admin_login_submit', function(){
        var email = $.trim($('.admin_login_form input[name="email"]').val());
        var password = $.trim($('.admin_login_form input[name="password"]').val());
	      //var g_recaptcha_response = $.trim($('textarea[name="g-recaptcha-response"]').val());

        var error = 0;
        var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
        $(".error").remove();
        $(".response").html('');
        if(email_pattern.test(email) == false) {
          $('.admin_login_form input[name="email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
          error = 1;
        }
        if(password == "") {
          $('.admin_login_form input[name="password"]').closest('.form-group').append("<div class='error'>Enter password</div>");
          error = 1;
        }
       /*  if(g_recaptcha_response == '') {
        $('textarea[name="g-recaptcha-response"]').closest('.reChapcha').append("<div class='error'>Validate captcha</div>");
        error = 1;
        } */
        if(error == 0) {
          $(".mw_loader").show();
          var data = new FormData();
          data.append('action', 'login_check');
          data.append('role', 1);
          data.append('email', email);
          data.append('password', password);
          data.append('_token', prop.csrf_token);
          $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
              if(data.success == 0) {
                  $(".mw_loader").hide();
                  $('.admin_login_form .response').html('<div class="error">' + data.message + '</div>');
              }
              if(data.success == 1) {
                  $('.admin_login_form form').submit();
              }
            }
          });
        }
      });
    });
</script>
@endpush