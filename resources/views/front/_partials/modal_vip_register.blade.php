<?php
$interest_cats = \App\User_category::all();
$countries = \App\Country::orderBy('name')->get();
?>

<section class="bcmVipArea">
    <div class="bcmVipWrap">
        <div class="bcmVipHeader">
            <h2>become a vip <span><a class="x-close" href="javascript:;">x</a></span></h2>
        </div>
        <div class="bcmVipBody">
            <div class="bcmVipBodyInner">
                <div class="bcmVipNav w-100">
                    <ul class="row justify-content-between">
                        <li class="active"><a href="javascript:;" step="1">STEP 1<span>PERSONAL DETAILS</span></a>
                        </li>
                        <li><a href="javascript:;" step="2">STEP 2<span>VERIFY IDENTITY</span></a></li>
                        <li><a href="javascript:;" step="3">STEP 3<span>PROFILE DETAILS</span></a></li>
                        <li><a href="javascript:;" step="4">STEP 4<span>SETUP PAYMENTS</span></a></li>
                    </ul>
                </div>
                <div class="bcmVipDetails">
                    <div class="personalDetails w-100" step_details="1">
                        <div class="w-100 stepSec-1">
                            <div class="row">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/user-img.png') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>PERSONAL DETAILS</h5>
                                            <p>Complete the form to start the process of becoming a VIP.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <p>Become a VIP and start making money from the content you share! Start by
                                            entering your personal details such as name, email address, username and
                                            display name. Your display name is the name that appears when followers
                                            chat to you.</p>
                                    </div>
                                    <div class="vipDtlsForm mt-3">
                                        <h5>GET STARTED!</h5>
                                        <form action="" method="get">
                                            <div class="form-group relative vrfy-btn-add">
                                                <input type="hidden" name="email_otp_verify" id="vipemail_otp_verify"
                                                    value="false" />
                                                <label for="input-vip-email">Email</label>
                                                <input type="text" name="email" id="input-vip-email"
                                                    class="input-3" placeholder="Email">
                                                <div class="vfWrap verifybtn"><a href="javascript:;"
                                                        class="vfBtn vipsendEmailOtp"><span>Confirm text
                                                            to</span>Verify</a></div>
                                                <!--<div class="vfWrap verifybtn verified"><a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a></div>-->
                                                <span class="errMsg" id="vipemail_otp_error"
                                                    style="display:none">Error</span>
                                            </div>
                                            <!--<div class="form-group">
                        <label for="">Confirm your Email</label>
                        <input type="text" name="confirm_email" id="" class="input-3" placeholder="Confirm your Email">
                      </div>-->
                                            <div class="form-group">
                                                <label for="">Password</label>
                                                <input type="password" name="password" id="" class="input-3"
                                                    placeholder="Password">
                                            </div>
                                            <div class="passwordStrength"></div>
                                            <!-- <div class="form-group formInfo">
                                                <p><span><i class="fas fa-info-circle"></i></span>Please ensure your password is at least 8 characters and contains at least one uppercase letter and at least one number.</p>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="">Confirm Password</label>
                                                <input type="password" name="confirm_password" id=""
                                                    class="input-3" placeholder="Confirm Password">
                                            </div>
                                            <!-- <div class="form-group">
                                                <div class="checkbox checkbox-info">
                                                    <input id="mdcb-1" class="styled" type="checkbox">
                                                    <label for="mdcb-1">Enable Two-factor authentication - you will be required to verify all logins from any new device or browser via email message</label>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="">Username</label>
                                                <input type="text" name="username" id="" class="input-3"
                                                    placeholder="Username">
                                            </div>
                                            <div class="form-group formInfo">
                                                <p><span><i class="fas fa-info-circle"></i></span>Your username is what
                                                    your account will be identified with, (@username) and will form your
                                                    profile URL. i.e. admireme.vip/username<br>
                                                    <br>
                                                    Once set, this cannot
                                                    be changed. Your username should be 50 characters or min 3
                                                    characters and contain only letters and numbers
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Display Name</label>
                                                <input type="text" name="display_name" id="" class="input-3"
                                                    placeholder="Display Name">
                                            </div>
                                            <div class="form-group formInfo">
                                                <p><span><i class="fas fa-info-circle"></i></span>This is the name that
                                                    will be displayed to other users.</p>
                                            </div>
                                            <div class="form-group">
                                                <h5>personal details</h5>
                                                <p class="p-0">You must use your full, real name exactly as
                                                    displayed on your passport, driving license or government issued
                                                    Identity Card. This is used for admin purposes only and is not shown
                                                    publicly.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Forenames</label>
                                                <input type="text" name="first_name" id=""
                                                    class="input-3" placeholder="Forenames">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Last Name</label>
                                                <input type="text" name="last_name" id=""
                                                    class="input-3" placeholder="Last Name">
                                            </div>
                                            <div class="form-group relative vrfy-btn-add phone-box">
                                                <input type="hidden" name="vipphone_otp_verify"
                                                    id="vipphone_otp_verify" value="false" />
                                                <label for="">Phone Number</label>
                                                <input type="tel" name="mobile" id="input-vip-phone"
                                                    class="input-3" placeholder="Phone Number">
                                                <input type="hidden" id="countrycode" name="countrycode"
                                                    value="1">
                                                <div class="vfWrap verifybtn"><a href="javascript:;"
                                                        class="vfBtn vipsendPhoneOtp"><span>Confirm text
                                                            to</span>Verify</a></div>
                                                <span class="errMsg" id="vipphone_otp_error"
                                                    style="display:none">Error</span>
                                            </div>




                                            <div class="form-group">
                                                <label for="">DOB</label>
                                                <input type="text" class="input-3 datepicker_single"
                                                    name="dob" placeholder="dd-mm-yyyy"
                                                    yearRange="{{ date('Y') - 100 . ':' . date('Y') }}"
                                                    maxDate="{{ date('d-m-Y', strtotime('-18 year')) }}" readonly />
                                            </div>
                                            <div class="form-group">
                                                <label for="">Category</label>
                                                <select name="user_cat_id" id="" class="selectMd-2">
                                                    <option value="">-select-</option>
                                                    <?php
                                                    foreach ($interest_cats as $key => $value) {
                                                        ?>
                                                    <option value="{{ $value->id }}">{{ $value->title }}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="form-group formInfo">
                                                <p><span><i class="fas fa-info-circle"></i></span>Please indicate the
                                                    category you wish to be featured in.</p>
                                            </div>
                                            <div class="interests">
                                                <h3>Interests</h3>
                                                <div class="row">
                                                    <?php
                                                    foreach ($interest_cats as $key => $value) {
                                                        ?>
                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="checkbox checkbox-info">
                                                            <input id="vintcat_{{ $value->id }}" class="styled"
                                                                type="checkbox" name="interest_cat_id[]"
                                                                value="{{ $value->id }}" />
                                                            <label
                                                                for="vintcat_{{ $value->id }}">{{ $value->title }}</label>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                    <!-- <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="checkbox checkbox-info">
                                                            <input id="Female-cb-2" class="styled" type="checkbox">
                                                            <label for="Female-cb-2">Female</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="checkbox checkbox-info">
                                                            <input id="male-cb-2" class="styled" type="checkbox">
                                                            <label for="male-cb-2">Male</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="checkbox checkbox-info">
                                                            <input id="couples-cb-2" class="styled" type="checkbox">
                                                            <label for="couples-cb-2">Couples</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                                        <div class="checkbox checkbox-info">
                                                            <input id="lgbtq-cb-2" class="styled" type="checkbox">
                                                            <label for="lgbtq-cb-2">LGBTQ+</label>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="form-group formInfo">
                                                <p><span><i class="fas fa-info-circle"></i></span>Please select the
                                                    categories you are interested in - only these categories will be
                                                    shown to you within the VIP listing. If you leave this empty all
                                                    categories will
                                                    be shown to you.</p>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info mb-3">
                                                    <input id="agreeCb" class="styled" type="checkbox"
                                                        name="agree_terms" value="1">
                                                    <label for="agreeCb">Please tick to confirm you agree to our <a
                                                            href="{{ url('/terms_conditions') }}"
                                                            target="_blank">Terms and Conditions</a></label>
                                                </div>
                                                <div class="checkbox checkbox-info">
                                                    <input id="agreeCb-2" class="styled" type="checkbox"
                                                        name="over_18" value="1">
                                                    <label for="agreeCb-2">I confirm that I am over 18</label>
                                                </div>
                                            </div>
                                            <div class="form-group reChapcha">
                                                <!-- <img src="{{ URL::asset('/public/front/images/recaptcha.jpg') }}" alt=""> -->
                                                <div class="g-recaptcha" data-sitekey="<?php echo $meta_data['global_settings']['settings_google_recaptcha_site_key']; ?>"></div>
                                            </div>
                                            <div class="form-group mt-4">
                                                <button type="button"
                                                    class="nextStepBtn vipRegSubmit-1">SUBMIT</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 stepSec-2">
                            <div class="row align-items-center">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/user-img.png') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <p>Complete the form to start the process of becoming a VIP.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <h4>awating email verification</h4>
                                        <!-- <p>We need you to verify your email.
                                            Please check your inbox for an email and click the verification link.
                                            Once click, this page will update and you will be able to proceed to the next step.</p> -->
                                        <p>We need you to verify your email.
                                            Please check your inbox for an email with verification code and put here.
                                            Once email verified, this page will update and you will be able to proceed
                                            to the next step.</p>
                                    </div>
                                    <div class="vipDtlsForm mt-3">
                                        <form action="" method="get">
                                            <div class="form-group">
                                                <label for="">Email Verification Code</label>
                                                <input type="text" name="email_verification_code" id=""
                                                    class="input-3" placeholder="Verification Code">
                                            </div>
                                            <div class="form-group mt-4">
                                                <button type="button"
                                                    class="nextStepBtn vipRegSubmit-1_3">VERIFY</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 stepSec-3">
                            <div class="w-100 text-center verifyEmailArea">
                                <h4 class="mb-3">VERIFY EMAIL ADDRESS</h4>
                                <p>Thank you - your email address has been verified successfully.</p>
                                <a href="#" class="allBtn mt-4">resume become a vip</a>
                            </div>
                        </div>
                    </div>
                    <div class="verifyIdentity w-100" step_details="2">
                        <div class="w-100 stepSec-1">
                            <div class="row">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/user-img.png') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>VERIFY IDENTITY</h5>
                                            <p>Upload your identity docouments.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight mb-3">
                                    <div class="vipDtlsBox">
                                        <h4>VERIFY YOUR IDENTITY</h4>
                                        <p>We need to verify your ID before you can become a VIP. Scan or take a photo
                                            of a form of photographic ID. We will then carry out a manual check to
                                            approve your account.</p>
                                        <p>In order to become a VIP we need to verify your identity by uploading a photo
                                            ID. Please ensure that the name on the Photo ID matches the name you set
                                            when you created your account. If you need to change your name, please use
                                            the button below.</p>
                                    </div>
                                    <div class="vipDtlsBox w-100 mt-4">
                                        <h6>Documents allowed:</h6>
                                        <ul>
                                            <li>Passport</li>
                                            <li>Driving License</li>
                                        </ul>
                                    </div>
                                    <form action="" method="get">
                                        <div class="w-100 resercherDiv mt-3">
                                            <h4>Photo of your ID Document</h4>
                                            <div class="file-upload" allowedExt="jpg,png" maxSize="4194304"
                                                maxSize_txt="4mb" no_preview>
                                                <!-- <div class="file-preview active" style="display: block;">
                                                    <a href="javascript:;" class="fp-close">x</a>
                                                    <img src="" class="def_img" />
                                                    <img src="" class="sel_img" />
                                                </div> -->
                                                <div class="file-select">
                                                    <div class="file-select-button" id111="fileName">Choose File</div>
                                                    <div class="file-select-name" id111="noFile"></div>
                                                    <input type="file" name="id_proof_doc" id="chooseFile">
                                                    <input type="hidden" name="id_proof_doc_removed"
                                                        value="" />
                                                    <a href="javascript:;" class="fp-close">x</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group formInfo mt-3">
                                            <p><span><i class="fas fa-info-circle"></i></span>Please upload a photo of
                                                your picture ID Document (i.e. Passport or Driving License)</p>
                                        </div>
                                        <div class="w-100 resercherDiv mt-3">
                                            <h4>Photo of your holding your ID</h4>
                                            <div class="file-upload" allowedExt="jpg,png" maxSize="4194304"
                                                maxSize_txt="4mb" no_preview>
                                                <div class="file-select">
                                                    <div class="file-select-button" id111="fileName">Choose File</div>
                                                    <div class="file-select-name" id111="noFile"></div>
                                                    <input type="file" name="id_proof" id="chooseFile">
                                                    <input type="hidden" name="id_proof_removed" value="" />
                                                    <a href="javascript:;" class="fp-close">x</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group formInfo mt-3">
                                            <p><span><i class="fas fa-info-circle"></i></span>Please upload a photo of
                                                you holding your ID document, ensuring your face is clearly visible.</p>
                                        </div>
                                        <div class="change_name" style="display: none;">
                                            <div class="form-group">
                                                <label for="">Forename</label>
                                                <input type="text" name="change_first_name" id=""
                                                    class="input-3" placeholder="Forename">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Lastname</label>
                                                <input type="text" name="change_last_name" id=""
                                                    class="input-3" placeholder="Lastname">
                                            </div>
                                            <div class="form-group mt-4">
                                                <button type="button"
                                                    class="nextStepBtn changeNameSubmit">Update</button>
                                            </div>
                                        </div>
                                        <div class="w-100 d-flex justify-content-between">
                                            <button type="button" class="allBtnWhite changeNameBtn">change
                                                name</button>
                                            <button type="button" class="allBtn vipRegSubmit-2">submit</button>
                                        </div>
                                        <div class="text-right form_msg"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 stepSec-2">
                            <div class="row">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/user-img.png') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>VERIFIED IDENTITY</h5>
                                            <p>Upload your identity documents.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <h4>DOCUMENT UPLOADED</h4>
                                        <p>Your ID has been successfully uploaded. We will review this within the next
                                            24 hours but this may take longer during weekends and holidays. Once we have
                                            reviewed and confirmed your ID, you will be able to complete your setup and
                                            start earning.</p>
                                        <p>In the meantime you can setup your profile and add your bank account.</p>
                                        <a href="javascript:;" class="allBtn mt-4" data-toggle="modal"
                                            data-target=".completeCrofile">complete profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profileDetails w-100" step_details="3">
                        <div class="w-100 stepSec-1">
                            <div class="row">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/profile.jpg') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>PROFILE DETAILS</h5>
                                            <p>Complete your profile, by uploading a profile image and banner, set your
                                                monthly subscription price and tell your admirers a bit more about you,
                                                your personality and what you like in your bio section.</p>
                                            <p>Maximise your earning potential and include a link to your Amazon
                                                wishlist so your admirers can shower you with gifts!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <form action="" method="get">
                                            <h4>SETUP YOUR PROFILE</h4>
                                            <div class="w-100 resercherDiv mt-3">
                                                <h4>Update Profile Picture</h4>
                                                <!-- <div class="file-upload">
                                                    <div class="file-select">
                                                        <div class="file-select-button" id="fileName">Choose File</div>
                                                        <div class="file-select-name" id="noFile">No file chosen...</div>
                                                        <input type="file" name="researcher_file" id="chooseFile">
                                                    </div>
                                                </div> -->
                                                <div class="file-upload" allowedExt="jpg,png" maxSize="4194304"
                                                    maxSize_txt="4mb" preview>
                                                    <div class="file-preview"> <img src="" class="sel_img" />
                                                    </div>
                                                    <div class="file-select">
                                                        <div class="file-select-button" id111="fileName">Choose File
                                                        </div>
                                                        <div class="file-select-name" id111="noFile"></div>
                                                        <input type="file" name="profile_photo" id="chooseFile">
                                                        <input type="hidden" name="profile_photo_removed"
                                                            value="" />
                                                        <a href="javascript:;" class="fp-close">x</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span>This image will
                                                    represent your profile and be visible to the public. Please don't
                                                    include any nudity in profile pictures... save that for your paying
                                                    admirers!
                                                    The ideal size for your profile picture is 450x450 pixels.</p>
                                            </div>
                                            <div class="w-100 resercherDiv mt-3">
                                                <h4>Profile Video</h4>
                                                <!-- <div class="file-upload">
                                                    <div class="file-select">
                                                        <div class="file-select-button" id="fileName">Choose File</div>
                                                        <div class="file-select-name" id="noFile">No file chosen...</div>
                                                        <input type="file" name="researcher_file" id="chooseFile">
                                                    </div>
                                                </div> -->
                                                <div class="file-upload" allowedExt="mp4" maxSize="20971520"
                                                    maxSize_txt="20mb" no_preview>
                                                    <div class="file-select">
                                                        <div class="file-select-button" id111="fileName">Choose File
                                                        </div>
                                                        <div class="file-select-name" id111="noFile"></div>
                                                        <input type="file" name="profile_video" id="chooseFile">
                                                        <input type="hidden" name="profile_video_removed"
                                                            value="" />
                                                        <a href="javascript:;" class="fp-close">x</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span>Add a short video to
                                                    be shown instead of your profile picture. This is limited to a
                                                    maximum of 5 seconds a boomerang video is the perfect length for
                                                    your profile video.
                                                    Please make sure this upload is PG. Anything uploaded here which is
                                                    X rated will result in a ban from adding profile videos in future.
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info mb-3">
                                                    <input id="agreeCb3" class="styled" type="checkbox"
                                                        name="agree_terms2" value="1" />
                                                    <label for="agreeCb3">Please tick to confirm you agree to our <a
                                                            href="{{ url('/terms_conditions') }}"
                                                            target="_blank">Terms and Condition</a></label>
                                                </div>
                                            </div>
                                            <div class="w-100 resercherDiv mt-3">
                                                <h4>Profile Banner</h4>
                                                <!-- <div class="file-upload">
                                                    <div class="file-select">
                                                        <div class="file-select-button" id="fileName">Choose File</div>
                                                        <div class="file-select-name" id="noFile">No file chosen...</div>
                                                        <input type="file" name="researcher_file" id="chooseFile">
                                                    </div>
                                                </div> -->
                                                <div class="file-upload" allowedExt="jpg,png" maxSize="10485760"
                                                    maxSize_txt="10mb" preview>
                                                    <div class="file-preview"> <img src="" class="sel_img" />
                                                    </div>
                                                    <div class="file-select">
                                                        <div class="file-select-button" id111="fileName">Choose File
                                                        </div>
                                                        <div class="file-select-name" id111="noFile"></div>
                                                        <input type="file" name="profile_banner" id="chooseFile">
                                                        <input type="hidden" name="profile_banner_removed"
                                                            value="" />
                                                        <a href="javascript:;" class="fp-close">x</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span>Your profile banner
                                                    will be displayed at the top of your profile page. This will be
                                                    viewable to anyone who clicks on your profile. No nudity is allowed
                                                    in your profile banners either please!</p>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info mb-3">
                                                    <input id="agreeCb4" class="styled" type="checkbox"
                                                        name="allow_vip_friend_request" value="1" />
                                                    <label for="agreeCb4">I would like to allow VIPs to send me friend
                                                        requests</label>
                                                </div>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Want to know why we
                                                    think its a good reason to have friends requests on? Read our blog
                                                    for more info</p>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="">Subscription Price</label>
                                                <input type="text" name="subscription_price" id="" class="input-3" placeholder="Subscription Price" min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}" max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}">
                                            </div> -->
                                            <h4>Subscription Prices ($)</h4>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label for="">1 Month</label>
                                                        <input type="text" name="subscription_price_1m"
                                                            id="" class="input-3" placeholder=""
                                                            min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}"
                                                            max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label for="">3 Months</label>
                                                        <input type="text" name="subscription_price_3m"
                                                            id="" class="input-3" placeholder=""
                                                            min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}"
                                                            max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label for="">6 Months</label>
                                                        <input type="text" name="subscription_price_6m"
                                                            id="" class="input-3" placeholder=""
                                                            min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}"
                                                            max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label for="">12 Months</label>
                                                        <input type="text" name="subscription_price_12m"
                                                            id="" class="input-3" placeholder=""
                                                            min="{{ $meta_data['global_settings']['settings_model_subscription_min_price'] }}"
                                                            max="{{ $meta_data['global_settings']['settings_model_subscription_max_price'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Minimum {{ number_format($meta_data['global_settings']['settings_model_subscription_min_price'], 2) }}. Maximum {{ number_format($meta_data['global_settings']['settings_model_subscription_max_price'], 2) }}. This is a monthly price admirers must pay to access your content. You dictate how much you want to charge. The money you earn will be subject to our fees and paid out to you on a weekly basis. See our T&C's for more information about this.</p>
                                            </div> -->
                                            <div class="form-group">
                                                <label for="">Location</label>
                                                <!-- <input type="text" name="" id="" class="input-3 max-400" placeholder="Last Name"> -->
                                                <input type="text" name="location" id=""
                                                    class="form-control input-3"
                                                    google_location_search_callback="vipmember_register_google_location_search">
                                                <input type="hidden" name="address_line_1" value="" />
                                                <input type="hidden" name="country_code" value="" />
                                                <input type="hidden" name="zip_code" value="" />
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Want your admirers
                                                    to know where you're based? Enter a town, city or country. Don't
                                                    want them to know? Leave this blank.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Twitter url</label>
                                                <input type="text" name="twitter_url" id=""
                                                    class="input-3 max-400" placeholder="Twitter url">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Wishlist url</label>
                                                <input type="text" name="wishlist_url" id=""
                                                    class="input-3 max-400" placeholder="Wishlist url">
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Maximise your
                                                    earning potential by entering your amazon wishlist. This will be
                                                    viewable by your admirers, so they are able to shower you with
                                                    gifts.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Bio</label>
                                                <textarea rows="" cols="" class="textArea max-400" name="about_bio"></textarea>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Maximise your
                                                    earning potential by entering your amazon wishlist. This will be
                                                    viewable by your admirers, so they are able to shower you with
                                                    gifts.</p>
                                            </div>
                                            <div class="form-group">
                                                <div class="checkbox checkbox-info mb-3">
                                                    <input id="agreeCb5" class="styled" type="checkbox"
                                                        name="free_follower" value="1" />
                                                    <label for="agreeCb5">Enable Free Followers</label>
                                                </div>
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Tick this box if you
                                                    would like to activate your teaser wall, where admireme users can
                                                    follow you for free and you can upload free content as teasers for
                                                    your paid wall.</p>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Tags</label>
                                                <input type="text" name="profile_keywords" id=""
                                                    class="input-3 max-400" placeholder="Tags">
                                            </div>
                                            <div class="form-group formInfo mt-3">
                                                <p><span><i class="fas fa-info-circle"></i></span> Enter tags relating
                                                    to you and your content that will help admirers find your profile.
                                                </p>
                                            </div>
                                            <div class="form-group formInfo mt-3 d-flex justify-content-end">
                                                <button type="button" class="allBtn vipRegSubmit-3">continue</button>
                                            </div>
                                            <div class="text-right form_msg"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="setupPayments w-100" step_details="4">
                        <div class="w-100 stepSec-1">
                            <div class="row">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/bank-icon.jpg') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>BANK ACCOUNT DETAILS</h5>
                                            <p>So you can receive funds you will need to add your bank account details.
                                            </p>
                                            <p>Any earnings you make each week will be deposited into this bank account.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <h4>ENTER BANK ACCOUNT DETAILS</h4>
                                        <p>Enter your bank account details to receive payment from the earnings you have
                                            made from follower subscriptions, tips and premium posts.</p>
                                        <p>You will receive your payments on a weekly basis when you hit a £100 minimum
                                            pay out. If you did not earn £100 in one week, your money will be rolled
                                            over to your next pay out, or if you have earned over £20 you can request a
                                            payout for a fee of £3.</p>
                                        <p>Your payments run from 00.00 am Friday to 11.59 pm Thursday and will be made
                                            11 days later. E.g. Friday 1st to Thursday 7th, payments will be made on
                                            Monday the 18th</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <select name="bank_country_id" id="" class="selectMd-2 max-400">
                                            <?php
                                            foreach ($countries as $key => $value) {
                                                $sel = '';
                                                if ($value->iso_code_2 == 'GB') {
                                                    $sel = 'selected="selected"';
                                                }
                                                echo '<option value="' . $value->country_id . '" ' . $sel . '>' . $value->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Account Name</label>
                                        <input type="text" name="bank_account_name" id=""
                                            class="input-3 max-400" placeholder="Account Name">
                                    </div>
                                    <div class="form-group formInfo mt-3">
                                        <p><span><i class="fas fa-info-circle"></i></span> Please enter your account
                                            name here. This will be the name you used when you set up your bank account
                                            and can usually be seen on your bank card. For example it might say Miss
                                            Smith, Miss S Smith or Susan Smith. Due to recent banking changes the
                                            account name must match exactly or your payment may be rejected when we try
                                            to make your payment.</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Sort code</label>
                                        <input type="text" name="bank_account_sort_code" id=""
                                            class="input-3 max-400" placeholder="00-00-00">
                                    </div>
                                    <div class="form-group formInfo mt-3">
                                        <p><span><i class="fas fa-info-circle"></i></span> 6 digit code found on the
                                            front of your bank card - should be in the format 00-00-00 - leave blank if
                                            you have an account outside the UK</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Account Number</label>
                                        <input type="text" name="bank_account_number" id=""
                                            class="input-3 max-400" placeholder="00000000">
                                    </div>
                                    <div class="form-group formInfo mt-3">
                                        <p><span><i class="fas fa-info-circle"></i></span> This should be an 8 digit
                                            number on the front of your card - leave blank if you have an account
                                            outside the UK</p>
                                    </div>
                                    <div class="form-group formInfo mt-3 d-flex justify-content-end mt-3">
                                        <button type="button" class="allBtn vipRegSubmit-4">Complete
                                            Registration</button>
                                    </div>
                                    <div class="text-right form_msg"></div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 stepSec-2" style="display: none;">
                            <div class="row align-items-center">
                                <div class="bcmVipDetailsLeft">
                                    <div class="uplodedImg"> <span> <img
                                                src="{{ URL::asset('/public/front/images/user-img.png') }}"
                                                class="" alt=""> </span>
                                        <div class="w-100 imgContent">
                                            <h5>VIP REGISTRATION</h5>
                                            <!-- <p>Upload your identity documents.</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="bcmVipDetailsRight">
                                    <div class="vipDtlsBox">
                                        <h4>REGISTRATION COMPLETED</h4>
                                        <p>Your registration successfully completed. We will review this within the next
                                            24 hours but this may take longer during weekends and holidays. Once we have
                                            reviewed and confirmed your ID, you will be able to complete your setup and
                                            start earning.</p>
                                        <p>In the meantime you can setup your profile and manage your bank account.</p>
                                        <a href="javascript:;" class="allBtn mt-4"
                                            onclick="$('.bcmVipArea .x-close').trigger('click'); $('.loginBtn').trigger('click');">Login
                                            To Your Account</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade cropImageModal" id="cropImagePop" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <button type="button" class="close-modal-custom" data-dismiss="modal" aria-label="Close"><i
            class="feather icon-x"></i></button>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="modal-header-bg"></div>
                <div class="up-photo-title">
                    <h3 class="modal-title">Update Profile Photo</h3>
                </div>
                <div class="up-photo-content pb-5">
                    <div id="upload-demo" class="center-block">
                        <h5><i class="fas fa-arrows-alt mr-1"></i> Drag your photo as you require</h5>
                    </div>
                    <div class="upload-action-btn text-center px-2">
                        <button type="button" id="cropImageBtn"
                            class="btn btn-default btn-medium bg-blue px-3 mr-2">Save Photo</button>
                        <button type="button"
                            class="btn btn-default btn-medium bg-default-light px-3 ml-sm-2 replacePhoto position-relative">Replace
                            Photo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade vfModalMd" id="vipphoneOtpVerifyModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Enter your verification code</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="mbInner">
                    <h5>A verification code was sent via sms.When you receive the code,Enter it below</h5>
                    <div class="otpAtra">
                        <div class="otpTop">
                            <h4>OTP</h4>
                        </div>
                        <div class="otpBtm d-flex">
                            <input type="text" placeholder="OTP PASSWORD" name="vipphone_otp_password"
                                id="vipphone_otp_password" onKeyPress="return isNumber(event)">
                            <button type="button" onClick="vipmatchPhoneOtpPassword()">OK</button>
                        </div>
                        <span id="viperror_phone_otp_msg" class="tryAgain"></span>
                    </div>
                    <div class="rcvSms text-center"> Didn't receive the sms <a href="javascript:;"
                            class="vipsendPhoneOtp">Resend</a> </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade vfModalMd" id="vipemailOtpVerifyModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Enter your verification code</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="mbInner">
                    <h5>A verification code was sent via email to <strong id="toEmail"></strong>.When you receive the
                        code,Enter it below</h5>
                    <div class="otpAtra">
                        <div class="otpTop">
                            <h4>OTP</h4>
                        </div>
                        <div class="otpBtm d-flex">
                            <input type="text" placeholder="OTP PASSWORD" name="email_otp_password"
                                id="vip_email_otp_password" onKeyPress="return isNumber(event)">
                            <button type="button" onClick="vipmatchEmailOtpPassword()">OK</button>
                        </div>
                        <span id="viperror_otp_msg" class="tryAgain"></span>
                    </div>
                    <div class="rcvSms text-center"> Didn't receive the sms <a href="javascript:;"
                            class="vipsendEmailOtp">Resend</a>
                        <p>Note: you may not be on our approved email list. This may require you to look for your
                            verification code in your SPAM or JUNK folder. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script type="text/javascript">
        var phone_number = window.intlTelInput(document.querySelector("#input-vip-phone"), {
            separateDialCode: true,
            preferredCountries: ["us"],
            //initialCountry: "us",
            hiddenInput: "full",
            utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
        });

        $(document).on('click', '.vipsendPhoneOtp', function() {
            var phone = phone_number.getNumber(intlTelInputUtils.numberFormat.E164);
            if (phone == '') {
                $('#input-vip-phone').removeClass('black_border').addClass('red_border');
            } else {
                $('#input-vip-phone').removeClass('red_border').addClass('black_border');
                $('#vipphone_otp_password').val('');
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'send_phone_otp',
                        phone: phone,
                        _token: prop.csrf_token
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $(".mw_loader").fadeIn();
                    },
                    complete: function() {
                        $('.mw_loader').fadeOut();
                    },
                    success: function(data) {
                        if (data.error != '') {
                            alert(data.error);
                        }
                        if (data.success == 0) {
                            $('#vipphone_otp_error').show().text(data.msg);
                            $('#input-vip-phone').removeClass('black_border').addClass('red_border');

                        } else {
                            $('#vipphone_otp_error').hide().text('');
                            $('#input-vip-phone').removeClass('red_border').addClass('black_border');
                            $('#vipphoneOtpVerifyModal').modal('show');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('.mw_loader').fadeOut();
                    }
                });
            }
        });

        function vipmatchPhoneOtpPassword() {
            var otp_password = $('#vipphone_otp_password').val();
            if (otp_password == '') {
                $('#vipphone_otp_password').removeClass('black_border').addClass('red_border');
            } else {
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'phoneOtpValidation',
                        otp_password: otp_password,
                        _token: prop.csrf_token
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data.action);
                        if (data.action == 1) {
                            $('.registerArea input[name="mobile"]').closest('.form-group').find('.error')
                                .hide();
                            $('.bcmVipArea input[name="mobile"]').closest('.form-group').find('.error').hide();
                            $('#viperror_phone_otp_msg').hide();
                            $('.vipsendPhoneOtp').parent().addClass('verified');
                            $('.verified').html(
                                '<a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a>'
                            );
                            $('#vipphone_otp_verify').val('true');
                            $('#vipphone_otp_password').removeClass('red_border').addClass('black_border');
                            $('#vipphoneOtpVerifyModal').modal('hide');
                            $('#input-vip-phone').attr('readonly', true);
                        } else {
                            $('#viperror_phone_otp_msg').text('Not macth!');
                            $('#vipphone_otp_password').removeClass('black_border').addClass('red_border');
                        }
                    }
                });

            }
        }

        $(document).on('click', '.vipsendEmailOtp', function() {
            var email = $('#input-vip-email').val();
            var role = 3;
            if (email == '') {
                $('#input-vip-email').removeClass('black_border').addClass('red_border');
            } else {
                $('#input-vip-email').removeClass('red_border').addClass('black_border');
                $('#email_otp_password').val('');
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'send_email_otp',
                        role: role,
                        email: email,
                        _token: prop.csrf_token
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $(".mw_loader").fadeIn();
                    },
                    complete: function() {
                        $('.mw_loader').fadeOut();
                    },
                    success: function(data) {
                        $('.bcmVipArea input[name="email"]').closest('.form-group').find('.error')
                            .hide();
                        if (data.success == 0) {
                            $('.bcmVipArea input[name="email"]').closest('.form-group').append(
                                "<div class='error'>" + data.error_msg + "</div>");
                            $('#input-vip-email').removeClass('black_border').addClass('red_border');
                        } else {
                            $('#vipemail_otp_error').hide().text('');
                            $('#input-vip-email').removeClass('red_border').addClass('black_border');
                            $('#vipemailOtpVerifyModal').modal('show');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        $('.mw_loader').fadeOut();
                    }
                });
            }
        });

        function vipmatchEmailOtpPassword() {
            var otp_password = $('#vip_email_otp_password').val();
            if (otp_password == '') {
                $('#vip_email_otp_password').removeClass('black_border').addClass('red_border');
            } else {
                $.ajax({
                    url: prop.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'emailOtpValidation',
                        otp_password: otp_password,
                        _token: prop.csrf_token
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data.action);
                        if (data.action == 1) {
                            $('.bcmVipArea input[name="email"]').closest('.form-group').find('.error').hide();
                            $('#viperror_otp_msg').hide();
                            $('.vipsendEmailOtp').parent().addClass('verified');
                            $('.verified').html(
                                '<a href="javascript:;" class="radius3"><i class="fa fa-check-square-o"></i> Verified</a>'
                            );
                            $('#vipemail_otp_verify').val('true');
                            $('#vip_email_otp_password').removeClass('red_border').addClass('black_border');
                            $('#vipemailOtpVerifyModal').modal('hide');
                            $('#input-vip-email').attr('readonly', true);
                        } else {
                            $('#viperror_otp_msg').text('Not macth!').show();
                            $('#vip_email_otp_password').removeClass('black_border').addClass('red_border');
                        }
                    }
                });

            }
        }


        function vipmember_register_google_location_search() {
            autocomplete_vip = new google.maps.places.Autocomplete($('.bcmVipArea input[name="location"]')[0], {
                //componentRestrictions: { country: ["us", "ca"] },
                //fields: ["address_components", "geometry"],
                //types: ["address"],
            });
            autocomplete_vip.addListener("place_changed", function() {
                var place = autocomplete_vip.getPlace();
                //console.log(place.address_components);
                var address_line_1 = [];
                var country_code = '';
                var zipcode = '';
                $(place.address_components).each(function(i, v) {
                    console.log(v);
                    if ($.inArray('subpremise', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('street_number', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('route', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('sublocality_level_2', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('sublocality_level_1', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('locality', v.types) >= 0) address_line_1.push(v.long_name);
                    if ($.inArray('administrative_area_level_2', v.types) >= 0) address_line_1.push(v
                        .long_name);
                    if ($.inArray('administrative_area_level_1', v.types) >= 0) address_line_1.push(v
                        .long_name);
                    if ($.inArray('country', v.types) >= 0) country_code = v.short_name;
                    if ($.inArray('postal_code', v.types) >= 0) zipcode = v.long_name;
                });
                /*console.log(address_line_1);
                console.log(country_code);
                console.log(zipcode);*/
                $('.bcmVipArea input[name="address_line_1"]').val(address_line_1.join(', '));
                $('.bcmVipArea input[name="country_code"]').val(country_code);
                $('.bcmVipArea input[name="zip_code"]').val(zipcode);
            });
        }


        $(document).ready(function() {



        });
    </script>
@endpush
