@extends('layouts.front')
@section('content')

<?php
$my_fav_user_ids = [];
if(isset($user_data['id'])) {
    $my_fav_user_ids = \App\User_fav_user::where('user_id', $user_data['id'])->pluck('fav_user_id')->toArray();
}
?>


<section class="our-profileBanner relative bCover d-flex align-items-center justify-content-center" style="background: url({{ URL::asset('public/front/images/our-profile-banner.png') }}) center center no-repeat #000;">
</section>

<?php
/*for ($i=0; $i < 20; $i++) {
    $meta_data['vip_members'][(2 + $i)] = $meta_data['vip_members'][0];
}*/

$set_1 = $set_2 = $set_3 = [];
foreach ($meta_data['vip_members'] as $key => $value) {
    if($key < 4) $set_1[] = $value;
    if($key >= 4 && $key < 12) $set_2[] = $value;
    if($key >= 12) $set_3[] = $value;
}

function vip_member_html($data, $params) {
    $user_data = $params['user_data'];
    $my_fav_user_ids = $params['my_fav_user_ids'];
    //$active = ($data->online == 1 ? 'active' : '');
    $active = ($data->live_session_id != '' ? 'active' : '');
    $profile_photo = URL::asset('public/front/images/user-placeholder.jpg');
    if($data->usermeta_data['profile_photo'] != '')
        $profile_photo = url('/public/uploads/profile_photo/' . $data->usermeta_data['profile_photo']);
    $fav_html = '<i class="far fa-heart" onclick="$(\'.loginBtn\').trigger(\'click\');"></i>';
    if(isset($user_data['role']) && $user_data['role'] == 3) {
        $cls = 'far';
        if(in_array($data->id, $my_fav_user_ids)) $cls = 'fas';
        $fav_html = '<i class="' . $cls . ' fa-heart set_user_fav" user_id="' . $data->id . '"></i>';
    }
    if(isset($user_data['role']) && $user_data['role'] == 2)
        $fav_html = '';
    $html = '<div class="col-lg-3 col-md-3 col-sm-6 col-12">
        <div class="performerBox w-100 ' . $active . ' wow wow fadeIn delay1 relative">
            <!--<div class="option-top-rgt">
                <div class="option-top-rgt-wrap relative">
                    <button type="button" class="option-top-btn"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="option-rgt-details">
                        <li><a href="#">option1</a></li>
                        <li><a href="#">option2</a></li>
                        <li><a href="javascript:;" class="report_user" user_id="' . $data->id . '">Report User</a></li>
                    </ul>
                </div>
            </div>-->
            <div class="performerImg w-100">

                <a href="' . url('u/' . $data->username) . '"><img src="' . $profile_photo . '" alt=""><span><i class="fas fa-video"></i></span></a>
            </div>
            <div class="performerdecc w-100 text-center">
                <h4><a href="' . url('u/' . $data->username) . '">' . $data->display_name . '</a><span>' . $fav_html . '</span></h4>
                <h5>@' . $data->username . '</h5>
                <!--<p><span>£3.50</span>/Month</p>-->
                <ul class="d-flex justify-content-between">
                    <li><a href="' . url('u/' . $data->username . '/photos') . '"><i class="ti-image"></i> ' . $data->photo_post_count . '</a></li>
                    <li><a href="' . url('u/' . $data->username . '/videos') . '"><i class="ti-video-camera"></i> ' . $data->video_post_count . '</a></li>
                </ul>
            </div>
            <div class="performerBtn text-center">
                <a href="' . url('u/' . $data->username) . '">view profile</a>
                <span class="add_to_hotlist" fav_user_id="' . $data->id . '"><i class="fas fa-fire"></i>Add to my hotlist</span>
            </div>
        </div>
    </div>';
    return $html;
}
?>

