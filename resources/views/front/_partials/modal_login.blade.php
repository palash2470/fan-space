<section class="loginArea">
    <div class="loginBody" for_login>
        <h3>membership login <span><a class="x-close" href="javascript:;">x</a></span></h3>
        <div class="loginAreaInner">
            <div class="loginAreaInnerTitle text-center"><span>Select Your Platform</span></div>
            <form action="{{ url('login') }}" method="post">
                @csrf
                <div class="form-group relative mt-3 mb-4">
                    <span class="selOpIcon"><i class="fas fa-user-circle"></i></span>
                    <select name="role" class="selectMd">
                        <option value="3">Follower</option>
                        <option value="2">VIP Member</option>
                        <!-- <option value="1">Admin</option> -->
                    </select>
                </div>
                <h3>Sign in to access your enrolled classes and account information</h3>
                <h6>By Creating an account, you agree to our<br>
                    <a href="#">Terms of services</a> and <a href="#">Privacy Policy</a></h6>
                <div class="emailPassWrap w-100">
                    <div class="form-group relative">
                        <span class="selOpIcon"><i class="fa fa-user"></i></span>
                        <input type="text" name="email" id="" placeholder="Enter Email Address" class="input-2">
                    </div>
                    <div class="form-group relative">
                        <span class="selOpIcon"><i class="fa fa-user"></i></span>
                        <input type="password" name="password" id="" placeholder="Password" class="input-2">
                    </div>
                    {{-- <div class="form-group relative reChapcha">
                        <!-- <img src="{{ URL::asset('/public/front/images/recaptcha.jpg') }}" alt=""> -->
                        <div class="g-recaptcha" data-sitekey="<?php echo $meta_data['global_settings']['settings_google_recaptcha_site_key']; ?>"></div>
                    </div> --}}
                </div>

                <div class="forgetPass w-100 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="checkbox checkbox-info pl-1">
                        <input id="rp-1" class="styled" type="checkbox" name="remember" value="1">
                        <label for="rp-1">Remember password</label>
                    </div>
                    <a href="javascript:;" class="to_forgot_password">Forgot Your Password?</a>
                </div>
                <div class="form-group text-center">
                    <button class="logInBtn login_submit" type="button">log In</button>
                </div>
                <div class="response"></div>
                <div class="needAccount w-100 text-center">Need an Account? <a href="#">Sign Up</a></div>
                
                <div class="orArea relative text-center">
                    <span>Or</span>
                </div>
                <div class="socilaBtn">
                    <a href="javascript:;" class="fbIcon" social_login="facebook"><span><i class="fab fa-facebook"></i></span> log In with Facebook</a>
                </div>
                <div class="socilaBtn">
                    <a href="javascript:;" class="googleIcon" social_login="google"><span><i class="fab fa-google"></i></span> log In with google</a>
                </div>
            </form>
        </div>
    </div>
    <div class="loginBody" for_forgot_password style="display: none;">
        <h3>forgot password <span><a class="x-close" href="javascript:;">x</a></span></h3>
        <div class="loginAreaInner">
            <form action="{{ url('forgot_password') }}" method="post">
                @csrf
                <div class="emailPassWrap w-100">
                    <div class="form-group relative">
                        <span class="selOpIcon"><i class="fa fa-user"></i></span>
                        <input type="text" name="email" id="" placeholder="Enter Email Address" class="input-2">
                    </div>
                </div>
                <div class="form-group text-center">
                    <button class="logInBtn forgot_password_submit" type="button">Get Reset Link</button>
                </div>
                <div class="response"></div>
                <div class="needAccount w-100 text-center">Have an Account? <a href="javascript:;" class="to_login">Log in?</a></div>
            </form>
        </div>
    </div>
</section>