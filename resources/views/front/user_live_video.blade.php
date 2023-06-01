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
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/developer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/public/front/css/media.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('public/front/css/jquery.mCustomScrollbar.css') }}"
        rel="stylesheet" />
    <script src="{{ URL::asset('/public/front/js/jquery-3.5.1.min.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script type="text/javascript">
        var prop = <?php echo json_encode(['ajaxurl' => url('/ajaxpost'), 'ajaxgeturl' => url('/ajaxget'), 'url' => url('/'), 'csrf_token' => csrf_token(), 'user_data' => $user_data, 'opentok' => ['apiKey' => '', 'sessionId' => '', 'token' => '', 'session' => null]]); ?>;
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

    <script src="{{ URL::asset('/public/front/js/jquery-ui.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/wow.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/wow.custom.js') }}" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
    <script src="{{ URL::asset('/public/front/js/croppie.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/paralax.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/video.min.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    <script src="{{ url('public/front/library/emojionearea-3.3.1/dist/emojionearea.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('public/front/js/select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/jquery.timeago.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/custom/common.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('/public/front/js/custom/site.js?t=' . time()) }}" type="text/javascript"></script>
    <!--<script src="{{ url('public/front/js/mCustomScrollbar.js') }}"></script>-->
    <script src="{{ url('public/front/js/jquery.mCustomScrollbar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


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


    <?php if(isset($user_data['id'])) { ?>
    <script type="text/javascript">
        setInterval(function() {
            user_last_activity();
        }, (30 * 1000));
    </script>
    <?php } ?>
    </body>
</html>