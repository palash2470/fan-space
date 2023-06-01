<?php
$prices = $subscription_modal_data['vip_member_price_data'];
?>
<div class="modal fade" id="subscribeModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Subscription Plans</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body proUserPlan">
                <div class='pricing pricing-palden'>
                    <div class='pricing-item'>
                        <div class='pricing-deco'>
                            <svg class='pricing-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg'
                                y='0px'>
                          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                          <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
                        </svg>
                            <div class='pricing-price'><span class='pricing-currency'>£</span><?php
                            $price = $prices['subscription_price_1m'];
                            if($prices['subscription_price_1m_discounted'] != '' && $prices['subscription_price_1m_discounted_todate'] != '' && $prices['subscription_price_1m_discounted_todate'] > time()) {
                                $price = $prices['subscription_price_1m_discounted'];
                            }
                            echo $price;
                            ?>
                                <!-- <span class='pricing-period'>1 Month</span> -->
                            </div>
                            <!-- <h3 class='pricing-title'>Freelance</h3> -->
                            <h3 class='pricing-title'>1 Month</h3>
                        </div>
                        <!-- <ul class='pricing-feature-list'>
                            <li class='pricing-feature'>1 GB of space</li>
                            <li class='pricing-feature'>Support at $25/hour</li>
                            <li class='pricing-feature'>Limited cloud access</li>
                        </ul> -->
                        <button class='pricing-action buy_subscription' duration="1m" price="{{ $price }}">Subscribe</button>
                    </div>
                    <div class='pricing-item pricing__item--featured'>
                        <div class='pricing-deco'>
                            <svg class='pricing-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg'
                                y='0px'>
                          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                          <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
                        </svg>
                            <div class='pricing-price'><span class='pricing-currency'>£</span><?php
                            $price = $prices['subscription_price_3m'];
                            if($prices['subscription_price_3m_discounted'] != '' && $prices['subscription_price_3m_discounted_todate'] != '' && $prices['subscription_price_3m_discounted_todate'] > time()) {
                                $price = $prices['subscription_price_3m_discounted'];
                            }
                            echo $price;
                            ?>
                                <!-- <span class='pricing-period'>3 Month</span> -->
                            </div>
                            <!-- <h3 class='pricing-title'>Business</h3> -->
                            <h3 class='pricing-title'>3 Months</h3>
                        </div>
                        <!-- <ul class='pricing-feature-list'>
                            <li class='pricing-feature'>5 GB of space</li>
                            <li class='pricing-feature'>Support at $5/hour</li>
                            <li class='pricing-feature'>Full cloud access</li>
                        </ul> -->
                        <button class='pricing-action buy_subscription' duration="3m" price="{{ $price }}">Subscribe</button>
                    </div>
                    <div class='pricing-item'>
                        <div class='pricing-deco'>
                            <svg class='pricing-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg'
                                y='0px'>
                          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                          <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
                        </svg>
                            <div class='pricing-price'><span class='pricing-currency'>£</span><?php
                            $price = $prices['subscription_price_6m'];
                            if($prices['subscription_price_6m_discounted'] != '' && $prices['subscription_price_6m_discounted_todate'] != '' && $prices['subscription_price_6m_discounted_todate'] > time()) {
                                $price = $prices['subscription_price_6m_discounted'];
                            }
                            echo $price;
                            ?>
                                <!-- <span class='pricing-period'>6 Month</span> -->
                            </div>
                            <!-- <h3 class='pricing-title'>Enterprise</h3> -->
                            <h3 class='pricing-title'>6 Months</h3>
                        </div>
                        <!-- <ul class='pricing-feature-list'>
                            <li class='pricing-feature'>10 GB of space</li>
                            <li class='pricing-feature'>Support at $5/hour</li>
                            <li class='pricing-feature'>Full cloud access</li>
                        </ul> -->
                        <button class='pricing-action buy_subscription' duration="6m" price="{{ $price }}">Subscribe</button>
                    </div>
                    <div class='pricing-item'>
                        <div class='pricing-deco'>
                            <svg class='pricing-deco-img' enable-background='new 0 0 300 100' height='100px' id='Layer_1' preserveAspectRatio='none' version='1.1' viewBox='0 0 300 100' width='300px' x='0px' xml:space='preserve' xmlns:xlink='http://www.w3.org/1999/xlink' xmlns='http://www.w3.org/2000/svg'
                                y='0px'>
                          <path class='deco-layer deco-layer--1' d='M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--2' d='M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z' fill='#FFFFFF' opacity='0.6'></path>
                          <path class='deco-layer deco-layer--3' d='M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z' fill='#FFFFFF' opacity='0.7'></path>
                          <path class='deco-layer deco-layer--4' d='M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z' fill='#FFFFFF'></path>
                        </svg>
                            <div class='pricing-price'><span class='pricing-currency'>£</span><?php
                            $price = $prices['subscription_price_12m'];
                            if($prices['subscription_price_12m_discounted'] != '' && $prices['subscription_price_12m_discounted_todate'] != '' && $prices['subscription_price_12m_discounted_todate'] > time()) {
                                $price = $prices['subscription_price_12m_discounted'];
                            }
                            echo $price;
                            ?>
                                <!-- <span class='pricing-period'>12 Month</span> -->
                            </div>
                            <!-- <h3 class='pricing-title'>Enterprise</h3> -->
                            <h3 class='pricing-title'>12 Months</h3>
                        </div>
                        <!-- <ul class='pricing-feature-list'>
                            <li class='pricing-feature'>10 GB of space</li>
                            <li class='pricing-feature'>Support at $5/hour</li>
                            <li class='pricing-feature'>Full cloud access</li>
                        </ul> -->
                        <button class='pricing-action buy_subscription' duration="12m" price="{{ $price }}">Subscribe</button>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div> -->

        </div>
    </div>
