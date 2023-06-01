<?php
$data = $meta_data['data'];
?>
<section class="content-header">
  <h1>
    Settings
  </h1>
</section>


<section class="content">

  <?php if(isset($_GET['updated'])) { ?>
    <div class="alert alert-success">Data successfully updated.</div>
  <?php } ?>

  <div class="box box-solid">
    <!-- <div class="box-header with-border">
      <h3 class="box-title">Collapsible Accordion</h3>
    </div> -->
    <!-- /.box-header -->
    <div class="box-body">
      <form class="form-horizontal">
      <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#general">
                General
              </a>
            </h4>
          </div>
          <div id="general" class="panel-collapse collapse in">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Logo</label>
                <div class="col-sm-6">
                  <input type="file" class="form-control" name="settings_website_logo" disabled="disabled" />
                </div>
                <div class="col-sm-3"><label><input type="checkbox" class="minimal keep_file" name="keep_thumbnail" value="1" checked> Keep existing</label>
                  <br>
                  <div class="image_preview">
                    <?php if(isset($data['settings']['settings_website_logo']) && $data['settings']['settings_website_logo'] != '') {
                      echo '<img src="' . url('/public/uploads/settings/settings_website_logo/' . $data['settings']['settings_website_logo']) . '" class="img-responsive" style="max-height: 60px;" />';
                    } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Website Title</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Name" name="settings_website_title" value="{{ $data['settings']['settings_website_title'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Phone Number</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Phone Number" name="settings_website_phone_number" value="{{ $data['settings']['settings_website_phone_number'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group" style="display: none;">
                <label class="col-sm-3 control-label">Terms & Conditions</label>
                <div class="col-sm-9">
                  <textarea class="form-control editor" id="settings_website_terms_conditions" name="settings_website_terms_conditions" rows="6">{{ $data['settings']['settings_website_terms_conditions'] ?? '' }}</textarea>
                </div>
              </div>
              <div class="form-group" style="display: none;">
                <label class="col-sm-3 control-label">Privacy Policy</label>
                <div class="col-sm-9">
                  <textarea class="form-control editor" id="settings_website_privacy_policy" name="settings_website_privacy_policy" rows="6">{{ $data['settings']['settings_website_privacy_policy'] ?? '' }}</textarea>
                </div>
              </div>


            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#model">
                Model (VIP Member) Settings
              </a>
            </h4>
          </div>
          <div id="model" class="panel-collapse collapse">
            <div class="box-body">

              <div class="form-group">
                <label class="col-sm-3 control-label">Subscription Min Price</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Subscription Min Price" name="settings_model_subscription_min_price" value="{{ $data['settings']['settings_model_subscription_min_price'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Subscription Max Price</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Subscription Max Price" name="settings_model_subscription_max_price" value="{{ $data['settings']['settings_model_subscription_max_price'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#customer">
                Customer (Follower) Settings
              </a>
            </h4>
          </div>
          <div id="customer" class="panel-collapse collapse">
            <div class="box-body">

              <div class="form-group">
                <label class="col-sm-3 control-label">New Registration Free Tokens</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Token Amount" name="settings_customer_new_account_free_token" value="{{ $data['settings']['settings_customer_new_account_free_token'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#google">
                Google
              </a>
            </h4>
          </div>
          <div id="google" class="panel-collapse collapse">
            <div class="box-body">

              <div class="form-group">
                <label class="col-sm-3 control-label">reCaptcha Site Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="reCaptcha Site Key" name="settings_google_recaptcha_site_key" value="{{ $data['settings']['settings_google_recaptcha_site_key'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">reCaptcha Secret Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="reCaptcha Secret Key" name="settings_google_recaptcha_secret_key" value="{{ $data['settings']['settings_google_recaptcha_secret_key'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Map Api Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Map Api Key" name="settings_google_map_api_key" value="{{ $data['settings']['settings_google_map_api_key'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#opentok">
                Opentok
              </a>
            </h4>
          </div>
          <div id="opentok" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Opentok Api Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Opentok Api Key" name="settings_opentok_api_key" value="{{ $data['settings']['settings_opentok_api_key'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Opentok Api Secret Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Opentok Api Secret Key" name="settings_opentok_api_secret_key" value="{{ $data['settings']['settings_opentok_api_secret_key'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#pubnub">Pubnub</a>
            </h4>
          </div>
          <div id="pubnub" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Pubnub Publish Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Pubnub Publish Key" name="settings_pubnub_publish_key" value="{{ $data['settings']['settings_pubnub_publish_key'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Pubnub Subscribe Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Pubnub Subscribe Key" name="settings_pubnub_subscribe_key" value="{{ $data['settings']['settings_pubnub_subscribe_key'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#epoch">Epoch</a>
            </h4>
          </div>
          <div id="epoch" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Epoch Payment Mode</label>
                <div class="col-sm-9">
                  <?php
                  $sel1 = $sel2 = '';
                  if(isset($data['settings']['settings_epoch_payment_mode'])) {
                    if($data['settings']['settings_epoch_payment_mode'] == 0) $sel1 = 'selected="selected"';
                    if($data['settings']['settings_epoch_payment_mode'] == 1) $sel2 = 'selected="selected"';
                  }
                  ?>
                  <select class="form-control" name="settings_epoch_payment_mode">
                    <option value="0" {{ $sel1 }}>Test</option>
                    <option value="1" {{ $sel2 }}>Live</option>
                  </select>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#paypal">Paypal</a>
            </h4>
          </div>
          <div id="paypal" class="panel-collapse collapse">
            <div class="box-body">

              <div class="form-group">
                <label class="col-sm-3 control-label">Paypal Status</label>
                <div class="col-sm-9">
                  <select class="form-control" name="settings_paypal_status">
                    <?php
                    $opt1 = $opt2 = '';
                    if(isset($data['settings']['settings_paypal_status'])) {
                      if($data['settings']['settings_paypal_status'] == '0')
                        $opt1 = 'selected="selected"';
                      if($data['settings']['settings_paypal_status'] == '1')
                        $opt2 = 'selected="selected"';
                    }
                    ?>
                    <option value="0" {{ $opt1 }}>Disabled</option>
                    <option value="1" {{ $opt2 }}>Enabled</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Paypal Mode</label>
                <div class="col-sm-9">
                  <select class="form-control" name="settings_paypal_mode">
                    <?php
                    $opt1 = $opt2 = '';
                    if(isset($data['settings']['settings_paypal_mode'])) {
                      if($data['settings']['settings_paypal_mode'] == '0')
                        $opt1 = 'selected="selected"';
                      if($data['settings']['settings_paypal_mode'] == '1')
                        $opt2 = 'selected="selected"';
                    }
                    ?>
                    <option value="0" {{ $opt1 }}>Sandbox</option>
                    <option value="1" {{ $opt2 }}>Live</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Paypal Merchant Email</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Paypal Merchant Email" name="settings_paypal_merchant_email" value="{{ $data['settings']['settings_paypal_merchant_email'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Paypal PDT Token</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Paypal PDT Token" name="settings_paypal_pdt_token" value="{{ $data['settings']['settings_paypal_pdt_token'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#payment">
                Payment Details
              </a>
            </h4>
          </div>
          <div id="payment" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Stripe Secret Key</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Stripe Secret Key" name="settings_payment_stripe_secret_key" value="{{ $data['settings']['settings_payment_stripe_secret_key'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Token To Currency<br><small>(1 Token = )</small></label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Currency Amount" name="settings_payment_token_to_currency" value="{{ $data['settings']['settings_payment_token_to_currency'] ?? '' }}" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Model Earning Deduction Percentage</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Model Earning Deduction Percentage %" name="settings_payment_earning_deduction_percentage" value="{{ $data['settings']['settings_payment_earning_deduction_percentage'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Model Mininmum Payout Amount</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Model Mininmum Payout Amount" name="settings_payment_min_payout_amount" value="{{ $data['settings']['settings_payment_min_payout_amount'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Affiliate Earning Percentage</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Affiliate Earning Percentage %" name="settings_payment_affiliate_earning_percentage" value="{{ $data['settings']['settings_payment_affiliate_earning_percentage'] ?? '' }}" />
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#contact">
                Contact Details
              </a>
            </h4>
          </div>
          <div id="contact" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Contact name" name="settings_contact_name" value="{{ $data['settings']['settings_contact_name'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Phone</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Contact Phone" name="settings_contact_phone" value="{{ $data['settings']['settings_contact_phone'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Email Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Contact Email Address" name="settings_contact_email" value="{{ $data['settings']['settings_contact_email'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Address</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Contact Address" name="settings_contact_address" value="{{ $data['settings']['settings_contact_address'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="City" name="settings_contact_city" value="{{ $data['settings']['settings_contact_city'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Zipcode</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Zipcode" name="settings_contact_zipcode" value="{{ $data['settings']['settings_contact_zipcode'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">State/Region</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="State/Region" name="settings_contact_state" value="{{ $data['settings']['settings_contact_state'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Country</label>
                <div class="col-sm-9">
                  <select class="form-control" name="settings_contact_country_id">
                    <?php
                    $settings_contact_country_id = $data['settings']['settings_contact_country_id'] ?? '';
                    foreach ($data['countries'] as $key => $value) {
                      $sel = '';
                      if($settings_contact_country_id == $value->country_id)
                        $sel = 'selected="selected"';
                      echo '<option value="' . $value->country_id . '" ' . $sel . '>' . $value->name . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Facebook Link</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Facebook Link" name="settings_contact_facebook_link" value="{{ $data['settings']['settings_contact_facebook_link'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Twitter Link</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Twitter Link" name="settings_contact_twitter_link" value="{{ $data['settings']['settings_contact_twitter_link'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Pinterest Link</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Pinterest Link" name="settings_contact_pinterest_link" value="{{ $data['settings']['settings_contact_pinterest_link'] ?? '' }}" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Instagram Link</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" placeholder="Instagram Link" name="settings_contact_instagram_link" value="{{ $data['settings']['settings_contact_instagram_link'] ?? '' }}" />
                </div>
              </div>
            </div>
          </div>
        </div>



        <div class="panel box box-default">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#misc">
                Misc
              </a>
            </h4>
          </div>
          <div id="misc" class="panel-collapse collapse">
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Admin table background color</label>
                <div class="col-sm-9">
                  <div class="input-group my-colorpicker">
                    <input type="text" class="form-control" name="settings_misc_admin_tablebg" value="{{ $data['settings']['settings_misc_admin_tablebg'] ?? '' }}">
                    <div class="input-group-addon"><i></i></div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">Admin table hover background color</label>
                <div class="col-sm-9">
                  <div class="input-group my-colorpicker">
                    <input type="text" class="form-control" name="settings_misc_admin_tablehoverbg" value="{{ $data['settings']['settings_misc_admin_tablehoverbg'] ?? '' }}">
                    <div class="input-group-addon"><i></i></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <button type="submit" class="btn btn-primary pull-right settings_submit">Save</button>
      </div>
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
</script>
<script type="text/javascript">
  function validate_settings() {
    //var settings_paypal_email = $.trim($('input[name="settings_paypal_email"]').val());
    //var settings_paypal_pdt_token = $.trim($('input[name="settings_paypal_pdt_token"]').val());
    var error = 0;
    var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    $(".form-group").removeClass('has-error');
    $(".help-block").remove();
    /*if(settings_paypal_email == "") {
      $("input[name='settings_paypal_email']").closest('.col-sm-9').append("<span class='help-block'>Enter paypal business email</span>");
      $("input[name='settings_paypal_email']").closest('.form-group').addClass('has-error');
      error = 1;
    }
    if(settings_paypal_pdt_token == "") {
      $("input[name='settings_paypal_pdt_token']").closest('.col-sm-9').append("<span class='help-block'>Enter paypal pdt token</span>");
      $("input[name='settings_paypal_pdt_token']").closest('.form-group').addClass('has-error');
      error = 1;
    }*/
    if(error == 1)
      return false;
    else
      return true;
  }
</script>

<script type="text/javascript">
  $(document).ready(function(){

    setTimeout(function(){ $(".content .alert").remove(); }, 3000);

    /*$('input[name="photo"]').change(function(){
        preview_image(this, '.photo');
    });
    remove_photo('.photo', 'input[name="photo"]');*/

    $(".keep_file").each(function(){
      $(this).on('ifUnchecked', function(){
        $(this).closest('.form-group').find('input[type="file"]').removeAttr('disabled');
      });
      $(this).on('ifChecked', function(){
        $(this).closest('.form-group').find('input[type="file"]').attr('disabled', 'disabled');
      });
    });

    $('.timepicker').each(function(){
      $(this).timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: false
      });
    });
    /*$('.timepicker').timepicker({
      showInputs: false
    })*/


    $(".settings_submit").click(function(e){
      e.preventDefault();
      if(validate_settings()) {
        var settings_website_logo = document.getElementsByName("settings_website_logo")[0];
        var settings_website_logo_keep_file = $('input[name="settings_website_logo"]').closest('.form-group').find('input.keep_file:checked').val();
        if(typeof settings_website_logo_keep_file == 'undefined')
          settings_website_logo_keep_file = 0;
        var settings_website_title = $.trim($('input[name="settings_website_title"]').val());
        var settings_website_phone_number = $.trim($('input[name="settings_website_phone_number"]').val());
        var settings_website_terms_conditions = CKEDITOR.instances['settings_website_terms_conditions'].getData();
        var settings_website_privacy_policy = CKEDITOR.instances['settings_website_privacy_policy'].getData();
        var settings_model_subscription_min_price = $.trim($('input[name="settings_model_subscription_min_price"]').val());
        var settings_model_subscription_max_price = $.trim($('input[name="settings_model_subscription_max_price"]').val());
        var settings_customer_new_account_free_token = $.trim($('input[name="settings_customer_new_account_free_token"]').val());
        var settings_google_recaptcha_site_key = $.trim($('input[name="settings_google_recaptcha_site_key"]').val());
        var settings_google_recaptcha_secret_key = $.trim($('input[name="settings_google_recaptcha_secret_key"]').val());
        var settings_google_map_api_key = $.trim($('input[name="settings_google_map_api_key"]').val());
        var settings_opentok_api_key = $.trim($('input[name="settings_opentok_api_key"]').val());
        var settings_opentok_api_secret_key = $.trim($('input[name="settings_opentok_api_secret_key"]').val());
        var settings_pubnub_publish_key = $.trim($('input[name="settings_pubnub_publish_key"]').val());
        var settings_pubnub_subscribe_key = $.trim($('input[name="settings_pubnub_subscribe_key"]').val());
        var settings_epoch_payment_mode = $.trim($('select[name="settings_epoch_payment_mode"]').val());
        var settings_paypal_status = $('select[name="settings_paypal_status"]').val();
        var settings_paypal_mode = $('select[name="settings_paypal_mode"]').val();
        var settings_paypal_merchant_email = $.trim($('input[name="settings_paypal_merchant_email"]').val());
        var settings_paypal_pdt_token = $.trim($('input[name="settings_paypal_pdt_token"]').val());
        var settings_payment_stripe_secret_key = $.trim($('input[name="settings_payment_stripe_secret_key"]').val());
        var settings_payment_token_to_currency = $.trim($('input[name="settings_payment_token_to_currency"]').val());
        var settings_payment_earning_deduction_percentage = $.trim($('input[name="settings_payment_earning_deduction_percentage"]').val());
        var settings_payment_min_payout_amount = $.trim($('input[name="settings_payment_min_payout_amount"]').val());
        var settings_payment_affiliate_earning_percentage = $.trim($('input[name="settings_payment_affiliate_earning_percentage"]').val());

        var settings_contact_name = $.trim($('input[name="settings_contact_name"]').val());
        var settings_contact_phone = $.trim($('input[name="settings_contact_phone"]').val());
        var settings_contact_email = $.trim($('input[name="settings_contact_email"]').val());
        var settings_contact_address = $.trim($('input[name="settings_contact_address"]').val());
        var settings_contact_city = $.trim($('input[name="settings_contact_city"]').val());
        var settings_contact_zipcode = $.trim($('input[name="settings_contact_zipcode"]').val());
        var settings_contact_state = $.trim($('input[name="settings_contact_state"]').val());
        var settings_contact_country_id = $('select[name="settings_contact_country_id"]').val();
        var settings_contact_facebook_link = $.trim($('input[name="settings_contact_facebook_link"]').val());
        var settings_contact_twitter_link = $.trim($('input[name="settings_contact_twitter_link"]').val());
        var settings_contact_pinterest_link = $.trim($('input[name="settings_contact_pinterest_link"]').val());
        var settings_contact_instagram_link = $.trim($('input[name="settings_contact_instagram_link"]').val());

        var settings_misc_admin_tablebg = $.trim($('input[name="settings_misc_admin_tablebg"]').val());
        var settings_misc_admin_tablehoverbg = $.trim($('input[name="settings_misc_admin_tablehoverbg"]').val());
        $(".box .overlay").show();
        var formData = new FormData();
        var data = {
          'settings_website_title': settings_website_title,
          'settings_website_phone_number': settings_website_phone_number,
          'settings_website_terms_conditions': settings_website_terms_conditions,
          'settings_website_privacy_policy': settings_website_privacy_policy,
          'settings_model_subscription_min_price': settings_model_subscription_min_price,
          'settings_model_subscription_max_price': settings_model_subscription_max_price,
          'settings_customer_new_account_free_token': settings_customer_new_account_free_token,
          'settings_google_recaptcha_site_key': settings_google_recaptcha_site_key,
          'settings_google_recaptcha_secret_key': settings_google_recaptcha_secret_key,
          'settings_google_map_api_key': settings_google_map_api_key,
          'settings_opentok_api_key': settings_opentok_api_key,
          'settings_opentok_api_secret_key': settings_opentok_api_secret_key,
          'settings_pubnub_publish_key': settings_pubnub_publish_key,
          'settings_pubnub_subscribe_key': settings_pubnub_subscribe_key,
          'settings_epoch_payment_mode': settings_epoch_payment_mode,
          'settings_paypal_status': settings_paypal_status,
          'settings_paypal_mode': settings_paypal_mode,
          'settings_paypal_merchant_email': settings_paypal_merchant_email,
          'settings_paypal_pdt_token': settings_paypal_pdt_token,
          'settings_payment_stripe_secret_key': settings_payment_stripe_secret_key,
          'settings_payment_token_to_currency': settings_payment_token_to_currency,
          'settings_payment_earning_deduction_percentage': settings_payment_earning_deduction_percentage,
          'settings_payment_min_payout_amount': settings_payment_min_payout_amount,
          'settings_payment_affiliate_earning_percentage': settings_payment_affiliate_earning_percentage,
          'settings_contact_name': settings_contact_name,
          'settings_contact_phone': settings_contact_phone,
          'settings_contact_email': settings_contact_email,
          'settings_contact_address': settings_contact_address,
          'settings_contact_city': settings_contact_city,
          'settings_contact_zipcode': settings_contact_zipcode,
          'settings_contact_state': settings_contact_state,
          'settings_contact_country_id': settings_contact_country_id,
          'settings_contact_facebook_link': settings_contact_facebook_link,
          'settings_contact_twitter_link': settings_contact_twitter_link,
          'settings_contact_pinterest_link': settings_contact_pinterest_link,
          'settings_contact_instagram_link': settings_contact_instagram_link,
          'settings_misc_admin_tablebg': settings_misc_admin_tablebg,
          'settings_misc_admin_tablehoverbg': settings_misc_admin_tablehoverbg,
          'files': []
        };
        if(settings_website_logo_keep_file == 0) {
          if(settings_website_logo.value != '') {
            settings_website_logo = settings_website_logo.files;
            formData.append('settings_website_logo', settings_website_logo[0]);
          } else {
            formData.append('settings_website_logo', '');
          }
          data.files.push({'field_name': 'settings_website_logo'});
        }
        formData.append('data', JSON.stringify(data));
        formData.append('_token', prop.csrf_token);
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: "<?php echo url('adminajax/settings'); ?>",
          data: formData,
          processData: false,
          contentType: false,
          success: function(data) {
            window.location.href = '<?php echo url('admin/settings?updated'); ?>';
            /*$(".box .overlay").hide();
            $(".content").prepend('<div class="alert alert-success">Data successfully updated.</div>');
            setTimeout(function(){ $(".content .alert").remove(); }, 3000);*/
          }
        });

      }
    });


  });
</script>

<script>
  $(function () {


    $(".editor").each(function(){
      var eid = $(this).attr('id');
      var editor = CKEDITOR.replace( eid, {
        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
        filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
        filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
        filebrowserWindowWidth: '1000',
        filebrowserWindowHeight: '700'
      });
      CKFinder.setupCKEditor( editor );
    });

    /*var editor = CKEDITOR.replace( 'editor1', {
      format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
      filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
      filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
      filebrowserWindowWidth: '1000',
      filebrowserWindowHeight: '700'
    });
    CKFinder.setupCKEditor( editor );*/

  })
</script>

@endpush
