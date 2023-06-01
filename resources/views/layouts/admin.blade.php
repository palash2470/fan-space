<?php
//dd($meta_data);
/*if(!isset($_GET['dev'])) { ?>
?>
?>
?>
?>
?>
?>
<style type="text/css">
    .content-wrapper * {
        display: none;
    }

</style>
<?php }*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $page_title ?? '' }} | Admin</title>
    <!-- <link rel="icon" href="{{ URL::asset('/public/admin/dist/img/logo-icon.png') }}" type="image/png" > -->
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet"
        href="{{ URL::asset('/public/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="{{ URL::asset('/public/admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet"
        href="{{ URL::asset('/public/admin/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/dist/css/skins/_all-skins.min.css') }}">

    <!-- jQuery-UI -->
    <link rel="stylesheet"
        href="{{ URL::asset('/public/admin/bower_components/jquery-ui/themes/base/jquery-ui.css') }}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/plugins/iCheck/all.css') }}">

    <!-- datatable -->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/public/admin/dist/css/custom.css') }}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


    <script type="text/javascript">
        var prop = <?php echo json_encode(['url' => url('/'), 'csrf_token' => csrf_token()]); ?>;
    </script>


    <script type="text/javascript"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="logo" target="_blank">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>D</span>
                <!-- <span class="logo-mini"><img src="{{ URL::asset('/public/admin/dist/img/logo-icon.png') }}" style="max-width: 100%; max-height: 40px; margin: 5px;" /></span> -->
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Admin</b></span>
                <!-- <span class="logo-lg"><img src="{{ URL::asset('/public/admin/dist/img/logo.png') }}" style="max-width: 100%; max-height: 37px; margin: 5px;" /></span> -->
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->

                        <li class="dropdown notifications-menu notify-list">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">{{ count($meta_data['notification']) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have {{ count($meta_data['notification']) }}
                                    notifications</li>
                                <li>
                                    <ul class="menu">
                                        @foreach ($meta_data['notification'] as $item)
                                            <li>
                                                <a href="{{ url($item->link) }}">
                                                    {{ $item->message }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- <img src="{{ URL::asset('/public/admin/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image"> -->
                                <span class="hidden-xs">{{ $user_data['name'] ?? '' }}</span>
                                <i class="fa fa-user"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <?php /*if(isset($global_settings['settings_quick_switch_login_user_data'])) { ?> ?> ?>
                                    ?> ?> ?> ?>
                                    <div class="pull-left">
                                        <a href="{{ url('/admin/quick_switch_login_user') }}"
                                            class="btn btn-default btn-flat">Log in as
                                            {{ $global_settings['settings_quick_switch_login_user_data']->first_name .
                                                '
                                                                                        ' .
                                                $global_settings['settings_quick_switch_login_user_data']->last_name }}</a>
                                    </div>
                                    <?php }*/ ?>

                                    <div class="pull-right">
                                        <!-- <a href="#" class="btn btn-default btn-flat" onclick="event.preventDefault(); if(confirm('Are you sure to logout?')) { document.getElementById('logout-form').submit(); }">Sign out</a> -->
                                        <a href="{{ $user_data['logout_url'] }}" class="btn btn-default btn-flat"
                                            onclick="return confirm('Are you sure to logout?');">Sign out</a>
                                    </div>

                                    <!-- <form id="logout-form" action="{{ url('admin/logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form> -->

                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">


                <?php
                foreach ($meta_data['general_settings']['selected_nav'] as $v) {
                    ${$v} = 'active';
                }
                ?>


                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul>
        </li> -->
                    <li class="{{ $nav_dashboard ?? '' }}">
                        <a href="{{ url('admin/dashboard') }}">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="{{ $nav_vip_member ?? '' }}">
                        <a href="{{ url('admin/vip_member') }}">
                            <i class="fa fa-user-secret"></i> <span>VIP Member</span>
                        </a>
                    </li>
                    <li class="{{ $nav_follower ?? '' }}">
                        <a href="{{ url('admin/follower_list') }}">
                            <i class="fa fa-user-secret"></i> <span>Follower</span>
                        </a>
                    </li>

                    <li class="{{ $nav_coin_plans ?? '' }}">
                        <a href="{{ url('admin/coin_plans') }}">
                            <i class="fa fa-database"></i> <span>Coin Plans</span>
                        </a>
                    </li>
                    <li class="{{ $nav_payouts ?? '' }}">
                        <a href="{{ url('admin/payouts') }}">
                            <i class="fa fa-money"></i> <span>Payouts</span>
                        </a>
                    </li>
                    <li class="{{ $nav_reported_items ?? '' }}">
                        <a href="{{ url('admin/reported_items') }}">
                            <i class="fa fa-ban"></i> <span>Reported Items</span>
                        </a>
                    </li>
                    <li class="{{ $nav_contact_us ?? '' }}">
                        <a href="{{ url('admin/contact_us') }}">
                            <i class="fa fa-address-book"></i> <span>Contact Us</span>
                        </a>
                    </li>
                    <li class="{{ $nav_faq ?? '' }}">
                        <a href="{{ url('admin/faq') }}">
                            <i class="fa fa-question-circle"></i> <span>FAQ</span>
                        </a>
                    </li>

                    <li class="{{ $nav_store ?? '' }}">
                        <a href="{{ url('admin/store') }}">
                            <i class="fa fa-university"></i> <span>Store</span>
                        </a>
                    </li>
                    <li class="{{ $nav_sales_report ?? '' }}">
                        <a href="{{ url('admin/sales_report') }}">
                            <i class="fa fa-file-excel-o"></i> <span>Sales Report</span>
                        </a>
                    </li>
                    <li class="{{ $nav_list_subscription ?? '' }}">
                        <a href="{{ url('admin/list_subscription') }}">
                            <i class="fa fa-credit-card-alt"></i> <span>Subscription</span>
                        </a>
                    </li>

                    <li class="{{ $nav_ticket ?? '' }}">
                        <a href="{{ url('admin/ticket') }}">
                            <i class="fa fa-ticket"></i> <span>Support Tickets</span>
                        </a>
                    </li>

                    <li class="treeview {{ $nav_transaction_history ?? '' }}">
                        <a href="#">
                            <i class="fa fa-history"></i> <span>Transaction History</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ $money_transaction_history ?? '' }}"><a
                                    href="{{ url('admin/money_transaction_history') }}"><i
                                        class="fa fa-circle-o"></i>
                                    Money</a></li>
                            <li class="{{ $coin_transaction_history ?? '' }}"><a
                                    href="{{ url('admin/coin_transaction_history') }}"><i class="fa fa-circle-o"></i>
                                    Coin</a>
                            </li>
                        </ul>
                    </li>
                    <li class="treeview {{ $nav_dynamic_content ?? '' }}">
                        <a href="#">
                            <i class="fa fa-list-alt"></i> <span>Dynamic Content</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ $home_page_dynamic_content ?? '' }}"><a
                                    href="{{ url('admin/dynamic_home_page_content') }}"><i
                                        class="fa fa-circle-o"></i>
                                    Home Page</a>
                            </li>
                            <li class="{{ $about_us_dynamic_content ?? '' }}"><a
                                    href="{{ url('admin/about_us_dynamic_content') }}"><i class="fa fa-circle-o"></i>
                                    About Us</a>
                            </li>
                            <li class="{{ $term_condition_dynamic_content ?? '' }}"><a
                                    href="{{ url('admin/term_condition_dynamic_content') }}"><i
                                        class="fa fa-circle-o"></i>
                                    Terms & Conditions</a>
                            </li>
                            <li class="{{ $privacy_policy_dynamic_content ?? '' }}"><a
                                    href="{{ url('admin/privacy_policy_dynamic_content') }}"><i
                                        class="fa fa-circle-o"></i>
                                    Privacy Policy</a>
                            </li>
                            <li class="{{ $contact_us_dynamic_content ?? '' }}"><a
                                    href="{{ url('admin/contact_us_dynamic_content') }}"><i
                                        class="fa fa-circle-o"></i>
                                    Contact Us</a>
                            </li>
                        </ul>
                    </li>

                    <!-- <li class="treeview {{ $nav_hotel ?? '' }}">
          <a href="#">
            <i class="fa fa-building-o"></i> <span>Hotel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{ $hotel_service ?? '' }}"><a href="{{ url('admin/hotel/service') }}"><i class="fa fa-circle-o"></i> Services</a></li>
            <li class="{{ $hotel_amenity ?? '' }}"><a href="{{ url('admin/hotel/amenity') }}"><i class="fa fa-circle-o"></i> Amenities</a></li>
            <li class="{{ $hotel_room_category ?? '' }}"><a href="{{ url('admin/hotel/room_category') }}"><i class="fa fa-circle-o"></i> Room Categories</a></li>
            <li class="{{ $hotel_hotel ?? '' }}"><a href="{{ url('admin/hotel/hotel') }}"><i class="fa fa-circle-o"></i> Hotels</a></li>
          </ul>
        </li> -->


                    <li class="{{ $nav_settings ?? '' }}">
                        <a href="{{ url('admin/settings') }}">
                            <i class="fa fa-cog"></i> <span>Settings</span>
                        </a>
                    </li>
                    <li class="{{ $nav_account ?? '' }}">
                        <a href="{{ url('admin/account') }}">
                            <i class="fa fa-user"></i> <span>Account</span>
                        </a>
                    </li>

                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            @yield('content')


        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.0
            </div>
            <strong>Copyright &copy; {{ date('Y') }}
                <!-- <a href="https://adminlte.io">Almsaeed Studio</a> -->.
            </strong> All rights
            reserved.
        </footer>

        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->


    <div class="mw_loader"><i class="fa fa-spinner fa-spin"></i></div>
    <style type="text/css">
        .mw_loader {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.6);
            text-align: center;
            z-index: 9999;
            display: none;
        }

        .mw_loader i {
            font-size: 80px;
            color: #350015;
            margin-top: 200px;
        }

    </style>



    <style type="text/css">
        <?php if (isset($meta_data['global_settings']['settings_admin_tablehoverbg'])) {
            echo '.table-hover>tbody>tr:hover {
        background-color: ' .
                $meta_data['global_settings']['settings_admin_tablehoverbg'] .
                ';
        
                }
        
                ';
        }
        
        ?>

    </style>


    <!-- jQuery 3 -->
    <script src="{{ URL::asset('/public/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ URL::asset('/public/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ URL::asset('/public/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- jvectormap  -->
    <script src="{{ URL::asset('/public/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ URL::asset('/public/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ URL::asset('/public/admin/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('/public/admin/dist/js/adminlte.min.js') }}"></script>
    <!-- <script src="{{ URL::asset('/public/admin/dist/js/adminlte.js') }}"></script> -->
    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('/public/admin/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ URL::asset('/public/admin/dist/js/pages/dashboard2.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ URL::asset('/public/admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}">
    </script>
    <!-- ChartJS -->
    <script src="{{ URL::asset('/public/admin/bower_components/chart.js/Chart.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()
        })
    </script>

    <!-- jQuery-UI -->
    <script src="{{ URL::asset('/public/admin/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ URL::asset('/public/admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <!-- bootstrap color picker -->
    <script
        src="{{ URL::asset('/public/admin/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}">
    </script>
    <script>
        $(document).ready(function() {
            //Colorpicker
            $('.my-colorpicker').colorpicker();
        });
    </script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ URL::asset('/public/admin/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

        })
    </script>

    <script src="{{ URL::asset('/public/admin/dist/js/canvasjs.min.js') }}"></script>


    <!-- CK Editor -->
    <script src="{{ URL::asset('/public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ URL::asset('/public/admin/ckfinder/ckfinder.js') }}"></script>


    <script src="{{ URL::asset('/public/front/js/custom/common.js') }}" type="text/javascript"></script>


    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ url('public/front/js/smooth-scrollbar.js') }}"></script>
    <script>
        Scrollbar.initAll();
    </script>

    @stack('scripts')


    <?php
    /*if(!isset($_GET['dev'])) { ?>
    ?>
    ?>
    ?>
    ?>
    ?>
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.content-wrapper').html(
                '<section class="content-header"><h1>Admin is in development mode</h1><div class="clearfix"></div></section>'
            );
            $('.content-wrapper .content-header, .content-wrapper .content-header h1').show();
        });
    </script>
    <?php }*/ ?>

</body>

</html>
