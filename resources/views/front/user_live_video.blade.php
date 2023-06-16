<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $page_title != '' ? $page_title . ' | ' : '' }} Fan-Space</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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

    <link rel="stylesheet" href="{{ URL::asset('/public/front/css/jquery-ui.css') }}">
  {{-- Toastr --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/developer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/media.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/front/css/jquery.mCustomScrollbar.css') }}"
        rel="stylesheet" />
    <script src="{{ URL::asset('/public/front/js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script type="text/javascript">
        var prop = @php
         echo json_encode(['ajaxurl' => url('/ajaxpost'), 'ajaxgeturl' => url('/ajaxget'), 'url' => url('/'), 'csrf_token' => csrf_token(), 'user_data' => $user_data, 'opentok' => ['apiKey' => '', 'sessionId' => '', 'token' => '', 'session' => null]]); @endphp;
    </script>
</head>
    <body>
        <style type="text/css">
        .mw_loader {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.6);
            background: rgba(0, 0, 0, 1);
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
    <?php
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

    ?>
    <?php
    $profile_photo = URL::asset('/public/front/images/user-placeholder.jpg');
    if(isset($usermeta['profile_photo']) && $usermeta['profile_photo'] != '')
      $profile_photo = url('public/uploads/profile_photo/' . $usermeta['profile_photo']);
    ?>
    <section class="live-sec follower-section live-sec-new">
      <input type="hidden" name="model_low_alert" id="model_low_alert" value="no">
    <div class="container-fluid">
      <div class="row header_height justify-content-between align-items-center">
        <div class="col-auto">
          <div class="website-logo">
            <a href="#">
              <img src="http://localhost/fan-space/public/uploads/settings/settings_website_logo/f32ed34d-5e31-494f-b20a-65de6c6c981f.png" alt="">
            </a>
          </div>
        </div>
        <div class="col-auto">
          <div class="chat-top-control">
            <ul class="d-flex justify-content-end">
              <li class="chat-tip">
                <button type="button" class="chat-control-btn send_tip_btn" style="display: none;">tip</button>
                <div class="send-tips-wrap video_chat_tips_popup" style="display: none;" vip_member_id="{{ $user->id }}">
                  <div class="input-wrap">
                    <input type="number" class="form-control tip-input-style" name="vide_chat_tips_amount" value="" placeholder="Enter coin amount" />
                    <button type="button" class="tip-send-btn tip_send_btn"><i class="far fa-paper-plane"></i></button>
                  </div>
                  <div class="ajax_response"></div>
                </div>
                
              </li>
              <li class="chat-group"><button type="button" class="chat-control-btn join_group_chat_btn">group {{ @$usermeta['group_chat_charge'] }} coin P/M</button></li>
              <li class="chat-private"><button type="button" class="chat-control-btn private-chat">private {{ $usermeta['private_chat_charge'] }} coin P/M</button></li>
              <li class="chat-exit"><button type="button" class="chat-control-btn exit_session_btn" style="display: none;">exit session</button></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="row h-100vh">
        <div class="new-video-chat-lft">
          <div class="video-chat-lft-video video_chat_lft">
            <div class="video-wrap-lft video_wrap_lft">
              <div class="video_area relative video_area_jq">
                <div class="full-screen-mode full_screen_mode" style="display: none;">
                  <button type="button" class="video-screen-mode" onclick="$('#opentok_subscriber').fullScreen(true)"><i class="fas fa-expand"></i></button>
                </div>
                {{-- <div class="full-video-wrap" id="full-screen-video-wrap"> --}}
                  
                  <div id="opentok_subscriber" class="opentok_player_area" style="display: none;">
                    <div class="small-screen-mode small_screen_mode" style="display: none;">
                      <button type="button" class="video-screen-mode" onclick="$(document).fullScreen(false)"><i class="fas fa-compress"></i></button>
                    </div>
                  </div>
                  
                {{-- </div> --}}
                <a href="javascript:void(0);" class="commonBtn2 opentok_start_session" user_id="{{ $user->id }}" style="display: none;">Start session</a>
                <div class="opentok_placeholder_img opentok_placeholder_jq" style="background: url({{ $profile_photo }}) center center no-repeat; background-size: contain;">
                  <div class="offCont">
                    @if (isset($live_session->id))
                      <h3>Please click Group or Private button </h3>
                    @else
                      <h3>I am currently offline</h3>
                    @endif
                    
                  </div>
                </div>
              </div>
              <!--<div class="private-chat" style="display: none;">
                  <ul class="d-flex justify-content-center">
                      <li><a href="javascript:;" class="prv-chat-btn" data-toggle="tooltip" data-placement="top" title="{{ $usermeta['private_chat_charge'] }} coin per minute">private chat</a></li>
                  </ul>
              </div>-->
              <div class="private-chat-msg" style="display: none;">
                  <ul class="d-flex justify-content-center">
                      <li>You have requested for private chat. wait for model response</li>
                  </ul>
              </div>
            </div>
            <?php if(isset($user_data['id'])) {
              if(in_array($user_data['role'], [2, 3])) {
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
              }
            }
            ?>
            <div class="video-chat-lft-control control_left">
              <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                  <div class="watch-cost">
                    <span class="coin-img"> <img src="{{asset('public/front/images/coin.png')}}"  alt=""></span>
                    <span id="follower_wallet_coins">{{ $wallet_coins . ' ' . ($wallet_coins == 1 ? 'Coin' : 'Coins') }}</span>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="chat-top-control">
                    <ul class="d-flex justify-content-end">
                      <li><button type="button" class="chat-control-btn balanceBtn"><i class="fas fa-coins"></i>buy credits</button></li>
                      <li><a href="{{ url('u/' . $user->username) }}" target="_blank" type="button" class="chat-control-btn"><i class="fas fa-user-alt"></i>view profile</a></li>
                      <li><button type="button" class="chat-control-btn"><i class="fas fa-percent"></i>super offer</button></li>
                      <li><button type="button" class="chat-control-btn"><i class="fas fa-cog"></i></button></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="new-video-chat-rgt">
          <!-- <h3>Messages</h3> -->
          <div class="chatbox chatbox_wrap offline blk-user-wrap" vip_member_id="{{ $user->id }}" ts="{{ time() }}">
            <div class="chat-box-wrap chat_box_wrap">
              <div class="chatoffline" style="display: none">Chatting unavailable. Model is not live</div>
              <div class="chatlist"></div>
              <div class="chatfields">

                <!--<a href="javascript:;" class="chat_tips"><i class="fas fa-coins"></i></a>-->
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
    </div>
  </section>
  <div class="video-chat-ask-popup" style="display: ;">
    <div class="ask-popup-wrap">
      <h2>Please click Group or Private button</h2>
      <ul class="d-flex">
        <li><button type="button" class="mode-chat-btn"><i class="fas fa-users"></i>group {{ @$usermeta['group_chat_charge'] }} coin P/M</button></li>
        <li><button type="button" class="mode-chat-btn"><i class="fas fa-chalkboard-teacher"></i> private {{ $usermeta['private_chat_charge'] }} coin P/M</button></li>
      </ul>
    </div>
  </div>

    <script src="{{ URL::asset('/public/front/js/jquery-ui.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/wow.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/wow.custom.js') }}" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
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
    <!--<script src="{{ url('public/front/js/mCustomScrollbar.js') }}"></script>-->
    <script src="{{ url('public/front/js/jquery.mCustomScrollbar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    {{-- Full Screen mode js --}}
    <script src="{{ url('public/front/js/jquery.fullscreen-min.js') }}"></script>
    {{-- Sweert Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Toastr --}}
    <script src="{{ URL::asset('/public/front/js/toastr.min.js') }}"></script>
    <script>
      /* window.addEventListener('beforeunload', (event) => {
        event.returnValue = 'Are you sure you want to leave?';
      }); */
     /*  window.onbeforeunload = function(event)
      {
        if(prop.opentok.sessionId !=''){
          return confirm("Confirm refresh");
        }
      }; */
      /* window.addEventListener('beforeunload', (event) => {
        event.preventDefault();
        if(prop.opentok.sessionId !=''){
          event.returnValue = '';
        }
      }); */
      $(document).ready(function(){
        console.log('session-id - '+prop.opentok.sessionId);
        if(document.getElementById("opentok_subscriber").style.display != "none") {
          console.log('display');
        }


        var bodyHeight = $(document).height();
        // $('body').css({"min-height": bodyHeight });
        var headerHeight = $('.header_height').outerHeight(true);
        var controlHeight = $('.control_left').outerHeight(true);
        $('.video_chat_lft').css({"padding-bottom": controlHeight});
        var wrapHeight = bodyHeight - (headerHeight + controlHeight);
        // $('.video_wrap_lft').css({"height": wrapHeight});
        // $('.opentok_placeholder_jq').css({"height": wrapHeight});
        // $('.video_area_jq').css({"height": wrapHeight});
        // Right        
        $('.chatbox_wrap').css({"padding-bottom": controlHeight});
        // $('.chat_box_wrap').css({"height": wrapHeight - 2});


        // Tip toggle
        $(document).on('click', '.send_tip_btn', function(){
          $('.send-tips-wrap').slideToggle();
        });

       /*  $(document).on('click', '.tip_send_btn', function(){
          $('.send-tips-wrap').slideToggle();
        }); */
      });

      $(function() {
          $(".fullscreen-supported").toggle($(document).fullScreen() != null);
          $(".fullscreen-not-supported").toggle($(document).fullScreen() == null);

          $(document).on("fullscreenchange", function(e) {
            console.log($(document).fullScreen());
            if($(document).fullScreen() == false){
              $('.small_screen_mode').css('display','none');
            }else{
              $('.small_screen_mode').css('display','block');
            }
            $("#status").text($(document).fullScreen() ?
                "Full screen enabled" : "Full screen disabled");
          });

          $(document).on("fullscreenerror", function(e) {
            console.log("Full screen error.");
            $("#status").text("Browser won't enter full screen mode for some reason.");
          });
      });
    </script>


  <script type="text/javascript">
    function session_is_offline() {
        $('#opentok_subscriber').hide();
        $('.opentok_placeholder_img').show();
        $('.chatbox').addClass('offline');
        $('.exit_session_btn').css('display','none');
        $('.send_tip_btn').css('display','none');
        $('.full_screen_mode').css('display','none');
        //$('.private-chat').css('display','none');
        //$('.private-chat-msg').hide();
        clearInterval(myInterval);
        $('#model_low_alert').val('no');
        location.reload();
    }


    function opentok_initializeSubSession(data) {
      //console.log(data);
        var apiKey = data.apiKey;
        var sessionId = data.sessionId;
        var token = data.token;
        var session = OT.initSession(apiKey, sessionId);
        session.on('streamCreated', function(event) {
        $('#opentok_subscriber').html('');
        //console.log('event',event);
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
            session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name,'follower_id': prop.user_data.id, 'vip_id': {{ $user->id }} ,'sessionId':sessionId
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
    function opentok_initializeSubSessionForGroup(data,follower_spent_so_far=0) {
        console.log('initilize -'+follower_spent_so_far);
        var apiKey = data.apiKey;
        var sessionId = data.sessionId;
        var token = data.token;
        var session = OT.initSession(apiKey, sessionId);
        session.on('streamCreated', function(event) {
        //$('#opentok_subscriber').html('');
        $('.full_screen_mode').css('display','block');
        //console.log('event',event);
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
            session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join_for_group', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name,'follower_id': prop.user_data.id, 'vip_id': {{ $user->id }} ,'sessionId':sessionId ,'token':token,'profile_photo': prop.user_data.meta_data.profile_photo,'follower_spent_so_far':follower_spent_so_far
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
              $('.exit_session_btn').css('display','block');
              $('.send_tip_btn').css('display','block');
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
      opentok_end_session_for_follower_page_refresh();
        //check_user_session({'user_id': {{ $user->id }}});

        /*  var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
            session.signal({type: 'msg', data: JSON.stringify({'action': 'live_session_follower_join', 'follower_name': prop.user_data.first_name+" "+prop.user_data.first_name, 'vip_id': {{ $user->id }}
            })}); */

    });
    /* $('.full_screen_mode').click(()=>{
      $('.small_screen_mode').css('display','block')
    }); */
    $('.join_group_chat_btn').click(()=>{
      //check_user_session({'user_id': {{ $user->id }}});
      var live_session = "{{@$live_session->id}}";
      console.log(live_session);
      if(live_session != ''){
        Swal.fire({
          title: 'Are you sure you want group chat ?',
          text: "it charges {{ $usermeta['group_chat_charge'] }} coin per minute",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#0cb3f0',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.isConfirmed) {
              // if (conf == true) {
                let follower_id = prop.user_data.id;
                let vip_id = "{{ $user->id }}";
                var data = new FormData();
                  data.append('action', 'check_follower_balance_for_group_chat');
                  data.append('follower_id', follower_id);
                  data.append('vip_id', vip_id);
                  data.append('_token', prop.csrf_token);
                  $.ajax(
                      {type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false,
                      success: function(data){
                           console.log(data.data.model_online_status);
                           //user offline
                          if(data.data.model_online_status == false){
                            Swal.fire({
                              icon: 'error',
                              title: 'Model currently offline',
                              //text: 'Something went wrong!',
                            });
                            location.reload();
                          }else{
                            if(!data.data.insufficient_balance){
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
                                $('.exit_session_btn').css('display','block');
                                $('.send_tip_btn').css('display','block');
                                reset_opentok_player_area();
                                //opentok_initializeSubSession(ot);
                                opentok_initializeSubSessionForGroup(ot,data.data.follower_spent_so_far);
                                    // console.log(OT);
                              } else {
                                session_is_offline();
                              }
                            }else{
                                //alert('Insufficient balance for this group chat !');
                                Swal.fire('Insufficient balance for this group chat !');
                                Swal.fire({
                                  title: '<strong>Insufficient balance for this group chat !</strong>',
                                  icon: 'error',
                                  html:'<a href="' + prop.url + '/dashboard/buy_coins" target="_blank">Click to recharge Now.</a> ',
                                  showCloseButton: true,

                                });
                            }
                          }
                          
                      }
                  });
              // }
            }
        })
        // let conf = confirm("Are you sure you want group chat ? it charges {{ $usermeta['group_chat_charge'] }} coin per minute");
        
      }else{
        Swal.fire({
        icon: 'error',
        title: 'Model currently offline',
        //text: 'Something went wrong!',
      })
        //alert('Model currently offline');
      }
    })

    $('.private-chat').click(()=>{
        var live_session = "{{@$live_session->id}}";
        if(live_session != ''){
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
        }else{
          alert('Model currently offline');
        }
    });
    $('.exit_session_btn').click(()=>{
      
      //update follower group chat by ajax call
      var data = new FormData();
      let follower_id = prop.user_data.id;
      let vip_id = "{{ $user->id }}";
      data.append('action', 'opentok_end_session_for_follower');
      data.append('follower_id', follower_id);
      data.append('model_id', vip_id);
      data.append('_token', prop.csrf_token);
      $.ajax(
          {type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false,
          success: function(data){
            // console.log(data);
            var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
            session.disconnect();
            prop.opentok.apiKey = '';
            prop.opentok.sessionId = '';
            prop.opentok.token = '';
            $('#opentok_subscriber').hide();
            $('.opentok_placeholder_img').show();
            $('.chatbox').addClass('offline');
            $('.exit_session_btn').css('display','none');
            $('.send_tip_btn').css('display','none');
            //$('.private-chat').css('display','none');
            //$('.private-chat-msg').hide();
            
            clearInterval(myInterval);
            $('#model_low_alert').val('no'); 
            location.reload();
           
          }
      });
      
    })
    
    function opentok_end_session_for_follower_page_refresh(){
      //update follower group chat by ajax call
      var data = new FormData();
      let follower_id = prop.user_data.id;
      let vip_id = "{{ $user->id }}";
      data.append('action', 'opentok_end_session_for_follower');
      data.append('follower_id', follower_id);
      data.append('model_id', vip_id);
      data.append('_token', prop.csrf_token);
      $.ajax(
          {type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false,
          success: function(data){
            // console.log(data);
            var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
            session.disconnect();
            prop.opentok.apiKey = '';
            prop.opentok.sessionId = '';
            prop.opentok.token = '';
            $('#opentok_subscriber').hide();
            $('.opentok_placeholder_img').show();
            $('.chatbox').addClass('offline');
            $('.exit_session_btn').css('display','none');
            $('.send_tip_btn').css('display','none');
            //$('.private-chat').css('display','none');
            //$('.private-chat-msg').hide();
            
            clearInterval(myInterval);
            $('#model_low_alert').val('no'); 
            //location.reload();
           
          }
      });
    }
    
  </script>


    <?php if(isset($user_data['id'])) { ?>
    <script type="text/javascript">
        setInterval(function() {
            user_last_activity();
        }, (30 * 1000));
    </script>
    <?php } ?>
    </body>
</html>