@extends('layouts.front')
@section('content')

<!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ URL::asset('/public/front/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>LOG IN</h2>
                        <div class="bt-option">
                            <a href="#">Home</a>
                            <!-- <a href="#">Pages</a> -->
                            <span>Log In</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Begin -->
    <section class="contact-section spad">
        <div class="container">
        	<?php if(Session::has('error')) {
						echo '<div class="alert alert-danger" role="alert">' . Session::get('error') . '</div>';
					} ?>
            <div class="row">
                <div class="col-lg-12">
                		<div class="section-title contact-title">
                        <!-- <span>LOG IN</span> -->
                        <h2>LOG IN</h2>
                    </div>
                    <div class="leave-comment">
                        <form method="post">
                        	{{ csrf_field() }}
                            <input type="text" name="email" value="" />
                            <input type="password" name="password" value="" />
                            <button type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->


@stop