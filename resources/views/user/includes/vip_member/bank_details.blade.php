<?php
$countries = \App\Country::orderBy('name')->get();
$countries2 = [];
foreach ($countries as $key => $value) {
  $countries2[$value->country_id] = $value;
}
?>
<div class="bank_details_form">

<div class="from-wrap-page">
  <h4>Bank Details</h4>
  <div class="form-group from-input-wrap">
      <label for="">Country</label>
      <select name="bank_country_id" id="" class="selectMd-2">
          <?php
          foreach ($countries as $key => $value) {
              $sel = '';
              if(isset($user_data['meta_data']['bank_country_id']) && $user_data['meta_data']['bank_country_id'] == $value->country_id)
                  $sel = 'selected="selected"';
              echo '<option value="' . $value->country_id . '" ' . $sel . '>' . $value->name . '</option>';
          }
          ?>
      </select>
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Account Name</label>
      <input type="text" name="bank_account_name" id="" class="input-3" placeholder="Account Name" value="{{ $user_data['meta_data']['bank_account_name'] }}" />
  </div>
  <div class="form-group formInfo mt-3">
      <p><span><i class="fas fa-info-circle"></i></span> Please enter your account name here. This will be the name you used when you set up your bank account and can usually be seen on your bank card. For example it might say Miss Smith, Miss S Smith or Susan Smith. Due to recent banking changes the account name must match exactly or your payment may be rejected when we try to make your payment.</p>
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Sort code</label>
      <input type="text" name="bank_account_sort_code" id="" class="input-3" placeholder="00-00-00" value="{{ $user_data['meta_data']['bank_account_sort_code'] }}" />
  </div>
  <div class="form-group formInfo mt-3">
      <p><span><i class="fas fa-info-circle"></i></span> 6 digit code found on the front of your bank card - should be in the format 00-00-00 - leave blank if you have an account outside the UK</p>
  </div>
  <div class="form-group from-input-wrap">
      <label for="">Account Number</label>
      <input type="text" name="bank_account_number" id="" class="input-3" placeholder="00000000" value="{{ $user_data['meta_data']['bank_account_number'] }}" />
  </div>
  <div class="form-group formInfo mt-3">
      <p><span><i class="fas fa-info-circle"></i></span> This should be an 8 digit number on the front of your card - leave blank if you have an account outside the UK</p>
  </div>
  <button type="button" class="commonBtn bank_details_submit">Update</button>
  <div class="form-group formInfo mt-3">
    <p><span><i class="fas fa-info-circle"></i></span> It is your personal responsibility to ensure you enter the correct banking details. Please check your details are correct before clicking "update". Fan-space cannot be held responsible for any errors regarding incorrect entry of banking details. </p>
  </div>
</div>

</div>


@push('script')

<script type="text/javascript">
    $(document).ready(function(){



    });
</script>

@endpush
