<div class="storeWrap">

    <div class="pricing pricing-palden">
        <?php
        foreach ($meta_data['coin_price_plans'] as $key => $value) {
            $featured = 0;
            if($key == 1) $featured = 1;
        ?>
        <div class="pricing-item {{ ($key+1)%2 == 0 ? 'pricing__item--featured' : '' }}">
            <div class="pricing-deco">
                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
              <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729
c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
              <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729
c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
              <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716
H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
              <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428
c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
            </svg>
                <div class="pricing-price"><span class="pricing-currency">£</span>{{ ($value->price + 0) }}
                    <!-- <span class="pricing-period">/ mo</span> -->
                </div>
                <h3 class="pricing-title">{{ $value->title }}</h3>
            </div>
            <ul class="pricing-feature-list">
                <li class="pricing-feature">{!! nl2br($value->info) !!}</li>
            </ul>
            <button class="pricing-action buy_coins" coin_price_plan_id="{{ $value->id }}" pay_title="{{ $value->title }}" pay_price="{{ $value->price }}">Buy</button>
        </div>
        <?php } ?>
    </div>

</div>


@include('user.includes.follower._partials.buy_coins_modals')


@push('script')

<script type="text/javascript">

    $(document).ready(function(){

      $(document).on('click', '.buy_coins', function(){
        var title = $(this).attr('pay_title');
        var price = $(this).attr('pay_price');
        var coin_price_plan_id = $(this).attr('coin_price_plan_id');
        var desc = title + ' : £' + price;
        $("#buyCoinsModal .ajax_response").html('');
        $("#buyCoinsModal .payment_desc").html(desc);
        $("#buyCoinsModal .pay_buy_coins").attr('coin_price_plan_id', coin_price_plan_id);
        $("#buyCoinsModal").modal({
          backdrop: 'static',
          keyboard: false
        });
      });

      $(document).on('click', '.pay_buy_coins', function(){
        var coin_price_plan_id = $(this).attr('coin_price_plan_id');
        var card_number = $.trim($('#buyCoinsModal input[name="card_number"]').val());
        var exp_month = $('#buyCoinsModal select[name="exp_month"]').val();
        var exp_year = $('#buyCoinsModal select[name="exp_year"]').val();
        var card_cvv = $.trim($('#buyCoinsModal input[name="card_cvv"]').val());
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'pay_buy_coins');
        data.append('coin_price_plan_id', coin_price_plan_id);
        data.append('card_number', card_number);
        data.append('exp_month', exp_month);
        data.append('exp_year', exp_year);
        data.append('card_cvv', card_cvv);
        data.append('_token', prop.csrf_token);
        $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
            $(".mw_loader").hide();
            if(data.success == '1') {
              $("#buyCoinsModal .ajax_response").html('<p class="text-success">' + data.message + '</p>');
            } else {
              $("#buyCoinsModal .ajax_response").html('<p class="text-danger">' + data.message + '</p>');
            }
          }
        });
      });

  });
</script>

@endpush
