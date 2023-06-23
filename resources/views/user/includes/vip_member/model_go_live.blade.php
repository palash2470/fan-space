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

<section class="golive_page model-section live-model-sec-new">
  <div class="container-fluid">
    <div class="row header_height justify-content-between align-items-center">
      <div class="col-auto">
        <div class="website-logo">
          <a href="{{ url('/') }}" target="_blank"><img
            src="{{ url('public/uploads/settings/settings_website_logo/' . $meta_data['global_settings']['settings_website_logo']) }}"
            alt=""></a>
        </div>
      </div>
        <div class="col-auto">
          <div class="chat-top-control model-live-control">
            <ul class="d-flex justify-content-end">
              <li><button type="button" class="chat-control-btn d-flex align-items-center req_user_list"><span>user</span> <span class="total-live-badge ml-2 online_user_count">0</span></button></li>
              <li><button type="button" class="chat-control-btn d-flex align-items-center req_private_list_btn"><span>private</span> <span class="total-live-badge ml-2 pvt_chat_request_count">0</span></button></li>
            </ul>
            <div class="live-user-list req_user_list_wrap">
              <div class="live-user-list-head">
                <div class="live-user-list-head-close">
                  <span class="hide-popup"><button class="hide_popup hide_popup_user_list"><i class="fas fa-angle-right"></i></button></span>
                </div>
                <div class="live-user-list-head-text">
                  <h4>user list</h4>
                </div>
                
                {{-- <h5>online - 5</h5> --}}
              </div>
              <div class="live-user-list-body onlineuser_list">
                {{-- <div class="live-user-list-box d-flex">
                  <div class="live-user-img">
                    <span class="live-user-img-box">
                      <img src="{{asset('public/front/images/user-placeholder.jpg')}}" alt="">
                      <span class="online-badge onlie"></span>
                    </span>
                  </div>
                  <div class="live-user-info">
                    <h4>barlowe</h4>
                    <ul class="d-flex">
                      <li><p>Lorem Ipsum Lorem Ipsum</p></li>
                      <li><strong><i class="far fa-clock"></i>11.30am</strong></li>
                      <li><strong><i class="fas fa-hourglass-half"></i>2hour</strong></li>
                      <li><strong><i class="fas fa-coins"></i>350coin</strong></li>
                    </ul>
                  </div>
                </div> --}}
                {{-- <div class="live-user-list-box d-flex">
                  <div class="live-user-img">
                    <span class="live-user-img-box">
                      <img src="{{asset('public/front/images/prf-lft-img.png')}}" alt="">
                      <span class="online-badge offlie"></span>
                    </span>
                  </div>
                  <div class="live-user-info">
                    <h4>barlowe</h4>
                    <ul class="d-flex">
                      <li><p>Lorem Ipsum Lorem Ipsum</p></li>
                      <li><strong><i class="far fa-clock"></i>11.30am</strong></li>
                      <li><strong><i class="fas fa-hourglass-half"></i>2hour</strong></li>
                      <li><strong><i class="fas fa-coins"></i>350coin</strong></li>
                    </ul>
                  </div>
                </div> --}}
                {{-- <div class="live-user-list-box d-flex">
                  <div class="live-user-img">
                    <span class="live-user-img-box">
                      <img src="{{asset('public/front/images/prf-lft-img.png')}}" alt="">
                      <span class="online-badge onlie"></span>
                    </span>
                  </div>
                  <div class="live-user-info">
                    <div class="d-flex justify-content-between">
                      <div class="live-user-info-lft">
                        <h4>barlowe</h4>
                        <ul class="d-flex">
                          <li><p>Lorem Ipsum Lorem Ipsum</p></li>
                          <li><strong><i class="fas fa-user"></i>male</strong></li>
                          <li><strong>Subscribe</strong></li>
                        </ul>
                      </div>
                      <div class="live-user-info-rgt">
                        <ul>
                          <li><a class="privet-chat-req req-accept" href="#">accept</a></li>
                          <li><a class="privet-chat-req req-decline" href="#">decline</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div> --}}
                
              </div>
            </div>
            <div class="live-user-list req_private_list">
              <div class="live-user-list-head">
                <div class="live-user-list-head-close">
                  <span class="hide-popup"><button class="hide_popup hide_popup_pvt_list"><i class="fas fa-angle-right"></i></button></span>
                </div>
                <div class="live-user-list-head-text">
                  <h4>Private chat request list</h4>
                </div>
                
                {{-- <h5>online - 5</h5> --}}
              </div>
              <div class="live-user-list-body private_request_user_list">
                {{-- <div class="live-user-list-box d-flex">
                  <div class="live-user-img">
                    <span class="live-user-img-box">
                      <img src="{{asset('public/front/images/prf-lft-img.png')}}" alt="">
                      <span class="online-badge onlie"></span>
                    </span>
                  </div>
                  <div class="live-user-info">
                    <div class="d-flex justify-content-between">
                      <div class="live-user-info-lft">
                        <h4>barlowe</h4>
                        <ul class="d-flex">
                          <li><p>Lorem Ipsum Lorem Ipsum</p></li>
                          <li><strong><i class="fas fa-user"></i>male</strong></li>
                          <li><strong>Subscribe</strong></li>
                        </ul>
                      </div>
                      <div class="live-user-info-rgt">
                        <ul>
                          <li><a class="privet-chat-req req-accept" href="#">accept</a></li>
                          <li><a class="privet-chat-req req-decline" href="#">decline</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div> --}}
                
              </div>
            </div>
          </div>
        </div>
      </div>
    <div class="row h-100vh">
        <div class="new-model-video-chat-lft">
          <div class="new-model-video-chat-lft-wrap new_model_video">
            <div class="new_model_video_main">
              <input type="hidden" name="model_low_alert" id="model_low_alert" value="no">
              <div class="live-main-videowrap">
                <div class="live-main-videowrap-lft" style="display: none;">
                  <div class="relative h-100"><div id="opentok_pvt_subscriber" class="opentok_player_area" style="display: none;"></div></div>
                </div>
                <div class="live-main-videowrap-rgt">
                  <div id="opentok_publisher" class="opentok_player_area"></div>
                </div>                
              </div>
            </div>
            {{-- <div class="d-flex justify-content-center mt-3">
                <a href="javascript:void(0);" class="commonBtn2 opentok_start_session">Start session</a>
                <a href="javascript:void(0);" class="commonBtn2 opentok_end_session" style="display: none;">End session</a>
                <a href="javascript:;" class="view_counter"><i class="fa fa-eye"></i> <span>0</span></a>
            </div> --}}
            <div class="video-chat-lft-control control_left">
              <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                  <div class="watch-cost">
                    <span class="coin-img"> <img src="{{asset('public/front/images/coin.png')}}"  alt=""></span>
                    <span id="model_wallet_coins">0</span></div>
                </div>
                <div class="col-auto">
                  <div class="chat-btm-control">
                    <ul class="d-flex justify-content-end">
                      <li><a href="javascript:;" class="sesson-btn view_counter"><i class="fa fa-eye"></i> <span>0</span></a></li>
                      <li><a href="javascript:void(0);" class="sesson-btn opentok_start_session">Start session</a>
                        <a href="javascript:void(0);" class="sesson-btn opentok_end_session" style="display: none;">End session</a></li>                      
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="new-model-video-chat-rgt">
          <div class="chatbox chatbox_wrap offline blk-user-wrap" vip_member_id="{{ $user_data['id'] }}" ts="{{ time() }}">
            <div class="chat-box-wrap chat_box_wrap">
              <div class="chatoffline">Chatting unavailable. Model is not live</div>
              <div class="chatlist"></div>
              <div class="chatfields">
              <!-- <input type="text" class="form-control" placeholder="Say Something ...." name="chat_input"> -->
                <div class="emoji_pad" save_action="live_session_chat_send_message" save_params='{!! json_encode(['vip_member_id' => $user_data['id']]) !!}'>
                  <textarea placeholder="Say Something ...."></textarea>
                  <div class="emoji_container" id="emoji_container_live"></div>
                  <a href="javascript:;" class="emoji_submit"><i class="far fa-paper-plane"></i></a>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.min.css" rel="stylesheet" />
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
    {{-- Sweert Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Toastr --}}
    <script src="{{ URL::asset('/public/front/js/toastr.min.js') }}"></script>




    <script>
      /* window.onbeforeunload = function(event)
      {
          return confirm("Confirm refresh");
      }; */
      $(document).ready(function(){
        var bodyHeight = $(document).height();
        // $('body').css({"min-height": bodyHeight });
        var headerHeight = $('.header_height').outerHeight(true);
        var controlHeight = $('.control_left').outerHeight(true);
        $('.new_model_video').css({"padding-bottom": controlHeight});
        // var wrapHeight = bodyHeight - (headerHeight + controlHeight);
        // $('.new_model_video_main').css({"height": wrapHeight});
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


        $(document).on('click', '.req_user_list', function(){
          if($('.online_user_count').text() > 0){
            $('.req_user_list_wrap').toggleClass('user_open');
            $('.req_private_list').removeClass('private_open');
          }
          
        });
        $(document).on('click', '.hide_popup_user_list', function(){
          $('.req_user_list_wrap').removeClass('user_open');
        });
        $(document).on('click', '.req_private_list_btn', function(){
          //console.log('dsfds');
          $('.req_private_list').toggleClass('private_open');
          $('.req_user_list_wrap').removeClass('user_open');
        });
        $(document).on('click', '.hide_popup_pvt_list', function(){
          $('.req_private_list').removeClass('private_open');
        });
      });
    </script>
  <script type="text/javascript">
   
   function opentok_initializePubSession(data) {
  var apiKey = prop.opentok.apiKey;
  var sessionId = prop.opentok.sessionId;
  var token = prop.opentok.token;
  var session = OT.initSession(apiKey, sessionId);
  // Subscribe to a newly created stream
  session.on('streamCreated', function(event) {
   /*  prop.opentok.subscriber = session.subscribe(event.stream, 'opentok_pvt_subscriber', {
      insertMode: 'append',
      width: '100%',
      height: '100%'
    }, handleOpentokError); */
  });

  session.on('streamDestroyed', function(event) {
    //console.log('streamDestroyed = streamDestroyed');
    reset_opentok_player_area();
  });

  // Create a publisher
  var publisher = OT.initPublisher('opentok_publisher', {
    insertMode: 'append',
    width: '100%',
    height: '100%',
    // resolution : "320x240",
    fitMode:'contain',
    //enableStereo: true,
    audioBitrate: 28000
  }, handleOpentokError);

  // Connect to the session
  session.connect(token, function(error) {
    // If the connection is successful, initialize a publisher and publish to the session
    if (error) {
      handleOpentokError(error);
    } else {
      session.publish(publisher, handleOpentokError);
    }
  });

  session.on("connectionCreated", function(event) {
    //console.log('connectionCreated',event);
    if(typeof window['live_viewer_count'] == 'undefined') window['live_viewer_count'] = 0;
      window['live_viewer_count']++;
    $('.golive_page .view_counter span').text((window['live_viewer_count'] - 1));
    $('.online_user_count').text((window['live_viewer_count'] - 1));

  });

  session.on("connectionDestroyed", function(event) {
    window['live_viewer_count']--;
    $('.golive_page .view_counter span').text((window['live_viewer_count'] - 1));
    $('.online_user_count').text((window['live_viewer_count'] - 1));
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

}

function opentok_destroyPubSession() {
  var session = OT.initSession(prop.opentok.apiKey, prop.opentok.sessionId);
  session.disconnect();
  prop.opentok.apiKey = '';
  prop.opentok.sessionId = '';
  prop.opentok.token = '';
}


  $(document).ready(function(){
    opentok_end_session_page_refresh();
    $(document).on('click', '.opentok_start_session', function(){

      $(".mw_loader").show();
      var data = new FormData();
      data.append('action', 'opentok_start_session');
      data.append('_token', prop.csrf_token);
      $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
          $(".mw_loader").hide();
          $('.opentok_start_session').hide();
          $('.opentok_end_session').show();
          $('.req_user_list_wrap .onlineuser_list').html('');

          var ot = data.data.opentok_data;
          var type = data.data.type;
          var old_opentok = {'apiKey': prop.opentok.apiKey, 'sessionId': prop.opentok.sessionId, 'token': prop.opentok.token};
          if(old_opentok.sessionId != ot.sessionId) {
            if(old_opentok.sessionId != '')
              opentok_destroyPubSession();
            prop.opentok.apiKey = ot.apiKey;
            prop.opentok.sessionId = ot.sessionId;
            prop.opentok.token = ot.token;
          }
          if(type == 'publisher') {
            $('.chatbox').removeClass('offline');
            opentok_initializePubSession(ot);
          }
        }
      });
    });

    $(document).on('click', '.opentok_end_session', function(){
      var data = new FormData();
      data.append('action', 'opentok_end_session');
      data.append('_token', prop.csrf_token);
      $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
          opentok_destroyPubSession();
          $('.opentok_start_session').show();
          $('.opentok_end_session').hide();
          $('.chatbox').addClass('offline');
          $('.chatbox .chatlist').html('');
          $('.private-chat-req').css('display','none');
          $('.private-req-tbody').html('');
          $('.req_user_list_wrap .onlineuser_list').html('');
          $('.req_private_list .private_request_user_list').html('');
          $('.pvt_chat_request_count').text('0');
          //window['live_viewer'] = {};
          window['live_viewer_count'] = 0;
          $('.golive_page .view_counter span').text('0');
          $('.online_user_count').text('0');
          clearInterval(myInterval);
          $('#model_low_alert').val('no');
        }
      });
    });

    //setInterval(function(){ live_viewer_track(); }, 3000);

  });
  
  function opentok_end_session_page_refresh(){
    var data = new FormData();
      data.append('action', 'opentok_end_session');
      data.append('_token', prop.csrf_token);
      $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
          opentok_destroyPubSession();
          $('.opentok_start_session').show();
          $('.opentok_end_session').hide();
          $('.chatbox').addClass('offline');
          $('.chatbox .chatlist').html('');
          $('.private-chat-req').css('display','none');
          $('.private-req-tbody').html('');
          $('.req_user_list_wrap .onlineuser_list').html('');
          //window['live_viewer'] = {};
          window['live_viewer_count'] = 0;
          $('.golive_page .view_counter span').text('0');
          $('.online_user_count').text('0');
          clearInterval(myInterval);
          $('#model_low_alert').val('no');
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