<?php
use App\User;
use App\Notification;
use App\User_earning;
use App\User_payout;

$total_notifications = 0;
if(isset($user_data['id']))
  $total_notifications = Notification::where('user_id', $user_data['id'])->where('seen', '0')->count();
$total_cart_items = 0;
$cart = $_COOKIE['cart'] ?? '{}';
$cart = json_decode($cart, true);
foreach ($cart as $key => $value) {
  $total_cart_items += $value['qty'];
}
$is_vip_member_profile='N';
$url_segment=Request::segment(1);
if(isset($url_segment)){
	if($url_segment=='u'){
		$is_vip_member_profile='Y';
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>{{ $page_title != '' ? $page_title . ' | ' : '' }} Fan-Space</title>
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/themify/themify-icons.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/fonts/stylesheet.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/owl.carousel.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/build.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/video-js.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ url('public/front/library/emojionearea-3.3.1/dist/emojionearea.min.css') }}" />
<link href="{{ url('public/front/css/select2.min.css') }}" rel="stylesheet" />
<link href="{{ url('public/front/css/mCustomScrollbar.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('/public/front/css/jquery-ui.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/developer.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/media.css') }}">
<script src="{{ URL::asset('/public/front/js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
@stack('head')
<script type="text/javascript">
    var prop = <?php echo json_encode(array('ajaxurl' => url('/ajaxpost'), 'ajaxgeturl' => url('/ajaxget'), 'url'=>url('/'), 'csrf_token'=>csrf_token(), 'user_data' => $user_data, 'opentok' => ['apiKey' => '', 'sessionId' => '', 'token' => '', 'session' => null]));?>;

</script>
</head>

