<div class="row">
  <?php
  foreach ($meta_data['hotlist'] as $key => $value) {
    $profile_photo = URL::asset('public/front/images/user-placeholder.jpg');
    if($value->usermeta_data['profile_photo'] != '')
      $profile_photo = url('/public/uploads/profile_photo/' . $value->usermeta_data['profile_photo']);
    ?>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 m-b-20">
        <div class="performerBox w-100 active wow wow fadeIn delay1 relative" style="visibility: visible; animation-name: fadeIn;">
            <div class="option-top-rgt">
                <div class="option-top-rgt-wrap relative">
                    <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="option-rgt-details">
                        <li><a href="javascript:;" user_id="{{ $value->id }}" class="set_user_fav">Remove from hotlist</a></li>
                    </ul>
                </div>
            </div>
            <div class="performerImg w-100">

                <a href="{{ url('u/' . $value->username) }}"><img src="{{ $profile_photo }}" alt=""></a>
            </div>
            <div class="performerdecc w-100 text-center">
                <h4><a href="{{ url('u/' . $value->username) }}">{{ $value->display_name }}</a><span><i class="fas fa-fire set_user_fav" user_id="{{ $value->id }}"></i></span></h4>
                <h5>{{ '@' . $value->username }}</h5>


            </div>
            <!-- <div class="performerBtn text-center">
                <a href="javascript:;" class="set_user_fav" user_id="{{ $value->id }}"><i class="fas fa-heart"></i> Unlist</a>
            </div> -->
        </div>
    </div>
    <?php
  }
  ?>
</div>

<div class="samplePage d-flex justify-content-center">
    {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
    <?php
    /*
    {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}
    */
    ?>
</div>

@push('script')
<script>

</script>
@endpush