</div>




<div class="modal fade" id="buySubscriptionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Buy Subscription</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

              <h5 class="payment_desc"></h5>

              <div class="payment_option">
                <div class="from-wrap-page">
                  <div class="row">
                      <div class="col-md-12 col-12">
                          <div class="form-group from-input-wrap">
                              <label for="">Card Number</label>
                              <input type="text" name="card_number" id="" class="input-3" placeholder="Card Number" value="" autocomplete="off" />
                          </div>
                      </div>
                      <div class="col-md-4 col-12">
                          <div class="form-group">
                              <label>Expiry Month</label>
                              <select class="selectMd-2" name="exp_month">
                                  <option value="01">January</option>
                                  <option value="02">February</option>
                                  <option value="03">March</option>
                                  <option value="04">April</option>
                                  <option value="05">May</option>
                                  <option value="06">June</option>
                                  <option value="07">July</option>
                                  <option value="08">August</option>
                                  <option value="09">September</option>
                                  <option value="10">October</option>
                                  <option value="11">November</option>
                                  <option value="12">December</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4 col-12">
                          <div class="form-group">
                              <label>Expiry Year</label>
                              <select class="selectMd-2" name="exp_year">
                                  <?php
                                  for($i = 0; $i < 10; $i++) {
                                      $val = date('Y') + $i;
                                      echo '<option value="' . $val . '">' . $val . '</option>';
                                  }
                                  ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4 col-12">
                          <div class="form-group from-input-wrap">
                              <label>Card CVV</label>
                              <input type="password" name="card_cvv" placeholder="Card CVV" class="input-3" autocomplete="off" value="">
                          </div>
                      </div>
                  </div>
              </div>
              <input type="hidden" name="user_id" value="{{ $subscription_modal_data['vip_member']->id }}" />
              <input type="hidden" name="duration" value="" />

              <div class="ajax_response"><!-- <p class="text-success">payment succnfi dkfndikf dfd</p> --></div>

              <a href="javascript:;" class="commonBtn pay_buy_subscription">Pay</a>
              <a href="javascript:;" onclick="location.reload();" class="commonBtn">Done</a>
            </div>
        </div>
    </div>
</div>
</div>
