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
        var prop = @php
         echo json_encode(['ajaxurl' => url('/ajaxpost'), 'ajaxgeturl' => url('/ajaxget'), 'url' => url('/'), 'csrf_token' => csrf_token(), 'user_data' => $user_data, 'opentok' => ['apiKey' => '', 'sessionId' => '', 'token' => '', 'session' => null]]); @endphp;
    </script>
</head>

<section class="golive_page model-section">
    <div class="row">
        <div class="col-md-8 col-12">
            <input type="hidden" name="model_low_alert" id="model_low_alert" value="no">
            <div id="opentok_publisher" class="opentok_player_area"></div>
            <div class="d-flex justify-content-center mt-3">
                <a href="javascript:void(0);" class="commonBtn2 opentok_start_session">Start session</a>
                <a href="javascript:void(0);" class="commonBtn2 opentok_end_session" style="display: none;">End session</a>
                <a href="javascript:;" class="view_counter"><i class="fa fa-eye"></i> <span>0</span></a>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="chatbox offline blk-user-wrap" vip_member_id="{{ $user_data['id'] }}" ts="{{ time() }}">
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
</section>
<div class="private-chat-req private-req-table" style="display: none;">
    <h4>Private chat request</h4>
    <div class="scrollFix">
        <div class="tableHead">
            <ul class="d-flex">
                <li>Name</li>
                <li class="text-center">Gender</li>
                <li class="text-center">User Type</li>
                <li class="text-right">Action</li>

            </ul>
        </div>
        <div class="tableScroll">
        <table class="table table-bordered mb-0">

            <tbody class="private-req-tbody">
                <!-- <tr>
                    <td>Jhon Paul</td>
                    <td class="text-center">Male</td>
                    <td class="text-center">Male</td>
                    <td>
                        <div class="request-list-rgt">
                            <ul class="d-flex justify-content-end">
                                <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                                <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                            </ul>
                        </div>
                    </td>
                </tr> -->

            </tbody>
        </table>
        </div>
    </div>
</div>


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
   
   function opentok_initializePubSession(data) {
  var apiKey = prop.opentok.apiKey;
  var sessionId = prop.opentok.sessionId;
  var token = prop.opentok.token;
  var session = OT.initSession(apiKey, sessionId);
  // Subscribe to a newly created stream
  session.on('streamCreated', function(event) {
    /*prop.opentok.subscriber = session.subscribe(event.stream, 'opentok_pvt_subscriber', {
      insertMode: 'append',
      width: '100%',
      height: '100%'
    }, handleOpentokError);*/
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
    console.log('connectionCreated',event);
    if(typeof window['live_viewer_count'] == 'undefined') window['live_viewer_count'] = 0;
      window['live_viewer_count']++;
    $('.golive_page .view_counter span').text((window['live_viewer_count'] - 1));

  });

  session.on("connectionDestroyed", function(event) {
    window['live_viewer_count']--;
    $('.golive_page .view_counter span').text((window['live_viewer_count'] - 1));
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

    $(document).on('click', '.opentok_start_session', function(){

      $(".mw_loader").show();
      var data = new FormData();
      data.append('action', 'opentok_start_session');
      data.append('_token', prop.csrf_token);
      $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
          $(".mw_loader").hide();
          $('.opentok_start_session').hide();
          $('.opentok_end_session').show();

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
          //window['live_viewer'] = {};
          window['live_viewer_count'] = 0;
          $('.golive_page .view_counter span').text('0');
          clearInterval(myInterval);
          $('#model_low_alert').val('no');
        }
      });
    });

    //setInterval(function(){ live_viewer_track(); }, 3000);

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