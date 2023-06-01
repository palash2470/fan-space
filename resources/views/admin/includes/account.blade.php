<section class="content-header">
  <h1>
    Account
    <!-- <small>it all starts here</small> -->
  </h1>
  <!--<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Examples</a></li>
    <li class="active">Blank page</li>
  </ol>-->
</section>


<section class="content">

  @if ($message = Session::get('success'))
  <div class="alert alert-success">{{ $message }}</div>
  @endif

  @if ($message = Session::get('error'))
  <div class="alert alert-danger">{{ $message }}</div>
  @endif

  <div class="box box-solid">
    <!-- <div class="box-header with-border">
      <h3 class="box-title">Collapsible Accordion</h3>
    </div> -->
    <!-- /.box-header -->
    <div class="box-body">

        <form class="form-horizontal account_form" method="post" enctype="multipart/form-data">

        <h4>Change Password</h4>

        <div class="form-group">
          <label class="col-sm-3 control-label">Current Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" name="current_password" value="" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <div class="form-group">
          <label class="col-sm-3 control-label">New Password</label>
          <div class="col-sm-6">
            <input type="password" class="form-control" name="new_password" value="" />
          </div>
          <div class="col-sm-3"></div>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <button type="submit" class="btn btn-primary pull-right account_submit">Add</button>

      </form>
        
    </div>
    <!-- /.box-body -->

    <div class="overlay" style="display: none;"><i class="fa fa-refresh fa-spin"></i></div>

  </div>

</section>



@push('scripts')

<script type="text/javascript">
  function preview_image(input, target) {
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
      $(input).val('');
        alert('invalid photo!');
        return;
    }
    if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            var output = "<center><img src='"+e.target.result+"' />\
            <input type='hidden' name='hidden_photo' value='"+e.target.result+"' />\
            <a href='javascript:void(0)' class='remove_photo'>x</a></center>";
              $(target).html(output);
              remove_photo(target, input);
          }
          reader.readAsDataURL(input.files[0]);
      }
  }

  function remove_photo(target, element) {
    $('.remove_photo').click(function(){
      $(target).html('');
      $(element).val('');
    });
  }


  function validate_account() {
    var current_password = $.trim($('input[name="current_password"]').val());
    var new_password = $.trim($('input[name="new_password"]').val());
    var error = 0;
    var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    $(".form-group").removeClass('has-error');
    $(".help-block").remove();
    if(current_password == "") {
      $("input[name='current_password']").closest('.col-sm-6').append("<span class='help-block'>Enter current password</span>");
      $("input[name='current_password']").closest('.form-group').addClass('has-error');
      error = 1;
    }
    if(new_password == "") {
      $("input[name='new_password']").closest('.col-sm-6').append("<span class='help-block'>Enter new password</span>");
      $("input[name='new_password']").closest('.form-group').addClass('has-error');
      error = 1;
    }
    if(error == 1)
      return false;
    else
      return true;
  }
</script>

<script type="text/javascript">
  $(document).ready(function(){

    $('input[name="photo"]').change(function(){
        preview_image(this, '.photo');
    });
    remove_photo('.photo', 'input[name="photo"]');

    setTimeout(function(){ $(".content .alert").remove(); }, 3000);

    /*$(".keep_file").each(function(){
      $(this).change(function(){
        console.log('ppp');
        if($(this).is(':checked')) {
          $(this).closest('.form-group').find('input[type="file"]').removeAttr('disabled');
        } else {
          $(this).closest('.form-group').find('input[type="file"]').attr('disabled', 'disabled');
        }
      });
    });*/

    $(".keep_file").each(function(){
      $(this).on('ifUnchecked', function(){
        $(this).closest('.form-group').find('input[type="file"]').removeAttr('disabled');
      });
      $(this).on('ifChecked', function(){
        $(this).closest('.form-group').find('input[type="file"]').attr('disabled', 'disabled');
      });
    });
    

    $(".account_submit").click(function(e){
      e.preventDefault();
      if(validate_account()) {
        $(".box .overlay").show();
        $('form.account_form').submit();
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