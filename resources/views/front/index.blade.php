@extends('layouts.front')
@section('content')

<?php if(Session::has('error')) {
    echo '<div class="alert alert-danger" role="alert">' . Session::get('error') . '</div>';
} ?>
    <section class="homeBanner relative bCover d-flex align-items-center"
        style="background: url({{ asset('public/admin/dist/img/dynamic-home-content/' . $meta_data['page_content']->content['banner_background_image']) }}) center right no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                    <div class="uTubeVideo" class="wow fadeInUp">
                        <iframe src="{{ $meta_data['page_content']->content['youtube_video_link'] }}"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                    <div class="sloganArea">
                        {{-- <h2 class="wow fadeInUp delay1">share or acces exlcusive content as a vip or admirer</h2>
                    <p class="wow fadeInUp delay2">ecome a VIP and make money by uploading exclusive content and charging your followers a monthly subscription fee. Or become an admirer and start following your favourite VIP’s for exclusive conten</p> --}}
                        {!! $meta_data['page_content']->content['content'] !!}
                        <?php if(!isset($user_data['id'])) { ?>
                        <ul class="d-flex">
                            <li class="wow fadeInUp delay3"><a href="javascript:;" class="bcmFollowerBtn">Become a Follower
                                </a></li>
                            <li class="wow fadeInUp delay4"><a href="javascript:;" class="bcmVipBtn">Become a VIP</a></li>
                        </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="performer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="sectionTitle text-uppercase text-center">
                        <h2>Our Latest Performers</h2>
                    </div>
                </div>
                @foreach ($meta_data['latest_model'] as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="performerBox w-100 wow fadeIn delay2">
                            <div class="performerImg w-100">

                                <a href="#"><img
                                        src="{{ !empty($item['profile_photo']) ? url('/public/uploads/profile_photo/' . $item['profile_photo']) : URL::asset('public/front/images/user-placeholder.jpg') }}"
                                        alt=""><span><i class="fas fa-video"></i></span></a>
                            </div>
                            <div class="performerdecc w-100 text-center">
                                <h4><a href="{{ url('u/' . $item['username']) }}">{{ $item['display_name'] }}</a><span><i
                                            class="ti-heart"></i></span></h4>
                                <h5>@<?php echo $item['username']; ?></h5>
                                <ul class="d-flex justify-content-between">
                                    <li><a href="#"><i class="ti-image"></i> {{ $item['image_post'] }}</a></li>
                                    <li><a href="#"><i class="ti-video-camera"></i> {{ $item['video_post'] }}</a></li>
                                </ul>
                            </div>
                            <div class="performerBtn text-center">
                                <a href="{{ url('u/' . $item['username']) }}">view profile</a>
                                {{-- <span><i class="fas fa-fire"></i>Add to my hotlist</span> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="paralaxBg parallax d-flex align-items-center justify-content-center"
        data-image-src="{{ asset('public/admin/dist/img/dynamic-home-content/' . $meta_data['page_content']->content['second_banner_background_image']) }}"
        data-height="330px">
        <div class="parallaxWrap">
            <div class="paralaxInner">
                {!! $meta_data['page_content']->content['second_banner_content'] !!}
                <a href="{{ $meta_data['page_content']->content['button_link'] }}" class="allBtn wow fadeInUp delay3">click
                    here</a>
            </div>
        </div>
    </section>
    <section class="performer">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="sectionTitle text-uppercase text-center">
                        <h2>Our Top Performers</h2>
                    </div>
                </div>
                @foreach ($meta_data['top_performer'] as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="performerBox w-100 wow fadeIn delay2">
                            <div class="performerImg w-100">

                                <a href="#"><img
                                        src="{{ !empty($item['profile_photo']) ? url('/public/uploads/profile_photo/' . $item['profile_photo']) : URL::asset('public/front/images/user-placeholder.jpg') }}"
                                        alt=""><span><i class="fas fa-video"></i></span></a>
                            </div>
                            <div class="performerdecc w-100 text-center">
                                <h4><a href="{{ url('u/' . $item['username']) }}">{{ $item['display_name'] }}</a><span><i
                                            class="ti-heart"></i></span></h4>
                                <h5>@<?php echo $item['username']; ?></h5>
                                <ul class="d-flex justify-content-between">
                                    <li><a href="#"><i class="ti-image"></i> {{ $item['image_post'] }}</a></li>
                                    <li><a href="#"><i class="ti-video-camera"></i> {{ $item['video_post'] }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="performerBtn text-center">
                                <a href="{{ url('u/' . $item['username']) }}">view profile</a>
                                {{-- <span><i class="fas fa-fire"></i>Add to my hotlist</span> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


@stop


@push('partial_html')
@endpush



@push('script')
    <script type="text/javascript">
        $(document).ready(function() {



        });
    </script>
@endpush
