<div class="storeWrap">
  <div class="row">
    <?php foreach($meta_data['products'] as $key => $value) { 
      $thumbnail = url('public/front/images/product-thumbnail.png');
      if($value->thumbnail != '')
        $thumbnail = url('public/uploads/product/' . $value->thumbnail);
    ?>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12 product_item">
          <div class="performerBox w-100 store">
              <div class="performerImg w-100">
                  <div class="storeImg"><img src="{{ $thumbnail }}" alt=""></div>
              </div>
              <div class="performerdecc w-100 text-center">
                  <h4><a href="javascript:;">{{ $value->title }}</a><!-- <span><i class="ti-heart"></i></span> --></h4>
                  <p>Cost : <span>{{ $value->price }} Coin(s)</span></p>
                  <!-- <h6>Total Buy : <span>2345</span> Qty</h6> -->
                  <ul class="d-flex justify-content-between">
                      <li><a href="{{ url('dashboard/product?id=' . $value->id) }}"><i class="ti-pencil-alt"></i> Edit</a></li>
                      <li><a href="javascript:;" toggle_status="{{ $value->id }}"><i class="ti-hand-point-left"></i> {{ ($value->status == 1 ? 'Deactivate' : '') . ($value->status == 0 ? 'Activate' : '') }}</a></li>
                  </ul>
              </div>
              <div class="performerBtn text-center">
                  <a href="javascript:;" product_delete="{{ $value->id }}">Delete</a>
              </div>
          </div>
      </div>
    <?php } ?>
  </div>
</div>


<div class="d-flex justify-content-center mt-3">
  {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
  <?php
  /*
  {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}
  */
  ?>
</div>