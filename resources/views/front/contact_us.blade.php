@extends('layouts.front')
@section('content')
<section class="other-page-banner banr-blk-bg" style="background: url({{asset('public/admin/dist/img/contact_us/'.$meta_data['page_content']->content['banner_img'])}}) no-repeat center;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="other-page-heading d-flex align-content-center justify-content-center">
                    <h2>contact us</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                <div class="contact-head">
                    <form method="POST" action="{{route('front.contact-us')}}">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="contact-input form-group">
                                    <input type="text" class="form-control input-contact" placeholder="Name" name="name" value="{{old('name')}}">
                                    @if($errors->has('name'))
                                        <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                                <div class="contact-input form-group">
                                    <input type="text" class="form-control input-contact" placeholder="Phone Number" name="ph_no" value="{{old('ph_no')}}">
                                    @if($errors->has('ph_no'))
                                        <div class="error">{{ $errors->first('ph_no') }}</div>
                                    @endif
                                </div>
                                <div class="contact-input form-group">
                                    <input type="text" class="form-control input-contact" placeholder="Email" name="email" value="{{old('email')}}">
                                    @if($errors->has('email'))
                                        <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="contact-txtre form-group">
                                    <textarea class="form-control txtre-contact" rows="5" placeholder="Message" name="message">{{old('message')}}</textarea>
                                    @if($errors->has('message'))
                                        <div class="error">{{ $errors->first('message') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="contactfrm-btn text-center">
                                    <button class="frm-btn" type="submit">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="contact-head">
                    {!!$meta_data['page_content']->content['content']??''!!}
                    <ul class="contact-social">
                        <li><a href="#"><i class="fas fa-phone-alt"></i> <span>{{$meta_data['page_content']->content['ph_no']??''}}</span></a>
                        </li>
                        <li><a href="#"><i class="fas fa-envelope"></i> <span>{{$meta_data['page_content']->content['email']??''}}</span></a>
                        </li>
                    </ul>
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
