{{-- @dd($meta_data['subscribers'],\Auth::user()); --}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-b-20">
        <button class="website-btn send-msg-to-all" data-toggle="modal" data-target="#exampleModal">send message to all</button>
    </div>
  <?php
  foreach ($meta_data['subscribers'] as $key => $value) {
    $profile_photo = URL::asset('public/front/images/user-placeholder.jpg');
    if($value->usermeta_data['profile_photo'] != '')
      $profile_photo = url('/public/uploads/profile_photo/' . $value->usermeta_data['profile_photo']);
    ?>

    <div class="col-lg-3 col-md-4 col-sm-6 col-12 m-b-20">
        <div class="performerBox w-100 active wow wow fadeIn delay1 relative" style="visibility: visible; animation-name: fadeIn;">
            <div class="option-top-rgt rgt-msg-top">
                <div class="option-top-rgt-wrap relative">
                    <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="option-rgt-details">
                        <li class="send-msg" data-name="{{ $value->first_name . ' ' . $value->last_name }}" data-subscriber-id="{{ $value->id }}" data-toggle="modal" data-target="#exampleModal"><a href="#">send message</a></li>
                    </ul>
                </div>
            </div>
            <div class="performerImg w-100">

                <a href111="{{ url('u/' . $value->username) }}"><img src="{{ $profile_photo }}" alt=""></a>
            </div>
            <div class="performerdecc w-100 text-center">
                <h4><a href111="{{ url('u/' . $value->username) }}">{{ $value->first_name . ' ' . $value->last_name }}</a></h4>
                <p>Valid upto {{ date('d-m-Y', strtotime($value->subscription_validity_date)) }}</p>

            </div>
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Message to <span></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <textarea name="message" id="message" rows="5" class="form-control resize-none"></textarea>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button type="button" class="website-btn" id="send_message">Submit</button>
                    <input type="hidden" name="subscriber_id" id="subscriber_id" value="0">
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@push('script')
<script>
    $(document).on('click','.send-msg',function(){
        $('#exampleModalLabel span').html($(this).data('name'));
        $('#subscriber_id').val($(this).data('subscriber-id'));

    });

    $(document).on('click','.send-msg-to-all',function(){
        $('#exampleModalLabel span').html('all subscriber');
        $('#subscriber_id').val('0');

    });
</script>
@endpush
