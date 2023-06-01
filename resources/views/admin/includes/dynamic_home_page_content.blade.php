
{{-- @dd($meta_data['page_content']->content) --}}
<section class="content-header">
  <h1>
    Home Page Content
  </h1>
</section>

<section class="content">
    <div class="box box-solid">
        <div class="box-body">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif
            <form action="{{route('dyanmic-content')}}" method="POST" enctype="multipart/form-data">

                @csrf
                <div class="box-group" id="accordion">
                    <div class="panel box box-default">
                        <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#general">
                            Banner Section
                            </a>
                        </h4>
                        </div>
                        <div id="general" class="panel-collapse collapse in">
                        <div class="box-body">
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Background Image</label>
                                    <div class="col-sm-6">
                                    <input type="file" id="banner_background_image" class="form-control" name="banner_background_image" onchange="preview_image(this,'.image-preview')" />
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="image-preview">
                                            @if (!empty($meta_data['page_content']->content['banner_background_image']))
                                                <img src="{{asset('public/admin/dist/img/dynamic-home-content/'.$meta_data['page_content']->content['banner_background_image'])}}" alt="">
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="old_banner_background_image" value="{{$meta_data['page_content']->content['banner_background_image']??''}}">
                                </div>
                            </div>
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Youtube video link</label>
                                    <div class="col-sm-9">
                                    <input type="text" id="youtube_video_link" class="form-control" name="youtube_video_link" value="{{old('youtube_video_link',$meta_data['page_content']->content['youtube_video_link'])}}"  />
                                    </div>

                                </div>
                            </div>
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Content</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control editor" id="content" name="content" rows="6">{{old('content',$meta_data['page_content']->content['content'])}}</textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        </div>
                    </div>
                    <div class="panel box box-default">
                        <div class="box-header with-border">
                        <h4 class="box-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#second_banner">
                            Second Banner Section
                            </a>
                        </h4>
                        </div>
                        <div id="second_banner" class="panel-collapse collapse">
                        <div class="box-body">
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Background Image</label>
                                    <div class="col-sm-6">
                                    <input type="file" id="second_banner_background_image" class="form-control" name="second_banner_background_image" onchange="preview_image(this,'.second-image-preview')" />
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="second-image-preview">
                                            @if (!empty($meta_data['page_content']->content['second_banner_background_image']))
                                                <img src="{{asset('public/admin/dist/img/dynamic-home-content/'.$meta_data['page_content']->content['second_banner_background_image'])}}" alt="">
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="old_second_banner_background_image" value="{{$meta_data['page_content']->content['second_banner_background_image']??''}}">
                                </div>
                            </div>
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Button Link</label>
                                    <div class="col-sm-9">
                                    <input type="text" id="button_link" class="form-control" name="button_link" value="{{old('button_link',$meta_data['page_content']->content['button_link'])}}" />
                                    </div>

                                </div>
                            </div>
                            <div class="box-wrap">
                                <div class="row">
                                    <label class="col-sm-3 control-label">Content</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control editor" id="second_banner_content" name="second_banner_content" rows="6">{{old('second_banner_content',$meta_data['page_content']->content['second_banner_content'])}}</textarea>
                                    </div>

                                </div>
                            </div>

                        </div>
                        </div>
                    </div>
                    <input type="hidden" name="page_type" id="page_type" value="HOME">

                    <button type="submit" class="btn btn-primary pull-right settings_submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>
@push('scripts')
<script type="text/javascript">
    function preview_image(input, target) {
      var ext = $(input).val().split('.').pop().toLowerCase();
      if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        $(input).val('');
          alert('invalid photo!');
          return;
      }
      if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              var output = "<img src='"+e.target.result+"' />";
                $(target).html(output);
                remove_photo(target, input);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function remove_photo(target, element) {
      $('.remove_photo').click(function(){
        $(target).html('');
        $(element).val('');
      });
    }
  </script>
<script>
    $(function () {


      $(".editor").each(function(){
        var eid = $(this).attr('id');
        var editor = CKEDITOR.replace( eid, {
          format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
          filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
          filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
          filebrowserWindowWidth: '1000',
          filebrowserWindowHeight: '700'
        });
        CKFinder.setupCKEditor( editor );
      });

      /*var editor = CKEDITOR.replace( 'editor1', {
        format_tags: 'p;h1;h2;h3;h4;h5;h6;pre;address;div',
        filebrowserBrowseUrl: '{{ URL::asset('public/admin/ckfinder/ckfinder.html') }}',
        filebrowserUploadUrl: '{{ URL::asset('public/admin/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
        filebrowserWindowWidth: '1000',
        filebrowserWindowHeight: '700'
      });
      CKFinder.setupCKEditor( editor );*/

    })
  </script>
@endpush
