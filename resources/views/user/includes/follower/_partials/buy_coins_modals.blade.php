<div class="modal fade" id="buyCoinsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Buy Coins</h4>
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

	            <div class="ajax_response"><!-- <p class="text-success">payment succnfi dkfndikf dfd</p> --></div>

            	<a href="javascript:;" class="commonBtn pay_buy_coins">Pay</a>
							<a href="javascript:;" onclick="location.reload();" class="commonBtn">Done</a>
            </div>
        </div>
    </div>
</div>


