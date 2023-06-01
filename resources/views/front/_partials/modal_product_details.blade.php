<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="productDetailsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title p-0" id111="exampleModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <section class="dtlsWrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                                <div class="imgContainer"><img src="{{ url('public/front/images/store-1.jpg') }}" alt=""></div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="namebox w-100">
                                    <div class="brandNameBtns d-flex align-items-center justify-content-between">
                                        <h3 class="brandDtls">Dell Laptop</h2>
                                        <!-- <ul class="d-flex">
                                            <li>
                                                <a href="#"><i class="fa fa-heart"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="fa fa-exchange"></i></a>
                                            </li>
                                        </ul> -->
                                    </div>
                                    <div class="description w-100">
                                        <h5>Description :</h5>
                                        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Eaque adipisci ullam assumenda repellat magni maxime ut. Incidunt, vel, quam expedita fugit,</p>
                                    </div>
                                    <div class="proFutures stock-type w-100">
                                        <ul>
                                            <li><span>Brand:</span> Apple</li>
                                            <li><span>Product Code:</span> Product 16</li>
                                            <li><span>Reward Points:</span> 600</li>
                                            <li><span>Availability:</span> Out Of Stock</li>
                                        </ul>
                                    </div>
                                    <div class="nameboxInner w-100 mt-3">
                                        <ul class="d-flex justify-content-between">
                                            <li class="price_info"><span class="cost-name">Cost:</span> <span class="price_show">30 coins</span></li>
                                            <!-- <li>Ex Tax: <span>$500.00</span></li> -->
                                        </ul>
                                    </div>

                                    <div class="quantity w-100 mt-3">
                                        <ul class="d-flex align-items-center">
                                            <li>
                                                <h5>Quantity</h5>
                                            </li>
                                            <li class="priceControl d-flex justify-content-center">
                                                <button type="button" class="controls2 qty_minus" value="-">-</button>
                                                <input type="text" class="qtyInput2" name="qty" value="1" readonly="readonly">
                                                <button type="button" class="controls2 qty_plus" value="+">+</button>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="aTCArea w-100 mt-3">
                                        <button type="button" class="btn btn-theme btn-block font-weight-bold text-uppercase {{(!empty(\Auth::user()->id)?'add_to_cart':'')}}" product_id=""><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                                        <div class="ajax_response"></div>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>

                </section>
              </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
              </div> -->
        </div>
    </div>
</div>
