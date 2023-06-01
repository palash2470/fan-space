
<section class="content-header">
    <h1>
      Terms & Condition Page Content
    </h1>
</section>
<section class="content">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <form action="{{route('dyanmic-content')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="box-wrap">
            <div class="row">
                <label class="col-sm-3 control-label">Banner Image</label>
                <div class="col-sm-6">
                <input type="file" id="banner_img" class="form-control" name="banner_img" onchange="preview_image(this,'#banner_img_preview')" />
                </div>
                <div class="col-sm-3">
                    <div class="image-preview" id="banner_img_preview">
                        @if (!empty($meta_data['page_content']->content['banner_img']))
                            <img src="{{asset('public/admin/dist/img/terms_condition/'.$meta_data['page_content']->content['banner_img'])}}" alt="">
                        @endif
                    </div>
                </div>
                <input type="hidden" name="old_banner_img" value="{{$meta_data['page_content']->content['banner_img']??''}}">
            </div>
        </div>
        <div class="box-wrap">
            <div class="row">
                <label class="col-sm-3 control-label">Content</label>
                <div class="col-sm-9">
                    <textarea class="form-control editor" id="content" name="content" rows="6">{{old('content',$meta_data['page_content']->content['content']??'')}}</textarea>
                </div>
            </div>
        </div>
        <div class="box-wrap">
            <div class="row">
                <div class="col-lg-12">
                <input type="hidden" name="page_type" id="page_type" value="TERMS_CONDITION">
                <button type="submit" class="btn btn-primary pull-right settings_submit">Save</button>
            </div>
        </div>
        </div>

    </form>
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
