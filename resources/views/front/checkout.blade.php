@extends('layouts.front')
@section('content')

<?php
use App\Cart;
$countries = \App\Country::orderBy('name')->get();
$countries2 = [];
foreach ($countries as $key => $value) {
  $countries2[$value->country_id] = $value;
}
$cart = Cart::get_cart();
//dd($cart);
//dd($user_data);

$location = $user_data['meta_data']['address_line_1'] ?? '';
if(isset($user_data['meta_data']['country_id']))
  $location .= ', ' . $countries2[$user_data['meta_data']['country_id']]->iso_code_2;
if(isset($user_data['meta_data']['zip_code']))
  $location .= ' ' . $user_data['meta_data']['zip_code'];

$wallet_coins = $user_data['meta_data']['wallet_coins'] ?? 0;
?>

<section class="cartArea checkout-container checkout_form">
    <div class="container">

      @if(Session::has('success'))
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          {!! Session::get('success') !!}
        </div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
          {!! Session::get('error') !!}
        </div>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <ul class="checkout-steps">
                    <li>
                        <h2 class="step-title">Billing details</h2>

                        <form action="#" id="checkout-form">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>First name
                                            <abbr class="required" title="required">*</abbr>
                                        </label>
                                        <input type="text" class="form-control" name="billing_first_name" value="{{ $user_data['first_name'] }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Last name
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" class="form-control" name="billing_last_name" value="{{ $user_data['last_name'] }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Company name (optional)</label>
                                <input type="text" class="form-control" name="billing_company_name" value="">
                            </div>

                            <div class="form-group">
                                <label>Street address
                                    <abbr class="required" title="required">*</abbr></label>
                                <input type="text" class="form-control" google_location_search_callback="checkout_billing_google_location_search" name="billing_location" value="{{ $location }}">
                                <input type="hidden" name="billing_address_line_1" value="{{ $user_data['meta_data']['address_line_1'] ?? '' }}">
                                <input type="hidden" name="billing_country_code" value="{{ isset($user_data['meta_data']['country_id']) ? $countries2[$user_data['meta_data']['country_id']]->iso_code_2 : '' }}">
                                <input type="hidden" name="billing_zip_code" value="{{ $user_data['meta_data']['zip_code'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>Phone <abbr class="required" title="required">*</abbr></label>
                                <input type="tel" class="form-control" name="billing_phone" value="{{ $user_data['phone'] }}">
                            </div>

                            <div class="form-group">
                                <label>Email address
                                    <abbr class="required" title="required">*</abbr></label>
                                <input type="email" class="form-control" name="billing_email" value="{{ $user_data['email'] }}">
                            </div>

                            <!-- <div class="form-group mb-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create-account">
                                    <label class="custom-control-label" data-toggle="collapse" data-target="#collapseThree" aria-controls="collapseThree" for="create-account">Create an
                                        account?</label>
                                </div>
                            </div>

                            <div id="collapseThree" class="collapse">
                                <div class="form-group">
                                    <label>Create account password
                                        <abbr class="required" title="required">*</abbr></label>
                                    <input type="password" placeholder="Password" class="form-control" required="">
                                </div>
                            </div> -->

                            <div class="form-group">
                                <div class="custom-control custom-checkbox mt-0">
                                    <input type="checkbox" class="custom-control-input" id="different-shipping" name="ship_different">
                                    <label class="custom-control-label" data-toggle="collapse" data-target="#collapseFour" aria-controls="collapseFour" for="different-shipping">Ship to a
                                        different
                                        address?</label>


                                </div>
                            </div>

                            <div id="collapseFour" class="collapse">
                                <div class="shipping-info">
                                  <h4>Shipping Address</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>First name <abbr class="required" title="required">*</abbr></label>
                                                <input type="text" class="form-control" name="shipping_first_name" value="{{ $user_data['first_name'] }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Last name <abbr class="required" title="required">*</abbr></label>
                                                <input type="text" class="form-control" name="shipping_last_name" value="{{ $user_data['last_name'] }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Company name (optional)</label>
                                        <input type="text" class="form-control" name="shipping_company_name" value="">
                                    </div>

                                    <div class="form-group">
                                        <label>Street address
                                            <abbr class="required" title="required">*</abbr></label>
                                        <input type="text" class="form-control" google_location_search_callback="checkout_shipping_google_location_search" name="shipping_location" value="{{ $location }}">
                                        <input type="hidden" name="shipping_address_line_1" value="{{ $user_data['meta_data']['address_line_1'] ?? '' }}">
                                        <input type="hidden" name="shipping_country_code" value="{{ isset($user_data['meta_data']['country_id']) ? $countries2[$user_data['meta_data']['country_id']]->iso_code_2 : '' }}">
                                        <input type="hidden" name="shipping_zip_code" value="{{ $user_data['meta_data']['zip_code'] ?? '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="order-comments">Order notes (optional)</label>
                                <textarea class="form-control" placeholder="Notes about your order, e.g. special notes for delivery." name="order_notes"></textarea>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
            <!-- End .col-lg-8 -->

            <div class="col-lg-5">
                <div class="order-summary">
                    <h3>YOUR ORDER</h3>

                    <?php
                    $cart_items = [];
                    //dd($cart['items']);
                    foreach ($cart['items'] as $key => $value) {
                      if(!isset($cart_items[$value->user_id]))
                        $cart_items[$value->user_id] = [];
                      $cart_items[$value->user_id][] = $value;
                    }
                    $total = 0;
                    foreach ($cart_items as $key => $value) {
                      $subtotal = 0;
                      ?>
                      <table class="table table-mini-cart">
                          <thead><tr><th colspan="2">Order to <a href="{{ url('u/' . $value[0]->seller_username) }}">{{ $value[0]->seller_display_name }}</a></th></tr></thead>
                            <tbody>
                      <?php 
                      foreach ($value as $k => $v) {
                        $subtotal += $v->item_total;
                      ?>
                        <tr>
                          <td class="product-col"><h3 class="product-title">{{ $v->title }} × <span class="product-qty">{{ $v->qty }}</span></h3></td>
                          <td class="price-col"><span>{{ $v->item_total }} Coin(s)</span></td>
                        </tr>
                      <?php } $total += $subtotal; ?>
                      </tbody>
                        <tfoot>
                          <tr class="cart-subtotal">
                            <td><h4>Subtotal</h4></td>
                            <td class="price-col"><span>{{ $subtotal }} coin(s)</span></td>
                          </tr>
                          <!-- <tr class="order-total">
                            <td><h4>Total</h4></td>
                            <td><b class="total-price"><span>$1,603.80</span></b></td>
                          </tr> -->
                        </tfoot>
                    </table>
                    <?php } ?>

                    <table class="table table-mini-cart">
                      <tfoot>
                        <tr class="order-total">
                          <td><h4>Total</h4></td>
                          <td><b class="total-price"><span>{{ $total }} coin(s)</span></b></td>
                        </tr>
                      </tfoot>
                    </table>

                    <!-- <table class="table table-mini-cart">
                        <thead>
                            <tr>
                                <th colspan="2">Product</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="product-col">
                                    <h3 class="product-title">
                                        Circled Ultimate 3D Speaker ×
                                        <span class="product-qty">4</span>
                                    </h3>
                                </td>

                                <td class="price-col">
                                    <span>$1,040.00</span>
                                </td>
                            </tr>

                            <tr>
                                <td class="product-col">
                                    <h3 class="product-title">
                                        Fashion Computer Bag ×
                                        <span class="product-qty">2</span>
                                    </h3>
                                </td>

                                <td class="price-col">
                                    <span>$418.00</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="product-col">
                                    <h3 class="product-title">
                                        Delivery Charges
                                        <span class="product-qty">$80.00</span>
                                    </h3>
                                </td>

                                <td class="price-col">
                                    <span>$418.00</span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="cart-subtotal">
                                <td>
                                    <h4>Subtotal</h4>
                                </td>

                                <td class="price-col">
                                    <span>$1,458.00</span>
                                </td>
                            </tr>

                            <tr class="order-total">
                                <td>
                                    <h4>Total</h4>
                                </td>
                                <td>
                                    <b class="total-price"><span>$1,603.80</span></b>
                                </td>
                            </tr>
                        </tfoot>
                    </table> -->

                    <?php if($total > $wallet_coins) { ?>
                    <div class="payment-methods">
                        <!-- <h4 class="">Payment methods</h4> -->
                        <div class="info-box with-icon p-0">
                            <p>
                                Sorry, you don't have sufficient coin balance in your wallet. Please buy coins to place {{ $cart_items > 1 ? 'these orders' : 'this order' }}.
                            </p>
                        </div>
                    </div>
                    <?php } else { ?>
                      <button type="submit" class="btn btn-dark btn-place-order place_order">Place order</button>
                    <?php } ?>

                    
                </div>
                <!-- End .cart-summary -->
            </div>
            <!-- End .col-lg-4 -->
        </div>
    </div>
</section>

@include('front._partials.modal_product_details')

@stop




@push('script')

<script type="text/javascript">
function checkout_billing_google_location_search() {
  autocomplete_billing = new google.maps.places.Autocomplete($('.checkout_form input[name="billing_location"]')[0], {
      //componentRestrictions: { country: ["us", "ca"] },
      //fields: ["address_components", "geometry"],
      //types: ["address"],
  });
  autocomplete_billing.addListener("place_changed", function(){
      var place = autocomplete_billing.getPlace();
      //console.log(place.address_components);
      var address_line_1 = [];
      var country_code = '';
      var zipcode = '';
      $(place.address_components).each(function(i, v){
          console.log(v);
          if($.inArray('subpremise', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('street_number', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('route', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('sublocality_level_2', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('sublocality_level_1', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('locality', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('administrative_area_level_2', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('administrative_area_level_1', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('country', v.types) >= 0) country_code = v.short_name;
          if($.inArray('postal_code', v.types) >= 0) zipcode = v.long_name;
      });
      /*console.log(address_line_1);
      console.log(country_code);
      console.log(zipcode);*/
      $('.checkout_form input[name="billing_address_line_1"]').val(address_line_1.join(', '));
      $('.checkout_form input[name="billing_country_code"]').val(country_code);
      $('.checkout_form input[name="billing_zip_code"]').val(zipcode);
  });
}

function checkout_shipping_google_location_search() {
  autocomplete_shipping = new google.maps.places.Autocomplete($('.checkout_form input[name="shipping_location"]')[0], {
      //componentRestrictions: { country: ["us", "ca"] },
      //fields: ["address_components", "geometry"],
      //types: ["address"],
  });
  autocomplete_shipping.addListener("place_changed", function(){
      var place = autocomplete_shipping.getPlace();
      //console.log(place.address_components);
      var address_line_1 = [];
      var country_code = '';
      var zipcode = '';
      $(place.address_components).each(function(i, v){
          console.log(v);
          if($.inArray('subpremise', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('street_number', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('route', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('sublocality_level_2', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('sublocality_level_1', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('locality', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('administrative_area_level_2', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('administrative_area_level_1', v.types) >= 0) address_line_1.push(v.long_name);
          if($.inArray('country', v.types) >= 0) country_code = v.short_name;
          if($.inArray('postal_code', v.types) >= 0) zipcode = v.long_name;
      });
      /*console.log(address_line_1);
      console.log(country_code);
      console.log(zipcode);*/
      $('.checkout_form input[name="shipping_address_line_1"]').val(address_line_1.join(', '));
      $('.checkout_form input[name="shipping_country_code"]').val(country_code);
      $('.checkout_form input[name="shipping_zip_code"]').val(zipcode);
  });
}


    $(document).ready(function(){

      
      $(document).on('click', '.checkout_form .place_order', function(){
        var billing_first_name = $.trim($('.checkout_form input[name="billing_first_name"]').val());
        var billing_last_name = $.trim($('.checkout_form input[name="billing_last_name"]').val());
        var billing_company_name = $.trim($('.checkout_form input[name="billing_company_name"]').val());
        var billing_location = $.trim($('.checkout_form input[name="billing_location"]').val());
        var billing_address_line_1 = $.trim($('.checkout_form input[name="billing_address_line_1"]').val());
        var billing_country_code = $.trim($('.checkout_form input[name="billing_country_code"]').val());
        var billing_zip_code = $.trim($('.checkout_form input[name="billing_zip_code"]').val());
        var billing_phone = $.trim($('.checkout_form input[name="billing_phone"]').val());
        var billing_email = $.trim($('.checkout_form input[name="billing_email"]').val());
        var ship_different = $('.checkout_form input[name="ship_different"]:checked').length;
        var shipping_first_name = $.trim($('.checkout_form input[name="shipping_first_name"]').val());
        var shipping_last_name = $.trim($('.checkout_form input[name="shipping_last_name"]').val());
        var shipping_company_name = $.trim($('.checkout_form input[name="shipping_company_name"]').val());
        var shipping_location = $.trim($('.checkout_form input[name="shipping_location"]').val());
        var shipping_address_line_1 = $.trim($('.checkout_form input[name="shipping_address_line_1"]').val());
        var shipping_country_code = $.trim($('.checkout_form input[name="shipping_country_code"]').val());
        var shipping_zip_code = $.trim($('.checkout_form input[name="shipping_zip_code"]').val());
        var order_notes = $.trim($('.checkout_form textarea[name="order_notes"]').val());
        var error = 0;
        var email_pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
        $(".error").remove();
        if(billing_first_name == "") {
          $('.checkout_form input[name="billing_first_name"]').closest('.form-group').append("<div class='error'>Enter first name</div>");
          error = 1;
        }
        if(billing_last_name == "") {
          $('.checkout_form input[name="billing_last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
          error = 1;
        }
        if(billing_location == "" || billing_address_line_1 == '' || billing_country_code == '' || billing_zip_code == '') {
          $('.checkout_form input[name="billing_location"]').closest('.form-group').append("<div class='error'>Type & choose full address</div>");
          error = 1;
        }
        if(billing_phone == "") {
          $('.checkout_form input[name="billing_phone"]').closest('.form-group').append("<div class='error'>Enter phone</div>");
          error = 1;
        }
        if(email_pattern.test(billing_email) == false) {
          $('.checkout_form input[name="billing_email"]').closest('.form-group').append("<div class='error'>Enter valid email</div>");
          error = 1;
        }
        if(ship_different == 1) {
          if(shipping_first_name == "") {
            $('.checkout_form input[name="shipping_first_name"]').closest('.form-group').append("<div class='error'>Enter first name</div>");
            error = 1;
          }
          if(shipping_last_name == "") {
            $('.checkout_form input[name="shipping_last_name"]').closest('.form-group').append("<div class='error'>Enter last name</div>");
            error = 1;
          }
          if(shipping_location == "" || shipping_address_line_1 == '' || shipping_country_code == '' || shipping_zip_code == '') {
            $('.checkout_form input[name="shipping_location"]').closest('.form-group').append("<div class='error'>Type & choose full address</div>");
            error = 1;
          }
        }
        if(error == 0) {
          $(".mw_loader").show();
          var data = new FormData();
          data.append('action', 'checkout_order');
          data.append('billing_first_name', billing_first_name);
          data.append('billing_last_name', billing_last_name);
          data.append('billing_company_name', billing_company_name);
          data.append('billing_location', billing_location);
          data.append('billing_address_line_1', billing_address_line_1);
          data.append('billing_country_code', billing_country_code);
          data.append('billing_zip_code', billing_zip_code);
          data.append('billing_phone', billing_phone);
          data.append('billing_email', billing_email);
          data.append('ship_different', ship_different);
          data.append('shipping_first_name', shipping_first_name);
          data.append('shipping_last_name', shipping_last_name);
          data.append('shipping_company_name', shipping_company_name);
          data.append('shipping_location', shipping_location);
          data.append('shipping_address_line_1', shipping_address_line_1);
          data.append('shipping_country_code', shipping_country_code);
          data.append('shipping_zip_code', shipping_zip_code);
          data.append('order_notes', order_notes);
          data.append('_token', prop.csrf_token);
          $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
              if(data.success == '0') {
                $(".mw_loader").hide();
                alert(data.message);
              } else {
                window.location.href = prop.url + '/order-placed';
              }
            }
          });
        }
      });

    });
</script>

@endpush