<section class="performer">
    <div class="container">
        <div class="row">
            <?php foreach ($set_1 as $key => $value) {
                echo vip_member_html($value, ['user_data' => $user_data, 'my_fav_user_ids' => $my_fav_user_ids]);
            } ?>
            <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="performerBox w-100 active wow wow fadeIn delay1">
                    <div class="performerImg w-100">

                        <a href="#"><img src="{{ URL::asset('public/front/images/performer/1.jpg') }}" alt=""><span><i class="fas fa-video"></i></span></a>
                    </div>
                    <div class="performerdecc w-100 text-center">
                        <h4><a href="#">KellyLouiseX</a><span><i class="ti-heart"></i></span></h4>
                        <h5>@Bikiniwarrior</h5>
                        <p><span>£3.50</span>/Month</p>
                        <ul class="d-flex justify-content-between">
                            <li><a href="#"><i class="ti-image"></i> 40</a></li>
                            <li><a href="#"><i class="ti-video-camera"></i> 60</a></li>
                        </ul>
                    </div>
                    <div class="performerBtn text-center">
                        <a href="#">view profile</a>
                        <span><i class="fas fa-fire"></i>Add to my hotlist</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="performerBox w-100 wow fadeIn delay2">
                    <div class="performerImg w-100">

                        <a href="#"><img src="{{ URL::asset('public/front/images/performer/1.jpg') }}" alt=""><span><i class="fas fa-video"></i></span></a>
                    </div>
                    <div class="performerdecc w-100 text-center">
                        <h4><a href="#">KellyLouiseX</a><span><i class="ti-heart"></i></span></h4>
                        <h5>@Bikiniwarrior</h5>
                        <p><span>£3.50</span>/Month</p>
                        <ul class="d-flex justify-content-between">
                            <li><a href="#"><i class="ti-image"></i> 40</a></li>
                            <li><a href="#"><i class="ti-video-camera"></i> 60</a></li>
                        </ul>
                    </div>
                    <div class="performerBtn text-center">
                        <a href="#">view profile</a>
                        <span><i class="fas fa-fire"></i>Add to my hotlist</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="performerBox w-100 wow fadeIn delay3">
                    <div class="performerImg w-100">

                        <a href="#"><img src="{{ URL::asset('public/front/images/performer/1.jpg') }}" alt=""><span><i class="fas fa-video"></i></span></a>
                    </div>
                    <div class="performerdecc w-100 text-center">
                        <h4><a href="#">KellyLouiseX</a><span><i class="ti-heart"></i></span></h4>
                        <h5>@Bikiniwarrior</h5>
                        <p><span>£3.50</span>/Month</p>
                        <ul class="d-flex justify-content-between">
                            <li><a href="#"><i class="ti-image"></i> 40</a></li>
                            <li><a href="#"><i class="ti-video-camera"></i> 60</a></li>
                        </ul>
                    </div>
                    <div class="performerBtn text-center">
                        <a href="#">view profile</a>
                        <span><i class="fas fa-fire"></i>Add to my hotlist</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                <div class="performerBox w-100 wow fadeIn delay4">
                    <div class="performerImg w-100">

                        <a href="#"><img src="{{ URL::asset('public/front/images/performer/1.jpg') }}" alt=""><span><i class="fas fa-video"></i></span></a>
                    </div>
                    <div class="performerdecc w-100 text-center">
                        <h4><a href="#">KellyLouiseX</a><span><i class="ti-heart"></i></span></h4>
                        <h5>@Bikiniwarrior</h5>
                        <p><span>£3.50</span>/Month</p>
                        <ul class="d-flex justify-content-between">
                            <li><a href="#"><i class="ti-image"></i> 40</a></li>
                            <li><a href="#"><i class="ti-video-camera"></i> 60</a></li>
                        </ul>
                    </div>
                    <div class="performerBtn text-center">
                        <a href="#">view profile</a>
                        <span><i class="fas fa-fire"></i>Add to my hotlist</span>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</section>


<?php if(count($set_2) > 0) { ?>
<section class="paralaxBg parallax d-flex align-items-center justify-content-center" data-image-src="{{ URL::asset('public/front/images/paralax/1.jpg') }}" data-height="330px">
    <div class="parallaxWrap">
        <div class="paralaxInner">
            <h2 class="wow fadeInUp">Lorem Ipsum Lorem Ipsum</h2>
            <p class="wow fadeInUp delay1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus </p>
            <a href="#" class="allBtn wow fadeInUp delay3">click here</a>
        </div>
    </div>
</section>

<section class="performer">
    <div class="container">
        <div class="row">
            <?php foreach ($set_2 as $key => $value) {
                echo vip_member_html($value, ['user_data' => $user_data, 'my_fav_user_ids' => $my_fav_user_ids]);
            } ?>
        </div>
    </div>
</section>
<?php } ?>


<?php if(count($set_3) > 0) { ?>
<section class="paralaxBg parallax d-flex align-items-center justify-content-center text-color-black" data-image-src="{{ URL::asset('public/front/images/paralax/paralax2.jpg') }}" data-height="330px">
    <div class="parallaxWrap">
        <div class="paralaxInner">
            <h2 class="wow fadeInUp">Lorem Ipsum Lorem Ipsum</h2>
            <p class="wow fadeInUp delay1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus </p>
            <a href="#" class="allBtn wow fadeInUp delay3">click here</a>
        </div>
    </div>
</section>

<section class="performer">
    <div class="container">
        <div class="row">
            <?php foreach ($set_3 as $key => $value) {
                echo vip_member_html($value, ['user_data' => $user_data, 'my_fav_user_ids' => $my_fav_user_ids]);
            } ?>
        </div>
    </div>
</section>
<?php } ?>


<section class="samplePageWrap">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="samplePage">
                    <div class="samplePage d-flex justify-content-center">
                        {!! App\Http\Helpers::paginate($meta_data['pagination']['per_page'], $meta_data['pagination']['cur_page'], $meta_data['pagination']['total_data'], $meta_data['pagination']['page_url'], $meta_data['pagination']['additional_params'], '') !!}
                        <?php
                        /*
                        {!! App\Http\Helpers::paginate(10, 25, 8524, '#', '', 'pagination-sm no-margin pull-right') !!}

                        */
                        ?>
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
