@extends('layouts.front')
@section('content')

<section class="changePasswordSec">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="loginBody changePassword">
                    <div class="loginAreaInner">
                        <h2>Reset Password</h2>
                        <form method="post">
                            @csrf
                            <div class="emailPassWrap-2 w-100">
                                <div class="form-group relative">
                                    <span class="selOpIcon"><i class="fas fa-envelope"></i></span>
                                    <input type="text" name="email" value="{{ $meta_data['data']['user']->email }}" placeholder="Enter Email Address" class="input-2">
                                </div>
                                <div class="form-group relative">
                                    <span class="selOpIcon"><i class="fas fa-key"></i></span>
                                    <input type="password" name="new_password" id="" placeholder="New Password" class="input-2">
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <input type="hidden" name="token" value="{{ $meta_data['data']['user']->password_reset_token }}">
                                <button class="logInBtn reset_password_submit" type="submit">Change Password</button>
                            </div>
                            <div class="response">
                                <?php
                                if(Session::has('error'))
                                    echo '<p class="error">' . Session::get('error') . '</p>';
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
           
        </div>
    </div>
</section>

@stop





@push('script')

<script type="text/javascript">
    $(document).ready(function(){

        

    });
</script>

@endpush