<body>
<header class="">
  <div class="container-fluid p-h-40">
    <div class="row justify-content-between align-items-center">
      <div class="logo"> <a href="{{ url('/') }}"><img src="{{ url('public/uploads/settings/settings_website_logo/' . $meta_data['global_settings']['settings_website_logo']) }}" alt=""></a> </div>
      <div class="search d-flex align-items-center">
        <div class="searchInner relative">
          <input type="text" name="s" id="" class="modelSearch" value="{{ $meta_data['search_params']['s'] ?? '' }}" />
          <button type="button" class="modelSearchBtn search_submit"><i class="fas fa-search"></i></button>
        </div>
        <?php if(isset($user_data['role']) && $user_data['role'] == 2) {
                    } else { ?>
        <a href="javascript:;" data-toggle="modal" data-target=".search_modal">advanced search</a>
        <?php } ?>
      </div>
      <div class="logReg d-flex justify-content-center">
        <ul class="d-flex">
          <?php if(isset($user_data['id'])) { ?>
          <?php if(in_array($user_data['role'], [2, 3])) {
                                $wallet_coins = 0;
                                if(in_array($user_data['role'], [2])) {
                                    /*$wallet_coins += User_earning::where('user_id', $user_data['id'])->sum('token_coins');
                                    $wallet_coins += User_earning::where('referral_user_id', $user_data['id'])->sum('referral_token_coins');
                                    $wallet_coins -= User_payout::where('user_id', $user_data['id'])->sum('token_coins');*/
                                    $wallet = User::wallet(['user_id' => $user_data['id']]);
                                    $wallet_coins = $wallet['balance'];
                                }
                                if(in_array($user_data['role'], [3])) {
                                    $wallet_coins = $user_data['meta_data']['wallet_coins'] ?? 0;
                                }
                                ?>
          <li><a href="javascript:;" class="balanceBtn"><i class="fas fa-coins"></i><span>{{ $wallet_coins . ' ' . ($wallet_coins == 1 ? 'Coin' : 'Coins') }}</span></a></li>
          <li><a href="{{ url('dashboard/notification') }}" class="greyBtn notifBtn"><i class="ti-bell"></i>
            <?php if($total_notifications > 0) {
                                    echo '<span>' . ($total_notifications > 99 ? '99+' : $total_notifications) . '</span>';
                                  } else {
                                    echo '<span style="display: none;">0</span>';
                                  } ?>
            </a></li>
          <?php if(in_array($user_data['role'], [3])) { ?>
          <li><a href="{{ url('cart') }}" class="greyBtn cartBtn"><i class="m-r-0 fas fa-shopping-cart"></i>
            <?php if($total_cart_items > 0) {
                                        echo '<span>' . ($total_cart_items > 99 ? '99+' : $total_cart_items) . '</span>';
                                      } else {
                                        echo '<span style="display: none;">0</span>';
                                      } ?>
            </a></li>
          <?php } ?>
          <li><a href="{{ $user_data['dashboard_url'] }}" class="blueBtn"><i class="fas fa-user"></i><span>Account</span></a></li>
          <li><a href="{{ $user_data['logout_url'] }}" class="greyBtn" onclick="return confirm('Are you sure to logout?');"><i class="m-r-0 fas fa-power-off"></i><!-- <span>Logout</span> --></a></li>
          <?php } ?>
          <?php if(in_array($user_data['role'], [1])) { ?>
          <li><a href="{{ $user_data['dashboard_url'] }}" class="blueBtn"><i class="fas fa-user"></i><span>Dashboard</span></a></li>
          <li><a href="{{ $user_data['logout_url'] }}" class="greyBtn" onclick="return confirm('Are you sure to logout?');"><i class="m-r-0 fas fa-power-off"></i><span>Log Out</span></a></li>
          <?php } ?>
          <?php } else { ?>
          <li><a href="javascript:;" class="loginBtn"><i class="fas fa-user"></i><span>LOGIN</span></a></li>
          <li><a href="javascript:;" class="register"><i class="fas fa-pencil-alt"></i><span>Register</span></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</header>
@yield('content')
<footer class="mainFooter">
  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-auto col-md-auto col-sm-12 col-12">
        <div class="address">
          <p>© 2021 Powered by Fan-Space All Rights Reserved</p>
          <a href="#">Email: Info@gmail.com</a> </div>
      </div>
      <div class="col-lg-auto col-md-auto col-sm-12 col-12">
        <div class="fNav">
          <ul class="d-flex justify-content-center">
            <li><a href="#">About Us</a></li>
            <li><a href="#">FAQs</a></li>
            <li><a href="{{ url('/privacy_policy') }}">Privacy Policy</a></li>
            <li><a href="{{ url('/terms_conditions') }}">Terms & Conditions</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Blog</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- Varification Modal -->

<div class="modal fade vfModalMd" id="emailOtpVerifyModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Enter your verification code</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="mbInner">
          <h5>A verification code was sent via email to <strong id="toEmail"></strong>.When you receive the code,Enter it below</h5>
          <div class="otpAtra">
            <div class="otpTop">
              <h4>OTP</h4>
            </div>
            <div class="otpBtm d-flex">
              <input type="text" placeholder="OTP PASSWORD" name="email_otp_password" id="email_otp_password" onKeyPress="return isNumber(event)">
              <button type="button" onClick="matchEmailOtpPassword()">OK</button>
            </div>
            <span id="error_otp_msg" class="tryAgain"></span> </div>
          <div class="rcvSms text-center"> Did't receive the sms <a href="javascript:;" class="sendEmailOtp">Resend</a>
            <p>Note: you may not be on our approved email list. This may require you to look for your verification code in your SPAM or JUNK folder. </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade vfModalMd" id="phoneOtpVerifyModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Enter your verification code</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="mbInner">
          <h5>A verification code was sent via sms.When you receive the code,Enter it below</h5>
          <div class="otpAtra">
            <div class="otpTop">
              <h4>OTP</h4>
            </div>
            <div class="otpBtm d-flex">
              <input type="text" placeholder="OTP PASSWORD" name="phone_otp_password" id="phone_otp_password" onKeyPress="return isNumber(event)">
              <button type="button" onClick="matchPhoneOtpPassword()">OK</button>
            </div>
            <span id="error_phone_otp_msg" class="tryAgain"></span> </div>
          <div class="rcvSms text-center"> Did't receive the sms <a href="javascript:;" class="sendPhoneOtp">Resend</a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('front._partials.modal_header_search')
<?php if(!isset($user_data['id'])) { ?>
@include('front._partials.modal_login')
        @include('front._partials.modal_register')
        @include('front._partials.modal_vip_register')
<?php } else { ?>
@include('front._partials.modal_send_tip')
<?php } ?>
@stack('partial_html')
@if($is_vip_member_profile=='Y')
<div class="mw_loader" style="display:block !important"><i class="fa fa-spinner fa-spin"></i></div>
@else
<div class="mw_loader"><i class="fa fa-spinner fa-spin"></i></div>
@endif

<style type="text/css">
      .mw_loader {
        position: fixed;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: rgba(255,255,255,0.6);
        background: rgba(0, 0, 0, 0.9);
        text-align: center;
        z-index: 999999;
        display: none;
      }
      .mw_loader i {
        font-size: 80px;
        color: #b7b7b7;
        margin-top: 200px;
      }
    </style>

<!-- <textarea id="demo1">
  Lorem ipsum dolor 😍 sit amet, consectetur 👻 adipiscing elit, 🖐 sed do eiusmod tempor ☔ incididunt ut labore et dolore magna aliqua 🐬.
  </textarea>
  <div id="emoji_container"></div>
  <span class="aa">dfdfdfdfdff</span> -->


<script src="{{ URL::asset('/public/front/js/jquery-ui.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/popper.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/wow.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/wow.custom.js') }}" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script> -->
<script src="{{ URL::asset('/public/front/js/croppie.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/paralax.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/owl.carousel.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/video.min.js') }}"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
<script src="{{ url('public/front/library/emojionearea-3.3.1/dist/emojionearea.min.js') }}" type="text/javascript"></script>
<script src="{{ url('public/front/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/custom/common.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('/public/front/js/custom/site.js?t=' . time()) }}" type="text/javascript"></script>
<script src="{{ url('public/front/js/mCustomScrollbar.js') }}"></script>

<script type="text/javascript">
       /* document.oncontextmenu = function() {
            return false;
        };*/
    </script>

<script type="text/javascript" id="cookieinfo2" src="{{ URL::asset('/public/front/js/cookieinfo.min.js') }}"></script>


<script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                $('header').addClass("sticky");
            } else {
                $('header').removeClass("sticky");
            }
        });
    </script>
