<?php
// dd($meta_data);
$friend_list	= $meta_data['friend_list'];
$own_user_img	= $meta_data['own_user_img'];
//print_r($notifications);exit;
?>

<!--<div class="row align-items-center justify-content-between">
  <div class="col-auto"> <a class="back-notification-btn" href="javascript:;">notification</a> </div>
</div>-->
<div class="massage-wrap d-flex">
  <div class="contact-wrap chat-left">
      <div class="chat-close"><button type="button" class="chat-close-btn"><i class="fas fa-times"></i></button></div>
    <?php if(count($meta_data['friend_list']) > 0) { ?>
    <div class="contacts" data-scrollbar>
      <ul>
        <?php $i=0;foreach($meta_data['friend_list'] as $row){ ?>
        <li class="user_box <?php if($i==0){ echo 'active'; } ?>" id="user_box_{{$row['user_id']}}"> <a href="javascript:;" class="d-flex align-items-center friend_btn" data-id="{{$row['user_id']}}"> <span class="contact-img"><img src="{{$row['user_img']}}" alt="{{$row['name_name']}}" /></span>
          <p class="contact-name">{{$row['name_name']}}</p>
          </a> </li>
        <?php $i++;} ?>
      </ul>
    </div>
    <?php } ?>
  </div>
  <div class="contact-msg-wrap" id="messages_display_section">
    <div class="messages_display_box">
      <div class="contact-profile">
          <div class="for-mobile-chat"><button type="button" class="chat-open-btn"><i class="fas fa-bars"></i></button></div>
        <div class="d-flex align-items-center justify-content-between lft-gap">
            <div class="d-flex align-items-center messages_profile_display_box"><a href="{{ url('u/'.$meta_data['friend_list'][0]['username']) }}"> <span class="contact-profile-img"><img src="<?php if(isset($meta_data['friend_list'][0]['user_img'])){ echo $meta_data['friend_list'][0]['user_img'];} ?>" alt="" /></span></a>
            <p>
                <a class="naming-style" href="{{ url('u/'.$meta_data['friend_list'][0]['username']) }}"><?php if(isset($meta_data['friend_list'][0]['name_name'])){ echo $meta_data['friend_list'][0]['name_name'];} ?></a>
            </p>
            </div>
            <div class="delete-box" style="display: none;">
                <button type="button" class="delete-icon"><i class="far fa-trash-alt"></i></button>
            </div>
        </div>
      </div>
      <div class="msg-wrap">
        <div class="msg-history msg-history-scrollbar">
          <div class="messages" id="chat_box"> </div>
        </div>
      </div>
    </div>
    <div class="message-input ">
      <div class="input-msg-write">
        <input type="hidden" name="to"  class="to_user_id" id="to_user_id" value="<?php if(isset($meta_data['friend_list'][0]['user_id'])){ echo $meta_data['friend_list'][0]['user_id'];} ?>"/>
        <textarea class="write-msg form-control emoji-area" placeholder="Type a message" rows="2" id="input-msg-textare"></textarea>
        <button type="submit" class="msg-send-btn input_send"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
@push('script')
<script>
var delete_id=[];
$(document).on('click','.friend_btn',function(){
	$('.user_box').removeClass('active');
	$(this).parent().addClass('active');
	var friend_id=$(this).data('id');
	get_friend_chat(friend_id);
});

<?php
if(isset($meta_data['friend_list'][0]['user_id'])){
	if($meta_data['friend_list'][0]['user_id']!=''){?>
		get_friend_chat({{$meta_data['friend_list'][0]['user_id']}});
	<?php }
}
?>


