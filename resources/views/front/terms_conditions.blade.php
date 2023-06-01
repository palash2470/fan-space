@extends('layouts.front')
@section('content')



{{-- <section class="samplePageWrap">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="samplePage">
                        <h4>Terms & Conditions</h4>

                        <?php echo $meta_data['global_settings']['settings_website_terms_conditions']; ?>

                    </div>
                </div>

            </div>
        </div>
    </section> --}}

    <section class="other-page-banner banr-blk-bg" style="background:url({{asset('public/admin/dist/img/terms_condition/'.$meta_data['page_content']->content['banner_img'])}}) no-repeat center;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="other-page-heading d-flex align-content-center justify-content-center">
                        <h2>Terms & Conditions</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="terms-conditions-sec">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="content-box-wrap term-condi">
                        {!!$meta_data['page_content']->content['content']??''!!}
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
