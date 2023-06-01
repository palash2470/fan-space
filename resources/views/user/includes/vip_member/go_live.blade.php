<?php
/*require_once(base_path() . '/opentok/OpenTokHelper.php');

$aa = OpenTokHelper::opentok_create_session([]);
$aa = opentok_create_session([]);
dd($aa);*/
/* Notification::create(['user_id' => follower id, 'object_type' => 'user', 'object_id' => model id, 'action' => 'new action', 'message' => '', 'json_data' => json_encode(['user_id' => logged in user id]), 'created_at' => date('Y-m-d H:i:s')]); */

?>

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
      <!-- <h3>Messages</h3> -->
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
{{-- <div class="private-chat-req private-chate-rq-wrap">
    <h4>Private chat request</h4>
    <div class="scroll-rq-chat">
        <div class="request-list d-flex align-items-center">
            <div class="request-list-lft">
                <h5>Jhon Paul</h5>
            </div>
            <div class="request-list-rgt">
                <ul class="d-flex">
                    <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                    <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                </ul>
            </div>
        </div>
        <div class="request-list d-flex align-items-center">
            <div class="request-list-lft">
                <h5>Hasan Paul</h5>
            </div>
            <div class="request-list-rgt">
                <ul class="d-flex">
                    <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                    <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                </ul>
            </div>
        </div>
        <div class="request-list d-flex align-items-center">
            <div class="request-list-lft">
                <h5>Jhon Paul</h5>
            </div>
            <div class="request-list-rgt">
                <ul class="d-flex">
                    <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                    <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                </ul>
            </div>
        </div>
        <div class="request-list d-flex align-items-center">
            <div class="request-list-lft">
                <h5>Hasan Paul</h5>
            </div>
            <div class="request-list-rgt">
                <ul class="d-flex">
                    <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                    <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                </ul>
            </div>
        </div>
        <div class="request-list d-flex align-items-center">
            <div class="request-list-lft">
                <h5>Hasan Paul</h5>
            </div>
            <div class="request-list-rgt">
                <ul class="d-flex">
                    <li><button type="button" class="accept-private-chat-req accept-rq-btn acc-req"><i class="fas fa-check-square"></i></button></li>
                    <li><button type="button" class="reject-private-chat-req accept-rq-btn rej-req"><i class="fas fa-times-circle"></i></button></li>
                </ul>
            </div>
        </div>
    </div>
</div> --}}


@push('script')

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

@endpush