function get_friend_chat(friend_id) {
    delete_id=[];
	$(".mw_loader").show();
	$('#to_user_id').val(friend_id);
    $.ajax({
        url: prop.ajaxurl,
        type: 'post',
        data: {
            action: 'getFriendChat',
            friend_id: friend_id,
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(data) {
            var html = '';
			//alert(data.friend_data.user_title);
            for (var i = 0; data.result.length > i; i++) {
                if (data.result[i].is_me == 'Y') {
                    if(!data.result[i].deleted_at){
                    html += '<div class="outgoing-msg d-flex justify-content-end mb-2"><div class="msg-delete-wrap"><div class="msg-delete checkbox"><input type="checkbox" id="chk'+data.result[i].id+'" name="message_id" value="'+data.result[i].id+'"><label for="chk'+data.result[i].id+'"></label></div></div><div class="sent-msg"><p>' + data.result[i].msg + '</p><span class="time-date"> ' + data.result[i].time + '</span></div><div class="user-img-wrap"><span class="user-img"> <img src="' + data.result[i].profile_photo + '" alt=""></span></div></div>';
                    }else{
                        html += '<div class="outgoing-msg d-flex justify-content-end mb-2"><div class="sent-msg deleted-msg"><p><i class="fas fa-ban"></i> ' + 'You have deleted the message' + '</p><span class="time-date"> ' + data.result[i].time + '</span></div><div class="user-img-wrap"><span class="user-img"> <img src="' + data.result[i].profile_photo + '" alt=""></span></div></div>';
                    }
                } else {
                    if(!data.result[i].deleted_at){
                    html += '<div class="incoming-msg d-flex justify-content-start mb-2"><div class="user-img-wrap"><span class="user-img"><img src="' + data.result[i].profile_photo + '" alt=""></span></div><div class="received-msg"><p>' + data.result[i].msg + '</p><span class="time-date"> ' + data.result[i].time + '</span></div></div>';
                    }else{
                        html += '<div class="incoming-msg d-flex justify-content-start mb-2"><div class="user-img-wrap"><span class="user-img"><img src="' + data.result[i].profile_photo + '" alt=""></span></div><div class="received-msg deleted-msg"><p><i class="fas fa-ban"></i> ' + data.friend_data.user_title+' has deleted this message' + '</p><span class="time-date"> ' + data.result[i].time + '</span></div></div>';
                    }
                }
            }
			$('#chat_box').html(html);
			$('.msg-history-scrollbar').scrollTop($('.msg-history-scrollbar')[0].scrollHeight);

			$('.messages_profile_display_box').html('<a href="{{ url("u/") }}/'+data.friend_data.username+'"><span class="contact-profile-img"><img src="'+data.friend_data.user_img+'" alt=""></span></a><a class="naming-style" href="{{ url("u/") }}/'+data.friend_data.username+'"><p>'+data.friend_data.user_title+'</p></a>');

			$(".mw_loader").hide();
        }
    });
}

$(document).on('click', '.input_send', function() {
    var message 	= $('#input-msg-textare').val();
	var to_user_id 	= $('#to_user_id').val();
    var data_time = formatAMPM();
    var own_user_img = '{{$own_user_img}}';
    if (message !== '') {
        var messagenl2br = message.replace(/\n/g, "<br />");
        // $('#chat_box').append('<div class="outgoing-msg d-flex justify-content-end mb-2"><div class="sent-msg"><p>' + message + '</p><span class="time-date"> ' + data_time + '</span></div><div class="user-img-wrap"><span class="user-img"> <img src="' + own_user_img + '" alt=""></span></div></div>');


        $.ajax({
            url: prop.ajaxurl,
            type: 'post',
            data: {
                action: 'sendMessge',
				friend_id: to_user_id,
				message: message,
                _token: prop.csrf_token
            },
            dataType: 'json',
            success: function(data) {
                get_friend_chat(to_user_id);
                $('#input-msg-textare').val('');
                $(".emojionearea-editor").html('');
                $('.msg-history-scrollbar').scrollTop($('.msg-history-scrollbar')[0].scrollHeight);
                
			}
        });
    }

});

$(document).on('click','.msg-delete input[type=checkbox]',function(){
    delete_id=[];
    if($(this).prop('checked')==true){
        // alert($(this).val());
        $(this).closest('.outgoing-msg').addClass('selected');
    }else{
        $(this).closest('.outgoing-msg').removeClass('selected');
    }
    $('.msg-delete input[type=checkbox]:checked').each(function(){
        delete_id.push($(this).val());
    });
    console.log('delete_id',delete_id);
    if(delete_id.length>0){
        $('.delete-box').show();
    }else{
        $('.delete-box').hide();
    }
});

$(document).on('click','.delete-box',function(){
    $.ajax({
        url: prop.ajaxurl,
        type: 'post',
        data: {
            action: 'deleteMessage',
            delete_id: delete_id,
            _token: prop.csrf_token
        },
        dataType: 'json',
        success: function(data) {
            var to_user_id 	= $('#to_user_id').val();
            get_friend_chat(to_user_id);
            $('.msg-history-scrollbar').scrollTop($('.msg-history-scrollbar')[0].scrollHeight);
            $('#input-msg-textare').val('');
            delete_id=[];
            $('.delete-box').hide();
        }
    });
});

function formatAMPM() {
    var date        = new Date;
    var hours       = date.getHours();
    var minutes     = date.getMinutes();
    var ampm        = hours >= 12 ? 'pm' : 'am';
    hours           = hours % 12;
    hours           = hours ? hours : 12; // the hour '0' should be '12'
    minutes         = minutes < 10 ? '0'+minutes : minutes;
    var strTime     = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}

// Chat Responsive
$(document).ready(function(){
    $(".chat-open-btn").click(function(){
        $(".chat-left").addClass("chat-left0");
    });
    $(".chat-close-btn, .contacts li a").click(function(){
        $(".chat-left").removeClass("chat-left0");
    });
});
// Chat Responsive
Scrollbar.initAll();
</script>


@endpush
