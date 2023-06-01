<?php
$payout = $meta_data['payout_form']['item'];
?>

<!-- Default box -->
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo (!isset($meta_data['payout_form']['item']->id)? 'Create' : 'Update') ?> Payout</h3>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
              title="Collapse">
        <i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fa fa-times"></i></button>
    </div> -->
  </div>
  <div class="box-body">
    
    <form class="form-horizontal payout_form" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <label class="col-sm-3 control-label">User</label>
          <div class="col-sm-6">{{ $payout->first_name ?? '' }} {{ $payout->last_name ?? '' }}</div>
          <div class="col-sm-3"></div>
        </div>
        
        <div class="form-group">
          <label class="col-sm-3 control-label">Amount</label>
          <div class="col-sm-6">${{ ($payout->price_amount ?? '') }} ({{ $payout->token_coins }} coins)</div>
          <div class="col-sm-3"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Date</label>
          <div class="col-sm-6">{{ date('d-m-Y h:i:a', strtotime($payout->created_at)) }}</div>
          <div class="col-sm-3"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Transaction ID</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="transaction_id" value="{{ $payout->transaction_id ?? '' }}" />
          </div>
          <div class="col-sm-3"></div>
        </div>
        <div class="form-group">
          <label class="col-sm-3 control-label">Status</label>
          <div class="col-sm-6">
            <?php
            $sel1 = $sel2 = '';
            if($payout->status == '0') $sel1 = 'selected="selected"';
            if($payout->status == '1') $sel2 = 'selected="selected"';
            ?>
            <select class="form-control" name="status">
              <option value="0" {!! $sel1 !!}>Pending</option>
              <option value="1" {!! $sel2 !!}>Paid</option>
            </select>
          </div>
          <div class="col-sm-3"></div>
        </div>

        <input type="hidden" name="payout_id" value="{{ $payout->id ?? 0 }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-primary pull-right payout_submit">Save</button>

      </form>


  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    <!--Footer-->
  </div>
  <!-- /.box-footer-->
</div>
<!-- /.box -->


@push('scripts')

<script type="text/javascript">

	$(document).ready(function(){


		$(".food_item_submit").click(function(e){
      e.preventDefault();
      if(validate_coin_plan()) {
        $(".box .overlay").show();
        $('form.coin_plan_form').submit();
        /*var settings_paypal_email = $.trim($('input[name="settings_paypal_email"]').val());
        var settings_paypal_pdt_token = $.trim($('input[name="settings_paypal_pdt_token"]').val());
        var settings_paypal_payment_mode = $('input[name="settings_paypal_payment_mode"]:checked').val();
        $(".box .overlay").show();
        var data = {
          'settings_paypal_email': settings_paypal_email,
          'settings_paypal_pdt_token': settings_paypal_pdt_token,
          'settings_paypal_payment_mode': settings_paypal_payment_mode,
        };
        $.ajax({
          method: "POST",
          url: "<?php echo url('adminajax/settings'); ?>",
          dataType : 'json',
          data: { _token: prop.csrf_token, data: data},
          success: function(data) {
            console.log(data);
            $(".box .overlay").hide();
            $(".content").prepend('<div class="alert alert-success">Data successfully updated.</div>');
            setTimeout(function(){ $(".content .alert").remove(); }, 3000);
          }
        });*/

        /*setTimeout(function(){
          $(".box .overlay").hide();
          $(".content").prepend('<div class="alert alert-success">Success alert preview. This alert is dismissable.</div>');
          setTimeout(function(){ $(".content .alert").remove(); }, 2000);
        }, 2000);*/

      }
    });

	});
  
</script>

@endpush