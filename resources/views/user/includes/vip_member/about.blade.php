<?php
$countries = \App\Country::orderBy('name')->get();
$countries2 = [];
foreach ($countries as $key => $value) {
  $countries2[$value->country_id] = $value;
}

$cupsizes = $meta_data['cupsizes'];
$sexual_orientations = $meta_data['sexual_orientations'];
$zodiacs = $meta_data['zodiacs'];
$model_attributes = $meta_data['model_attributes'];
$vip_member_model_attributes = $meta_data['vip_member_model_attributes'];
$vip_member_model_attribute_ids = [];
foreach ($vip_member_model_attributes as $key => $value) {
  $vip_member_model_attribute_ids[] = $value->model_attribute_id;
}

$composite_banners = json_decode(($user_data['meta_data']['composite_banners'] ?? '{}'), true);
?>
<div class="about_form">

  <div class="profBan w-100">
    <div class="row">
      <?php for($i = 1; $i <= 8; $i++) {
        ?>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <?php 
            $ban = $composite_banners[('ban_' . $i)] ?? '';
            ?>
            <div class="confirm-identity relative crop_image_uploader {{ $ban != '' ? 'active' : '' }}" composite_order="{{ $i }}">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ $ban != '' ? url('public/uploads/profile_banner/' . $ban) : url('public/front/images/profle_banner_placeholder.jpg') }}" def_img="{{ url('public/front/images/profle_banner_placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
      <?php } ?>
        <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="1">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="2">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="3">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="4">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="5">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="6">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="7">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-12">
            <div class="confirm-identity relative crop_image_uploader" composite_order="8">
              <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                    <div class="ci-user-picture pictureDb">
                        <img src="{{ url('public/front/images/user-placeholder.jpg') }}" def_img="{{ url('public/front/images/user-placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt="">
                    </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn">
                    <a href="javascript:;" class="userEditeBtn position-relative">
                        <input type="file" class="item-img file center-block filepreviewprofile">
                    </a>
                </div>
            </div>
        </div> -->
    </div>
  </div>
  <div class="profileDb">
    <div class="row">
      <div class="col-12"><h4>About</h4></div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
            <?php
            $opt1 = $opt2 = $opt3 = '';
            if($user_data['gender'] == 1) $opt1 = 'selected="selected"';
            if($user_data['gender'] == 2) $opt2 = 'selected="selected"';
            if($user_data['gender'] == 3) $opt3 = 'selected="selected"';
            ?>
              <label for="">Gender</label>
              <select name="gender" id="" class="selectMd-2">
                <option value="">-- Select --</option>
                <option value="1" {{ $opt1 }}>Male</option>
                <option value="2" {{ $opt2 }}>Female</option>
              </select>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Sexual Orientation</label>
              <select name="sexual_orientation_id" id="" class="selectMd-2">
                <option value="">--- Select ---</option>
                <?php
                foreach ($sexual_orientations as $key => $value) {
                  $sel = '';
                  if(isset($user_data['meta_data']['sexual_orientation_id']) && $user_data['meta_data']['sexual_orientation_id'] == $value->id)
                    $sel = 'selected="selected"';
                  echo '<option value="' . $value->id . '" ' . $sel . '>' . $value->title . '</option>';
                }
                ?>
              </select>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Language spoken</label>
              <input type="text" name="language_spoken" id="" class="input-3" placeholder="Language Spoken" value="{{ $user_data['meta_data']['language_spoken'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Zodiac</label>
              <select name="zodiac_id" id="" class="selectMd-2">
                <option value="">--- Select ---</option>
                <?php
                foreach ($zodiacs as $key => $value) {
                  $sel = '';
                  if(isset($user_data['meta_data']['zodiac_id']) && $user_data['meta_data']['zodiac_id'] == $value->id)
                    $sel = 'selected="selected"';
                  echo '<option value="' . $value->id . '" ' . $sel . '>' . $value->title . '</option>';
                }
                ?>
              </select>
          </div>
      </div>
      <div class="col-12"><h4>Appearance</h4></div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Height (in cm)</label>
              <input type="text" name="height" id="" class="input-3" placeholder="Height (in cm)" value="{{ $user_data['meta_data']['height'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Weight (in kg)</label>
              <input type="text" name="weight" id="" class="input-3" placeholder="Weight (in kg)" value="{{ $user_data['meta_data']['weight'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Eye Color</label>
              <input type="text" name="eye_color" id="" class="input-3" placeholder="Eye Color" value="{{ $user_data['meta_data']['eye_color'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Hair Color</label>
              <input type="text" name="hair_color" id="" class="input-3" placeholder="Hair Color" value="{{ $user_data['meta_data']['hair_color'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Build</label>
              <input type="text" name="build" id="" class="input-3" placeholder="Build" value="{{ $user_data['meta_data']['build'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Ethnicity</label>
              <input type="text" name="ethnicity" id="" class="input-3" placeholder="Ethnicity" value="{{ $user_data['meta_data']['ethnicity'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Cupsize</label>
              <select name="cupsize_id" id="" class="selectMd-2">
                <option value="">--- Select ---</option>
                <?php
                foreach ($cupsizes as $key => $value) {
                  $sel = '';
                  if(isset($user_data['meta_data']['cupsize_id']) && $user_data['meta_data']['cupsize_id'] == $value->id)
                    $sel = 'selected="selected"';
                  echo '<option value="' . $value->id . '" ' . $sel . '>' . $value->title . '</option>';
                }
                ?>
              </select>
          </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Public Hair</label>
              <input type="text" name="public_hair" id="" class="input-3" placeholder="Public Hair" value="{{ $user_data['meta_data']['public_hair'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-6 col-12">
          <div class="form-group from-input-wrap">
              <label for="">Mesurements (xx–yy–zz in inches)</label>
              <input type="text" name="measurements" id="" class="input-3" placeholder="Mesurements (xx–yy–zz in inches)" value="{{ $user_data['meta_data']['measurements'] ?? '' }}" />
          </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="form-group from-input-wrap">
          <label for="">Attributes</label>
        </div>
      </div>
      <?php
      foreach ($model_attributes as $key => $value) {
        $chk = '';
        if(in_array($value->id, $vip_member_model_attribute_ids))
          $chk = 'checked="checked"';
        echo '<div class="form-group from-input-wrap col-md-6 col-lg-4 col-sm-12 col-12">   
        <div class="checkbox checkbox-info">
          <input id="vmmai' . $key . '" class="styled" type="checkbox" name="model_attributes[' . $value->id . ']" value="' . $value->id . '" ' . $chk . ' />
          <label for="vmmai' . $key . '">' . $value->title . '</label>
        </div>
      </div>';
      }
      ?>
      <div class="col-lg-12 col-md-12 col-sm-12 col-12">
        <button type="button" class="commonBtn about_submit">Update</button>
      </div>

    </div>
  </div>

</div>

@include('front._partials.modal_crop_image')


@push('script')

<script type="text/javascript">
    $(document).ready(function(){

        

    });
</script>

<!-- <script type="text/javascript">
  $(document).ready(function() {
      var $uploadCrop,
          tempFilename,
          rawImg,
          imageId;

      function readFile(input) {
          if (input.files && input.files[0]) {
              var reader = new FileReader();
              reader.onload = function(e) {
                  $('.upload-demo').addClass('ready');
                  $('#cropImagePop').modal('show');
                  rawImg = e.target.result;
              }
              reader.readAsDataURL(input.files[0]);
          } else {
              console.log("Sorry - you're browser doesn't support the FileReader API");
          }
      }

      $uploadCrop = $('#upload-demo').croppie({
          viewport: {
              width: 400,
              height: 271,
          },
          enforceBoundary: false,
          enableExif: true
      });
      $('#cropImagePop').on('shown.bs.modal', function() {
          $('.cr-slider-wrap').prepend('<p>Image Zoom</p>');
          $uploadCrop.croppie('bind', {
              url: rawImg
          }).then(function() {
              console.log('jQuery bind complete');
          });
      });

      $('#cropImagePop').on('hidden.bs.modal', function() {
          $('.item-img').val('');
          $('.cr-slider-wrap p').remove();
      });

      $('.item-img').on('change', function() {
          readFile(this);
      });

      $('.replacePhoto').on('click', function() {
          $('#cropImagePop').modal('hide');
          $('.item-img').trigger('click');
      });

      $('#cropImageBtn').on('click', function(ev) {
          $uploadCrop.croppie('result', {
              type: 'base64',
              format: 'jpeg',
              size: {
                  width: 400,
                  height: 271,
              }
          }).then(function(resp) {
              $('#item-img-output').attr('src', resp);
              $('#cropImagePop').modal('hide');
              $('.item-img').val('');
          });
      });
  });
</script> -->

<script type="text/javascript">
/*function readFile(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
        $('.upload-demo').addClass('ready');
        $('#cropImagePop').modal('show');
        rawImg = e.target.result;
    }
    reader.readAsDataURL(input.files[0]);
  } else {
    console.log("Sorry - you're browser doesn't support the FileReader API");
  }
}*/


  $(document).ready(function() {

    $('#upload-demo').croppie({
        viewport: {
            width: 400,
            height: 271,
            // type: 'circle'
        },
        enforceBoundary: false,
        enableExif: true
    });

    $(document).on('click', '.crop_image_uploader .remove_photo', function(){
      var def_img = $(this).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('def_img');
      $(this).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', def_img);
      $(this).closest('.crop_image_uploader').removeClass('active');
    });


    $('.item-img').unbind('change').on('change', function() {
      var that = this;
      var def_img = $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('def_img');
      $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', def_img);
      $(that).closest('.crop_image_uploader').removeClass('active');
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.upload-demo').addClass('ready');
            $('#cropImagePop').modal('show');
            rawImg = e.target.result;
            $('#cropImagePop').unbind('shown.bs.modal').on('shown.bs.modal', function() {
              $('.cr-slider-wrap p').remove();
              $('.cr-slider-wrap').prepend('<p>Image Zoom</p>');
              $('#upload-demo').croppie('bind', {
                  url: rawImg
              }).then(function() {
                  console.log('jQuery bind complete');
              });
            });
            $('.replacePhoto').unbind('click').on('click', function() {
                $('#cropImagePop').modal('hide');
                $(that).trigger('click');
            });
            $('#cropImageBtn').unbind('click').on('click', function(ev) {
                $('#upload-demo').croppie('result', {
                    type: 'base64',
                    format: 'jpeg',
                    size: {
                        width: 400,
                        height: 271,
                    }
                }).then(function(resp) {
                  $(that).closest('.crop_image_uploader').find('img.imgpreviewPrf').attr('src', resp);
                  $(that).closest('.crop_image_uploader').addClass('active');
                  $('#cropImagePop').modal('hide');
                });
            });
        }
        reader.readAsDataURL(this.files[0]);
      }
    });

  });
</script>

@endpush