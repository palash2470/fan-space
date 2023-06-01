@extends('layouts.front')
@section('content')

<section class="other-page-banner banr-blk-bg" style="background: url({{asset('public/admin/dist/img/about_us/'.$meta_data['page_content']->content['banner_img'])}}) no-repeat center;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="other-page-heading d-flex align-content-center justify-content-center">
                    <h2>About Us</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-page-sec">
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="about-main-wrap">
                <img src="{{asset('public/admin/dist/img/about_us/'.$meta_data['page_content']->content['about_img'])}}" class="abt-blk-img" alt="">
                <div class="about-content">
                    {!!$meta_data['page_content']->content['content']??''!!}
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