<script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<!-- <script>
        $(document).ready(function() {
            var $uploadCrop,
                tempFilename,
                rawImg,
                imageId;

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.upload-demo').addClass('ready');
                        $('#cropImagePop').modal('show');
                        rawImg = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    console.log("Sorry - you're browser doesn't support the FileReader API");
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 160,
                    height: 160,
                    type: 'circle'
                },
                enforceBoundary: false,
                enableExif: true
            });
            $('#cropImagePop').on('shown.bs.modal', function() {
                $('.cr-slider-wrap').prepend('<p>Image Zoom</p>');
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            });

            $('#cropImagePop').on('hidden.bs.modal', function() {
                $('.item-img').val('');
                $('.cr-slider-wrap p').remove();
            });

            $('.item-img').on('change', function() {
                readFile(this);
            });

            $('.replacePhoto').on('click', function() {
                $('#cropImagePop').modal('hide');
                $('.item-img').trigger('click');
            })

            $('#cropImageBtn').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                    type: 'base64',
                    format: 'jpeg',
                    size: {
                        width: 160,
                        height: 160
                    }
                }).then(function(resp) {
                    $('#item-img-output').attr('src', resp);
                    $('#cropImagePop').modal('hide');
                    $('.item-img').val('');
                });
            });
        })
    </script> -->

@stack('script')
<script type="text/javascript">
        function initGoogleAutocomplete() {
            $('[google_location_search_callback]').each(function(){
                var callback = $(this).attr('google_location_search_callback');
                window[callback]();
            });
        }
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $meta_data['global_settings']['settings_google_map_api_key']; ?>&callback=initGoogleAutocomplete&libraries=places" async></script>
<script type="text/javascript">
        var cur_url = new URL(window.location.href);
        var scrollto = cur_url.searchParams.get("scrollto");
        if(scrollto != null) {
            scrollto_elem = scrollto.substring(0, 1);
            scrollto = scrollto.substring(1);
            if(scrollto_elem == 'C') {
                document.getElementsByClassName(scrollto)[0].scrollIntoView();
            }
            if(scrollto_elem == 'I') {
                document.getElementById(scrollto).scrollIntoView();
            }
        }
    </script>
<?php if(isset($user_data['id'])) { ?>
<script type="text/javascript">
            setInterval(function(){ user_last_activity(); }, (30 * 1000));
        </script>
<?php } ?>
</body>
</html>
