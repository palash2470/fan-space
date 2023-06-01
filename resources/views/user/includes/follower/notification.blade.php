<?php

?>

<div class="row align-items-center justify-content-between">
  <div class="col-auto"> <a class="back-notification-btn" href=""><!-- <span class="ti-arrow-left"></span> -->Notifications</a> </div>
  <!-- <div class="col-auto">
        <div class="option-btn-wrap relative notification-setting">
            <button type="button" class="option-btn"><span class="ti-settings"></span></button>
            <ul class="option-btn-details">
                <li><a href="#">Setting1</a></li>
                <li><a href="#">Setting1</a></li>
            </ul>
        </div>
    </div> --> 
</div>
<div class="notification-menu notifcat_nav">
  <ul class="d-flex justify-content-between">
    <li class="active"><a href="javascript:;" class="show_notification" notification_type=""><span class="ti-check-box"></span>All</a></li>
    <li><a href="javascript:;" class="show_notification" notification_type="comment"><span class="ti-comment"></span>Interations</a></li>
    <li><a href="javascript:;" class="show_notification" notification_type="like"><span class="ti-heart"></span>Liked</a></li>
  </ul>
</div>
<?php
$notifications = $meta_data['notifications'];
//$total_page = ceil($notifications['total_data'] / $meta_data['per_page']);
?>
<div class="notification_list_box" ajax_running="0" end_list="0" per_page="{{ $meta_data['per_page'] }}">
  <?php
  $notification_ids = [];
  foreach ($notifications['data'] as $key => $value) {
    $notification_html = \App\Notification::get_notification_html(['notification' => $value]);
    echo $notification_html['html'];
    $notification_ids[] = $value->id;
  } ?>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Message to <span></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <textarea name="message" id="message" rows="5" class="form-control resize-none"></textarea>
            </div>
          </div>
          <div class="col-sm-12">
            <button type="button" class="website-btn" id="send_message">Submit</button>
            <input type="hidden" name="subscriber_id" id="subscriber_id" value="0">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@push('script') 
<script>
$(document).on('click','.sendMessageToSubscriber',function(){
	$('#subscriber_id').val($(this).data('user_id'));
	//$('#exampleModalLabel span').html($(this).find('.user_title').text());
	//$('#exampleModal').modal('show');	
	
	window.location.href = "{{ url('dashboard/message') }}/?id="+$(this).data('user_id');
});
</script> 
<script type="text/javascript">
    $(document).ready(function(){

        notification_lists();
        set_notification_seen({'notification_ids': {!! json_encode($notification_ids) !!} });

    });
</script> 
@endpush