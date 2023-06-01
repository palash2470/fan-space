{{-- @dd($user_data['meta_data']) --}}
<div class="modal" id="bannerEditModal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Profile</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="profBan w-100">
          <div class="row">
            <?php for($i = 1; $i <= 8; $i++) {
						        ?>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <?php
						            $ban = $composite_banners[('ban_' . $i)] ?? '';
						            ?>
              <div class="confirm-identity relative crop_image_uploader {{ $ban != '' ? 'active' : '' }}" composite_order="{{ $i }}"> <a href="javascript:;" class="remove_photo">x</a>
                <div class="ci-user d-flex align-items-center justify-content-center">
                  <div class="ci-user-picture pictureDb"> <img src="{{ $ban != '' ? url('public/uploads/profile_banner/' . $ban) : url('public/front/images/profle_banner_placeholder.jpg') }}" def_img="{{ url('public/front/images/profle_banner_placeholder.jpg') }}" id1111="item-img-output" class="imgpreviewPrf img-fluid" alt=""> </div>
                </div>
                <div class="ci-user-btn text-center ciUserBtn"> <a href="javascript:;" class="userEditeBtn position-relative">
                  <input type="file" class="item-img file center-block filepreviewprofile">
                  </a> </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <a href="javascript:;" class="commonBtn update_profile_banner">Save</a> </div>

      <!-- Modal footer -->
      <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div> -->

    </div>
  </div>
</div>
<div class="modal" id="bannercontEditModal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Profile</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="from-wrap-page">
          <div class="form-group from-input-wrap">
            <label for="">Display Name</label>
            <input type="text" name="display_name" id="" class="input-3" placeholder="Display Name" value="{{ $user_data['display_name'] }}" />
          </div>
          <div class="form-group from-input-wrap mt-3">
            <label for="">Profile Picture</label>
            <?php
							    $profile_photo = $user_data['meta_data']['profile_photo'];
							    ?>
            <div class="file-upload {{ $profile_photo != '' ? 'active' : '' }}" allowedExt="jpg,png" maxSize="4194304" maxSize_txt="4mb" preview>
              <div class="file-preview"> <img src="{{ url('public/uploads/profile_photo/' . $profile_photo) }}" class="sel_img" /> </div>
              <div class="file-select">
                <div class="file-select-button" id111="fileName">Choose File</div>
                <div class="file-select-name" id111="noFile">{{ $profile_photo }}</div>
                <input type="file" name="profile_photo" id="chooseFile">
                <input type="hidden" name="profile_photo_removed" value="" />
                <a href="javascript:;" class="fp-close">x</a> </div>
            </div>
          </div>
          <div class="form-group from-input-wrap">
            <label for="">Short Bio</label>
            <!--<input type="text" name="short_bio" id="" class="input-3" placeholder="Short Bio" value="{{ $user_data['meta_data']['short_bio'] ?? '' }}" />-->
            <textarea name="short_bio" id="" class="input-3" placeholder="Short Bio" style="height: 92px;">{{ $user_data['meta_data']['short_bio'] ?? '' }}</textarea>
          </div>
        </div>
        <a href="javascript:;" class="commonBtn update_profile_banner_cont">Save</a> </div>
    </div>
  </div>
</div>
<div class="modal" id="bannerRightcontEditModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Profile</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="from-wrap-page">
          <div class="form-group from-input-wrap">
            <label for="">Bio</label>
            <textarea rows="" cols="" class="textArea" name="about_bio" style="height: 240px;">{{ $user_data['meta_data']['about_bio'] }}</textarea>
          </div>
        </div>
        <a href="javascript:;" class="commonBtn update_profile_rightcont">Save</a> </div>
    </div>
  </div>
</div>
<div class="modal" id="bannerLeftcontEditModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Profile</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="profileDb">
          <div class="row">
            <div class="col-12">
              <h4>About</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                <label for="">Address</label>
                <input type="text" name="address_line_1" id="" class="input-3" placeholder="Address" value="{{ $user_data['meta_data']['address_line_1'] ?? '' }}" />
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                
                <label for="">Country</label>
                <select name="country_id" id="" class="selectMd-2">
                  <option value="">-- Select --</option>
                  @foreach ($countries as $value)
                      <option value="{{$value->country_id}}" @if($user_data['meta_data']['country_id']==$value->country_id) selected @endif>{{$value->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                <?php
                    $opt1 = $opt2 = $opt3 = $opt4='';
                    if($user_data['gender'] == 1) $opt1 = 'selected="selected"';
                    if($user_data['gender'] == 2) $opt2 = 'selected="selected"';
                    if($user_data['gender'] == 3) $opt3 = 'selected="selected"';
                    if($user_data['gender'] == 4) $opt4 = 'selected="selected"';
						    ?>
                <label for="">Gender</label>
                <select name="gender" id="" class="selectMd-2">
                  <option value="">-- Select --</option>
                  <option value="1" {{ $opt1 }}>Male</option>
                  <option value="2" {{ $opt2 }}>Female</option>
                  <option value="3" {{ $opt3 }}>Transgender</option>
                  <option value="4" {{ $opt4 }}>Non-binary</option>
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
            <div class="col-12">
              <h4>Appearance</h4>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                <label for="">Height (in ft)</label>
                <input type="text" name="height" id="" class="input-3" placeholder="Height (in cm)" value="{{ $user_data['meta_data']['height'] ?? '' }}" />
              </div>
            </div>
            {{-- <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                <label for="">Weight (in kg)</label>
                <input type="text" name="weight" id="" class="input-3" placeholder="Weight (in kg)" value="{{ $user_data['meta_data']['weight'] ?? '' }}" />
              </div>
            </div> --}}
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
                {{-- <input type="text" name="build" id="" class="input-3" placeholder="Build" value="{{ $user_data['meta_data']['build'] ?? '' }}" /> --}}

                <select name="build" id="build" class="selectMd-2">
                    <option value="">--- Select ---</option>
                    <option value="Athletic" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Athletic') selected @endif>Athletic</option>

                    <option value="Toned" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Toned') selected @endif>Toned</option>

                    <option value="Slim" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Slim') selected @endif>Slim</option>

                    <option value="Average" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Average') selected @endif>Average</option>

                    <option value="Curvy" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Curvy') selected @endif>Curvy</option>

                    <option value="Ample" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Ample') selected @endif>Ample</option>

                    <option value="Large" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Large') selected @endif>Large</option>

                    <option value="Muscular" @if (!empty($user_data['meta_data']['build'])&& $user_data['meta_data']['build'] == 'Muscular') selected @endif>Muscular</option>
                </select>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
              <div class="form-group from-input-wrap">
                <label for="">Ethnicity</label>
                <input type="text" name="ethnicity" id="" class="input-3" placeholder="Ethnicity" value="{{ $user_data['meta_data']['ethnicity'] ?? '' }}" />
              </div>
            </div>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="form-group from-input-wrap">
                <label for="">Likes/enjoys</label>
              </div>
            </div>
            <?php
            	foreach ($model_attributes2 as $key => $value) {
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
              <div class="form-group from-input-wrap">
                <label for="">More Likes</label>
                <input type="text" name="other_attribute" id="other_attribute" class="input-3" placeholder="Other Attribute" value="{{ $user_data['meta_data']['other_attribute'] ?? '' }}" />
              </div>
            </div>
          </div>
        </div>
        <a href="javascript:;" class="commonBtn update_profile_leftcont">Save</a> </div>
    </div>
  </div>
</div>
