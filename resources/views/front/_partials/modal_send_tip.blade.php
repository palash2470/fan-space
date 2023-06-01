<div class="modal fade" id="sendTipModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Send Tip</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

              <h5></h5>
              <div class="from-wrap-page">
                <div class="row">
                  <div class="col-md-12 col-12">
                      <div class="form-group from-input-wrap">
                          <label for="">Coin Amount</label>
                          <input type="text" name="token_coin" id="" class="input-3" placeholder="Coin Amount" value="" autocomplete="off" />
                      </div>
                  </div>
                  <div class="col-md-12 col-12">
                      <div class="form-group from-input-wrap">
                          <label for="">Message</label>
                          {{-- <input type="text" name="message" id="" class="input-3" placeholder="Message" value="" autocomplete="off" /> --}}
                          <textArea name="message" class="form-control" rows="4" placeholder="Message" autocomplete="off" style="resize:none;"></textarea>
                      </div>
                  </div>
                </div>
              </div>
                
              <input type="hidden" name="post_id" value="" />

              <div class="ajax_response"><!-- <p class="text-success">payment succnfi dkfndikf dfd</p> --></div>

              <a href="javascript:;" class="commonBtn pay_send_tip">Send</a>
              <a href="javascript:;" class="commonBtn" data-dismiss="modal">Done</a>
            
        </div>
    </div>
</div>
</div>