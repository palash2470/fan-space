<?php
use App\User;
use App\User_earning;
use App\User_payout;
$tab = $meta_data['tab'];
$global_settings = $meta_data['global_settings'];
//dd($global_settings);
?>
<div class="wallet w-100">
    <div class="walletNav w-100">
        <ul id="tabs" class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a id111="tab-A" href111="#pane-A" href="{{ url('dashboard/wallet') }}" class="nav-link {{ $tab == '' ? 'active' : '' }}" data-toggle111="tab" role111="tab">Wallet</a>
            </li>
            <li class="nav-item">
                <a id111="tab-B" href111="#pane-B" href="{{ url('dashboard/wallet?tab=withdrawls') }}" class="nav-link {{ $tab == 'withdrawls' ? 'active' : '' }}" data-toggle111="tab" role111="tab">Withdrawls</a>
            </li>
            <li class="nav-item">
                <a id111="tab-C" href111="#pane-C" href="{{ url('dashboard/wallet?tab=earnings') }}" class="nav-link {{ $tab == 'earnings' ? 'active' : '' }}" data-toggle111="tab" role111="tab">Earnings</a>
            </li>
        </ul>
    </div>
    <div class="walletBody w-100">
        <div id="content" class="tab-content" role="tablist">
            <div id="pane-A" class="card tab-pane {{ $tab == '' ? 'fade show active' : '' }}" role="tabpanel" aria-labelledby="tab-A">
                <div class="card-header" role="tab" id="heading-A">
                    <h5 class="mb-0">
                        <!-- Note: `data-parent` removed from here -->
                        <a data-toggle11="collapse" href="{{ url('dashboard/wallet') }}" aria-expanded11="true" aria-controls11="collapse-A">
                            Wallet
                        </a>
                    </h5>
                </div>
                <?php if($tab == '') { ?>
                <!-- Note: New place of `data-parent` -->
                <div id="collapse-A" class="collapse show" data-parent="#content" role="tabpanel" aria-labelledby="heading-A">
                    <div class="card-body">
                        <div class="walletInner">
                            <div class="walletInnerTop">
                              <?php
                              /*$wallet_coins = 0;
                              $wallet_coins += User_earning::where('user_id', $user_data['id'])->sum('token_coins');
                              $wallet_coins += User_earning::where('referral_user_id', $user_data['id'])->sum('referral_token_coins');
                              $wallet_coins -= User_payout::where('user_id', $user_data['id'])->sum('token_coins');*/
                              $wallet = User::wallet(['user_id' => $user_data['id']]);
                              $wallet_coins = $wallet['balance'];
                              ?>
                                <h4>Wallet Balance <span>{{ $wallet_coins }}</span> {{ ($wallet_coins == 1 ? 'Coin' : 'Coins') }}</h4><!-- <span class="reload"><i class="ti-reload"></i></span> -->
                            </div>
                            <!-- <div class="walletInnerMid">
                                <ul class="d-flex justify-content-center align-items-center">
                                    <li><a href="#">50 Coins</a></li>
                                    <li><a href="#">200 Coins</a></li>
                                    <li><a href="#">500 Coins</a></li>
                                    <li>
                                        <input type="text" name="" id="" class="customInput" placeholder="choose Coins">
                                    </li>
                                </ul>
                            </div> -->
                            <div class="walletInnerMid d-flex justify-content-center w-100">
                                <a href="{{ url('dashboard/wallet?tab=withdrawls') }}" class="allBtn">Withdraw Balance</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
              <div id="pane-B" class="card tab-pane {{ $tab == 'withdrawls' ? 'fade show active' : '' }}" role="tabpanel" aria-labelledby="tab-B">
                  <div class="card-header" role="tab" id="heading-B">
                      <h5 class="mb-0">
                          <a class="collapsed" data-toggle11="collapse" href="{{ url('dashboard/wallet?tab=withdrawls') }}" aria-expanded11="false" aria-controls11="collapse-B">
                              Withdrawls
                          </a>
                      </h5>
                  </div>
                  <?php if($tab == 'withdrawls') { ?>
                  <div id="collapse-B" class="collapse {{ $tab == 'withdrawls' ? 'show' : '' }}" data-parent="#content" role="tabpanel" aria-labelledby="heading-B">
                      <div class="card-body">
                        <h5>Request Payouts</h5>
                        <?php $min_payout_req = $global_settings['settings_payment_min_payout_amount']; ?>
                        <p>Minimum payout request {{ $min_payout_req }} {{ $min_payout_req == 1 ? 'coin' : 'coins' }}, (1 coin = £{{ $global_settings['settings_payment_token_to_currency'] }})</p>
                        <div class="payout_req_form m-t-10 m-b-20">
                          <div class="from-wrap-page">
                            <div class="row">
                              <div class="col-md-6 col-12">
                                <div class="form-group from-input-wrap">
                                    <label for="">Enter Coin Amount</label>
                                    <input type="text" name="coin_amount" id="" class="input-3" placeholder="Coin Amount" value="" />
                                </div>
                              </div>
                            </div>
                            <button type="button" class="commonBtn payout_req_submit">Submit Request</button>
                          </div>
                          <div class="ajax_response"></div>
                        </div>
                          <div class="table-responsive">
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th scope="col">Coins</th>
                                          <th scope="col">Price Amount</th>
                                          <th scope="col">Paid</th>
                                          <th scope="col">Transaction Id</th>
                                          <th scope="col">Date</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                      foreach ($meta_data['payouts'] as $key => $value) {
                                        $paid = '';
                                        if($value->status == '1') {
                                          $paid = '<i class="fas fa-check"></i>';
                                        }
                                        echo '<tr>
                                            <td>' . $value->token_coins . '</td>
                                            <td>£' . $value->price_amount . '</td>
                                            <td>' . $paid . '</td>
                                            <td>' . $value->transaction_id . '</td>
                                            <td>' . date('d-m-Y', strtotime($value->created_at)) . '</td>
                                        </tr>';
                                      }
                                      ?>
                                  </tbody>
                              </table>
                          </div>
                          <div class="samplePage d-flex justify-content-center">
                              {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
                              <?php
                              /*
                              {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}
                              */
                              ?>
                          </div>
                      </div>
                  </div>
                  <?php } ?>
              </div>
            
            
              <div id="pane-C" class="card tab-pane {{ $tab == 'earnings' ? 'fade show active' : '' }}" role="tabpanel" aria-labelledby="tab-C">
                  <div class="card-header" role="tab" id="heading-C">
                      <h5 class="mb-0">
                          <a class="collapsed" data-toggle11="collapse" href="{{ url('dashboard/wallet?tab=earnings') }}" aria-expanded11="false" aria-controls11="collapse-C">
                              Earnings
                          </a>
                      </h5>
                  </div>
                  <?php if($tab == 'earnings') { ?>
                  <div id="collapse-C" class="collapse {{ $tab == 'earnings' ? 'show' : '' }}"" data-parent="#content" role="tabpanel" aria-labelledby="heading-C">
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th scope="col">Coins</th>
                                          <th scope="col">Type</th>
                                          <th scope="col">Referral</th>
                                          <th scope="col">Purchased By</th>
                                          <th scope="col">Date</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    foreach ($meta_data['user_earnings'] as $key => $value) {
                                      $coins = $referral = '';
                                      if($value->user_id == $user_data['id']) {
                                        $coins = $value->token_coins;
                                      }
                                      if($value->referral_user_id == $user_data['id']) {
                                        $coins = $value->referral_token_coins;
                                        $referral = '<i class="fas fa-check"></i>';
                                      }
                                      $type = '';
                                      $paid_user_name= '';
                                      $paid_user_image = url('/public/front/images/user-placeholder.jpg');
                                      if($value->post_paid_users_id != '') 
                                      {
                                        $type = 'Paid post';
                                        $user_details = User_earning::getPaidBy('post_paid_users',$value->post_paid_users_id,'user_id');
                                        if(count($user_details) > 0){
                                            $paid_user_name = @$user_details[0]->first_name.' '.@$user_details[0]->last_name;
                                            $paid_user_image = url('public/uploads/profile_photo/' . $user_details[0]->profile_photo);
                                        }
                                      }
                                      if($value->post_tips_id != ''){
                                        $type = 'Post tips';
                                        $user_details = User_earning::getPaidBy('post_tips',$value->post_tips_id,'tipper_id');
                                        if(count($user_details) > 0){
                                            $paid_user_name = @$user_details[0]->first_name.' '.@$user_details[0]->last_name;
                                            $paid_user_image = url('public/uploads/profile_photo/' . $user_details[0]->profile_photo);
                                        }
                                      } 
                                      if($value->order_id != ''){
                                        $type = 'Store order';
                                        $user_details = User_earning::getPaidBy('orders',$value->order_id,'user_id');
                                        //echo "<pre>";print_r($user_details);die;
                                        if(count($user_details) > 0){
                                            $paid_user_name = @$user_details[0]->first_name.' '.@$user_details[0]->last_name;
                                            $paid_user_image = url('public/uploads/profile_photo/' . $user_details[0]->profile_photo);
                                        }
                                      } 
                                      if($value->live_session_tips_id != ''){
                                        $type = 'Live session';
                                        $user_details = User_earning::getPaidBy('live_session_tips',$value->live_session_tips_id,'tipper_id');
                                        if(count($user_details) > 0){
                                            $paid_user_name = @$user_details[0]->first_name.' '.@$user_details[0]->last_name;
                                            $paid_user_image = url('public/uploads/profile_photo/' . @$user_details[0]->profile_photo);
                                        }
                                      } 
                                      if($value->private_chat_id ){
                                        $type = 'Private chat session';
                                        $user_details = User_earning::getPaidBy('private_chat',$value->private_chat_id,'follower_id');
                                        if(count($user_details) > 0){
                                            $paid_user_name = @$user_details[0]->first_name.' '.@$user_details[0]->last_name;
                                            $paid_user_image = url('public/uploads/profile_photo/' . $user_details[0]->profile_photo);
                                        }
                                      } 
                                      echo '<tr>
                                          <td>' . $coins . '</td>
                                          <td>' . $type . '</td>
                                          <td>' . $referral . '</td>
                                          <td>
                                                <div class="dashboard-user earning-paid-by d-flex align-items-center">
                                                    <div class="dashboard-user-img">
                                                        <span><img src="'.@$paid_user_image.'" alt=""></span>
                                                    </div>
                                                    <div class="dashboard-user-details">
                                                        <h3>'.@$paid_user_name.'</h3>
                                                    </div>
                                                </div>
                                            </td>
                                          <td>' . date('d-m-Y', strtotime($value->created_at)) . '</td>
                                      </tr>';
                                    }
                                    ?>
                                  </tbody>
                              </table>
                          </div>
                          <div class="samplePage d-flex justify-content-center">
                              {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
                              <?php
                              /*
                              {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}
                              */
                              ?>
                          </div>
                      </div>
                  </div>
                  <?php } ?>
              </div>
            
        </div>
    </div>
</div>
@push('script')
<script type="text/javascript">
  $(document).ready(function(){
      
    onlyNumbers('.payout_req_form input[name="coin_amount"]');
    $(document).on('click', '.payout_req_submit', function(){
      var coin_amount = $.trim($('.payout_req_form input[name="coin_amount"]').val());
      $(".payout_req_form .ajax_response").html('');
      $(".mw_loader").show();
      var data = new FormData();
      data.append('action', 'payout_request');
      data.append('coin_amount', coin_amount);
      data.append('_token', prop.csrf_token);
      $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
          if(data.success == 1) {
            location.reload();
          } else {
            $(".mw_loader").hide();
            $(".payout_req_form .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
          }
        }
      });
    });
  });
</script>
@endpush
