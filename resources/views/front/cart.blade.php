@extends('layouts.front')
@section('content')

<?php
use App\Cart;

$cart = Cart::get_cart();
//dd($cart);
?>


<section class="cartArea">
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="wishlistWrap w-100">
                    <h2>Cart</h2>

                    <?php
                    if(count($cart['items']) == 0) {
                        echo '<p>No items in your cart.</p>';
                    } else { ?>

                        <div class="table-responsive">
                            <table class="table table-hover no-margin">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Model</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Coin Amount</th>
                                        <th scope="col">Total Coin Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($cart['items'] as $key => $value) {
                                        ?>
                                       <tr product_id="{{ $value->id }}">
                                          <td><button type="button" class="btn btn-danger remove_cart" product_id="{{ $value->id }}"><i class="fa fa-times-circle"></i></button></td>
                                            <td>
                                                <a href="javascript:;" class="wlProImg show_product_details" product_id="{{ $value->id }}"><img src="{{ $value->thumbnail }}" alt=""></a>
                                            </td>
                                            <td><a href="javascript:;" class="proNameTd show_product_details" product_id="{{ $value->id }}">{{ $value->title }}</a></td>
                                            <td><a href="{{ url('u/' . $value->seller_username) }}">{{ $value->seller_display_name }}</a></td>
                                            <td>
                                                <!-- <div class="input-group btn-block" style="max-width: 200px;">
                                                    <input type="text" name="" value="4" size="1" class="form-control">
                                                    <span class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i></button>
                                                    </span></div> -->
                                              <div class="quantity w-100 mt-4" style="">
                                                <ul class="d-flex align-items-center">
                                                  <li class="priceControl d-flex justify-content-center">
                                                      <button type="button" class="controls2 qty_minus" value="-">-</button>
                                                      <input type="text" class="qtyInput2" name="qty" value="{{ $value->qty }}" readonly="readonly" max_limit111="10">
                                                      <button type="button" class="controls2 qty_plus" value="+">+</button>
                                                  </li>
                                                </ul>
                                              </div>
                                            </td>
                                            <td>{{ $value->price }}</td>
                                            <td>{{ $value->item_total }}</td>
                                        </tr> 
                                    <?php } ?>
                                    <!-- <tr>
                                        <td>
                                            <a href="#" class="wlProImg"><img src="images/store-1.jpg" alt=""></a>
                                        </td>
                                        <td><a href="#" class="proNameTd">Dell Laptop</a></td>
                                        <td>@product 11</td>
                                        <td>
                                            <div class="input-group btn-block" style="max-width: 200px;">
                                                <input type="text" name="" value="4" size="1" class="form-control">
                                                <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i></button>
                                                </span></div>
                                        </td>
                                        <td>$123.20</td>
                                        <td>$492.80</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a href="#" class="wlProImg"><img src="images/store-1.jpg" alt=""></a>
                                        </td>
                                        <td><a href="#" class="proNameTd">Dell Laptop</a></td>
                                        <td>@product 11</td>
                                        <td>
                                            <div class="input-group btn-block" style="max-width: 200px;">
                                                <input type="text" name="" value="4" size="1" class="form-control">
                                                <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-sync"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger"><i class="fa fa-times-circle"></i></button>
                                                </span></div>
                                        </td>
                                        <td>$123.20</td>
                                        <td>$492.80</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>

                        <!-- <div class="likeDoNext w-100">
                            <h3>What would you like to do next?</h3>
                            <p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
                            <div class="codeBox">
                                <h4>Use Coupon Code</h4>
                                <div class="couponCodeInner d-flex">
                                    <input type="text" class="form-control coupInput" placeholder="Enter your coupon here">
                                    <button class="btn applyBtn">Apply Coupon</button>
                                </div>
                            </div>
                            <div class="codeBox">
                                <h4>Estimate Shipping & Taxes </h4>
                                <p>Enter your destination to get a shipping estimate.</p>
                                <div class="row align-items-end">
                                    <div class="col">
                                        <div class="form-group required no-margin">
                                            <label for="">Country</label>
                                            <select class="selectOption">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group required no-margin">
                                            <label for="">Region / State</label>
                                            <select class="selectOption">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group required no-margin">
                                            <label for="">Post Code</label>
                                            <select class="selectOption">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="getQuotes">Get Quotes <i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="codeBox">
                                <h4>Use Gift Certificate</h4>
                                <div class="couponCodeInner d-flex">
                                    <input type="text" class="form-control coupInput" placeholder="Enter your gift certificate code here">
                                    <button class="btn applyBtn">Apply Gift Certificate</button>
                                </div>
                            </div>
                        </div> -->
                        <div class="subTotalTable w-100 justify-content-end d-flex">
                            <div class="subTotalTableInner">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <!-- <tr>
                                                <td class="text-right"><strong>Sub-Total:</strong> </td>
                                                <td class="text-right">$404.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><strong>Flat Shipping Rate:</strong> </td>
                                                <td class="text-right">$5.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><strong>Eco Tax (-2.00):</strong> </td>
                                                <td class="text-right">$10.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right"><strong>VAT (20%):</strong> </td>
                                                <td class="text-right">$81.80</td>
                                            </tr> -->
                                            <tr>
                                                <td class="text-right"><strong>Total Coin Amount:</strong> </td>
                                                <td class="text-right">{{ $cart['total_amount'] }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-right"><a href="{{ url('checkout') }}" class="continueBtn">Checkout <i class="fa fa-long-arrow-right"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="continueArea d-flex justify-content-between w-100">
                            <a href="#" class="continueShopping"><i class="fa fa-long-arrow-left"></i> Continue Shopping</a>
                            <a href="#" class="continueBtn">Checkout <i class="fa fa-long-arrow-right"></i></a>
                        </div> -->

                    <?php } ?>

                </div>
            </div>

        </div>
    </div>
</section>

@include('front._partials.modal_product_details')

@stop




@push('script')

<script type="text/javascript">
    $(document).ready(function(){

      $(document).on('click', '.cartArea .quantity .qty_plus', function(){
        var qtyEl = $(this).closest('.priceControl').find('input[name="qty"]');
        var qty = parseInt(qtyEl.val());
        var product_id = $(this).closest('tr').attr('product_id');
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'update_cart');
        data.append('product_id', product_id);
        data.append('qty', (qty + 1));
        data.append('_token', prop.csrf_token);
        $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
            location.reload();
          }
        });
      });
      $(document).on('click', '.cartArea .quantity .qty_minus', function(){
        var qtyEl = $(this).closest('.priceControl').find('input[name="qty"]');
        var qty = parseInt(qtyEl.val());
        var product_id = $(this).closest('tr').attr('product_id');
        if(qty == 1) return false;
        $(".mw_loader").show();
        var data = new FormData();
        data.append('action', 'update_cart');
        data.append('product_id', product_id);
        data.append('qty', (qty - 1));
        data.append('_token', prop.csrf_token);
        $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
            location.reload();
          }
        });
      });

      $(document).on('click', '.remove_cart', function(){
          var product_id = $(this).attr('product_id');
          if(!confirm('Are you sure to remove this from your cart?')) return false;
          $(".mw_loader").show();
          var data = new FormData();
          data.append('action', 'remove_cart');
          data.append('product_id', product_id);
          data.append('_token', prop.csrf_token);
          $.ajax({type: 'POST', dataType: 'json', url: prop.ajaxurl, data: data, processData: false, contentType: false, success: function(data){
              location.reload();
            }
          });
        });

    });
</script>

@endpush