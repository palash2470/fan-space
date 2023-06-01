<?php
$coin_plan = $meta_data['coin_plan_form']['item'];
?>

<!-- Default box -->
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo (!isset($meta_data['coin_plan_form']['item']->id)? 'Create' : 'Update') ?> Coin Plan</h3>

    <!-- <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
              title="Collapse">
        <i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
        <i class="fa fa-times"></i></button>
    </div> -->
  </div>
  <div class="box-body">
    
    <form class="form-horizontal coin_plan_form" method="post" enctype="multipart/form-data">

        <div class="form-group">
          <label class="col-sm-3 control-label">Title</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="title" value="{{ $coin_plan->title ?? '' }}" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Info</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="info" value="{{ $coin_plan->info ?? '' }}" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Coins</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="coins" value="{{ $coin_plan->coins ?? '' }}" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">Price</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" name="price" value="{{ $coin_plan->price ?? '' }}" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <input type="hidden" name="coin_plan_id" value="{{ $coin_plan->id ?? 0 }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-primary pull-right coin_plan_submit">Save</button>

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
	function validate_coin_plan() {
		return true;
	}

	$(document).ready(function(){

		onlyNumbersWithDecimal('input[name="coins"]');
		onlyNumbersWithDecimal('input[name="price"]');

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