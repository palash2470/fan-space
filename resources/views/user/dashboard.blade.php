@extends('layouts.front')
@section('content')


    <section class="dashboard-sec-speace">
        <div class="container-fluid p-h-40">

            @if (Session::has('success'))
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    {!! Session::get('success') !!}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    {!! Session::get('error') !!}
                </div>
            @endif

            <div class="d-flex relative">
                <div class="overlay">
                    <div class="new-dashboard-menu">
                        <!-- <div class="dashboard-user d-flex align-items-center">
                            <?php
                            $profile_photo = URL::asset('/public/front/images/user-placeholder.jpg');
                            if (isset($user_data['meta_data']['profile_photo']) && $user_data['meta_data']['profile_photo'] != '') {
                                $profile_photo = url('public/uploads/profile_photo/' . $user_data['meta_data']['profile_photo']);
                            }
                            ?>
                              <div class="dashboard-user-img">
                                  <span><img src="{{ $profile_photo }}" alt=""></span>
                              </div>
                              <div class="dashboard-user-details">
                                  <h3>{{ $user_data['first_name'] . ' ' . $user_data['last_name'] }}</h3>
                              </div>
                            </div> -->
                        <ul class="menu-scroll" id="left-menu-scroll" data-scrollbar>
                            <li class="{{ $meta_data['slug'] == 'home' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/home') }}"><span class="ti-home"></span>
                                    <p>home</p>
                                </a></li>
                            <?php if($user_data['role'] == 2) { ?>
                            {{-- <li class="{{ $meta_data['slug'] == 'go_live' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/go_live') }}"><i class="fas fa-video"></i>
                                    <p>Live</p>
                                </a>
                            </li> --}}
                            <li class="{{ $meta_data['slug'] == 'go_live' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/go_live_chat') }}" target="_blank"><i class="fas fa-video"></i>
                                    <p>Live</p>
                                </a>
                            </li>
                            <li class="{{ $meta_data['slug'] == 'subscribers' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/subscribers') }}"><span class="ti-thumb-up"></span>
                                    <p>Subscribers</p>
                                </a></li>
                            <!-- <li><a href="#"><i class="fas fa-user"></i>subscribers</a></li> -->
                            <li class="{{ $meta_data['slug'] == 'notification' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/notification') }}"><span class="ti-bell"></span>
                                    <p>Notification</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'orders' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/orders') }}"><i class="fas fa-cubes"></i>
                                    <p>Orders</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'my_store' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/my_store') }}"><i class="fas fa-store"></i>
                                    <p>my store</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'product' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/product') }}"><i class="fab fa-product-hunt"></i>
                                    <p>upload product</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'message' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/message') }}"><span class="ti-comment"></span>
                                    <p>Message</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'wallet' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/wallet') }}"><span class="ti-wallet"></span>
                                    <p>wallet</p>
                                </a></li>
                                <li class="{{ $meta_data['slug'] == 'bank_details' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/bank_details') }}"><i class="fas fa-piggy-bank"></i>
                                    <p>Bank Details</p>
                                </a></li>
                                <li class="{{ $meta_data['slug'] == 'ticket' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/ticket') }}"><span class="ti-ticket"></span>
                                    <p>Support Tickets</p>
                                </a></li>
                                {{-- <li class="{{ $meta_data['slug'] == 'ticket' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/ticket') }}"><span class="ti-ticket"></span>
                                    <p>wallet</p>
                                </a></li> --}}
                            {{-- <li><a href="#"><span class="ti-unlock"></span> membership and plan</a></li> --}}
                            {{-- <li><a href="#"><i class="fas fa-igloo"></i>vault</a></li> --}}
                            <!--<li><a href="#"><span class="ti-gallery"></span>upload media</a></li>-->
                            <?php } ?>
                            <?php if($user_data['role'] == 3) { ?>
                            <!-- <li><a href="#"><span class="ti-bell"></span>Notification</a></li>
                                    <li><a href="#"><span class="ti-comment"></span>Message</a></li>
                                    <li><a href="#"><span class="ti-bookmark"></span>Bookmarks</a></li>
                                    <li><a href="#"><i class="fas fa-list-ul"></i>List</a></li>
                                    <li><a href="#"><i class="fas fa-user"></i>Subscription</a></li>
                                    <li><a href="#"><i class="fas fa-user-circle"></i>User</a></li> -->
                            <li class="{{ $meta_data['slug'] == 'message' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/message') }}"><span class="ti-comment"></span>
                                    <p>Message</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'subscription' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/subscription') }}"><span class="ti-thumb-up"></span>
                                    <p>Subscription</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'following' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/following') }}"><i class="fas fa-users"></i>
                                    <p>Following</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'hotlist' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/hotlist') }}"><i class="fas fa-fire"></i>
                                    <p>Hotlist</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'favorites' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/favorites') }}"><i class="fas fa-heart"></i>
                                    <p>Favorites</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'my_orders' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/my_orders') }}"><i class="fas fa-cubes"></i>
                                    <p>My Orders</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'notification' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/notification') }}"><span class="ti-bell"></span>
                                    <p>Notification</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'ticket' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/ticket') }}"><span class="ti-ticket"></span>
                                    <p>Support Tickets</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'buy_coins' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/buy_coins') }}"><i class="fas fa-database"></i>
                                    <p>Buy Coins</p>
                                </a></li>
                            <li class="{{ $meta_data['slug'] == 'live_users' ? 'active' : '' }}"><a
                                href="{{ url('dashboard/live_users') }}"><i class="fas fa-video"></i><span class="badge badge-light">{{$meta_data['live_user_count']}}</span>
                                <p>Live Users</p>
                            </a></li>
                            <?php } ?>

                            <li class="{{ $meta_data['slug'] == 'profile' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/profile') }}"><i class="fas fa-user-edit"></i>
                                    <p>Profile</p>
                                </a></li>
                            <?php if($user_data['role'] == 2) { ?>
                            <!-- <li class="{{ $meta_data['slug'] == 'about' ? 'active' : '' }}"><a href="{{ url('dashboard/about') }}"><i class="fas fa-user"></i>About</a></li> -->
                            {{-- <li class="{{ $meta_data['slug'] == 'bank_details' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/bank_details') }}"><i class="fas fa-piggy-bank"></i>
                                    <p>Bank Details</p>
                                </a></li> --}}
                            <li class="{{ $meta_data['slug'] == 'settings' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/settings') }}"><i class="fas fa-cog"></i>
                                    <p>Settings</p>
                                </a></li>
                            <?php } ?>
                            <li class="{{ $meta_data['slug'] == 'change_password' ? 'active' : '' }}"><a
                                    href="{{ url('dashboard/change_password') }}"><i class="fas fa-key"></i>
                                    <p>Change Password</p>
                                </a></li>
                            <!-- <li><a href="#"><span class="ti-more"></span>More</a></li> -->
                        </ul>
                    </div>
                </div>

                <div class="wrap-rgt">

                    <?php
                    $dir = '';
                    if ($user_data['role'] == 2) {
                        $dir = 'vip_member';
                    }
                    if ($user_data['role'] == 3) {
                        $dir = 'follower';
                    }
                    ?>
                    @include('user.includes.' . $dir . '.' . $meta_data['slug'])

                </div>
                <?php if($user_data['role'] == 3 && isset($meta_data['right_sidebar'])) { ?>
                <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                    <div class="dashboard-rgt">
                        <div class="dashboard-post-sec relative">
                            <input type="text" name="" id="" class="post-src">
                            <button type="button" class="postlSearchBtn"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="dashboard-post-sliderwrap">
                            <div class="slider-top d-flex justify-content-between align-items-center w-100">
                                <h4>suggestions</h4>
                                <ul class="d-flex">
                                    <li><a href="#"><i class="fas fa-tags"></i></a></li>
                                    <li><a href="#"><i class="fas fa-sync-alt"></i></a></li>
                                </ul>
                            </div>
                            <div class="loop owl-carousel dashboard-post-slider">
                                <div class="item">
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashboard-slider-box relative"
                                        style="background: url({{ URL::asset('/public/front/images/dashboard-slider/slider-image1.jpg') }}) no-repeat center center;">
                                        <div class="slider-name-box-wrap">
                                            <div class="slider-name-box d-flex align-items-center justify-content-center">
                                                <a href="#">
                                                    <div class="slider-user-img">
                                                        <span><img
                                                                src="{{ URL::asset('/public/front/images/user-pic.jpg') }}"
                                                                alt=""></span>
                                                    </div>
                                                    <div class="slider-name-details">
                                                        <h5>Nicola Russin</h5>
                                                        <p>@nicrussin</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="free-text">
                                            <p>free</p>
                                        </div>
                                        <div class="slider-option">
                                            <div class="slider-option-wrap relative">
                                                <button type="button" class="slider-option-btn"><i
                                                        class="fas fa-ellipsis-v"></i></button>
                                                <ul class="slider-option-btn-details">
                                                    <li><a href="#">option1</a></li>
                                                    <li><a href="#">option2</a></li>
                                                    <li><a href="#">option3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>




@stop


@push('script')
{{-- <script>
    (function($){
        $(window).on("load",function(){
            
            $("#content-1").mCustomScrollbar({
                theme:"minimal"
            });
            
        });
    })(jQuery);
</script> --}}
{{-- <script>
    $(document).ready(function(){
        let div_height = $('.wrap-rgt').height()-30;
        $('.menu-scroll').css('height',div_height);
    });
</script> --}}
    <script src="{{ url('public/front/js/smooth-scrollbar.js') }}"></script>
    <script>
        Scrollbar.initAll();
    </script>
    <script type="text/javascript">
        $('.dashboard-post-slider').owlCarousel({
            autoplay: false,
            stagePadding: 5,
            autoplayTimeout: 5000,
            loop: true,
            margin: 8,
            nav: true,
            dots: true,
            navElement: 'div',
            navText: ["<span class='ti-angle-left'></span>", "<span class='ti-angle-right'></span>"],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });

        // $(document).on('ready', function() {});

        
    </script>
@endpush
